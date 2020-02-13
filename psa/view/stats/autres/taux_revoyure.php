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
    <title>Indicateurs d'évaluation Asalée : nombre de patients vus en consultation</title>
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

entete_asalee("Indicateurs d'évaluation Asalée : taux de revoyure");
?>

<br><br>
<?php

$req="SELECT cabinet, nom_cab ".
    "FROM account ".
    "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' and ".
    "cabinet!='sbirault'  and region!='' and infirmiere!='' ".
    "GROUP BY nom_cab ".
    "ORDER BY nom_cab ";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
$tcabinet=array();
$t4mois=array();
$t24mois=array();

$total_4mois = 0;
$total_24mois = 0;
date_default_timezone_set('Europe/Paris');
while(list($cab, $ville) = mysql_fetch_row($res)) {
    $tcabinet[] = $cab;
    $tville[$cab]=$ville;
    $req1 = "select COUNT(DISTINCT(numero)) from evaluation_infirmier, dossier where cabinet='$cab' and evaluation_infirmier.id = dossier.id and date > '".date("Y-m-d",strtotime("-4 month"))."'";
    $req2 = "select COUNT(DISTINCT(numero)) from evaluation_infirmier, dossier where cabinet='$cab' and evaluation_infirmier.id = dossier.id and date > '".date("Y-m-d",strtotime("-24 month"))."'";

    $res1=mysql_query($req1) or die("erreur SQL:".mysql_error()."<br>$req");
    $resultat1 = mysql_fetch_row($res1);
    $total_4mois += $resultat1[0];
    $t4mois[$cab] = $resultat1[0];


    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req");
    $resultat2 = mysql_fetch_row($res2);
    $total_24mois += $resultat2[0];
    $t24mois[$cab] = $resultat2[0];
}



$mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
    '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');
$tab_date=split('-', date('Y-m-d'));
echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";






?>
<br>
<br>
<table border=1 width='100%'>
    <tr>
        <td></td>
        <td align='center'><b> Total</b></td>
        <?php foreach($tcabinet as $cab) { ?>
            <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
        <?php } ?>
    </tr>
    <tr>
        <td>Nombre de dossier différents vu en consultation depuis 24 mois</td>
        <td><?php echo $total_24mois ?></td>
        <?php foreach($tcabinet as $cab) { ?>
            <td align='center'><b><?php echo $t24mois[$cab]; ?></b></td>
        <?php } ?>
    </tr>
    <tr>
        <td>Nombre de dossier différents vu depuis les 4 derniers mois glissants</td>
        <td><?php echo $total_4mois ?></td>
        <?php foreach($tcabinet as $cab) { ?>
            <td align='center'><b><?php echo $t4mois[$cab]; ?></b></td>
        <?php } ?>
    </tr>
    <tr>
        <td>Ratio</td>
        <td><?php
            if($total_4mois!=0)  //EA 22-12-2014 division par 0
                printf("%.1f", $total_24mois/$total_4mois) ?>
        </td>
        <?php foreach($tcabinet as $cab) { ?>
            <td align='center'><b><?php
                    printf("%.1f", $t24mois[$cab]/$t4mois[$cab]); ?></b></td>
        <?php } ?>
    </tr>
</table>
<br>
<br>


</body>
</html>
