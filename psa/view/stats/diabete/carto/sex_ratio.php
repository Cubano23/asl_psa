<?php
session_start();
if(!isset($_SESSION['nom'])) {
    # pas pass� par l'identification
    $debut=dirname($_SERVER['PHP_SELF']);
    $self=basename($_SERVER['PHP_SELF']);
    header("Location: $debut/ident_util.php?url=$self");
    echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
    exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Cartographie des patients</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux donn�es
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter � la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("../../global/entete.php");
//echo $loc;

entete_asalee("Cartographie des patients");
?>

<br><br>
<?

# boucle principale
do {
    $repete=false;



    # �tape 1 : donn�es � la date du jour
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            //donn�es � la date du jour
            case 1:
                etape_1($repete);
                break;


        }
    }
} while($repete);

# fin de traitement principal

//donn�es � la date du jour
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;



    $req="SELECT dossier.cabinet, count(*), nom_cab ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet!='zTest' and dossier.cabinet!='irdes'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='ergo'  and dossier.cabinet!='sbirault' ".
        "AND actif='oui' ".
        "and dossier.cabinet=account.cabinet ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();

    while(list($cab, $pat, $ville) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tville[$cab]=$ville;
//	 $tpat[$cab] = $pat;
    }

    $mois=array('01'=>'Janvier', '02'=>'F�vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Ao�t', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'D�cembre');

    echo '<b>Donn�es � la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        //taux diab 2 suivis dans asal�e
        $req="SELECT cabinet, dnaiss, sexe ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND actif='oui' ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "GROUP BY cabinet, id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab][0]=150;
            $tpat[$cab][1]="ND";
            $tpat[$cab][2]=0;
            $tpat[$cab]['H']=0;
            $tpat[$cab]['F']=0;
            $tpat[$cab]['ratio']="ND";
        }

        $nbtot=0;
        $age_tot=0;
        $nbeval=$nbeval2=$nbeval3=0;
        $age_eval=$age_eval2=$age_eval3=0;
        $cab_prec="";
        $nb_cab=0;
        $tpat['tot'][0]=150;
        $tpat['tot'][2]=0;
        $tpat['tot']['H']=0;
        $tpat['tot']['F']=0;

        $tpat['eval'][0]=150;
        $tpat['eval'][2]=0;
        $tpat['eval']['H']=0;
        $tpat['eval']['F']=0;

        $tpat['eval2'][0]=150;
        $tpat['eval2'][2]=0;
        $tpat['eval2']['H']=0;
        $tpat['eval2']['F']=0;

        $tpat['eval3'][0]=150;
        $tpat['eval3'][2]=0;
        $tpat['eval3']['H']=0;
        $tpat['eval3']['F']=0;


        while(list($cab, $dnaiss, $sexe) = mysql_fetch_row($res)) {
            $nbtot++;

            if($cab!=$cab_prec)
            {
                if($nb_cab!=0)
                {
                    $tpat[$cab_prec][1]=round($age_cab/$nb_cab,1);
                }

                if($cab_prec!='')
                {
                    if(($tpat[$cab_prec]['H']!=0) && ($tpat[$cab_prec]['F']!=0))
                    {
                        $tpat[$cab_prec]['ratio']=round($tpat[$cab_prec]['H']/$tpat[$cab_prec]['F'], 2);
                    }
                    elseif(($tpat[$cab_prec]['H']!=0)&&($tpat[$cab_prec]['F']==0))
                    {
                        $tpat[$cab_prec]['ratio']='100% H';
                    }
                    elseif(($tpat[$cab_prec]['H']==0)&&($tpat[$cab_prec]['F']!=0))
                    {
                        $tpat[$cab_prec]['ratio']='100% F';
                    }
                    else
                        $tpat[$cab_prec]['ratio']='ND';

                }
                $nb_cab=0;
                $age_cab=0;
                $cab_prec=$cab;
            }
            $nb_cab++;

            if($sexe=='M')
            {
                $tpat[$cab]['H']=$tpat[$cab]['H']+1;


                $tpat['tot']['H']=$tpat['tot']['H']+1;
            }
            else
            {
                $tpat[$cab]['F']=$tpat[$cab]['F']+1;


                $tpat['tot']['F']=$tpat['tot']['F']+1;
            }


            $age=calcage($dnaiss);


            $age_cab=$age_cab+$age;
            $age_tot=$age_tot+$age;



            if($age<$tpat[$cab][0])
                $tpat[$cab][0] = $age;
            if($age>$tpat[$cab][2])
                $tpat[$cab][2]=$age;




            if($age<$tpat['tot'][0]){
                $tpat['tot'][0]=$age;
            }
            if($age>$tpat['tot'][2]){
                $tpat['tot'][2]=$age;
            }

        }

        if($nb_cab!=0)
        {
            $tpat[$cab_prec][1]=round($age_cab/$nb_cab,1);
        }
        if(($tpat[$cab_prec]['H']!=0) && ($tpat[$cab_prec]['F']!=0))
        {
            $tpat[$cab_prec]['ratio']=round($tpat[$cab_prec]['H']/$tpat[$cab_prec]['F'], 2);
        }
        elseif(($tpat[$cab_prec]['H']!=0)&&($tpat[$cab_prec]['F']==0))
        {
            $tpat[$cab_prec]['ratio']='100% H';
        }
        elseif(($tpat[$cab_prec]['H']==0)&&($tpat[$cab_prec]['F']!=0))
        {
            $tpat[$cab_prec]['ratio']='100% F';
        }
        else
            $tpat[$cab_prec]['ratio']='ND';



        $tpat['tot'][1]=round($age_tot/$nbtot, 1);

        if(($tpat['tot']['H']!=0) && ($tpat['tot']['F']!=0))
        {
            $tpat['tot']['ratio']=round($tpat['tot']['H']/$tpat['tot']['F'], 2);
        }
        elseif(($tpat['tot']['H']!=0)&&($tpat['tot']['F']==0))
        {
            $tpat['tot']['ratio']='100% H';
        }
        elseif(($tpat['tot']['H']==0)&&($tpat['tot']['F']!=0))
        {
            $tpat['tot']['ratio']='100% F';
        }


        ?>

        <tr>
            <td></td><td>Age moyen (ans)<sup>1</sup></td>
            <td>Ages extr�mes (ans)<sup>1</sup></td>
            <td>Sexe ratio H/F<sup>1</sup></td>
        </tr>

        <?php

        foreach($tcabinet as $cab) {
            ?>
            <tr>
                <td align='left'><?php echo $tville[$cab]; ?></td>
                <td align='right'><?php echo $tpat[$cab][1]=='ND'?'ND':number_format($tpat[$cab][1], 1, ',', ' '); ?></td>
                <td align='center'><?php echo (($tpat[$cab][0]==150)&&$tpat[$cab][2]==0)?"ND":$tpat[$cab][0]." � ".$tpat[$cab][2]; ?></td>
                <td align='right'><?php if($tpat[$cab]['ratio']=='ND')
                        echo 'ND';
                    elseif (($tpat[$cab]['ratio']=='100% H')||($tpat[$cab]['ratio']=='100% F'))
                        echo $tpat[$cab]['ratio'];
                    else
                        echo number_format($tpat[$cab]['ratio'], 1, ',', ' ');?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td align='left'>Moyenne</td>
            <td align='right'><?php echo number_format($tpat['tot'][1], 1, ',', ' '); ?></td>
            <td align='center'><?php echo $tpat['tot'][0]." � ".$tpat['tot'][2]; ?></td>
            <td align='right'><?php echo number_format($tpat['tot']['ratio'], 1, ',', ' ');?></td>
        </tr>

    </table>
    <br><br>
    <?php

    $annee0=2004;
    $mois0=3;

    $annee=date('Y');
    $mois=date('m');

    $mois--;


    if($mois<3)
    {
        $annee--;
        $mois=12;
    }
    elseif(($mois>=3)&&($mois<6))
    {
        $mois=3;
    }
    elseif(($mois>=6)&&($mois<9))
    {
        $mois=6;
    }
    elseif(($mois>=9)&&($mois<12))
    {
        $mois=9;
    }

    $jour[3]=$jour[12]=31;
    $jour[6]=$jour[9]=30;

    while(($annee>$annee0)||(($annee==$annee0)&&($mois>=$mois0)))
    {
        if($mois<10)
        {
            $date=$annee.'-0'.$mois.'-'.$jour[$mois];
        }
        else
        {
            $date=$annee.'-'.$mois.'-'.$jour[$mois];
        }
        tableau($date);

        $mois=$mois-3;

        if($mois<=0)
        {
            $mois=$mois+12;
            $annee--;
        }
    }
    ?>
    <sup>1</sup>Seules les personnes qui ont eu un suivi dans les 5 derniers mois sont prises en compte dans le calcul des statistiques
    <?

}

