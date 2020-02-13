<?php

session_start();
require_once("persistence/ConnectionFactory.php");
require_once("bean/Account.php");
require_once("persistence/AccountMapper.php");
require_once("persistence/EvaluationInfirmierMapper.php");
require_once("bean/GroupesDossiers.php");

global $account;

require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;

$path = 'https://'.$_SERVER['HTTP_HOST'];
#echo $path;

if($_SERVER['APPLICATION_ENV']=='dev-herve'){
    $_SESSION['id.login'] = 'arizk';
    $_SESSION['id.nom'] = 'Rizk';
    $_SESSION['id.prenom'] = 'Antoine';
    $_SESSION['id.email'] = 'antoine.rizk@gisgo.fr';
    $_SESSION['id.telephone'] = '0680118013';
    $path = 'http://'.$_SERVER['HTTP_HOST']. $config->psa_path;
}


$conn = new ConnectionFactory();
$conn->getConnection();

if(!isset($_GET['id']) || $_GET['id'] == "") {
    header('location:'. $config->psa_path .'/view/dossier/liste_groupes.php');
}

if(GroupesDossiers::disableGroupeById($_GET['id'])) {
    header('location:'. $config->psa_path .'view/dossier/liste_groupes.php');
}