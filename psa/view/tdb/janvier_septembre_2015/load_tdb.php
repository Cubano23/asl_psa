<?php

require_once("persistence/ConnectionFactory.php");
require_once("controler/UtilityControler.php");
require_once("bean/dashboard.php");

#echo $path;
$conn = new ConnectionFactory();
$conn->getConnection();
session_start();
#var_dump($_POST);

$rep = $_GET['rep'];
$path = $_SERVER['DOCUMENT_ROOT'];

if($_SERVER['APPLICATION_ENV']=='dev-herve'){
	
	$path = $_SERVER['DOCUMENT_ROOT'];
}
$cabinet = $_SESSION['cabinet'];
$mdCab = MD5($cabinet);

$fichier = $path.'/_files/dashboard/pdf/'.$rep.'/'.$mdCab.'_'.$cabinet.'.pdf';

if(is_file($fichier)){
	
	$nom_export = $cabinet.'_'.$rep.'.pdf';

	header("Content-type: application/force-download");
    header("Content-Transfer-Encoding: Binary");
    header("Content-length: ".filesize($fichier));
    header("Content-disposition: attachment; filename=".$nom_export);

    // appeler une page en ajax ou un trus à la con pour générer des hits GA

    readfile("$fichier");


}
else{
	echo 'fichier '.$fichier.' existe pas';
}

?>


