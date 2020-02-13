<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/Hemocult.php");
	
	class HemocultMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
	
		function getKeysMap(){
			return array("id"=>"id","date"=>"date");
		}
		
		function getTableName(){
			return "hemocult";
		}
	
		function getLedgerName(){
			return "HemocultMapper";
		}
	
		function getObject(){
			return new Hemocult();
		}
		
		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;
			return "select * from ".$this->getTableName()." where id=".$propertiesArray["id"];
		}
		
		
 		function getExpiredExams($cabinet,$period=0){
			$query =  "select ".$this->getTableName().".id, `date`, date_convoc, date_plaquette, ".
					"date_resultat, resultat, date_rappel, rappel, sortir_rappel, ".
					"raison_sortie, ".$this->getTableName().".dmaj, ".
					"dossier.id, cabinet, numero, dnaiss, sexe, taille, actif, dossier.dmaj ".
					"from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".$this->getTableName().
					".id = dossier.id  AND dossier.actif='oui' ".
					  "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) > CURDATE() "./* and ".
					"((`colos_date` is not NULL and DATE_ADD(`colos_date`, INTERVAL 12*rappel_colos_period MONTH) <= DATE_ADD(CURDATE(), ".
					"INTERVAL $period MONTH)))  */"order by numero, date_resultat desc";
			$result = $this->findAnyRows($query);
   
			if($result == false) return false;
			return $this->buildRowArray($result);
		}


		
		function getdernierRappel($id, $date_resultat){
		    $query = "SELECT id, sortir_rappel, date_rappel ".
					 "from ".$this->getTableName()." where id='$id' AND date_resultat='$date_resultat'";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getExamName(){
			return "date_resultat";
		}	

		function getRappelName(){
			return "date_rappel";
		}	

		function getFindDernierExamQuery($object){
			return "select * from ".$this->getTableName()." where id ='$object->id' ORDER BY `date`";

		}		

	}
?>
