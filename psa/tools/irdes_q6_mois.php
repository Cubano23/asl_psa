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


		$ecg_rcv = $ecg_dt2 = $bpco = $pied = $mono = $fondoeil = $exambio = 0 ;

		$cabinet = $cab['cabinet'];

		// pour chaque cabinet on calcule les consultations
		$req = "Select * from evaluation_infirmier as E left join dossier as D on E.id=D.id where D.cabinet = '$cabinet' AND date >= '$mois_deb' AND date <= '$mois_fin' ";
		$sql = mysql_query($req);
			#var_dump($req);exit;
			while($row = mysql_fetch_array($sql)){
				
				$typeTab = explode(",",$row['type_consultation']);

				if(in_array('rcva',$typeTab) || in_array('automesure',$typeTab)){
					if($row['ecg_seul']=='1' || $row['ecg']=='1'){
						$ecg_rcv = $ecg_rcv+1;
					}
				}

				if(in_array('suivi_diab',$typeTab) || in_array('dep_diab',$typeTab)){
					if($row['ecg_seul']=='1' || $row['ecg']=='1'){
						$ecg_dt2 = $ecg_dt2+1;
					}
					if($row['exapied']=='1'|| $row['monofil']=='1'){
						$pied = $pied+1;
					}
					if($row['monofil']=='1'){
						$mono = $mono+1;
					}
					if($row['hba']=='1'){
						$exambio = $exambio+1;
					}

				}

				if(in_array('bpco',$typeTab) || $row['spirometre_seul']=='1' || $row['spirometre']=='1'){
					$bpco = $bpco+1;
				}

				



			}
			
		

		echo utf8_encode($cabinet).';';
		echo $ecg_rcv.';'.$ecg_dt2.';';
		echo $bpco.';'.$pied.';';
		echo $mono.';non disponible;'.$exambio;
		echo '<br>';


}


		



?>