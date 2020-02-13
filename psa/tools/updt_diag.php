<?php

require_once("../persistence/ConnectionFactory.php");

require_once("../controler/UtilityControler.php");

$serveur = 'localhost';
/*
// pierre
$idDB = 'root';
$mdpDB = 'root';
$DB = 'isas';
// rv

$idDB = 'root';
$mdpDB = 'root';
$DB = 'isas';
*/

$idDB = 'informed';
$mdpDB = 'no11iugX';
$DB = 'informed3';






mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter &agrave; la base");



function listeDiag(){

	$req = "SELECT * from sevrage_tabac where numero!='' ";
	$res = mysql_query($req);
	#echo count($res);exit;
	while($row = mysql_fetch_object($res)){
		#var_dump($row);exit;
		$results[] = $row;

	}

	return $results;
}

function add($diag){

	$req = "INSERT INTO diagnostic_educatif set
			`id_dossier` = '$diag->id_dossier',
			`type` = '$diag->type',
			`statut` = '1',
			`aspects_limitants` = '$diag->aspects_limitants',
			`aspects_facilitants` = '$diag->aspects_facilitants',
			`objectifs_patient` = '$diag->objectifs_patient',
			`created_at` = now()
			";
	
	mysql_query($req);
}

function update($diag){

	$req = "UPDATE diagnostic_educatif set
			`aspects_limitants` = '$diag->aspects_limitants',
			`aspects_facilitants` = '$diag->aspects_facilitants',
			`objectifs_patient` = '$diag->objectifs_patient',
			`updated_at` = now()
			where `id_dossier` = '$diag->id_dossier'
			AND `type` = '$diag->type'
			AND `statut` = '1'
			";
	echo $req.'<br>';
	mysql_query($req);
}


function diagExist($id_dossier,$type){

	$req = "SELECT * from diagnostic_educatif where id_dossier='$id_dossier' and statut='1' and type = '$type' ";
	$res = mysql_query($req);
	$result = mysql_fetch_object($res);
	return $result;
}

$liste = listeDiag();

		
foreach($liste as $sevrage){
	
	$date = UtilityControler::inverseDate($sevrage->date,'fr');

	$diag = new stdClass();
	$diag->id_dossier = $sevrage->numero;
	$diag->created_at = $sevrage->date;
	$diag->type = 'sevrage_tabac';
	$diag->statut = '1';
	
	if(!empty($sevrage->aspects_limitants) && $sevrage->aspects_limitants!=''){
		$diag->aspects_limitants = $date.' : '.addslashes($sevrage->aspects_limitants);
	}else{$diag->aspects_limitants = '';}
	
	if(!empty($sevrage->aspects_facilitants) && $sevrage->aspects_facilitants!=''){
		$diag->aspects_facilitants = $date.' : '.addslashes($sevrage->aspects_facilitants);
	}else{$diag->aspects_facilitants = '';}
	
	if(!empty($sevrage->objectifs_patient) && $sevrage->objectifs_patient!=''){
		$diag->objectifs_patient = $date.' : '.addslashes($sevrage->objectifs_patient);
	}else{$diag->objectifs_patient = '';}


	#var_dump($diag);
#
	if(!empty($diag->aspects_limitants) || !empty($diag->aspects_facilitants) || !empty($diag->objectifs_patient) ){


		// verifier que le diagnostic n'existe pas sinon on concaténe
		$diagExist = diagExist($diag->id_dossier,'sevrage_tabac');
		if($diagExist){

			if(!empty($diag->aspects_limitants) && $diag->aspects_limitants!='' ){
				$diag->aspects_limitants = $diagExist->aspects_limitants."
".$diag->aspects_limitants;
			}else{$diag->aspects_limitants = $diagExist->aspects_limitants;}
			if(!empty($diag->aspects_facilitants) && $diag->aspects_facilitants!='' ){
			$diag->aspects_facilitants = $diagExist->aspects_facilitants."
".$diag->aspects_facilitants;
			}else{$diag->aspects_facilitants = $diagExist->aspects_facilitants;}
			if(!empty($diag->objectifs_patient) && $diag->objectifs_patient!='' ){
			$diag->objectifs_patient = $diagExist->objectifs_patient."
".$diag->objectifs_patient;
			}else{$diag->objectifs_patient = $diagExist->objectifs_patient;}
			

			update($diag);echo 'update dossier '.$diag->id_dossier.'<br>';
		}
		else{
			add($diag);echo 'add dossier '.$diag->id_dossier.'<br>';
		}
		
	}


}		



?>