<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/DepistageCancerUterus.php");
	
	class DepistageCancerUterusMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
		
		function getKeysMap(){
			return array("id"=>"id","date"=>"date");
		}
		
		function getTableName(){
			return "depistage_uterus";
		}
	
		function getLedgerName(){
			return "DepistageCancerUterusMapper";
		}
	
		function getObject(){
			return new DepistageCancerUterus();
		}

		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;
			return "select * from ".$this->getTableName()." where id=".$propertiesArray["id"];
		}
		

		function getExpiredExams($cabinet,$period=0){
			$query =  "select ".$this->getTableName().".id, `date`, max(date_frottis) as date_frottis, frottis_normal, ".
					  "max(date_rappel) as date_rappel, avis_medecin, sortir_rappel, raison_sortie, sortir_rappel, raison_sortie, ".
					  $this->getTableName().".dmaj, ".
					  "dossier.id, cabinet, numero, dnaiss, sexe, taille, actif, dossier.dmaj ".
					  "from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".$this->getTableName().
					  ".id = dossier.id  AND dossier.actif='oui' ".
					  "AND DATE_ADD(dnaiss,INTERVAL 65 YEAR) > CURDATE() "./*and ".
					  "((date_rappel is not NULL and date_rappel <= DATE_ADD(CURDATE(), ".
					  "INTERVAL $period MONTH))) */"GROUP by numero order by numero";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		
		function getdernierRappel($id, $date_frottis){
		    $query = "SELECT id, date_rappel, sortir_rappel ".
					 "from ".$this->getTableName()." where id='$id' AND date_frottis='$date_frottis'";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getExamName(){
			return "date_frottis";
		}	

		function getRappelName(){
			return "date_rappel";
		}	

		function getFindDernierExamQuery($object){
			return "select * from ".$this->getTableName()." where id ='$object->id' ORDER BY `date` DESC limit 0,1";

		}
	}
?>