//arr�t�s trimestriels
function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;


    $mois=array('01'=>'Janvier', '02'=>'F�vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Ao�t', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'D�cembre');

    $tab_date=split('-', $date);

    echo "<b>Donn�es au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        //taux diab 2 suivis dans asal�e
        $req="SELECT cabinet, dnaiss, sexe ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "GROUP BY cabinet, id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab][0]=150;
            $tpat[$cab][1]="ND";
            $tpat[$cab][2]=0;
            $tpat[$cab]['H']=0;
            $tpat[$cab]['F']=0;
            $tpat[$cab]['ratio']="ND";
        }

        $nbtot=0;
        $age_tot=0;
        $nbeval=0;
        $age_eval=0;
        $nbeval2=0;
        $age_eval2=0;
        $nbeval3=0;
        $age_eval3=0;
        $cab_prec="";
        $nb_cab=0;
        $tpat['tot'][0]=150;
        $tpat['tot'][2]=0;
        $tpat['tot']['H']=0;
        $tpat['tot']['F']=0;

        while(list($cab, $dnaiss, $sexe) = mysql_fetch_row($res)) {
            $nbtot++;


            if($cab!=$cab_prec)
            {
                if($nb_cab!=0)
                {
                    $tpat[$cab_prec][1]=round($age_cab/$nb_cab,1);
                }

                if($cab_prec!='')
                {
                    if(($tpat[$cab_prec]['H']!=0) && ($tpat[$cab_prec]['F']!=0))
                    {
                        $tpat[$cab_prec]['ratio']=round($tpat[$cab_prec]['H']/$tpat[$cab_prec]['F'], 2);
                    }
                    elseif(($tpat[$cab_prec]['H']!=0)&&($tpat[$cab_prec]['F']==0))
                    {
                        $tpat[$cab_prec]['ratio']='100% H';
                    }
                    elseif(($tpat[$cab_prec]['H']==0)&&($tpat[$cab_prec]['F']!=0))
                    {
                        $tpat[$cab_prec]['ratio']='100% F';
                    }
                    else
                        $tpat[$cab_prec]['ratio']='ND';

                }
                $nb_cab=0;
                $age_cab=0;
                $cab_prec=$cab;
            }
            $nb_cab++;

            if($sexe=='M')
            {
                $tpat[$cab]['H']=$tpat[$cab]['H']+1;
                $tpat['tot']['H']=$tpat['tot']['H']+1;


            }
            else
            {
                $tpat[$cab]['F']=$tpat[$cab]['F']+1;
                $tpat['tot']['F']=$tpat['tot']['F']+1;


            }


            $age=calcage2($dnaiss, $date);


            $age_cab=$age_cab+$age;
            $age_tot=$age_tot+$age;


            if($age<$tpat[$cab][0])
                $tpat[$cab][0] = $age;
            if($age>$tpat[$cab][2])
                $tpat[$cab][2]=$age;




            if($age<$tpat['tot'][0]){
                $tpat['tot'][0]=$age;
            }
            if($age>$tpat['tot'][2]){
                $tpat['tot'][2]=$age;
            }


        }

        if($nb_cab!=0)
        {
            $tpat[$cab_prec][1]=round($age_cab/$nb_cab,1);
        }
        if(($tpat[$cab_prec]['H']!=0) && ($tpat[$cab_prec]['F']!=0))
        {
            $tpat[$cab_prec]['ratio']=round($tpat[$cab_prec]['H']/$tpat[$cab_prec]['F'], 2);
        }
        elseif(($tpat[$cab_prec]['H']!=0)&&($tpat[$cab_prec]['F']==0))
        {
            $tpat[$cab_prec]['ratio']='100% H';
        }
        elseif(($tpat[$cab_prec]['H']==0)&&($tpat[$cab_prec]['F']!=0))
        {
            $tpat[$cab_prec]['ratio']='100% F';
        }
        else
            $tpat[$cab_prec]['ratio']='ND';



        $tpat['tot'][1]=round($age_tot/$nbtot, 1);

        if(($tpat['tot']['H']!=0) && ($tpat['tot']['F']!=0))
        {
            $tpat['tot']['ratio']=round($tpat['tot']['H']/$tpat['tot']['F'], 2);
        }
        elseif(($tpat['tot']['H']!=0)&&($tpat['tot']['F']==0))
        {
            $tpat['tot']['ratio']='100% H';
        }
        elseif(($tpat['tot']['H']==0)&&($tpat['tot']['F']!=0))
        {
            $tpat['tot']['ratio']='100% F';
        }

        ?>

        <tr>
            <td></td><td>Age moyen (ans)</td>
            <td>Ages extr�mes (ans)</td>
            <td>Sexe ratio H/F</td>
        </tr>

        <?php

        foreach($tcabinet as $cab) {
            ?>
            <tr>
                <td align='left'><?php echo $tville[$cab]; ?></td>
                <td align='right'><?php echo $tpat[$cab][1]=='ND'?'ND':number_format($tpat[$cab][1], 1, ',', ' '); ?></td>
                <td align='center'><?php echo (($tpat[$cab][0]==150)&&$tpat[$cab][2]==0)?"ND":$tpat[$cab][0]." � ".$tpat[$cab][2]; ?></td>
                <td align='right'><?php if($tpat[$cab]['ratio']=='ND')
                        echo 'ND';
                    elseif (($tpat[$cab]['ratio']=='100% H')||($tpat[$cab]['ratio']=='100% F'))
                        echo $tpat[$cab]['ratio'];
                    else
                        echo number_format($tpat[$cab]['ratio'], 1, ',', ' ');?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td align='left'>Moyenne </td>
            <td align='right'><?php echo number_format($tpat['tot'][1], 1, ',', ' '); ?></td>
            <td align='center'><?php echo $tpat['tot'][0]." � ".$tpat['tot'][2]; ?></td>
            <td align='right'><?php echo number_format($tpat['tot']['ratio'], 1, ',', ' ');?></td>
        </tr>



    </table>
    <br><br>
    <?php

}

//calcule l'age � partir de la date de naissance date1, � la date date2, les dates sont au format aaaa-mm-jj
function calcage2($date1, $date2){

    list($a1,$m1,$j1)= explode('-',$date1,3);
    list($a2,$m2,$j2)= explode('-',$date2,3);

    $age = $a2 - $a1;
    if($m2 < $m1) $age--;
    if(($m2 == $m1) and ($j2 < $j1)) $age--;
    return $age;
}
?>
</body>
</html>
