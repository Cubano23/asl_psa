<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/DepistageCancerSein.php");
	
	class DepistageCancerSeinMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
		
		function getKeysMap(){
			return array("id"=>"id","date"=>"date");
		}
		
		function getTableName(){
			return "depistage_sein";
		}
	
		function getLedgerName(){
			return "DepistageCancerSeinMapper";
		}
	
		function getObject(){
			return new DepistageCancerSein();
		}

		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;
			return "select * from ".$this->getTableName()." where id=".$propertiesArray["id"];
		}
		

		function getExpiredExams($cabinet,$period=0){
			$query =  "select ".$this->getTableName().".id, `date`, ant_fam_mere, ant_fam_soeur, ant_fam_tante".
					  ", ant_fam_grandmere, ant_fam_fille, dep_type, max(mamograph_date) as mamograph_date, ".
					  " max(rappel_mammographie) as rappel_mammographie, sortir_rappel, raison_sortie, ".
					  $this->getTableName().".dmaj, ".
					  "dossier.id, cabinet, numero, dnaiss, sexe, taille, actif, dossier.dmaj ".
					  "from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".$this->getTableName().
					  ".id = dossier.id  AND dossier.actif='oui' ".
					  "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) > CURDATE() "./*
					  "and ((rappel_mammographie is not NULL and rappel_mammographie <= DATE_ADD(CURDATE(), ".
					  "INTERVAL $period MONTH))) */"GROUP by numero order by numero";
			$result = $this->findAnyRows($query);
//echo $query;
			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getdernierRappel($id, $mamograph_date){
		    $query = "SELECT id, rappel_mammographie, sortir_rappel ".
					 "from ".$this->getTableName()." where id='$id' AND mamograph_date='$mamograph_date'";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getFindDernierExamQuery($object){
			return "select * from ".$this->getTableName()." where id ='$object->id' ORDER BY `date` DESC limit 0,1";

		}	
		
		function getExamName(){
			return "mamograph_date";
		}	

		function getRappelName(){
			return "rappel_mammographie";
		}	

	}
?>
