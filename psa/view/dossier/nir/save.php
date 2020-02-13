<?php


include 'conn.php';

$cabinet = $_SESSION['cabinet'];
$numero = $_REQUEST['numero'];
$dnaiss=   $_REQUEST['dnaiss'];
$sexe=   $_REQUEST['sexe'];
$dconsentement=   $_REQUEST['dconsentement'];
$taille=   intval(isset($_REQUEST['taille'])?$_REQUEST['taille']: "0");
$actif =    $_REQUEST['actif'];
date_default_timezone_set('Europe/Paris');
$dcreat = date("Y-m-d");
$dnaiss = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $dnaiss);
$dconsentement = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $dconsentement);
$sql = "INSERT INTO $table(`cabinet`, `numero`, `dnaiss`, `sexe`, `taille`, `actif`, `dconsentement`, `dcreat`) VALUES ('$cabinet','$numero','$dnaiss', '$sexe', $taille, '$actif', '$dconsentement', '$dcreat')";


$result = @mysql_query($sql);

require_once ("Config.php");
$config = new Config();
require_once($config->webservice_path ."/AsaleeLog.php");
LogAccess("", "dossier_save", $UserIDLog, 'na', $numero,  1, "Cabinet:".$cabinet." /".$result);



if ($result){
    echo json_encode(array('success'=>true));
}
else {
    echo json_encode(array('msg'=>'Erreur:'.mysql_error()));
}
?>
