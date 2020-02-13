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
$DB = 'isas';
*/

// prod

$idDB = 'informed';
$mdpDB = 'no11iugX';
$DB = 'informed3';

mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter &agrave; la base");


// parsing du fichier
#$fichier = 'split-chef1.csv';


function updateAccount($cab,$cp){

	$req = "UPDATE account set code_postal ='$cp' where cabinet='$cab' LIMIT 1 ";
	$sql = mysql_query($req);
}
		

$fichier = 'cp_cab2.csv';

$contenu=file($fichier);
		
		list( $numero_ligne, $ligne1) = each($contenu);
		while ( list( $numero_ligne, $ligne) = each( $contenu ) ) {
			if($numero_ligne!=0){
				$datas = explode(";",$ligne);

				$cab = $datas[0];
				$zip = trim($datas[1]);
				if(strlen($zip)==4){ $zip='0'.$zip; }
				updateAccount($cab,$zip);
				echo '<p>'.$cab.' -> '.$zip.'</p>';
			}
		}
		





?>