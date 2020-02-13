<?php

require_once("../persistence/ConnectionFactory.php");

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



function calculHeures($var){
	return round($var/60,1);
}
function calculDM($var){
	return round($var/60/3.5,1);
}


$reqCabs = "Select cabinet from evaluation_infirmier as E left join dossier as D on E.id=D.id where date >= '$mois_deb' and date <= '$mois_fin' and D.cabinet!='' group by D.cabinet order by D.cabinet ";
$sqlCabs = mysql_query($reqCabs);


echo 'cabinet;Q3bisB;Q3bisC;Q3bisD;Q3bisE<br>';
while ($cab = mysql_fetch_array($sqlCabs)){


		$cabinet = $cab['cabinet'];

		// pour chaque cabinet on calcule les consultations
		$req = "Select * from suivi_hebdo_temps_passe where cabinet = '$cabinet' AND date = '$sem_deb' ";
		$sql = mysql_query($req);
			
			
			$row1 = mysql_fetch_array($sql);
				#var_dump($req);exit;
				$nb_reu_infirmiere = $row1['nb_reunion_infirmiere'];
				$tps_reunion_infirmiere = $row1['tps_reunion_infirmiere'];

				if(!$nb_reu_infirmiere){
					$nb_reu_infirmiere=0;
				}
		
		// pour chaque cabinet on calcule les consultations
		$req2 = "Select * from suivi_reunion_medecin where cabinet = '$cabinet' AND date >= '$sem_deb' and date <= '$sem_fin' ";
		$sql2 = mysql_query($req2);
			
			$tps_reunion_medecin = $nb_reunions = 0;
			#var_dump($req2);exit;
			while($row2 = mysql_fetch_array($sql2)){
				$tps_reunion_medecin = $tps_reunion_medecin+$row2['duree'];
				$nb_reunions = $nb_reunions+1;
			}
		
			


		echo utf8_decode($cabinet).';'.$nb_reunions.';'.calculHeures($tps_reunion_medecin).';'.$nb_reu_infirmiere.';'.calculHeures($tps_reunion_infirmiere).';';
		echo '<br>';


}


		



?>