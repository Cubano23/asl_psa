<?php
session_start();
if(!isset($_SESSION['nom'])) {
    # pas passé par l'identification
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
    <title>Taux de patients disposant d'au moins une mise à jour dans l'année</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("../../global/entete.php");
//echo $loc;

entete_asalee("Taux de patients disposant d'au moins une mise à jour dans l'année");
?>

<br><br>
<?

# boucle principale
do {
    $repete=false;


    # étape 1 : tableau àç la date du jour
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            // tableau à la date du jour
            case 1:
                etape_1($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal

//tableau à la date du jour
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab, $total_diab;

    $req="SELECT cabinet, total_diab2, nom_cab ".
        "FROM account ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'   and cabinet!='ergo' and cabinet!='jgomes' ".
        "and cabinet!='sbirault' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $total_diab=0;
    $total_diab_eval=0;
    $total_diab_eval2=0;
    $total_diab_eval3=0;

    while(list($cab, $total_diab2, $ville) = mysql_fetch_row($res)) {
        $t_diab[$cab]=$total_diab2;
        $total_diab=$total_diab+$total_diab2;
        $tville[$cab]=$ville;

        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $total_diab_eval=$total_diab_eval+$total_diab2;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $total_diab_eval2=$total_diab_eval2+$total_diab2;
        }
        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
        {
            $total_diab_eval3=$total_diab_eval3+$total_diab2;
        }
    }


    $req="SELECT dossier.cabinet, count(*) ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet!='zTest' and dossier.cabinet!='irdes'  and dossier.cabinet!='ergo'  and ".
        "dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' and dossier.cabinet=account.cabinet ".
        "AND actif='oui' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
//	 $tpat[$cab] = $pat;
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";


    ?>
    <br>
    <br>
    <table border=1 align='center'>
        <?php

        ///////////////taux diab 2 suivis dans asalée//////////////////////////
        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND actif='oui' ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "and ((dsuivi is not NULL and DATE_ADD(dsuivi, ".
            "INTERVAL 1 YEAR) >= CURDATE())) ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $patient=0;
        $patient_eval=0;
        $patient_eval2=0;
        $patient_eval3=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $patient=$patient+1;

            if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $patient_eval=$patient_eval+1;
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $patient_eval2=$patient_eval2+1;
            }
            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
            {
                $patient_eval3=$patient_eval3+1;
            }
        }

        ?>

        <tr>
            <td>Taux de Suivi &nbsp;</td>
            <td> &nbsp;<?php echo date('m-Y') ?> <sup>1</sup>&nbsp;</td>
        </tr>

        <?php

        $moyenne=0;
        $nb_cab=0;

        foreach($tcabinet as $cab) {

            if ($t_diab[$cab]==0)
                $taux="ND";
            else
            {
                $taux=$tpat[$cab]/$t_diab[$cab]*100;
                $taux=round($taux, 0);
//		$moyenne+=$taux;
                $taux.="%";
//		$nb_cab++;
            }


            ?>
            <tr>
                <td><?php echo $tville[$cab];?></td>
                <td align='right'><?php echo $taux; ?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>Moyenne : </td>
            <td align='right'><?php echo round($patient/$total_diab*100);echo '%'; ?></td>
        </tr>
        <tr>
            <td>Moyenne eval : </td>
            <td align='right'><?php echo round($patient_eval/$total_diab_eval*100);echo '%'; ?></td>
        </tr>
        <tr>
            <td>Moyenne cab 2005: </td>
            <td align='right'><?php echo round($patient_eval2/$total_diab_eval2*100);echo '%'; ?></td>
        </tr>
        <tr>
            <td>Moyenne cab 2006 : </td>
            <td align='right'><?php echo round($patient_eval3/$total_diab_eval3*100);echo '%'; ?></td>
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
    <sup>1</sup>Nombre de patients ayant au moins une mise à jour dans l'année/potentiel du cabinet
    <?

}

//arrêtés trimestriels
function tableau($date) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab, $total_diab;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";



    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet, numero ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
//$tcabinet=array();
    $t_diab['tot']=0;
    $t_diab['eval']=$t_diab['eval2']=$t_diab['eval3']=0;
    $cab_prec="";

    foreach ($tcabinet as $cab)
    {
//	$tpat[$cab]=0;
        $tcabinet_util[$cab]=0;

    }


    while(list($cab, $pat) = mysql_fetch_row($res)) {
//	 $tcabinet[] = $cab;

        if($cab!=$cab_prec)
        {
            $t_diab['tot']=$t_diab['tot']+$t_diab[$cab];

            if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $t_diab['eval']=$t_diab['eval']+$t_diab[$cab];
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $t_diab['eval2']=$t_diab['eval2']+$t_diab[$cab];
            }
            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
            {
                $t_diab['eval3']=$t_diab['eval3']+$t_diab[$cab];
            }

            $cab_prec=$cab;
            $tcabinet_util[$cab]=$t_diab[$cab];
        }
    }


    ?>
    <br>
    <br>
    <table border=1 align='center'>
        <?php

        ///////////////taux diab 2 suivis dans asalée//////////////////////////
        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "and ((dsuivi is not NULL and dsuivi<='$date' and DATE_ADD(dsuivi, ".
            "INTERVAL 1 YEAR) >= '$date')) ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $patient=0;
        $patient_eval=$patient_eval2=$patient_eval3=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $patient++;

            if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $patient_eval=$patient_eval+1;
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $patient_eval2=$patient_eval2+1;
            }
            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
            {
                $patient_eval3=$patient_eval3+1;
            }
        }

        list($a,$m,$j)= explode('-',$date,3);

        ?>

        <tr>
            <td>Taux de Suivi &nbsp;</td>
            <td> &nbsp;<?php echo date('m-Y',mktime(0, 0, 0, $m, $j, $a)) ?> <sup>1</sup>&nbsp;</td>
        </tr>

        <?php

        //$moyenne=0;
        //$nb_cab=0;

        foreach($tcabinet as $cab) {
            if ($tcabinet_util[$cab]==0)
                $taux="ND";
            else
            {
                $taux=$tpat[$cab]/$t_diab[$cab]*100;
                $taux=round($taux, 0);
//		$moyenne+=$taux;
                $taux.="%";
//		$nb_cab++;
            }


            ?>
            <tr>
                <td><?php echo $tville[$cab];?></td>
                <td align='right'><?php echo $taux; ?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>Moyenne : </td>
            <td align='right'><?php echo round($patient/$t_diab['tot']* 100);echo '%'; ?></td>
        </tr>

        <tr>
            <td>Moyenne eval : </td>
            <td align='right'><?php echo round($patient_eval/$t_diab['eval']* 100);echo '%'; ?></td>
        </tr>
        <tr>
            <td>Moyenne cab 2005 : </td>
            <td align='right'><?php echo ($t_diab['eval2']==0)?"ND":round($patient_eval2/$t_diab['eval2']* 100);echo '%'; ?></td>
        </tr>
        <tr>
            <td>Moyenne cab 2006 : </td>
            <td align='right'><?php echo ($t_diab['eval3']==0)?"ND":round($patient_eval3/$t_diab['eval3']* 100);echo '%'; ?></td>
        </tr>


    </table>
    <br><br>
    <?php

}
?>
</body>
</html>
