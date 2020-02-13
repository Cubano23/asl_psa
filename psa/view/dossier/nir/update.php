<?php

include 'conn.php';

$cabinet = $_SESSION['cabinet'];
$numero = $_REQUEST['numero'];
$id = $_REQUEST['id'];
$dnaiss=   $_REQUEST['dnaiss'];
$sexe=   $_REQUEST['sexe'];
$dconsentement=   $_REQUEST['dconsentement'];
$taille=   intval($_REQUEST['taille']);
$actif =    $_REQUEST['actif'];
$dnaiss = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $dnaiss);
$dconsentement = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $dconsentement);
$sql = "UPDATE `dossier` SET `numero`='$numero', `dnaiss`='$dnaiss',`sexe`='$sexe',`taille`=$taille,`actif`='$actif',`dconsentement`='$dconsentement' ".
    "where id='$id'";


$result = @mysql_query($sql);

require_once ("Config.php");
$config = new Config();
require_once($config->webservice_path ."/AsaleeLog.php");
LogAccess("", "dossier_update", $UserIDLog, 'na', $numero,  2, "Cabinet:".$cabinet." /".$result);



if ($result){
    echo json_encode(array('success'=>true));
}
else {
    echo json_encode(array('msg'=>'Erreur'));
}
?>
