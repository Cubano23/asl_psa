<?php

require_once "Config.php";
$config = new Config();

session_start();

if(!isset($_SESSION['nom'])) {
    # pas passé par l'identification
    $debut=dirname($_SERVER['PHP_SELF']);
    $self=basename($_SERVER['PHP_SELF']);

    header("Location: $debut/ident_util.php?url=$self");
    echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
    exit;
}
set_time_limit(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-15">
    <title>Exporter des tables</title>
</head>
<body bgcolor="#FFE887">
<?php
require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

mysql_connect($serveur,$idDB,$mdpDB) or die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or die("Impossible de se connecter à la base");

$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
require("../global/entete.php");
entete_asalee("Exporter des tables");



// #- Listes des évaluations de consultations depuis l’origine 
// - Exporter des examens
// #- Cabinet PSA (fichier « account ») sans la colonne mot de passe
// #- Médecins (fichier « medecin asalee »)
// #- suivi hebdo temps passé (joint fichier « suiv hebdo passe all »
// #- Réunions de coordination médecin (fichier « suivi_reunion_medecin.csv »)
$tables = array('account', 'medecin', 'suivi_reunion_medecin', "suivi_hebdo_temps_passe","suivi_hebdo_temps_passe_infirmiere", "cardio_vasculaire_depart", "sevrage_tabac");


$records = array();
for($i=0; $i < sizeof($tables); $i++){
    $q = "SELECT * FROM ".$tables[$i];
    $r = mysql_query($q);
    $records[$tables[$i]] = mysql_num_rows($r);
}
?>

<br><br>

<table width="50%" cellspacing="0" cellpadding="5" border="1">
    <?php for($i=0; $i < sizeof($tables); $i++): ?>
        <tr>
            <td width="60%"><?php echo $tables[$i] ?></td>
            <td><i><?php echo number_format($records[$tables[$i]], 0, '', ' ').' lignes' ?></i></td>

            <?php
            if($tables	[$i]=='suivi_hebdo_temps_passe_infirmiere'){
                $linkExport='export_bdd_export_tpsinf.php';
            }
            else{
                $linkExport='export_bdd_export.php?exported='.$tables[$i];
            }
            ?>
            <td><input type="button" value="Exporter" onClick="window.open('<?php echo $linkExport;?>','_blank')"></td>
        </tr>
    <?php endfor ?>

    <tr>
        <td width="60%">suivi_hebdo_temps_passe + suivi_reunion_medecin</td>
        <td>cf suivi_hebdo_temps_passe</i></td>

        <?php
        $linkExport = $config->psa_path . '/view/stats/export/export_bdd_export_tempscomplet.php';
        ?>
        <td><input type="button" value="Exporter" onClick="window.open('<?php echo $linkExport;?>','_blank')"></td>
    </tr>


</table>
</body>
</html>
