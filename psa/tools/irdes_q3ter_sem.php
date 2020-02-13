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


echo 'cabinet;Q3TerB;Q3TerC;Q3TerD;Q3TerE<br>';


while ($cab = mysql_fetch_array($sqlCabs)){


		$tps_dom = $tps_tel = $tps_col = 0 ;

		$cabinet = $cab['cabinet'];

		// pour chaque cabinet on calcule les consultations
		$req = "Select * from evaluation_infirmier as E left join dossier as D on E.id=D.id where D.cabinet = '$cabinet' AND date >= '$sem_deb' AND date <= '$sem_fin' ";
		$sql = mysql_query($req);
			#var_dump($req);exit;
			while($row = mysql_fetch_array($sql)){
				


				if($row['consult_domicile']=='1'){
					$tps_dom = $tps_dom+$row['duree'];
				}
				if($row['consult_tel']=='1'){
					$tps_tel = $tps_tel+$row['duree'];
				}
				if($row['consult_collective']=='1'){
					$tps_col = $tps_col+$row['duree'];
				}


			}
			
		

		echo utf8_decode($cabinet).';';
		echo calculHeures($tps_dom).';'.calculDM($tps_dom).';';
		echo calculHeures($tps_tel).';'.calculDM($tps_tel).';';
		echo calculHeures($tps_col).';'.calculDM($tps_col).';';
		echo '<br>';


}


		



?>