<?php

session_start();
require_once("persistence/ConnectionFactory.php");
require_once("bean/GroupesDossiers.php");

#var_dump($_SESSION['cabinet']); 
require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;

$path = 'http://'.$_SERVER['HTTP_HOST'];
#echo $path;

if($_SERVER['APPLICATION_ENV']=='dev-herve'){
  $_SESSION['id.login'] = 'arizk';
  $_SESSION['id.nom'] = 'Rizk';
  $_SESSION['id.prenom'] = 'Antoine';
  $_SESSION['id.email'] = 'antoine.rizk@gisgo.fr';
  $_SESSION['id.telephone'] = '0680118013';
  $path = 'http://'.$_SERVER['HTTP_HOST']. $config->psa_path;
}

#
$conn = new ConnectionFactory();
$conn->getConnection();
session_start();
#var_dump($_POST);
#

$id_groupe = $_POST['idgroupe'];
$groupe = GroupesDossiers::getGroupeById($id_groupe);
$response['code'] = '200';
$response['libelle'] = $groupe['libelle'];
$response['commentaire'] = stripslashes($groupe['commentaire']);
$dossiersTab = json_decode($groupe['dossiers'],true);
#$dossierTab = json_decode(json_encode($dossiersTab), true);
#var_dump($dossiersTab);
foreach($dossiersTab as $dos){
	#$tab = get_object_vars($dos);
	$key = key($dos);
	$dossiers .=$key.',';
}
#var_dump($dossiers);
$response['dossiers'] = substr($dossiers,0,-1);
#$response['dossiers'] = $dossiers;
echo json_encode($response);



?>

