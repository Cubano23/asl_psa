<?php

session_start();
require_once("persistence/ConnectionFactory.php");
require_once("bean/GroupesDossiers.php");
require_once("persistence/DossierMapper.php");

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

$dossiers = $_POST['dossiers'];
$cabinet = $_POST['cabinet'];

$dossiersTab = explode(',',$dossiers);

$errorDossiers = array();
foreach($dossiersTab as $dossierNum){

  $dossierData = DossierMapper::getByNum($dossierNum,$cabinet);
  #echo json_encode($dossierData);exit;
  if(!$dossierData || $dossierData->actif=='non'){
    array_push($errorDossiers,$dossierNum);
    $listError .=$dossierNum.',';
  }
  else{
    #echo json_encode($dossierData);exit;
  }

}

if(count($errorDossiers)==0){
  $response['code'] = '200';
  $response['msg'] = '<span style="color:green">Les dossiers sont OK</span>';
}
else{
  $response['code'] = '201';
  $listError = substr($listError,0,-1);
  $response['cabinet'] = $dossiers;
  $response['msg'] = 'Les dossiers suivants ne sont pas connus ou inactifs : <span style="color:red">'.$listError.'</span>';
}


echo json_encode($response);



?>

