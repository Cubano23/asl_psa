<?php

require_once("../persistence/ConnectionFactory.php");
require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php');

$serveur = 'localhost';
/*
// pierre
$idDB = 'root';
$mdpDB = 'root';
$DB = 'isas';
*/

// rv
/*
$idDB = 'root';
$mdpDB = 'root';
$DB = 'informed3';
*/

$idDB = 'informed';
$mdpDB = 'no11iugX';
$DB = 'informed3';

$periode='03/2016';

mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter &agrave; la base");


function updateRecord($newInf,$cabinet,$periode){

	$updt = "UPDATE dashboard_results set infirmieres='$newInf' 
			where cabinet='$cabinet' and periode='$periode' LIMIT 1";
	$sql = mysql_query($updt);
	echo $updt.'<br>';

}



	$req = "select * from dashboard_results where periode='03/2016' ";
	$sql = mysql_query($req);

	while ($row = mysql_fetch_array($sql)){
		$cabinet = trim($row['cabinet']);
		
		// on recup les infirmières dans le cabinet

		$infs = GetLoginsByCab($cabinet);
		
		
		$newInf='';
		foreach($infs as $key=>$inf){

			$newInf .=utf8_decode($inf['prenom']).' '.utf8_decode($inf['nom']).', ';

		}
			$newInf = substr($newInf,0,-2);

			updateRecord($newInf,$cabinet,$periode);
	}


?>