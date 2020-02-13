<?php

include 'conn.php';

$id = 	$_GET['id'];
//	$id = $_REQUEST['id'];
$prenom = $_REQUEST['prenom'];
$nom =    $_REQUEST['nom'];
$courriel  = $_REQUEST['courriel'];
$telephone = $_REQUEST['telephone'];
$portable  = $_REQUEST['portable'];
$adeli     = $_REQUEST['adeli'];
$rpps      = $_REQUEST['rpps'];

$sql = "update $table set nom='$nom', prenom='$prenom', courriel = '$courriel ', telephone= '$telephone', portable = '$portable ', adeli = '$adeli', rpps = '$rpps' where id= $id";
$result = @mysql_query($sql);


require_once ("Config.php");
$config = new Config();

if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
{
    require_once($config->webservice_path . "/AsaleeLog.php");
//  LogAccess("psaet.asalee.fr", "mg_update", $UserIDLog, 'na', $id.' '.'/'.$prenom.' '.$nom,  2, "Modification Medecin Traitant: ".$answerLog."/".$result);
}




if ($result){
    echo json_encode(array('success'=>true));
}
else {
    echo json_encode(array('msg'=>'Erreur'));
}
?>
