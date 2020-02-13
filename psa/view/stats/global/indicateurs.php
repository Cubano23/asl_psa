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
    <title>Indicateurs d'évaluation Asalée</title>
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

require("../global/entete.php");
//echo $loc;

entete_asalee("Indicateurs d'évaluation Asalée");
?>
<!--<table cellpadding="2" cellspacing="2" border="0"
 style="text-align: left; width: 100%;">
  <tbody>
    <tr>
      <td style="width: 20%; vertical-align: top;">
      <br>
      <img src="<?php echo $loc; ?>/images/inf79.gif" alt="logo informed79"><br>
        <a href="mailto:contact@asalee.fr"><font size="-1">contact</font></a>
      </td>
      <td style="text-align: center; vertical-align: top;">
	       <span style="font-family: arial; font-weight: bold;">
 <?php
echo "
<i><font face='times new roman' size='32'>Asalée</font><br>
<font face='times new roman'>Indicateurs d'évaluation Asalée</font></i>";
?>
           </span><br>
 <?php if(isset($_SESSION['nom'])) echo '<font size="-1"><i>'.$_SESSION['nom'].'</i></font>'; ?>
      </td>
      <td style="width: 15%; text-align: right; vertical-align: middle;">
      <img src="<?php echo $loc; ?>/images/urml.jpg" alt="logo urml"><br>
      </td>
    </tr>
  </tbody>
</table>
-->
<br><br>
<?php

# boucle principale
do {
    $repete=false;


    # étape 1 : identification du patient et de la date
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 1:
                etape_1($repete);
                break;

            # étape 2  : saisie des détails
            case 2:
                etape_2($repete);
                break;

            # étape 3  : validation des données et màj base
            case 3:
                etape_3($repete);
                break;
        }
    }
} while($repete);

# fin de traitement principal


