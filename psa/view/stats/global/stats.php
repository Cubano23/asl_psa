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
    <title>Statistiques Asalée</title>
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

entete_asalee("Statistiques : suivi d'activité");
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
<font face='times new roman'>Statistiques : suivi du trafic</font></i>";
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

    # fenêtre glissante:
    if (isset($_GET['mois']) && isset($_GET['annee']))
    {
        etape_2($repete);
        exit;
    }

    # étape 1 : identification du patient et de la date
    if (!isset($_POST['etape'])) {
        etape_2($repete);
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
    global $message,$Dossier,$Cabinet, $deval, $self;


    echo "<b>Statistiques consolidées ";

    for ($i=2004; $i<date('Y'); $i++)
    {
        echo $i.'-';
    }

    echo date('Y')."</b><br>";

    $req="SELECT cabinet, nom_cab, region ".
        "FROM account ".
        "WHERE region!='' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $reg=array();
    $tcabinet=array();
    while(list($cab, $ville, $region) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tville[$cab]=$ville;
        if(!in_array($region, $reg)){
            $reg[]=$region;
        }

        $regions[$cab]=$region;

        $tpat[$region]=0;

    }

    sort($reg);

    $req="SELECT dossier.cabinet, count(*) ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet!='zTest' and dossier.cabinet!='irdes'  and dossier.cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' and dossier.cabinet=account.cabinet ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }

    $total_pat=0;

    while(list($cab, $pat) = mysql_fetch_row($res)) {

        if(isset($regions[$cab])){

            $tpat[$cab] = $pat;
            $total_pat+=$pat;

            $tpat[$regions[$cab]]=$tpat[$regions[$cab]]+$pat;
        }

    }

    ?>
    <br>
    <br>
    <table border=1 width='100%'>
        <tr>
            <td></td><td align="center"><b>total</b></td>
            <?php
            foreach($reg as $region){
                echo "<td align='center'><b>total $region</b></td>";
            }

            foreach($tville as $cab =>$nom_cab) {
                if($_SESSION["national"]==1){
                    echo "<td align='center'><b>$nom_cab</b></td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='center'><b>$nom_cab</b></td>";
                    }
                }
            }
            ?>
        </tr>
        <tr>
            <td width='20%'>Total patients</td><td align='right'><?php echo $total_pat; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat[$region]."</td>";
            }
            foreach($tville as $cab=>$ville) {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>
        <?php

        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_diabete, dossier
	 WHERE suivi_diabete.dossier_id=dossier.id
	 AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes'
	 and dossier.cabinet!='sbirault' 
	 GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat_sdiab[$cab]="";
            $tpat_sdiab[$regions[$cab]]=0;
        }

        $total_sdiab=0;

        while (list($cab_sdiab, $pat_sdiab) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_sdiab])){
                $tpat_sdiab[$cab_sdiab]=$pat_sdiab;
                $total_sdiab+=$pat_sdiab;
                $tpat_sdiab[$regions[$cab_sdiab]]+=$pat_sdiab;
            }
        }

        ?>
        <tr>
            <td>Suivis diabète</td><td align='right'><?php echo $total_sdiab; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_sdiab[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_sdiab[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_sdiab[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>

        <?php

        /* $req="SELECT cabinet, COUNT(*)
             FROM inf79_reponses
             WHERE doc='depistage_diabete'
             GROUP BY cabinet
             ORDER BY cabinet";
        */
        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_diabete as dep, dossier
	 WHERE dep.id=dossier.id
	 AND cabinet!='zTest' and cabinet!='irdes' and cabinet!='ergo' and dossier.cabinet!='jgomes'
	 and dossier.cabinet!='sbirault' 
	 GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat_diab[$cab]="";
            $tpat_diab[$regions[$cab]]=0;
        }

        $total_diab=0;

        while (list($cab_diab, $pat_diab) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_diab])){
                $tpat_diab[$cab_diab]=$pat_diab;
                $total_diab+=$pat_diab;
                if(!isset($tpat_diab[$regions[$cab_diab]])){
                    $tpat_diab[$regions[$cab_diab]]=0;
                }
                $tpat_diab[$regions[$cab_diab]]+=$pat_diab;
            }
        }

        ?>
        <tr>
            <td>Dépistages diabète</td><td align='right'><?php echo $total_diab; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_diab[$region]."</td>";
            }
            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_diab[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_diab[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


        <?php
        /*$req="SELECT cabinet, COUNT(*)
             FROM inf79_reponses
             WHERE doc='depistage_sein'
             GROUP BY cabinet
             ORDER BY cabinet";
        */
        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_sein as dep, dossier
	 WHERE dep.id=dossier.id
	 AND cabinet!='zTest' and cabinet!='irdes' and cabinet!='ergo' and dossier.cabinet!='jgomes'
	 and dossier.cabinet!='sbirault' 
	 GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat_sein[$cab]="";
            $tpat_sein[$regions[$cab]]=0;
        }

        $total_sein=0;

        while (list($cab_sein, $pat_sein) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_sein])){
                $tpat_sein[$cab_sein]=$pat_sein;
                $total_sein+=$pat_sein;

                if(!isset($tpat_sein[$regions[$cab_sein]])){
                    $tpat_sein[$regions[$cab_sein]]=0;
                }
                $tpat_sein[$regions[$cab_sein]]+=$pat_sein;
            }
        }

        ?>
        <tr>
            <td>Dépistages du cancer du sein</td><td  align='right'><?php echo $total_sein; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_sein[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_sein[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_sein[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>




        <?php
        /*$req="SELECT cabinet, COUNT(*)
             FROM inf79_reponses
             WHERE doc='depistage_colon'
             GROUP BY cabinet
             ORDER BY cabinet";*/
        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_colon as dep, dossier
	 WHERE dep.id=dossier.id
	 AND cabinet!='zTest' and cabinet!='irdes' and cabinet!='ergo' and dossier.cabinet!='jgomes'
	 and dossier.cabinet!='sbirault' 
	 GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat_colon[$cab]="";
            $tpat_colon[$regions[$cab]]=0;
        }

        $total_colon=0;

        while (list($cab_colon, $pat_colon) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_colon])){
                $tpat_colon[$cab_colon]=$pat_colon;
                $total_colon+=$pat_colon;

                if(!isset($tpat_colon[$regions[$cab_colon]])){
                    $tpat_colon[$regions[$cab_colon]]=0;
                }
                $tpat_colon[$regions[$cab_colon]]+=$pat_colon;
            }
        }

        ?>
        <tr>
            <td>Dépistages du cancer du colon</td><td  align='right'><?php echo $total_colon; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_colon[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_colon[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_colon[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>

        <?php
        /*$req="SELECT cabinet, COUNT(*)
             FROM inf79_reponses
             WHERE doc='depistage_sein'
             GROUP BY cabinet
             ORDER BY cabinet";
        */
        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_uterus as dep, dossier
	 WHERE dep.id=dossier.id
	 AND cabinet!='zTest' and cabinet!='irdes' and cabinet!='ergo' and dossier.cabinet!='jgomes'
	 and dossier.cabinet!='sbirault' 
	 GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat_uterus[$cab]="";
            $tpat_uterus[$regions[$cab]]=0;
        }

        $total_uterus=0;
        $tpat_uterus[""]=0;

        while (list($cab_uterus, $pat_uterus) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_uterus])){
                $tpat_uterus[$cab_uterus]=$pat_uterus;
                $total_uterus+=$pat_uterus;
                $tpat_uterus[$regions[$cab_uterus]]+=$pat_uterus;
            }
        }

        ?>
        <tr>
            <td>Dépistages du cancer du col de l'utérus</td><td  align='right'><?php echo $total_uterus; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_uterus[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_uterus[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_uterus[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>

        <?php
        /*$req="SELECT cabinet, COUNT(*)
             FROM inf79_reponses
             WHERE doc='depistage_sein'
             GROUP BY cabinet
             ORDER BY cabinet";
        */
        $req="SELECT cabinet, COUNT(*)
	 FROM trouble_cognitif as dep, dossier
	 WHERE dep.id=dossier.id
	 AND cabinet!='zTest' and cabinet!='irdes' and cabinet!='ergo' and dossier.cabinet!='jgomes'
	 and dossier.cabinet!='sbirault' 
	 GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat_cognitif[$cab]="";
            $tpat_cognitif[$regions[$cab]]=0;
        }

        $total_cognitif=0;
        $tpat_cognitif[""]=0;

        while (list($cab_cognitif, $pat_cognitif) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_cognitif])){
                $tpat_cognitif[$cab_cognitif]=$pat_cognitif;
                $total_cognitif+=$pat_cognitif;
                $tpat_cognitif[$regions[$cab_cognitif]]+=$pat_cognitif;
            }
        }

        ?>
        <tr>
            <td>Dépistages des troubles cognitifs</td><td  align='right'><?php echo $total_cognitif;?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_cognitif[$region]."</Td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_cognitif[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_cognitif[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


        <?php


        $req="SELECT cabinet, COUNT(*)
	 FROM tension_arterielle_moyenne as dep, dossier
	 WHERE dep.id=dossier.id
	 AND cabinet!='zTest' and cabinet!='irdes' and cabinet!='ergo' and dossier.cabinet!='jgomes'
	 and dossier.cabinet!='sbirault' 
	 AND date_debut!='0000-00-00'
	 GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat_tension[$cab]="";
            $tpat_tension[$regions[$cab]]=0;
        }

        $total_tension=0;
        $tpat_tension[""]=0;

        while (list($cab_tension, $pat_tension) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_tension])){
                $tpat_tension[$cab_tension]=$pat_tension;
                $total_tension+=$pat_tension;
                $tpat_tension[$regions[$cab_tension]]+=$pat_tension;
            }
        }
        ?>
        <tr>
            <td>Automesures</td><td  align='right'><?php echo $total_tension; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_tension[$region]."</Td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_tension[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_tension[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>



        <?php


        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_hebdomadaire as dep
	 WHERE cabinet!='zTest' and cabinet!='irdes' and cabinet!='ergo' and cabinet!='jgomes'
	 and cabinet!='sbirault' 
	 GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat_hebdo[$cab]="";
            $tpat_hebdo[$regions[$cab]]=0;
        }

        $total_hebdo=0;
        $tpat_hebdo[""]=0;

        while (list($cab_hebdo, $pat_hebdo) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_hebdo])){
                $tpat_hebdo[$cab_hebdo]=$pat_hebdo;
                $total_hebdo+=$pat_hebdo;
                $tpat_hebdo[$regions[$cab_hebdo]]+=$pat_hebdo;
            }
        }


        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_hebdomadaire2007 as dep
	 WHERE cabinet!='zTest' and cabinet!='irdes' and cabinet!='ergo' and cabinet!='jgomes'
	 and cabinet!='sbirault' 
	 GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while (list($cab_hebdo, $pat_hebdo) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_hebdo])){
                $tpat_hebdo[$cab_hebdo]=$tpat_hebdo[$cab_hebdo]+$pat_hebdo;
                $total_hebdo+=$pat_hebdo;
                $tpat_hebdo[$regions[$cab_hebdo]]+=$pat_hebdo;
            }
        }
        ?>
        <tr>
            <td>Suivis Hebdomadaires</td><td  align='right'><?php echo $total_hebdo; ?></td>

            <?php
            foreach($reg as $region){
                echo "<td align='right'>".$tpat_hebdo[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_hebdo[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_hebdo[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>

        <?php


        $req="SELECT cabinet, COUNT(*)
	 FROM cardio_vasculaire_depart as dep, dossier
	 WHERE dep.id=dossier.id
	 AND cabinet!='zTest' and cabinet!='irdes' and cabinet!='ergo' and dossier.cabinet!='jgomes'
	 GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat_hta[$cab]="";
            $tpat_hta[$regions[$cab]]=0;
        }

        $total_hta=0;
        $tpat_hta[""]=0;

        while (list($cab_hta, $pat_hta) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_hta])){
                $tpat_hta[$cab_hta]=$pat_hta;
                $total_hta+=$pat_hta;
                $tpat_hta[$regions[$cab_hta]]+=$pat_hta;
            }
        }

        ?>
        <tr>
            <td>Suivis RCVA</td><td  align='right'><?php echo $total_hta; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_hta[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_hta[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_hta[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>

        <?php
        /*$req="SELECT cabinet, COUNT(*)
             FROM inf79_reponses
             WHERE doc='evaluation_infirmier'
             GROUP BY cabinet
             ORDER BY cabinet";*/

        $req="SELECT cabinet, COUNT(*)
	 FROM evaluation_infirmier as dep, dossier
	 WHERE dep.id=dossier.id
	 AND cabinet!='zTest' and cabinet!='irdes' and cabinet!='ergo' and dossier.cabinet!='jgomes'
	 and dossier.cabinet!='sbirault' 
	 GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat_infir[$cab]="";
            $tpat_infir[$regions[$cab]]=0;
        }

        $total_inf=0;
        $tpat_infir[""]=0;

        while (list($cab_infir, $pat_infir) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_infir])){
                $tpat_infir[$cab_infir]=$pat_infir;
                $total_inf+=$pat_infir;
                $tpat_infir[$regions[$cab_infir]]+=$pat_infir;
            }
        }

        ?>
        <tr>
            <td>Evaluations infirmiers</td><td  align='right'><?php echo $total_inf; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_infir[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_infir[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_infir[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


    </table>
    <br><br>
    <b></b>
    <table border='0' width='100%'>
        <tr>
            <form action="<?php echo $self; ?>" method="post" name="form">
                <input type="hidden" name="etape" value="2">
                <td><input type="submit" name="submit" size='30' value="Retour aux statistiques mensuelles"></form></td>
        </tr>

    </table>

    <br><br>
    <b>statistiques annuelles</b>
    <table border='0'>
        <tr><?php

            for ($i=2004; $i<=date('Y'); $i++)
            {
                ?>

                <form action="<?php echo $self; ?>" method="post" name="form">
                    <input type="hidden" name="etape" value="3">
                    <input type="hidden" name="annee" value="<?php echo $i; ?>">
                    <td><input type="submit" name="submit" size='30' value="<?php echo "Statistiques ".$i;?>"></form></td>
                <?php
            }
            ?>

        </tr>
    </table>

    <?php
}

function etape_2(&$repete) {
    global $message, $Dossier, $Cabinet, $deval, $self, $doc;

    if(isset($_GET['mois']) && isset($_GET['annee']))
    {
        $num_mois=$_GET['mois'];
        $annee=$_GET['annee'];
    }
    elseif(isset($_POST['mois']) && isset($_POST['annee']))
    {
        $num_mois=$_POST['mois'];
        $annee=$_POST['annee'];
    }
    else
    {
        $num_mois=date('n');
        $annee=date('Y');
    }
//print_r($_POST);

    $mois=array(1=>"Janvier", 2=>"Février", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 7=>"Juillet",
        8=>"Août", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"Décembre");

    echo "<b>Statistiques pour ".$mois[$num_mois]." ".$annee."</b>";

    $req="SELECT cabinet, nom_cab, region
	 FROM account
	 WHERE region!=''
	 GROUP BY nom_cab
	 ORDER BY nom_cab";


    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $reg=array();
    $tcabinet=array();

    while(list($cab, $ville, $region) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tville[$cab]=$ville;
        if(!in_array($region, $reg)){
            $reg[]=$region;
        }
        $regions[$cab]=$region;
        $tpat[$region]=0;
    }

    sort($reg);

    $req="SELECT dossier.cabinet, COUNT(*), nom_cab, region
	 FROM dossier, account
	 WHERE dossier.cabinet!='zTest' and dossier.cabinet!='irdes' and dossier.cabinet!='ergo' 
	 and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' 
	 and dossier.cabinet=account.cabinet
	 GROUP BY nom_cab
	 ORDER BY nom_cab";


    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }


    while(list($cab, $pat, $ville, $region) = mysql_fetch_row($res)) {
        if(isset($regions[$cab])){
            $tpat[$cab] = $pat;
            $tpat[$region]+=$pat;
        }
    }

    ?>
    <br>
    <br>
    <?php

# boutons pour faire varier le moins des statistiques

    $mois_moins=$num_mois-1;
    $mois_plus=$num_mois+1;
    $annee_moins=$annee_plus=$annee;

    if ($num_mois==1)
    {
        $mois_moins=12;
        $annee_moins=$annee-1;
    }

    if ($num_mois==12)
    {
        $mois_plus=1;
        $annee_plus=$annee+1;
    }

    if (($mois_moins=='2') && ($annee=='2004'))
    {
        echo '<table border=0><tr><td align="right">'.
            '<img src="../img/left.gif" border=0 alt="mois précédents" width=13 height=12>';
        echo ' <a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_plus.'&annee='.$annee_plus.'">'.
            '<img src="../img/right.gif" border=0 alt="mois suivant" width=13 height=12></a></td></tr>';
    }

    elseif (($num_mois==date('n')) && ($annee==date('Y')))
    {
        echo '<table border=0><tr><td align="right"><a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_moins.'&annee='.$annee_moins.'">'.
            '<img src="../img/left.gif" border=0 alt="mois précédents" width=13 height=12></a>';
        echo ' <img src="../img/right.gif" border=0 alt="mois suivants" width=13 height=12></td></tr>';
    }
    else
    {
        echo '<table border=0><tr><td align="right"><a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_moins.'&annee='.$annee_moins.'">'.
            '<img src="../img/left.gif" border=0 alt="mois précédents" width=13 height=12></a>';
        echo ' <a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_plus.'&annee='.$annee_plus.'">'.
            '<img src="../img/right.gif" border=0 alt="mois suivants" width=13 height=12></a></td></tr>';
    }
    ?>


    <table border=1 width='100%'>
        <tr>
            <td></td><td align="center"><b>total</b></td>
            <?php
            foreach($reg as $region){
                echo "<th>total $region</th>";
            }

            foreach($tville as $cab=>$nom_cab) {
                if($_SESSION["national"]==1){
                    echo "<td align='center'><b>$nom_cab</b></td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='center'><b>$nom_cab</b></td>";
                    }
                }
            }
            ?>
        </tr>
        <?php
        /*
        $req="SELECT cabinet, count(*) ".
                 "FROM inf79_patient ";
        */
        $req="SELECT cabinet, COUNT(*)
	 FROM dossier ";

        /*	if (($num_mois=='8') && ($annee=='2004'))
            {
                $req.="WHERE DATEDIFF(dmaj, '2004-09-01')<0 ";
            }
            else*/
        {
            $date_dep=$annee."-".$num_mois."-1";
            $req.="WHERE DATEDIFF(dcreat,'$date_dep')>=0 ";
            if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
                ($num_mois=="10") || ($num_mois=="12"))
                $req.="AND DATEDIFF(dcreat,'$date_dep')<=30 ";
            elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
                $req.="AND DATEDIFF(dcreat,'$date_dep')<=29 ";
            elseif ($num_mois=="2" && (($annee%4)==0))
                $req.="AND DATEDIFF(dcreat,'$date_dep')<=28 ";
            elseif ($num_mois=="2" && (($annee%4)!=0))
                $req.="AND DATEDIFF(dcreat,'$date_dep')<=27 ";
        }
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' 
				and cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        $tcabinetmois=array();

        foreach($tcabinet as $cab) {
            $tpatmois[$cab]="";
            $tpatmois[$regions[$cab]]=0;
        }

        $total_pat=0;
        $tpatmois[""]=0;


        while(list($cabmois, $patmois) = mysql_fetch_row($res)) {
            if(isset($regions[$cabmois])){
                $tcabinetmois[] = $cabmois;
                $tpatmois[$cabmois] = $patmois;
                $total_pat+=$patmois;
                $tpatmois[$regions[$cabmois]]+=$patmois;
            }
        }

        ?>
        <tr>
            <td width='20%'>Nb de patients de la période/total de patients créés depuis le début</td><td  align='right'><?php echo $total_pat;?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpatmois[$region]."/".$tpat[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpatmois[$cab]."/".$tpat[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpatmois[$cab]."/".$tpat[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


        <?php

        /*$req="SELECT cabinet, COUNT(*)
             FROM inf79_suividiabete ";
        */

        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ";

        /*	if (($num_mois=='8') && ($annee=='2004'))
            {
                $req.="WHERE DATEDIFF(dmaj, '2004-09-01')<0 ";
            }
            else*/
        {
            $date_dep=$annee."-".$num_mois."-1";
            $req.="AND DATEDIFF(dep.dsuivi,'$date_dep')>=0 ";
            if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
                ($num_mois=="10") || ($num_mois=="12"))
                $req.="AND DATEDIFF(dep.dsuivi,'$date_dep')<=30 ";
            elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
                $req.="AND DATEDIFF(dep.dsuivi,'$date_dep')<=29 ";
            elseif ($num_mois=="2" && (($annee%4)==0))
                $req.="AND DATEDIFF(dep.dsuivi,'$date_dep')<=28 ";
            elseif ($num_mois=="2" && (($annee%4)!=0))
                $req.="AND DATEDIFF(dep.dsuivi,'$date_dep')<=27 ";
        }
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' 
				and cabinet!='sbirault' ";
        $req.="GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_sdiab[$cab]="";
            $tpat_sdiab[$regions[$cab]]=0;
        }


        $total_sdiab=0;
        $tpat_sdiab[""]=0;

        while (list($cab_sdiab, $pat_sdiab) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_sdiab])){
                $tcab_sdiab[]=$cab_sdiab;
                $tpat_sdiab[$cab_sdiab]=$pat_sdiab;
                $total_sdiab+=$pat_sdiab;
                $tpat_sdiab[$regions[$cab_sdiab]]+=$pat_sdiab;
            }
        }

        ?>
        <tr>
            <td>Suivis diabète</td><td  align='right'><?php echo $total_sdiab; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_sdiab[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_sdiab[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_sdiab[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>

        <?php

        /*$req="SELECT cabinet, COUNT(*)
             FROM inf79_reponses
             WHERE doc='depistage_diabete' ";
        */

        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_diabete as dep, dossier
	 WHERE dep.id=dossier.id ";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
		and cabinet!='sbirault' ";

        /*	if (($num_mois=='8') && ($annee=='2004'))
            {
                $req.="AND DATEDIFF(dmaj, '2004-09-01')<0 ";
            }
            else*/
        {
            $date_dep=$annee."-".$num_mois."-1";
            $req.="AND DATEDIFF(dep.date,'$date_dep')>=0 ";
            if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
                ($num_mois=="10") || ($num_mois=="12"))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=30 ";
            elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=29 ";
            elseif ($num_mois=="2" && (($annee%4)==0))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=28 ";
            elseif ($num_mois=="2" && (($annee%4)!=0))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=27 ";
        }
        $req.="GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_diab[$cab]="";
            $tpat_diab[$regions[$cab]]=0;
        }


        $total_diab=0;
        $tpat_diab[""]=0;

        while (list($cab_diab, $pat_diab) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_diab])){
                $tcab_diab[]=$cab_diab;
                $tpat_diab[$cab_diab]=$pat_diab;
                $total_diab+=$pat_diab;
                $tpat_diab[$regions[$cab_diab]]+=$pat_diab;
            }
        }

        ?>
        <tr>
            <td>Dépistages diabète</td><td  align='right'><?php echo $total_diab; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_diab[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_diab[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_diab[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


        <?php
        /*$req="SELECT cabinet, COUNT(*)
             FROM inf79_reponses
             WHERE doc='depistage_sein' ";
        */

        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_sein as dep, dossier
	 WHERE dep.id=dossier.id ";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
		and cabinet!='sbirault' ";

        /*	if (($num_mois=='8') && ($annee=='2004'))
            {
                $req.="AND DATEDIFF(dmaj, '2004-09-01')<0 ";
            }
            else*/
        {
            $date_dep=$annee."-".$num_mois."-1";
            $req.="AND DATEDIFF(dep.date,'$date_dep')>=0 ";
            if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
                ($num_mois=="10") || ($num_mois=="12"))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=30 ";
            elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=29 ";
            elseif ($num_mois=="2" && (($annee%4)==0))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=28 ";
            elseif ($num_mois=="2" && (($annee%4)!=0))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=27 ";
        }
        $req.="GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_sein[$cab]="";
            $tpat_sein[$regions[$cab]]=0;
        }

        $total_sein=0;
        $tpat_sein[""]="";

        while (list($cab_sein, $pat_sein) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_sein])){
                $tcab_sein[]=$cab_sein;
                $tpat_sein[$cab_sein]=$pat_sein;
                $total_sein+=$pat_sein;
                $tpat_sein[$regions[$cab_sein]]+=$pat_sein;
            }
        }

        ?>
        <tr>
            <td>Dépistages du cancer du sein</td><td  align='right'><?php echo $total_sein;?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_sein[$region]."</Td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_sein[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_sein[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


        <?php
        /*$req="SELECT cabinet, COUNT(*)
             FROM inf79_reponses
             WHERE doc='depistage_colon' ";
        */

        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_colon as dep, dossier
	 WHERE dep.id=dossier.id ";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
		and cabinet!='sbirault' ";

        /*	if (($num_mois=='8') && ($annee=='2004'))
            {
                $req.="AND DATEDIFF(dmaj, '2004-09-01')<0 ";
            }
            else*/
        {
            $date_dep=$annee."-".$num_mois."-1";
            $req.="AND DATEDIFF(dep.date,'$date_dep')>=0 ";
            if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
                ($num_mois=="10") || ($num_mois=="12"))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=30 ";
            elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=29 ";
            elseif ($num_mois=="2" && (($annee%4)==0))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=28 ";
            elseif ($num_mois=="2" && (($annee%4)!=0))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=27 ";
        }
        $req.="GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $tpat_colon=array();

        foreach($tcabinet as $cab) {
            $tpat_colon[$cab]="";
            $tpat_colon[$regions[$cab]]=0;
        }

        $total_colon=0;
        $tpat_colon[""]="";

        while (list($cab_colon, $pat_colon) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_colon])){
                $tcab_colon[]=$cab_colon;
                $tpat_colon[$cab_colon]=$pat_colon;
                $total_colon+=$pat_colon;
                $tpat_colon[$regions[$cab_colon]]+=$pat_colon;
            }
        }

        ?>
        <tr>
            <td>Dépistages du cancer du colon</td><td  align='right'><?php echo $total_colon;?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_colon[$region]."</Td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_colon[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_colon[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


        <?php



        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_uterus as dep, dossier
	 WHERE dep.id=dossier.id ";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
		and cabinet!='sbirault' ";

        /*	if (($num_mois=='8') && ($annee=='2004'))
            {
                $req.="AND DATEDIFF(dmaj, '2004-09-01')<0 ";
            }
            else*/
        {
            $date_dep=$annee."-".$num_mois."-1";
            $req.="AND DATEDIFF(dep.date,'$date_dep')>=0 ";
            if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
                ($num_mois=="10") || ($num_mois=="12"))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=30 ";
            elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=29 ";
            elseif ($num_mois=="2" && (($annee%4)==0))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=28 ";
            elseif ($num_mois=="2" && (($annee%4)!=0))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=27 ";
        }
        $req.="GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_uterus[$cab]="";
            $tpat_uterus[$regions[$cab]]=0;
        }

        $total_uterus=0;
        $tpat_uterus[""]="";

        while (list($cab_uterus, $pat_uterus) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_uterus])){
                $tcab_uterus[]=$cab_uterus;
                $tpat_uterus[$cab_uterus]=$pat_uterus;
                $total_uterus+=$pat_uterus;
                $tpat_uterus[$regions[$cab_uterus]]+=$pat_uterus;
            }
        }

        ?>
        <tr>
            <td>Dépistages du cancer du col de l'utérus</td><td  align='right'><?php echo $total_uterus; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_uterus[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_uterus[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_uterus[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>

        <?php



        $req="SELECT cabinet, COUNT(*)
	 FROM trouble_cognitif as dep, dossier
	 WHERE dep.id=dossier.id ";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
		and cabinet!='sbirault' ";

        /*	if (($num_mois=='8') && ($annee=='2004'))
            {
                $req.="AND DATEDIFF(dmaj, '2004-09-01')<0 ";
            }
            else*/
        {
            $date_dep=$annee."-".$num_mois."-1";
            $req.="AND DATEDIFF(dep.date,'$date_dep')>=0 ";
            if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
                ($num_mois=="10") || ($num_mois=="12"))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=30 ";
            elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=29 ";
            elseif ($num_mois=="2" && (($annee%4)==0))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=28 ";
            elseif ($num_mois=="2" && (($annee%4)!=0))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=27 ";
        }
        $req.="GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_cognitif[$cab]="";
            $tpat_cognitif[$regions[$cab]]=0;
        }

        $total_cognitif=0;
        $tpat_cognitif[""]=0;

        while (list($cab_cognitif, $pat_cognitif) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_cognitif])){
                $tcab_cognitif[]=$cab_cognitif;
                $tpat_cognitif[$cab_cognitif]=$pat_cognitif;
                $total_cognitif+=$pat_cognitif;
                $tpat_cognitif[$regions[$cab_cognitif]]+=$pat_cognitif;
            }
        }

        ?>
        <tr>
            <td>Dépistages des troubles cognitifs</td><td  align='right'><?php echo $total_cognitif; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_cognitif[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_cognitif[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_cognitif[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>

        <?php


        $req="SELECT cabinet, COUNT(*)
	 FROM tension_arterielle_moyenne as dep, dossier
	 WHERE dep.id=dossier.id
	 AND cabinet!='zTest' and cabinet!='irdes' and cabinet!='ergo' and dossier.cabinet!='jgomes'
	 and dossier.cabinet!='sbirault' 
	 AND date_debut!='0000-00-00'";

        $date_dep=$annee."-".$num_mois."-1";
        $req.="AND DATEDIFF(dep.date_debut,'$date_dep')>=0 ";
        if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
            ($num_mois=="10") || ($num_mois=="12"))
            $req.="AND DATEDIFF(dep.date_debut,'$date_dep')<=30 ";
        elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
            $req.="AND DATEDIFF(dep.date_debut,'$date_dep')<=29 ";
        elseif ($num_mois=="2" && (($annee%4)==0))
            $req.="AND DATEDIFF(dep.date_debut,'$date_dep')<=28 ";
        elseif ($num_mois=="2" && (($annee%4)!=0))
            $req.="AND DATEDIFF(dep.date_debut,'$date_dep')<=27 ";

        $req.=" GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_tension[$cab]="";
            $tpat_tension[$regions[$cab]]=0;
        }

        $total_tension=0;
        $tpat_tension[""]=0;

        while (list($cab_tension, $pat_tension) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_tension])){
                $tcab_tension[]=$cab_tension;
                $tpat_tension[$cab_tension]=$pat_tension;
                $total_tension+=$pat_tension;
                $tpat_tension[$regions[$cab_tension]]+=$pat_tension;
            }
        }
        ?>
        <tr>
            <td>Automesures</td><td  align='right'><?php echo $total_tension; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_tension[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_tension[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_tension[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>




        <?php


        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_hebdomadaire as dep
	 WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo' and cabinet!='jgomes' 
	 and cabinet!='sbirault' ";


        $date_dep=$annee."-".$num_mois."-1";
        $req.="AND DATEDIFF(dep.dmaj,'$date_dep')>=0 ";
        if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
            ($num_mois=="10") || ($num_mois=="12"))
            $req.="AND DATEDIFF(dep.dmaj,'$date_dep')<=30 ";
        elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
            $req.="AND DATEDIFF(dep.dmaj,'$date_dep')<=29 ";
        elseif ($num_mois=="2" && (($annee%4)==0))
            $req.="AND DATEDIFF(dep.dmaj,'$date_dep')<=28 ";
        elseif ($num_mois=="2" && (($annee%4)!=0))
            $req.="AND DATEDIFF(dep.dmaj,'$date_dep')<=27 ";

        $req.="GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_hebdo[$cab]="";
            $tpat_hebdo[$regions[$cab]]=0;
        }

        $total_hebdo=0;
        $tpat_hebdo[""]=0;

        while (list($cab_hebdo, $pat_hebdo) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_hebdo])){
                $tcab_hebdo[]=$cab_hebdo;
                $tpat_hebdo[$cab_hebdo]=$pat_hebdo;
                $total_hebdo+=$pat_hebdo;
                $tpat_hebdo[$regions[$cab_hebdo]]+=$pat_hebdo;
            }
        }


        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_hebdomadaire2007 as dep
	 WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo' and cabinet!='jgomes' 
	 and cabinet!='sbirault' ";

        $date_dep=$annee."-".$num_mois."-1";
        $req.="AND DATEDIFF(dep.dmaj,'$date_dep')>=0 ";
        if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
            ($num_mois=="10") || ($num_mois=="12"))
            $req.="AND DATEDIFF(dep.dmaj,'$date_dep')<=30 ";
        elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
            $req.="AND DATEDIFF(dep.dmaj,'$date_dep')<=29 ";
        elseif ($num_mois=="2" && (($annee%4)==0))
            $req.="AND DATEDIFF(dep.dmaj,'$date_dep')<=28 ";
        elseif ($num_mois=="2" && (($annee%4)!=0))
            $req.="AND DATEDIFF(dep.dmaj,'$date_dep')<=27 ";

        $req.="GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while (list($cab_hebdo, $pat_hebdo) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_hebdo])){
                $tpat_hebdo[$cab_hebdo]=$tpat_hebdo[$cab_hebdo]+$pat_hebdo;
                $total_hebdo+=$pat_hebdo;
                $tpat_hebdo[$regions[$cab_hebdo]]+=$pat_hebdo;
            }
        }
        ?>
        <tr>
            <td>Suivis Hebdomadaires</td><td  align='right'><?php echo $total_hebdo;?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_hebdo[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_hebdo[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_hebdo[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>

        <?php
        $req="SELECT cabinet, COUNT(*)
	 FROM cardio_vasculaire_depart as dep, dossier
	 WHERE dep.id=dossier.id ";

        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
	and dossier.cabinet!='sbirault' ";
        /*	if (($num_mois=='8') && ($annee=='2004'))
            {
                $req.="AND DATEDIFF(dmaj, '2004-09-01')<0 ";
            }
            else*/
        {
            $date_dep=$annee."-".$num_mois."-1";
            $req.="AND DATEDIFF(dep.date,'$date_dep')>=0 ";
            if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
                ($num_mois=="10") || ($num_mois=="12"))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=30 ";
            elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=29 ";
            elseif ($num_mois=="2" && (($annee%4)==0))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=28 ";
            elseif ($num_mois=="2" && (($annee%4)!=0))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=27 ";
        }
        $req.="GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_hta[$cab]="";
            $tpat_hta[$regions[$cab]]=0;
        }

        $total_hta=0;
        $tpat_hta[""]=0;

        while (list($cab_hta, $pat_hta) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_hta])){
                $tcab_hta[]=$cab_hta;
                $tpat_hta[$cab_hta]=$pat_hta;
                $total_hta+=$pat_hta;
                $tpat_hta[$regions[$cab_hta]]+=$pat_hta;
            }
        }

        ?>



        <tr>
            <td>Suivis RCVA</td><td  align='right'><?php echo $total_hta;?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_hta[$region]."</Td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_hta[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_hta[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


        <?php
        /*$req="SELECT cabinet, COUNT(*)
             FROM inf79_reponses
             WHERE doc='evaluation_infirmier' ";
        */

        $req="SELECT cabinet, COUNT(*)
	 FROM evaluation_infirmier as dep, dossier
	 WHERE dep.id=dossier.id ";

        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
		and dossier.cabinet!='sbirault' ";
        /*	if (($num_mois=='8') && ($annee=='2004'))
            {
                $req.="AND DATEDIFF(dmaj, '2004-09-01')<0 ";
            }
            else*/
        {
            $date_dep=$annee."-".$num_mois."-1";
            $req.="AND DATEDIFF(dep.date,'$date_dep')>=0 ";
            if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
                ($num_mois=="10") || ($num_mois=="12"))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=30 ";
            elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=29 ";
            elseif ($num_mois=="2" && (($annee%4)==0))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=28 ";
            elseif ($num_mois=="2" && (($annee%4)!=0))
                $req.="AND DATEDIFF(dep.date,'$date_dep')<=27 ";
        }
        $req.="GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_infir[$cab]="";
            $tpat_infir[$regions[$cab]]=0;
        }

        $total_inf=0;
        $tpat_infir[""]=0;

        while (list($cab_infir, $pat_infir) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_infir])){
                $tcab_infir[]=$cab_infir;
                $tpat_infir[$cab_infir]=$pat_infir;
                $total_inf+=$pat_infir;
                $tpat_infir[$regions[$cab_infir]]+=$pat_infir;
            }
        }

        ?>



        <tr>
            <td>Evaluations infirmiers</td><td  align='right'><?php echo $total_inf;?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_infir[$region]."</td>";
            }
            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_infir[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_infir[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


    </table>
    <br><br>
    <b>statistiques annuelles</b>
    <table border='0'>
        <tr><?php

            for ($i=2004; $i<=date('Y'); $i++)
            {
                ?>

                <form action="<?php echo $self; ?>" method="post" name="form">
                    <input type="hidden" name="etape" value="3">
                    <input type="hidden" name="annee" value="<?php echo $i; ?>">
                    <td><input type="submit" name="submit" size='30' value="<?php echo "Statistiques ".$i;?>"></form></td>
                <?php
            }
            ?>

        </tr>
    </table>
    <br><br>
    <b>statistiques globales</b>
    <table border='0'>
        <tr>

            <form action="<?php echo $self; ?>" method="post" name="form">
                <input type="hidden" name="etape" value="1">
                <td><input type="submit" name="submit" size='30' value="Statistiques globales"></form></td>

        </tr>
    </table>
    <?php

}



//stats annuelles
function etape_3(&$repete) {
    global $message, $Dossier, $Cabinet, $deval, $self, $doc;

    if(isset($_GET['annee']))
    {
        $annee=$_GET['annee'];
    }
    else
    {
        $annee=$_POST['annee'];
    }
//print_r($_POST);

    /*
    if (($num_mois=='8') && ($annee=='2004'))
    {
        echo "<b>Statistiques avant Septembre 2004</b>";
    }
    else*/
    {
        echo "<b>Statistiques pour ".$annee."</b><br>";
    }

    /*$req="SELECT cabinet, count(*) ".
             "FROM inf79_patient ".
             "GROUP BY cabinet ".
             "ORDER BY cabinet ";
             */


    $req="SELECT cabinet, nom_cab, region
	 FROM account
	 WHERE region!=''
	 GROUP BY nom_cab
	 ORDER BY nom_cab";


    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $tcabinet=array();
    $reg=array();

    while(list($cab, $ville, $region) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tville[$cab]=$ville;

        if(!in_array($region, $reg)){
            $reg[]=$region;
        }

        $regions[$cab]=$region;

        $tpat[$region]=0;

    }

    $req="SELECT dossier.cabinet, COUNT(*), nom_cab, region
	 FROM dossier, account
	 WHERE dossier.cabinet!='zTest' and dossier.cabinet!='irdes' and dossier.cabinet!='ergo' 
	 and dossier.cabinet!='jgomes'
	 and dossier.cabinet!='sbirault' 
	 and dossier.cabinet=account.cabinet
	 GROUP BY nom_cab
	 ORDER BY nom_cab";


    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }

    $tpat[""]=0;

    while(list($cab, $pat, $ville, $region) = mysql_fetch_row($res)) {
        if(isset($regions[$cab])){
            $tpat[$cab] = $pat;
            $tpat[$region]+=$pat;
        }
    }

    ?>
    <br>
    <br>
    <?php

    ?>

    <table border=1 width='100%'>
        <tr>
            <td></td><td align="center"><b>total</b></td>
            <?php

            foreach($reg as $region){
                echo "<td align='center'><b>total $region</b></td>";
            }

            foreach($tville as $cab=>$nom_cab) {
                if($_SESSION["national"]==1){
                    echo "<td align='center'><b>".$nom_cab."</b></td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='center'><b>".$nom_cab."</b></td>";
                    }
                }
            }
            ?>
        </tr>
        <?php
        /*
        $req="SELECT cabinet, count(*) ".
                 "FROM inf79_patient ";
        */
        $req="SELECT cabinet, COUNT(*)
	 FROM dossier ";

        $req.="WHERE date_format(dcreat, '%Y')='$annee'";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
				and dossier.cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        $tcabinetmois=array();

        foreach($tcabinet as $cab) {
            $tpatmois[$cab]="";
            $tpatmois[$regions[$cab]]=0;
        }

        $total_pat=0;
        while(list($cabmois, $patmois) = mysql_fetch_row($res)) {
            if(isset($regions[$cabmois])){
                $tcabinetmois[] = $cabmois;
                $tpatmois[$cabmois] = $patmois;
                $total_pat+=$patmois;
                $tpatmois[$regions[$cabmois]]+=$patmois;
            }
        }

        ?>
        <tr>
            <td width='20%'>nb de patients de la période/total de patients créés depuis le début</td><td  align='right'><?php echo $total_pat; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpatmois[$region]."/".$tpat[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpatmois[$cab]."/".$tpat[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpatmois[$cab]."/".$tpat[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


        <?php

        /*$req="SELECT cabinet, COUNT(*)
             FROM inf79_suividiabete ";
        */

        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ";


        $req.="AND date_format(dep.dsuivi, '%Y')='$annee'";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
				and dossier.cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_sdiab[$cab]="";
            $tpat_sdiab[$regions[$cab]]=0;
        }

        $total_sdiab=0;

        while (list($cab_sdiab, $pat_sdiab) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_sdiab])){
                $tcab_sdiab[]=$cab_sdiab;
                $tpat_sdiab[$cab_sdiab]=$pat_sdiab;
                $total_sdiab+=$pat_sdiab;
                $tpat_sdiab[$regions[$cab_sdiab]]+=$pat_sdiab;
            }
        }

        ?>
        <tr>
            <td>Suivis diabète</td><td  align='right'><?php echo $total_sdiab; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_sdiab[$region]."</Td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_sdiab[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_sdiab[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>

        <?php

        /*$req="SELECT cabinet, COUNT(*)
             FROM inf79_reponses
             WHERE doc='depistage_diabete' ";
        */

        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_diabete as dep, dossier
	 WHERE dep.id=dossier.id ";

        $req.="AND date_format(dep.date, '%Y')='$annee'";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
				and dossier.cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_diab[$cab]="";
            $tpat_diab[$regions[$cab]]=0;
        }

        $total_diab=0;
        while (list($cab_diab, $pat_diab) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_diab])){
                $tcab_diab[]=$cab_diab;
                $tpat_diab[$cab_diab]=$pat_diab;
                $total_diab+=$pat_diab;
                $tpat_diab[$regions[$cab_diab]]+=$pat_diab;
            }
        }

        ?>
        <tr>
            <td>Dépistages diabète</td><td  align='right'><?php echo $total_diab; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_diab[$region]."</Td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_diab[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_diab[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


        <?php

        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_sein as dep, dossier
	 WHERE dep.id=dossier.id ";

        $req.="AND date_format(dep.date, '%Y')='$annee'";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
			and dossier.cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_sein[$cab]="";
            $tpat_sein[$regions[$cab]]=0;
        }

        $total_sein=0;

        while (list($cab_sein, $pat_sein) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_sein])){
                $tcab_sein[]=$cab_sein;
                $tpat_sein[$cab_sein]=$pat_sein;
                $total_sein+=$pat_sein;
                $tpat_sein[$regions[$cab_sein]]+=$pat_sein;
            }
        }

        ?>
        <tr>
            <td>Dépistages du cancer du sein</td><td  align='right'><?php echo $total_sein; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_sein[$region]."</Td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_sein[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_sein[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>

        <?php




        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_colon as dep, dossier
	 WHERE dep.id=dossier.id ";

        $req.="AND date_format(dep.date, '%Y')='$annee'";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
				and dossier.cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $tpat_colon=array();

        foreach($tcabinet as $cab) {
            $tpat_colon[$cab]="";
            $tpat_colon[$regions[$cab]]=0;
        }

        $total_colon=0;

        while (list($cab_colon, $pat_colon) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_colon])){
                $tcab_colon[]=$cab_colon;
                $tpat_colon[$cab_colon]=$pat_colon;
                $total_colon+=$pat_colon;
                $tpat_colon[$regions[$cab_colon]]+=$pat_colon;
            }
        }

        ?>
        <tr>
            <td>Dépistages du cancer du colon</td><td  align='right'><?php echo $total_colon; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_colon[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_colon[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_colon[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>

        <?php


        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_uterus as dep, dossier
	 WHERE dep.id=dossier.id ";

        $req.="AND date_format(dep.date, '%Y')='$annee'";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
			and dossier.cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_uterus[$cab]="";
            $tpat_uterus[$regions[$cab]]=0;
        }

        $total_uterus=0;

        while (list($cab_uterus, $pat_uterus) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_uterus])){
                $tcab_uterus[]=$cab_uterus;
                $tpat_uterus[$cab_uterus]=$pat_uterus;
                $total_uterus+=$pat_uterus;
                $tpat_uterus[$regions[$cab_uterus]]+=$pat_uterus;
            }
        }

        ?>
        <tr>
            <td>Dépistages du cancer du col de l'utérus</td><td  align='right'><?php echo $total_uterus; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_uterus[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_uterus[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_uterus[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


        <?php


        $req="SELECT cabinet, COUNT(*)
	 FROM trouble_cognitif as dep, dossier
	 WHERE dep.id=dossier.id ";

        $req.="AND date_format(dep.date, '%Y')='$annee'";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
				and dossier.cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_cognitif[$cab]="";
            $tpat_cognitif[$regions[$cab]]=0;
        }

        $total_cognitif=0;

        while (list($cab_cognitif, $pat_cognitif) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_cognitif])){
                $tcab_cognitif[]=$cab_cognitif;
                $tpat_cognitif[$cab_cognitif]=$pat_cognitif;
                $total_cognitif+=$pat_cognitif;
                $tpat_cognitif[$regions[$cab_cognitif]]+=$pat_cognitif;
            }
        }

        ?>
        <tr>
            <td>Dépistages des troubles cognitifs</td><td  align='right'><?php echo $total_cognitif; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_cognitif[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_cognitif[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_cognitif[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>

        <?php

        $req="SELECT cabinet, COUNT(*)
	 FROM tension_arterielle_moyenne as dep, dossier
	 WHERE dep.id=dossier.id
	 AND date_debut!='0000-00-00'";

        $req.="AND date_format(dep.date_debut, '%Y')='$annee'";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
				and dossier.cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_tension[$cab]="";
            $tpat_tension[$regions[$cab]]=0;
        }

        $total_tension=0;
        while (list($cab_tension, $pat_tension) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_tension])){
                $tcab_tension[]=$cab_tension;
                $tpat_tension[$cab_tension]=$pat_tension;
                $total_tension+=$pat_tension;
                $tpat_tension[$regions[$cab_tension]]+=$pat_tension;
            }
        }
        ?>
        <tr>
            <td>Automesures</td><td  align='right'><?php echo $total_tension; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_tension[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_tension[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_tension[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>




        <?php


        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_hebdomadaire as dep ";

        $req.="WHERE date_format(dep.date, '%Y')='$annee'";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' 
			and cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_hebdo[$cab]="";
            $tpat_hebdo[$regions[$cab]]=0;
        }

        $total_hebdo=0;

        while (list($cab_hebdo, $pat_hebdo) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_hebdo])){
                $tcab_hebdo[]=$cab_hebdo;
                $tpat_hebdo[$cab_hebdo]=$pat_hebdo;
                $total_hebdo+=$pat_hebdo;
                $tpat_hebdo[$regions[$cab_hebdo]]+=$pat_hebdo;
            }
        }


        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_hebdomadaire2007 as dep ";

        $req.="WHERE date_format(dep.date, '%Y')='$annee'";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' 
				and cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while (list($cab_hebdo, $pat_hebdo) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_hebdo])){
                $tpat_hebdo[$cab_hebdo]=$tpat_hebdo[$cab_hebdo]+$pat_hebdo;
                $total_hebdo+=$pat_hebdo;
                $tpat_hebdo[$regions[$cab_hebdo]]+=$pat_hebdo;
            }
        }
        ?>
        <tr>
            <td>Suivis Hebdomadaires</td><td  align='right'><?php echo $total_hebdo; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_hebdo[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_hebdo[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_hebdo[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


        <?php

        $req="SELECT cabinet, COUNT(*)
	 FROM cardio_vasculaire_depart as dep, dossier
	 WHERE dep.id=dossier.id ";

        $req.="AND date_format(dep.date, '%Y')='$annee'";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
				and dossier.cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_hta[$cab]="";
            $tpat_hta[$regions[$cab]]=0;
        }

        $total_hta=0;
        while (list($cab_hta, $pat_hta) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_hta])){
                $tcab_hta[]=$cab_hta;
                $tpat_hta[$cab_hta]=$pat_hta;
                $total_hta+=$pat_hta;
                $tpat_hta[$regions[$cab_hta]]+=$pat_hta;
            }
        }

        ?>



        <tr>
            <td>Suivis RCVA</td><td  align='right'><?php echo $total_hta; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_hta[$region]."</Td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_hta[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_hta[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>



        <?php

        $req="SELECT cabinet, COUNT(*)
	 FROM evaluation_infirmier as dep, dossier
	 WHERE dep.id=dossier.id ";

        $req.="AND date_format(dep.date, '%Y')='$annee'";
        $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
				and dossier.cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_infir[$cab]="";
            $tpat_infir[$regions[$cab]]=0;
        }

        $total_inf=0;
        while (list($cab_infir, $pat_infir) = mysql_fetch_row($res))
        {
            if(isset($regions[$cab_infir])){
                $tcab_infir[]=$cab_infir;
                $tpat_infir[$cab_infir]=$pat_infir;
                $total_inf+=$pat_infir;
                $tpat_infir[$regions[$cab_infir]]+=$pat_infir;
            }
        }

        ?>



        <tr>
            <td>Evaluations infirmiers</td><td  align='right'><?php echo $total_inf; ?></td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".$tpat_infir[$region]."</td>";
            }

            foreach ($tville as $cab=>$ville)
            {
                if($_SESSION["national"]==1){
                    echo "<td align='right'>".$tpat_infir[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='right'>".$tpat_infir[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


    </table>
    <br><br>
    <b></b>
    <table border='0' width='100%'>

        <tr>
            <form action="<?php echo $self; ?>" method="post" name="form">
                <input type="hidden" name="etape" value="2">
                <td><input type="submit" name="submit" size='30' value="Retour aux statistiques mensuelles"></form></td>
        </tr>

    </table>

    <br><br>
    <b>statistiques annuelles</b>
    <table border='0'>
        <tr><?php

            for ($i=2004; $i<=date('Y'); $i++)
            {
                ?>

                <form action="<?php echo $self; ?>" method="post" name="form">
                    <input type="hidden" name="etape" value="3">
                    <input type="hidden" name="annee" value="<?php echo $i; ?>">
                    <td><input type="submit" name="submit" size='30' value="<?php echo "Statistiques ".$i;?>"></form></td>
                <?php
            }
            ?>

        </tr>
    </table>

    <br><br>
    <b>statistiques globales</b>
    <table border='0'>
        <tr>

            <form action="<?php echo $self; ?>" method="post" name="form">
                <input type="hidden" name="etape" value="1">
                <td><input type="submit" name="submit" size='30' value="Statistiques globales"></form></td>

        </tr>
    </table>
    <?php
}
?>
</body>
</html>
