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

function getInfosMG($nom, $cabinet){

	$sql = "select * from medecin where concat(prenom,' ',nom) = '$nom' ";
	#echo $sql;
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	return $row;
}


function updatereu($idreu,$idmg){
	
	$sql = "update suivi_reunion_medecin set id_mg='$idmg' where id_reu = '$idreu' LIMIT 1 ";
	$res = mysql_query($sql);

}

function listeReu(){

	$sql = "SELECT * from suivi_reunion_medecin where medecin!='nc' ";
	$res = mysql_query($sql);

	$totalErreur = 0;
	while($row = mysql_fetch_array($res)){
		

		
		$medecins = explode(",", $row['medecin']);
		$id_mg = explode(",", $row['id_mg']);
		
		foreach($id_mg as $m){
			if($m==''){
				
				if($row['medecin']!='Sectionner un médecin'){
					
						#echo $row['medecin'].'<br/>';
						echo '<p style="color:red">'.$row['id_reu'].' '.$row['cabinet'].' '.$row['medecin'].' => '.$row['id_mg'].'</p>';
						$mginfos = getInfosMG($row['medecin'],$cabinet);

						if($mginfos!=''){
							#echo '<p>Trouvé => '.$mginfos['id'].'</p>';
							#updateReu($row['id_reu'],$mginfos['id']);

						}
					$totalErreur = $totalErreur+1;
					}
				
				
				
				
			}
			else{
				#echo '<p>'.$row['id_reu'].' OK</p>';
			}
		}


	
	}
	echo '<p>'.$totalErreur.' Erreurs identifiées</p>';

}


$liste = listeReu();
#2/on récupere les mg on split, on récupere les id_mg










?>