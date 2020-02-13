<?php

require_once("../persistence/ConnectionFactory.php");

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


mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter &agrave; la base");


	$req = "select * from dossier where cabinet='aubin' ";
	$sql = mysql_query($req);

	while ($row = mysql_fetch_array($sql)){
		$num = trim($row['numero']);
		$id = $row['id'];
		mysql_query("update dossier set numero = '$num' where id='$id' and cabinet = 'aubin' LIMIT 1 ");
		echo $num.'<br>';
	}		
		



?>