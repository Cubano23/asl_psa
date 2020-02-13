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
    <title>Cartographie des suivis</title>
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

entete_asalee("Cartographie des suivis");
//echo $loc;
?>

<br><br>
<?

# boucle principale
do {
    $repete=false;


    # étape 1 : valeurs à la date ud jour
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 1://valeurs à la date du jour
                etape_1($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal

//valeurs à la date du joru
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;



    $req="SELECT dossier.cabinet, count(*), nom_cab ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet!='zTest' and dossier.cabinet!='irdes'  and dossier.cabinet!='ergo'  ".
        "and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
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
//	 $tpat[$cab] = $pat;
        $tville[$cab]=$ville;
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, id, dsuivi ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' and cabinet!='sbirault' ".
            "AND actif='oui' ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "ORDER BY cabinet, id, dsuivi ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab][0]=0;
            $tpat[$cab][4]=0;
            $tpat[$cab][8]=0;
            $tpat[$cab][12]=0;
            $tpat[$cab]['total']=0;
        }

        $tpat['tot'][0]=0;
        $tpat['tot'][4]=0;
        $tpat['tot'][8]=0;
        $tpat['tot'][12]=0;
        $total=0;

        $tpat['eval'][0]=0;
        $tpat['eval'][4]=0;
        $tpat['eval'][8]=0;
        $tpat['eval'][12]=0;
        $total_eval=0;

        $tpat['eval2'][0]=0;
        $tpat['eval2'][4]=0;
        $tpat['eval2'][8]=0;
        $tpat['eval2'][12]=0;
        $total_eval2=0;

        $tpat['eval3'][0]=0;
        $tpat['eval3'][4]=0;
        $tpat['eval3'][8]=0;
        $tpat['eval3'][12]=0;
        $total_eval3=0;

        $id_prec='';

        while(list($cab, $id, $dsuivi) = mysql_fetch_row($res)) {
            if($id_prec!=$id)
            {
                $nb_mois=diffmois($dsuivi);

                if($nb_mois<4)
                {
                    $tpat[$cab][0]=$tpat[$cab][0]+1;
                    $tpat['tot'][0]=$tpat['tot'][0]+1;

                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $tpat['eval'][0]=$tpat['eval'][0]+1;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $tpat['eval2'][0]=$tpat['eval2'][0]+1;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $tpat['eval3'][0]=$tpat['eval3'][0]+1;
                    }
                }
                elseif(($nb_mois>=4)&&($nb_mois<8))
                {
                    $tpat[$cab][4]=$tpat[$cab][4]+1;
                    $tpat['tot'][4]=$tpat['tot'][4]+1;

                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $tpat['eval'][4]=$tpat['eval'][4]+1;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $tpat['eval2'][4]=$tpat['eval2'][4]+1;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $tpat['eval3'][4]=$tpat['eval3'][4]+1;
                    }
                }
                elseif(($nb_mois>=8)&&($nb_mois<12))
                {
                    $tpat[$cab][8]=$tpat[$cab][8]+1;
                    $tpat['tot'][8]=$tpat['tot'][8]+1;

                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $tpat['eval'][8]=$tpat['eval'][8]+1;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $tpat['eval2'][8]=$tpat['eval2'][8]+1;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $tpat['eval3'][8]=$tpat['eval3'][8]+1;
                    }
                }
                else
                {
                    $tpat[$cab][12]=$tpat[$cab][12]+1;
                    $tpat['tot'][12]=$tpat['tot'][12]+1;

                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $tpat['eval'][12]=$tpat['eval'][12]+1;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $tpat['eval2'][12]=$tpat['eval2'][12]+1;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $tpat['eval3'][12]=$tpat['eval3'][12]+1;
                    }
                }
                $total++;

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval++;
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2++;
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3++;
                }

                $tpat[$cab]['total']=$tpat[$cab]['total']+1;
            }
        }



        ?>

        <tr>
            <td rowspan='2'></td>
            <td colspan='4' align='center'>Taux de répartition des patients suivis</td>
        </tr>
        <tr>
            <td align='center'>< 4 mois <sup>1</sup></td>
            <td align='center'>[4 - 8[ mois <sup>2</sup</td>
            <td align='center'>[8 - 12 [ mois <sup>3</sup></td>
            <td align='center'>>= 12 mois <sup>4</sup></td>
        </tr>

        <?php

        foreach($tcabinet as $cab) {

            if($tpat[$cab]['total']!=0)
            {
                $total_0=round($tpat[$cab][0]/$tpat[$cab]['total']*100).'%';
                $total_4=round($tpat[$cab][4]/$tpat[$cab]['total']*100).'%';
                $total_8=round($tpat[$cab][8]/$tpat[$cab]['total']*100).'%';
                $total_12=round($tpat[$cab][12]/$tpat[$cab]['total']*100).'%';
            }
            else
            {
                $total_0='ND';
                $total_4='ND';
                $total_8='ND';
                $total_12='ND';
            }

            ?>
            <tr>
                <td align='left'><?php echo $tville[$cab];?></td>
                <td align='right'><?php echo $total_0; ?></td>
                <td align='right'><?php echo $total_4; ?></td>
                <td align='right'><?php echo $total_8;?></td>
                <td align='right'><?php echo $total_12;?></td>
            </tr>
            <?php
        }


        $total_0=round($tpat['tot'][0]/$total*100).'%';
        $total_4=round($tpat['tot'][4]/$total*100).'%';
        $total_8=round($tpat['tot'][8]/$total*100).'%';
        $total_12=round($tpat['tot'][12]/$total*100).'%';
        ?>
        <tr>
            <td align='left'>Total</td>
            <td align='right'><?php echo $total_0; ?></td>
            <td align='right'><?php echo $total_4; ?></td>
            <td align='right'><?php echo $total_8;?></td>
            <td align='right'><?php echo $total_12;?></td>
        </tr>
        <?
        $total_0=round($tpat['eval'][0]/$total_eval*100).'%';
        $total_4=round($tpat['eval'][4]/$total_eval*100).'%';
        $total_8=round($tpat['eval'][8]/$total_eval*100).'%';
        $total_12=round($tpat['eval'][12]/$total_eval*100).'%';
        ?>
        <tr>
            <td align='left'>Total eval</td>
            <td align='right'><?php echo $total_0; ?></td>
            <td align='right'><?php echo $total_4; ?></td>
            <td align='right'><?php echo $total_8;?></td>
            <td align='right'><?php echo $total_12;?></td>
        </tr>
        <?
        $total_0=round($tpat['eval2'][0]/$total_eval2*100).'%';
        $total_4=round($tpat['eval2'][4]/$total_eval2*100).'%';
        $total_8=round($tpat['eval2'][8]/$total_eval2*100).'%';
        $total_12=round($tpat['eval2'][12]/$total_eval2*100).'%';
        ?>
        <tr>
            <td align='left'>Total cab 2005</td>
            <td align='right'><?php echo $total_0; ?></td>
            <td align='right'><?php echo $total_4; ?></td>
            <td align='right'><?php echo $total_8;?></td>
            <td align='right'><?php echo $total_12;?></td>
        </tr>
        <?
        $total_0=round($tpat['eval3'][0]/$total_eval3*100).'%';
        $total_4=round($tpat['eval3'][4]/$total_eval3*100).'%';
        $total_8=round($tpat['eval3'][8]/$total_eval3*100).'%';
        $total_12=round($tpat['eval3'][12]/$total_eval3*100).'%';
        ?>
        <tr>
            <td align='left'>Total cab 2006</td>
            <td align='right'><?php echo $total_0; ?></td>
            <td align='right'><?php echo $total_4; ?></td>
            <td align='right'><?php echo $total_8;?></td>
            <td align='right'><?php echo $total_12;?></td>
        </tr>

    </table>
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
        echo "<br><br>";

        tableau($date);

        $mois=$mois-3;

        if($mois<=0)
        {
            $mois=$mois+12;
            $annee--;
        }
    }
    ?>
    <sup>1</sup>Proportion de diabétiques intégrés il y a moins de 4 mois<br>
    <sup>2</sup>Proportion de diabétiques intégrés entre 4 et 8 mois<br>
    <sup>3</sup>Proportion de diabétiques intégrés entre 8 et 12 mois<br>
    <sup>3</sup>Proportion de diabétiques intégrés il y a plus de 12 mois<br>
    <?
}

//arrêté trimestriels
function tableau($date)
{
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";



    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, id, dsuivi ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "AND suivi_diabete.dsuivi<='$date' ".
            "ORDER BY cabinet, id, dsuivi ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab][0]=0;
            $tpat[$cab][4]=0;
            $tpat[$cab][8]=0;
            $tpat[$cab][12]=0;
            $tpat[$cab]['total']=0;
        }

        $tpat['tot'][0]=0;
        $tpat['tot'][4]=0;
        $tpat['tot'][8]=0;
        $tpat['tot'][12]=0;
        $total=0;

        $tpat['eval'][0]=0;
        $tpat['eval'][4]=0;
        $tpat['eval'][8]=0;
        $tpat['eval'][12]=0;
        $total_eval=0;

        $tpat['eval2'][0]=0;
        $tpat['eval2'][4]=0;
        $tpat['eval2'][8]=0;
        $tpat['eval2'][12]=0;
        $total_eval2=0;

        $tpat['eval3'][0]=0;
        $tpat['eval3'][4]=0;
        $tpat['eval3'][8]=0;
        $tpat['eval3'][12]=0;
        $total_eval3=0;
        $id_prec='';

        while(list($cab, $id, $dsuivi) = mysql_fetch_row($res)) {

            if($id_prec!=$id)
            {
                $nb_mois=diffmois($dsuivi, $date);

                if($nb_mois<4)
                {
                    $tpat[$cab][0]=$tpat[$cab][0]+1;
                    $tpat['tot'][0]=$tpat['tot'][0]+1;

                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $tpat['eval'][0]=$tpat['eval'][0]+1;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $tpat['eval2'][0]=$tpat['eval2'][0]+1;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $tpat['eval3'][0]=$tpat['eval3'][0]+1;
                    }
                }
                elseif(($nb_mois>=4)&&($nb_mois<8))
                {
                    $tpat[$cab][4]=$tpat[$cab][4]+1;
                    $tpat['tot'][4]=$tpat['tot'][4]+1;

                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $tpat['eval'][4]=$tpat['eval'][4]+1;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $tpat['eval2'][4]=$tpat['eval2'][4]+1;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $tpat['eval3'][4]=$tpat['eval3'][4]+1;
                    }
                }
                elseif(($nb_mois>=8)&&($nb_mois<12))
                {
                    $tpat[$cab][8]=$tpat[$cab][8]+1;
                    $tpat['tot'][8]=$tpat['tot'][8]+1;

                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $tpat['eval'][8]=$tpat['eval'][8]+1;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $tpat['eval2'][8]=$tpat['eval2'][8]+1;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $tpat['eval3'][8]=$tpat['eval3'][8]+1;
                    }
                }
                else
                {
                    $tpat[$cab][12]=$tpat[$cab][12]+1;
                    $tpat['tot'][12]=$tpat['tot'][12]+1;

                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $tpat['eval'][12]=$tpat['eval'][12]+1;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $tpat['eval2'][12]=$tpat['eval2'][12]+1;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $tpat['eval3'][12]=$tpat['eval3'][12]+1;
                    }
                }
                $total++;

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval++;
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2++;
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3++;
                }

                $tpat[$cab]['total']=$tpat[$cab]['total']+1;

            }
        }



        ?>

        <tr>
            <td rowspan='2'></td>
            <td colspan='4' align='center'>Taux de répartition des patients suivis</td>
        </tr>
        <tr>
            <td align='center'>< 4 mois <sup>1</sup></td>
            <td align='center'>[4 - 8[ mois <sup>2</sup></td>
            <td align='center'>[8 - 12 [ mois <sup>3</sup></td>
            <td align='center'>>= 12 mois <sup>4</Sup></td>
        </tr>

        <?php

        foreach($tcabinet as $cab) {

            if($tpat[$cab]['total']!=0)
            {
                $total_0=round($tpat[$cab][0]/$tpat[$cab]['total']*100).'%';
                $total_4=round($tpat[$cab][4]/$tpat[$cab]['total']*100).'%';
                $total_8=round($tpat[$cab][8]/$tpat[$cab]['total']*100).'%';
                $total_12=round($tpat[$cab][12]/$tpat[$cab]['total']*100).'%';
            }
            else
            {
                $total_0='ND';
                $total_4='ND';
                $total_8='ND';
                $total_12='ND';
            }
            ?>
            <tr>
                <td align='left'><?php echo $tville[$cab];?></td>
                <td align='right'><?php echo $total_0; ?></td>
                <td align='right'><?php echo $total_4; ?></td>
                <td align='right'><?php echo $total_8;?></td>
                <td align='right'><?php echo $total_12;?></td>
            </tr>
            <?php
        }


        $total_0=round($tpat['tot'][0]/$total*100).'%';
        $total_4=round($tpat['tot'][4]/$total*100).'%';
        $total_8=round($tpat['tot'][8]/$total*100).'%';
        $total_12=round($tpat['tot'][12]/$total*100).'%';
        ?>
        <tr>
            <td align='left'>Total</td>
            <td align='right'><?php echo $total_0; ?></td>
            <td align='right'><?php echo $total_4; ?></td>
            <td align='right'><?php echo $total_8;?></td>
            <td align='right'><?php echo $total_12;?></td>
        </tr>
        <?

        $total_0=round($tpat['eval'][0]/$total_eval*100).'%';
        $total_4=round($tpat['eval'][4]/$total_eval*100).'%';
        $total_8=round($tpat['eval'][8]/$total_eval*100).'%';
        $total_12=round($tpat['eval'][12]/$total_eval*100).'%';
        ?>
        <tr>
            <td align='left'>Total eval</td>
            <td align='right'><?php echo $total_0; ?></td>
            <td align='right'><?php echo $total_4; ?></td>
            <td align='right'><?php echo $total_8;?></td>
            <td align='right'><?php echo $total_12;?></td>
        </tr>
        <?

        if($total_eval2==0){
            $total_0="ND";
            $total_4="ND";
            $total_8="ND";
            $total_12="ND";
        }
        else{
            $total_0=round($tpat['eval2'][0]/$total_eval2*100).'%';
            $total_4=round($tpat['eval2'][4]/$total_eval2*100).'%';
            $total_8=round($tpat['eval2'][8]/$total_eval2*100).'%';
            $total_12=round($tpat['eval2'][12]/$total_eval2*100).'%';
        }
        ?>
        <tr>
            <td align='left'>Total cab 2005</td>
            <td align='right'><?php echo $total_0; ?></td>
            <td align='right'><?php echo $total_4; ?></td>
            <td align='right'><?php echo $total_8;?></td>
            <td align='right'><?php echo $total_12;?></td>
        </tr>
        <?
        if($total_eval3==0){
            $total_0="ND";
            $total_4="ND";
            $total_8="ND";
            $total_12="ND";
        }
        else{
            $total_0=round($tpat['eval3'][0]/$total_eval3*100).'%';
            $total_4=round($tpat['eval3'][4]/$total_eval3*100).'%';
            $total_8=round($tpat['eval3'][8]/$total_eval3*100).'%';
            $total_12=round($tpat['eval3'][12]/$total_eval3*100).'%';
        }
        ?>
        <tr>
            <td align='left'>Total cab 2006</td>
            <td align='right'><?php echo $total_0; ?></td>
            <td align='right'><?php echo $total_4; ?></td>
            <td align='right'><?php echo $total_8;?></td>
            <td align='right'><?php echo $total_12;?></td>
        </tr>


    </table>

    <?php

}


# calcul de la différence en mois à partir d'un timestamp MySQL
function diffmois($date, $ref=false) {

    list($a,$m,$j)= explode('-',$date,3);

    if($ref===false)//aucune date de référence
    {
        $diff_mois = (date('Y')-$a)*12;
        $diff_mois=$diff_mois+ date('m')-$m;
        /*  if(date('m') < $m) $age--;*/
        if(date('d') < $j) $diff_mois--;
    }
    else //une date de référence au format 'yyyy-mm-dd-
    {
        list($aref, $mref, $jref)=explode('-', $ref, 3);
        $diff_mois = ($aref-$a)*12;
        $diff_mois=$diff_mois+ $mref-$m;
        /*  if(date('m') < $m) $age--;*/
        if($jref < $j) $diff_mois--;
    }
    return $diff_mois;
}

?>
</body>
</html>
