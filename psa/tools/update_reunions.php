<?php

require_once("../persistence/ConnectionFactory.php");

$serveur = 'localhost';

$env = 'ids';
#$env ='rv';
#$env = 'pierre';


switch($env){
	case 'pierre':
		$idDB = 'root';
		$mdpDB = 'root';
		$DB = 'isas';
	break;
		
	case 'rv' :
		$idDB = 'root';
		$mdpDB = 'root';
		$DB = 'isas';
	break;

	case 'ids' :
		$idDB = 'informed';
		$mdpDB = 'no11iugX';
		$DB = 'informed3';
	break;
}


mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter &agrave; la base");



function getIdMedecinByNom($prenom,$nom){

	$sql2 = "SELECT * from medecin where prenom='$prenom' and nom='$nom' ";
	echo $sql2.'<br>';
	$res2 = mysql_query($sql2);
	$data = mysql_fetch_array($res2);
	#var_dump($row);exit;
	return $data[id];
}

function listeReu(){

	$sql = "SELECT * from suivi_reunion_medecin where id_mg='' limit 10000";
	$res = mysql_query($sql);


	while($row = mysql_fetch_array($res)){
		

		$id_reu = $row['id_reu'];
		// upgrade les medecins
		$id_medecins ='';
		$medecins = explode(",",$row['medecin']);
		#var_dump($medecins);exit;
		foreach($medecins as $med){
			
			$medTab = explode(" ",$med); // prenom nom
			if(count($medTab) > 2){
				$id_med = getIdMedecinByNom(trim($medTab[0]).' '.trim($medTab[1]),trim($medTab[2]));echo '3';
			}
			else{
				$id_med = getIdMedecinByNom(trim($medTab[0]),trim($medTab[1]));echo '2';
			}
			$id_medecins .=$id_med.',';
		}
		$id_mg = substr($id_medecins,0,-1);
		
		$updt = "UPDATE suivi_reunion_medecin set id_mg = '$id_mg' where id_reu='$id_reu' ";
		#echo $updt;
		$result = mysql_query($updt);
		$id_mg = '';
		// upgrade les infirmieres
		

	
	}
	#var_dump($rows);
	return $rows;

}


$liste = listeReu();
#2/on récupere les mg on split, on récupere les id_mg










?>