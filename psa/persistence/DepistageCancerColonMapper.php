<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/DepistageCancerColon.php");
	
	class DepistageCancerColonMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
	
		function getKeysMap(){
			return array("id"=>"id","date"=>"date");
		}
		
		function getTableName(){
			return "depistage_colon";
		}
	
		function getLedgerName(){
			return "DepistageCancerColonMapper";
		}
	
		function getObject(){
			return new DepistageCancerColon();
		}
		
		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;
			return "select * from ".$this->getTableName()." where id=".$propertiesArray["id"];
		}
		
		
 		function getExpiredExams($cabinet,$period=0){
			$query =  "select ".$this->getTableName().".id, `date`, ant_pere_type, ant_pere_age, ant_mere_type".
			        ", ant_mere_age, ant_fratrie_type, ant_fratrie_age, ant_collat_type, ant_collat_age, ".
			        "ant_enfants_type, ant_enfants_age, just_ant_fam, just_ant_polype, just_ant_cr_colique, ".
			        "just_ant_sg_selles, colos_date, colos_polypes, colos_dysplasie, rappel_colos_period".
					", sortir_rappel, raison_sortie, ".$this->getTableName().".dmaj, ".
					"dossier.id, cabinet, numero, dnaiss, sexe, taille, actif, dossier.dmaj ".
					"from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".$this->getTableName().
					".id = dossier.id  AND dossier.actif='oui' "./* and ".
					"((`colos_date` is not NULL and DATE_ADD(`colos_date`, INTERVAL 12*rappel_colos_period MONTH) <= DATE_ADD(CURDATE(), ".
					"INTERVAL $period MONTH)))  "GROUP by numero */"order by numero, colos_date desc";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}


		
		function getdernierRappel($id, $colos_date){
		    $query = "SELECT id, sortir_rappel ".
					 "from ".$this->getTableName()." where id='$id' AND colos_date='$colos_date'";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getExamName(){
			return "colos_date";
		}	

		function getRappelName(){
			return "";
		}	

		function getFindDernierExamQuery($object){
			return "select * from ".$this->getTableName()." where id ='$object->id' ORDER BY `date` DESC limit 0,1";

		}		

	}
?>
