<?php

require_once("../persistence/ConnectionFactory.php");

require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;


#$env = 'herve';


$sem_deb = '2015-06-08';
$sem_fin = '2015-06-14';

$mois_deb = '2015-05-18';
$mois_fin = '2015-06-14';

switch($env){

	case 'herve' :
	$idDB = 'root';
	$mdpDB = 'root';
	$DB = 'isas';
	break;

	default:
	$idDB = 'informed';
	$mdpDB = 'no11iugX';
	$DB = 'informed3';
	break;

}
$serveur = 'localhost';

mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter &agrave; la base");

function calculBilan($suivi_diab,$dep_diab,$rcva,$bpco,$cognitif,$autres,$automesure,$mixte){
	$result = (($suivi_diab*0.25) + ($dep_diab*0.25) + ($rcva*0.25) + ($bpco*0.2) + ($cognitif*0.1) + ($autres*0.2) + ($cognitif*0.1));
	return $result;
}

function calculHeures($var){
	return round($var/60,1);
}
function calculDM($var){
	return round($var/60/3.5,1);
}

/*
Colonne G, préparation bilan consultations délégués
 //  Ces taux sont diff&eacute;renci&eacute;s pour les diff&eacute;rents protocoles avec les coefficients suivant :
                          //Suivi diabète –> taux 0,25
                          //D&eacute;pistage du Diabète type 2 –> taux 0,25
                          //Suivi du patient RCVA –> taux 0,25
                          //Rep&eacute;rage BPCO tabagique –> taux 0,2
                          //Rep&eacute;rage trouble cognitif –> taux 0,1
                          //H&eacute;moccult –> taux 0
                          //D&eacute;pistage cancer du sein –> taux 0
                          //D&eacute;pistage cancer du colon –> taux 0
                          //D&eacute;pistage cancer de l'ut&eacute;rus –> taux 0
                          //D&eacute;pistage cancer du sein –> taux 0
                          //Autres0,2
                          //Automesure -> 0,10

    $tempsPrepaBilanConsultation = (($TpsConsultation['suivi_diab']*0.25) + ($TpsConsultation['dep_diab']*0.25) + ($TpsConsultation['rcva']*0.25) +
    ($TpsConsultation['bpco']*0.2) + ($TpsConsultation['cognitif']*0.1) + ($TpsConsultation['autres']*0.2) + ($TpsConsultation['cognitif']*0.1));
 */


$reqCabs = "Select cabinet from evaluation_infirmier as E left join dossier as D on E.id=D.id where date >= '$mois_deb' and date <= '$mois_fin' and D.cabinet!='' group by D.cabinet order by D.cabinet ";
$sqlCabs = mysql_query($reqCabs);


echo 'Q2A;Q2B;Q2C<br>';
while ($cab = mysql_fetch_array($sqlCabs)){

	$cabinet = $cab['cabinet'];

	// on chope les infirmères du cabinet 
	
		$infirmieres = GetLoginsByCab($cabinet, &$status);

		foreach ($infirmieres as $inf) {
			#echo $cabinet.' - '.utf8_decode($inf['nom']).' '.utf8_decode($inf['prenom']).';5 semaines;';
			#echo $cabinet.';'.utf8_decode($inf['nom']).' '.utf8_decode($inf['prenom']).';';
			echo utf8_decode($inf['login']).';5 semaines;';
		echo '<br>';
		}



}


		



?>