function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self,$tcabinet, $tville, $t_tot, $t_sein;
    global $t_cogni, $t_colon, $t_uterus, $t_diab, $t_HTA, $tregion;

    $req="SELECT cabinet, total_pat, total_sein, total_cogni, total_colon, total_uterus, total_diab2, total_HTA, nom_cab, region ".
        "FROM account ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' ".
        "and cabinet!='sbirault'  and region!='' and infirmiere!=''".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $t_tot['tot']=$t_sein['tot']=$t_cogni['tot']=$t_colon['tot']=$t_uterus['tot']=$t_diab['tot']=$t_HTA['tot']=0;
    $t_tot['79']=$t_sein['79']=$t_cogni['79']=$t_colon['79']=$t_uterus['79']=$t_diab['79']=$t_HTA['79']=0;
    $t_tot['Bourgogne']=$t_sein['Bourgogne']=$t_cogni['Bourgogne']=$t_colon['Bourgogne']=$t_uterus['Bourgogne']=$t_diab['Bourgogne']=$t_HTA['Bourgogne']=0;
    $t_tot['Lorraine']=$t_sein['Lorraine']=$t_cogni['Lorraine']=$t_colon['Lorraine']=$t_uterus['Lorraine']=$t_diab['Lorraine']=$t_HTA['Lorraine']=0;
    $t_tot['Poitou-Charentes hors 79']=$t_sein['Poitou-Charentes hors 79']=$t_cogni['Poitou-Charentes hors 79']=$t_colon['Poitou-Charentes hors 79']=$t_uterus['Poitou-Charentes hors 79']=$t_diab['Poitou-Charentes hors 79']=$t_HTA['Poitou-Charentes hors 79']=0;
    $t_tot['Rhone-Alpes']=$t_sein['Rhone-Alpes']=$t_cogni['Rhone-Alpes']=$t_colon['Rhone-Alpes']=$t_uterus['Rhone-Alpes']=$t_diab['Rhone-Alpes']=$t_HTA['Rhone-Alpes']=0;

    $tcabinet=array();

    while(list($cab, $total_pat, $total_sein, $total_cogni, $total_colon, $total_uterus, $total_diab2, $total_HTA, $ville, $region) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $t_tot[$cab]=$total_pat;
        $t_sein[$cab]=$total_sein;
        $t_cogni[$cab]=$total_cogni;
        $t_colon[$cab]=$total_colon;
        $t_uterus[$cab]=$total_uterus;
        $t_diab[$cab]=$total_diab2;
        $t_HTA[$cab]=$total_HTA;
        $tville[$cab]=$ville;
        $tregion[$cab]=$region;

        if(strcasecmp($cab, 'Saint-Varent')!=0){
            $t_tot['tot']=$t_tot['tot']+$total_pat;
            $t_sein['tot']=$t_sein['tot']+$total_sein;
            $t_cogni['tot']=$t_cogni['tot']+$total_cogni;
            $t_colon['tot']=$t_colon['tot']+$total_colon;
            $t_uterus['tot']=$t_uterus['tot']+$total_uterus;
            $t_diab['tot']=$t_diab['tot']+$total_diab2;
            $t_HTA['tot']=$t_HTA['tot']+$total_HTA;

            $t_tot[$region]=$t_tot[$region]+$total_pat;
            $t_sein[$region]=$t_sein[$region]+$total_sein;
            $t_cogni[$region]=$t_cogni[$region]+$total_cogni;
            $t_colon[$region]=$t_colon[$region]+$total_colon;
            $t_uterus[$region]=$t_uterus[$region]+$total_uterus;
            $t_diab[$region]=$t_diab[$region]+$total_diab2;
            $t_HTA[$region]=$t_HTA[$region]+$total_HTA;
        }
    }

    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND actif='oui' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet, numero ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }

    $tpat['tot']=0;
    $tpat['79']=0;
    $tpat['Bourgogne']=0;
    $tpat['Lorraine']=0;
    $tpat['Poitou-Charentes hors 79']=0;
    $tpat['Rhone-Alpes']=0;

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $tpat[$cab] = $pat;
        $tpat['tot']=$tpat['tot']+$pat;

        $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+$pat;
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";


    ?>
    <br>
    <br>
    <table border=1 width='100%'>
        <tr>
            <td></td>
            <td align='center'><b> Moyenne générale</b></td>
            <td align='center'><b> Moyenne cabinets 79 </b></td>
            <td align='center'><b> Moyenne cabinets Bourgogne </b></td>
            <td align='center'><b> Moyenne cabinets Lorraine </b></td>
            <td align='center'><b> Moyenne cabinets Poitou-Charentes hors 79 </b></td>
            <td align='center'><b> Moyenne cabinets Rhone-Alpes </b></td>
            <?php
            foreach($tcabinet as $cab) {
                ?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }
            ?>
        </tr>

        <tr>
            <td>Taux de patients suivis dans Asalée</td>
            <td align="right"><?php echo round($tpat['tot']/$t_tot['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_tot['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Bourgogne']==0)?"0":round($tpat['Bourgogne']/$t_tot['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Lorraine']==0)?"0":round($tpat['Lorraine']/$t_tot['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Poitou-Charentes hors 79']==0)?"0":round($tpat['Poitou-Charentes hors 79']/$t_tot['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Rhone-Alpes']==0)?"0":round($tpat['Rhone-Alpes']/$t_tot['Rhone-Alpes']*100, 0);?>%</td>

            <?php
            foreach($tcabinet as $cab) {
                if ($t_tot[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_tot[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab, "saint-varent")==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
        </tr>

        <?php

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and dossier.cabinet!='saint-varent' ".
            "AND actif='oui' ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "and ((dsuivi is not NULL and DATE_ADD(dsuivi, ".
            "INTERVAL 5 MONTH) >= CURDATE())) ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;

        }


        ?>

        <tr>
            <td>Taux de patients diabétiques suivis dans Asalée</td>
            <td align="right"><?php echo round($tpat['tot']/$t_diab['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_diab['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Bourgogne']==0)?"0":round($tpat['Bourgogne']/$t_diab['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Lorraine']==0)?"0":round($tpat['Lorraine']/$t_diab['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Poitou-Charentes hors 79']==0)?"0":round($tpat['Poitou-Charentes hors 79']/$t_diab['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Rhone-Alpes']==0)?"0":round($tpat['Rhone-Alpes']/$t_diab['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($t_diab[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_diab[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab, "saint-varent")==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
        </tr>




        <?php

        ///taux de diabétiques 2 vus en consult : pas ok à priori

        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and dossier.cabinet!='saint-varent' ".
            "AND dossier.actif='oui' ".
            "AND suivi_diabete.dossier_id=evaluation_infirmier.id ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;


            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;

        }


        ?>

        <tr>
            <td>Taux de patients diabétiques type 2 vus en consultation</td>
            <td align="right"><?php echo round(($tpat['tot']-$tpat['Saint-Varent'])/($t_diab['tot']-$t_diab['Saint-Varent'])*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_diab['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Bourgogne']==0)?"0":round($tpat['Bourgogne']/$t_diab['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Lorraine']==0)?"0":round($tpat['Lorraine']/$t_diab['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Poitou-Charentes hors 79']==0)?"0":round($tpat['Poitou-Charentes hors 79']/$t_diab['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Rhone-Alpes']==0)?"0":round($tpat['Rhone-Alpes']/$t_diab['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($t_diab[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_diab[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab, "saint-varent")==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
        </tr>


        <?php

        //taux patientes cancer sein
        $req="SELECT cabinet, count(*) ".
            "FROM depistage_sein, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and cabinet!='Saint-varent' ".
            "AND dossier.actif='oui' ".
            "AND depistage_sein.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) > CURDATE() ".
            "and (mamograph_date is not NULL and DATE_ADD(mamograph_date,INTERVAL 25 MONTH) >= CURDATE()) ".
            "GROUP BY cabinet, dossier.id ".
            "ORDER BY cabinet ";
        //echo $req;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;
        }


        ?>

        <tr>
            <td>Taux de patientes éligibles au dépistage du cancer du sein</td>
            <td align="right"><?php echo round($tpat['tot']/$t_sein['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_sein['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_sein['Bourgogne']==0)?"0":round($tpat['Bourgogne']/$t_sein['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_sein['Lorraine']==0)?"0":round($tpat['Lorraine']/$t_sein['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_sein['Poitou-Charentes hors 79']==0)?"0":round($tpat['Poitou-Charentes hors 79']/$t_sein['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_sein['Rhone-Alpes']==0)?"0":round($tpat['Rhone-Alpes']/$t_sein['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($t_sein[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_sein[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab, "saint-varent")==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
        </tr>


        <?php

        ////////////////////////dépistages colon////////////////////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM depistage_colon, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and dossier.cabinet!='saint-varent' ".
            "AND dossier.actif='oui' ".
            "AND depistage_colon.id=dossier.id ".
            "GROUP BY cabinet, numero ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;

        }


        ?>

        <tr>
            <td>Taux de patients éligibles au dépistage du cancer du colon</td>
            <td align="right"><?php echo round($tpat['tot']/$t_colon['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_colon['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_colon['Bourgogne']==0)?"0":round($tpat['Bourgogne']/$t_colon['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_colon['Lorraine']==0)?"0":round($tpat['Lorraine']/$t_colon['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_colon['Poitou-Charentes hors 79']==0)?"0":round($tpat['Poitou-Charentes hors 79']/$t_colon['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_colon['Rhone-Alpes']==0)?"0":round($tpat['Rhone-Alpes']/$t_colon['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($t_colon[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_colon[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab, "Saint-Varent")==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
        </tr>



        <?php

        /////////////////////DEPISTAGE UTERUS//////////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM depistage_uterus, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and cabinet!='Saint-varent' ".
            "AND dossier.actif='oui' ".
            "AND depistage_uterus.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 65 YEAR) >= CURDATE() ".
            "and (depistage_uterus.date_rappel is not NULL and ".
            "DATE_ADD(depistage_uterus.date_rappel, INTERVAL 1 MONTH) >= CURDATE()) ".
            "GROUP BY cabinet, numero ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;
        }


        ?>

        <tr>
            <td>Taux de patientes éligibles au dépistage du cancer du col de l'utérus</td>
            <td align="right"><?php echo round($tpat['tot']/$t_uterus['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_uterus['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_uterus['Bourgogne']==0)?"0":round($tpat['Bourgogne']/$t_uterus['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_uterus['Lorraine']==0)?"0":round($tpat['Lorraine']/$t_uterus['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_uterus['Poitou-Charentes hors 79']==0)?"0":round($tpat['Poitou-Charentes hors 79']/$t_uterus['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_uterus['Rhone-Alpes']==0)?"0":round($tpat['Rhone-Alpes']/$t_uterus['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($t_uterus[$cab]==0)
                    $taux="ND";
                else
                {
//	    $taux="ND";
                    $taux=$tpat[$cab]/$t_uterus[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab, "Saint-Varent")==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
        </tr>




        <?php

        ///////////////////////////TROUBLES COGNITIFS//////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM trouble_cognitif, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and cabinet!='Saint-varent' ".
            "AND dossier.actif='oui' ".
            "AND trouble_cognitif.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) <= CURDATE() ".
            "and (trouble_cognitif.date_rappel is not NULL and ".
            "DATE_ADD(trouble_cognitif.date_rappel, INTERVAL 1 MONTH) >= CURDATE()) ".
            "GROUP BY cabinet, numero ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;
        }


        ?>

        <tr>
            <td>Taux de patients éligibles au dépistage des troubles cognitifs</td>
            <td align="right"><?php echo round($tpat['tot']/$t_cogni['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_cogni['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_cogni['Bourgogne']==0)?"0":round($tpat['Bourgogne']/$t_cogni['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_cogni['Lorraine']==0)?"0":round($tpat['Lorraine']/$t_cogni['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_cogni['Poitou-Charentes hors 79']==0)?"0":round($tpat['Poitou-Charentes hors 79']/$t_cogni['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_cogni['Rhone-Alpes']==0)?"0":round($tpat['Rhone-Alpes']/$t_cogni['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($t_cogni[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_cogni[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab, "Saint-Varent")==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
        </tr>


        <?php


        ///////////////////////////SUIVIS HTA//////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM hyper_tension, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and cabinet!='Saint-Varent' ".
            "AND dossier.actif='oui' ".
            "AND hyper_tension.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) >= CURDATE() ".
            "GROUP BY cabinet, numero ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;
        }


        ?>

        <tr>
            <td>Taux de patient éligibles au suivi HTA</td>
            <td align="right"><?php echo round($tpat['tot']/$t_HTA['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_HTA['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_HTA['Bourgogne']==0)?"0":round($tpat['Bourgogne']/$t_HTA['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_HTA['Lorraine']==0)?"0":round($tpat['Lorraine']/$t_HTA['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_HTA['Poitou-Charentes hors 79']==0)?"0":round($tpat['Poitou-Charentes hors 79']/$t_HTA['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_HTA['Rhone-Alpes']==0)?"0":round($tpat['Rhone-Alpes']/$t_HTA['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($t_HTA[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_HTA[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab, "Saint-Varent")==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
        </tr>


        <?php

        ////////////////////EVALUATION INFIRMIER////////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and cabinet!='saint-varent' ".
            "AND dossier.actif='oui' ".
            "AND evaluation_infirmier.id =dossier.id ".
            "GROUP BY cabinet, dossier.id ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;
        }


        ?>

        <tr>
            <td>Taux de patients vus en consultation</td>
            <td align="right"><?php echo round($tpat['tot']/$t_tot['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_tot['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Bourgogne']==0)?"0":round($tpat['Bourgogne']/$t_tot['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Lorraine']==0)?"0":round($tpat['Lorraine']/$t_tot['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Poitou-Charentes hors 79']==0)?"0":round($tpat['Poitou-Charentes hors 79']/$t_tot['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Rhone-Alpes']==0)?"0":round($tpat['Rhone-Alpes']/$t_tot['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($t_tot[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_tot[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab, "saint-varent")==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
        </tr>

    </table>
    <br>
    <br>
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

}


function tableau($date)
{
    global $tcabinet, $tville, $t_tot, $t_sein, $t_cogni, $t_colon, $t_uterus, $t_diab, $t_HTA, $tregion;

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ";

    if($date>='2008-01-01'){
        $req.="and cabinet!='saint-varent' ";
    }

    $req.=	 "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
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
    $tpat['tot']=0;
    $t_tot['tot']=$t_sein['tot']=$t_cogni['tot']=$t_colon['tot']=$t_uterus['tot']=$t_diab['tot']=$t_HTA['tot']=0;
    $t_tot['79']=$t_sein['79']=$t_cogni['79']=$t_colon['79']=$t_uterus['79']=$t_diab['79']=$t_HTA['79']=0;
    $t_tot['Bourgogne']=$t_sein['Bourgogne']=$t_cogni['Bourgogne']=$t_colon['Bourgogne']=$t_uterus['Bourgogne']=$t_diab['Bourgogne']=$t_HTA['Bourgogne']=0;
    $t_tot['Lorraine']=$t_sein['Lorraine']=$t_cogni['Lorraine']=$t_colon['Lorraine']=$t_uterus['Lorraine']=$t_diab['Lorraine']=$t_HTA['Lorraine']=0;
    $t_tot['Poitou-Charentes hors 79']=$t_sein['Poitou-Charentes hors 79']=$t_cogni['Poitou-Charentes hors 79']=$t_colon['Poitou-Charentes hors 79']=$t_uterus['Poitou-Charentes hors 79']=$t_diab['Poitou-Charentes hors 79']=$t_HTA['Poitou-Charentes hors 79']=0;
    $t_tot['Rhone-Alpes']=$t_sein['Rhone-Alpes']=$t_cogni['Rhone-Alpes']=$t_colon['Rhone-Alpes']=$t_uterus['Rhone-Alpes']=$t_diab['Rhone-Alpes']=$t_HTA['Rhone-Alpes']=0;

    $tpat['79']=0;
    $tpat['Bourgogne']=0;
    $tpat['Lorraine']=0;
    $tpat['Poitou-Charentes hors 79']=0;
    $tpat['Rhone-Alpes']=0;

    foreach($tcabinet as $cab)
    {
        $tcabinet_util[$cab]=0;
    }

    while(list($cab, $pat) = mysql_fetch_row($res)) {
//	 $tcabinet[] = $cab;
        $tpat[$cab] = $pat;
        $tpat['tot']=$tpat['tot']+$pat;

        $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+$pat;

        $t_tot[$tregion[$cab]]=$t_tot[$tregion[$cab]]+ $t_tot[$cab];
        $t_sein[$tregion[$cab]]=$t_sein[$tregion[$cab]]+$t_sein[$cab];
        $t_cogni[$tregion[$cab]]=$t_cogni[$tregion[$cab]]+$t_cogni[$cab];
        $t_colon[$tregion[$cab]]=$t_colon[$tregion[$cab]]+$t_colon[$cab];
        $t_uterus[$tregion[$cab]]=$t_uterus[$tregion[$cab]]+$t_uterus[$cab];
        $t_diab[$tregion[$cab]]=$t_diab[$tregion[$cab]]+$t_diab[$cab];
        $t_HTA[$tregion[$cab]]=$t_HTA[$tregion[$cab]]+$t_HTA[$cab];

        $t_tot['tot']=$t_tot['tot']+ $t_tot[$cab];
        $t_sein['tot']=$t_sein['tot']+$t_sein[$cab];
        $t_cogni['tot']=$t_cogni['tot']+$t_cogni[$cab];
        $t_colon['tot']=$t_colon['tot']+$t_colon[$cab];
        $t_uterus['tot']=$t_uterus['tot']+$t_uterus[$cab];
        $t_diab['tot']=$t_diab['tot']+$t_diab[$cab];
        $t_HTA['tot']=$t_HTA['tot']+$t_HTA[$cab];
        $tcabinet_util[$cab]=$t_diab[$cab];

    }

//print_r($t_diab);
    ?>


    <table border=1 width='100%'>
        <tr>
            <td></td>
            <td align='center'><b> Moyenne générale</b></td>
            <td align='center'><b> Moyenne cabinets 79</b></td>
            <td align='center'><b> Moyenne cabinets Bourgogne</b></td>
            <td align='center'><b> Moyenne cabinets Lorraine</b></td>
            <td align='center'><b> Moyenne cabinets Poitou-Charentes hors 79</b></td>
            <td align='center'><b> Moyenne cabinets Rhone-Alpes</b></td>
            <?php
            foreach($tcabinet as $cab) {
                ?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }

            ?>
        </tr>

        <tr>
            <td>Taux de patients suivis dans Asalée</td>
            <td align="right"><?php echo round($tpat['tot']/$t_tot['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_tot['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Bourgogne']==0)?"ND":round($tpat['Bourgogne']/$t_tot['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Lorraine']==0)?"ND":round($tpat['Lorraine']/$t_tot['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Poitou-Charentes hors 79']==0)?"ND":round($tpat['Poitou-Charentes hors 79']/$t_tot['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Rhone-Alpes']==0)?"ND":round($tpat['Rhone-Alpes']/$t_tot['Rhone-Alpes']*100, 0);?>%</td>
            <?php
            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_tot[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>

        <?php

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.="and dossier.Cabinet!='Saint-Varent' ";
        }

        $req.=	 "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "and ((dsuivi is not NULL and DATE_ADD(dsuivi, ".
            "INTERVAL 5 MONTH) >= '$date'  and (dsuivi<='$date'))) ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;
        }


        ?>

        <tr>
            <td>Taux de patients diabétiques suivis dans Asalée</td>
            <td align="right"><?php echo round($tpat['tot']/$t_diab['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_diab['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Bourgogne']==0)?"ND":round($tpat['Bourgogne']/$t_diab['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Lorraine']==0)?"ND":round($tpat['Lorraine']/$t_diab['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Poitou-Charentes hors 79']==0)?"ND":round($tpat['Poitou-Charentes hors 79']/$t_diab['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Rhone-Alpes']==0)?"ND":round($tpat['Rhone-Alpes']/$t_diab['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_diab[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>




        <?php

        ///taux de diabétiques 2 vus en consult : pas ok à priori

        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.="AND dossier.cabinet!='Saint-Varent' ";
        }

        $req.=	 "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND suivi_diabete.dossier_id=evaluation_infirmier.id ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "AND evaluation_infirmier.date <='$date' ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;
        }


        ?>

        <tr>
            <td>Taux de patients diabétiques type 2 vus en consultation</td>
            <td align="right"><?php echo round($tpat['tot']/$t_diab['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_diab['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Bourgogne']==0)?"ND":round($tpat['Bourgogne']/$t_diab['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Lorraine']==0)?"ND":round($tpat['Lorraine']/$t_diab['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Poitou-Charentes hors 79']==0)?"ND":round($tpat['Poitou-Charentes hors 79']/$t_diab['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_diab['Rhone-Alpes']==0)?"ND":round($tpat['Rhone-Alpes']/$t_diab['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_diab[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>


        <?php

        //taux patientes cancer sein
        $req="SELECT cabinet, count(*) ".
            "FROM depistage_sein, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.=" and dossier.cabinet!='Saint-Varent' ";
        }

        $req.=	 "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND depistage_sein.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) > '$date' ".
            "and (mamograph_date is not NULL and DATE_ADD(mamograph_date,INTERVAL 25 MONTH) >= '$date') ".
            "GROUP BY cabinet, dossier.id ".
            "ORDER BY cabinet ";
        //echo $req;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;
        }


        ?>

        <tr>
            <td>Taux de patientes éligibles au dépistage du cancer du sein</td>
            <td align="right"><?php echo round($tpat['tot']/$t_sein['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_sein['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_sein['Bourgogne']==0)?"ND":round($tpat['Bourgogne']/$t_sein['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_sein['Lorraine']==0)?"ND":round($tpat['Lorraine']/$t_sein['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_sein['Poitou-Charentes hors 79']==0)?"ND":round($tpat['Poitou-Charentes hors 79']/$t_sein['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_sein['Rhone-Alpes']==0)?"ND":round($tpat['Rhone-Alpes']/$t_sein['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_sein[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>


        <?php

        ////////////////////////dépistages colon////////////////////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM depistage_colon, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.="AND dossier.cabinet!='saint-varent' ";
        }

        $req.=	 "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND depistage_colon.id=dossier.id ".
            "AND depistage_colon.date<'$date' ".
            "GROUP BY cabinet, numero ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;
        }


        ?>

        <tr>
            <td>Taux de patients éligibles au dépistage du cancer du colon</td>
            <td align="right"><?php echo round($tpat['tot']/$t_colon['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_colon['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_colon['Bourgogne']==0)?"ND":round($tpat['Bourgogne']/$t_colon['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_colon['Lorraine']==0)?"ND":round($tpat['Lorraine']/$t_colon['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_colon['Poitou-Charentes hors 79']==0)?"ND":round($tpat['Poitou-Charentes hors 79']/$t_colon['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_colon['Rhone-Alpes']==0)?"ND":round($tpat['Rhone-Alpes']/$t_colon['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_colon[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>



        <?php

        /////////////////////DEPISTAGE UTERUS//////////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM depistage_uterus, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes'  ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.="and dossier.cabinet!='Saint-Varent' ";
        }

        $req.=	 "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND depistage_uterus.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 65 YEAR) >= '$date' ".
            "and (depistage_uterus.date_rappel is not NULL and ".
            "DATE_ADD(depistage_uterus.date_rappel, INTERVAL 1 MONTH) >= '$date') ".
            "GROUP BY cabinet, numero ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;
        }


        ?>

        <tr>
            <td>Taux de patientes éligibles au dépistage du cancer du col de l'utérus</td>
            <td align="right"><?php echo round($tpat['tot']/$t_uterus['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_uterus['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_uterus['Bourgogne']==0)?"ND":round($tpat['Bourgogne']/$t_uterus['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_uterus['Lorraine']==0)?"ND":round($tpat['Lorraine']/$t_uterus['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_uterus['Poitou-Charentes hors 79']==0)?"ND":round($tpat['Poitou-Charentes hors 79']/$t_uterus['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_uterus['Rhone-Alpes']==0)?"ND":round($tpat['Rhone-Alpes']/$t_uterus['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
//	    $taux="ND";
                    $taux=$tpat[$cab]/$t_uterus[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>




        <?php

        ///////////////////////////TROUBLES COGNITIFS//////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM trouble_cognitif, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.="and dossier.Cabinet!='Saint-Varent' ";
        }

        $req.=	 "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND trouble_cognitif.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) <= '$date' ".
            "and (trouble_cognitif.date_rappel is not NULL and ".
            "DATE_ADD(trouble_cognitif.date_rappel, INTERVAL 1 MONTH) >= '$date') ".
            "GROUP BY cabinet, numero ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;
        }


        ?>

        <tr>
            <td>Taux de patients éligibles au dépistage des troubles cognitifs</td>
            <td align="right"><?php echo round($tpat['tot']/$t_cogni['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_cogni['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_cogni['Bourgogne']==0)?"ND":round($tpat['Bourgogne']/$t_cogni['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_cogni['Lorraine']==0)?"ND":round($tpat['Lorraine']/$t_cogni['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_cogni['Poitou-Charentes hors 79']==0)?"ND":round($tpat['Poitou-Charentes hors 79']/$t_cogni['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_cogni['Rhone-Alpes']==0)?"ND":round($tpat['Rhone-Alpes']/$t_cogni['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_cogni[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>


        <?php


        ///////////////////////////SUIVIS HTA//////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM hyper_tension, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.="and dossier.cabinet!='Saint-Varent' ";
        }

        $req.=	 "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND hyper_tension.date<='$date' ".
            "AND hyper_tension.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) >= '$date' ".
            "GROUP BY cabinet, numero ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;
        }


        ?>

        <tr>
            <td>Taux de patients éligibles au suivi HTA</td>
            <td align="right"><?php echo round($tpat['tot']/$t_HTA['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_HTA['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_HTA['Bourgogne']==0)?"ND":round($tpat['Bourgogne']/$t_HTA['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_HTA['Lorraine']==0)?"ND":round($tpat['Lorraine']/$t_HTA['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_HTA['Poitou-Charentes hors 79']==0)?"ND":round($tpat['Poitou-Charentes hors 79']/$t_HTA['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_HTA['Rhone-Alpes']==0)?"ND":round($tpat['Rhone-Alpes']/$t_HTA['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    if($t_HTA[$cab]!=0)
                    {
                        $taux=$tpat[$cab]/$t_HTA[$cab]*100;
                        $taux=round($taux, 0);
                        $taux.="%";
                    }
                    else
                    {
                        $taux="ND";
                    }
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>


        <?php

        ////////////////////EVALUATION INFIRMIER////////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.="and dossier.cabinet!='Saint-Varent' ";
        }

        $req.=	 "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND evaluation_infirmier.id =dossier.id ".
            "AND evaluation_infirmier.date<'$date' ".
            "GROUP BY cabinet, dossier.id ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['79']=0;
        $tpat['Bourgogne']=0;
        $tpat['Lorraine']=0;
        $tpat['Poitou-Charentes hors 79']=0;
        $tpat['Rhone-Alpes']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            $tpat[$tregion[$cab]]=$tpat[$tregion[$cab]]+1;
        }


        ?>

        <tr>
            <td>Taux de patients vus en consultation</td>
            <td align="right"><?php echo round($tpat['tot']/$t_tot['tot']*100, 0);?>%</td>
            <td align="right"><?php echo round($tpat['79']/$t_tot['79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Bourgogne']==0)?"ND":round($tpat['Bourgogne']/$t_tot['Bourgogne']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Lorraine']==0)?"ND":round($tpat['Lorraine']/$t_tot['Lorraine']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Poitou-Charentes hors 79']==0)?"ND":round($tpat['Poitou-Charentes hors 79']/$t_tot['Poitou-Charentes hors 79']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['Rhone-Alpes']==0)?"ND":round($tpat['Rhone-Alpes']/$t_tot['Rhone-Alpes']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_tot[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>

    </table>
    <br>
    <br>
    <?php
}
?>
</body>
</html>
