<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/DepistageDiabete.php");
	
	class DepistageDiabeteMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
		
		function getKeysMap(){
			return array("id"=>"id","date"=>"date");
		}
			
		function getTableName(){
			return "depistage_diabete";
		}
	
		function getLedgerName(){
			return "DepistageDiabeteMapper";
		}
	
		function getObject(){
			return new DepistageDiabete();
		}

		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;
			return "select * from ".$this->getTableName()." where id=".$propertiesArray["id"];
		}
		
 		function getExpiredExams($cabinet,$period=0){
			$query =  "select ".$this->getTableName().".id, `date`, poids, surpoids, parent_diabetique_type2, ".
					  "ant_intolerance_glucose, hypertension_arterielle, dyslipidemie_en_charge, hdl, bebe_sup_4kg, ".
					  "ant_diabete_gestationnel, corticotherapie, infection, intervention_chirugicale, autre, ".
					  "max(derniere_gly_date) as derniere_gly_date, derniere_gly_resultat, prescription_gly, ".
					  "mesure_suivi_diabete, mesure_suivi_hygieno_dietetique, ".
					  "mesure_suivi_controle_annuel, sortir_rappel, raison_sortie, ".
					  $this->getTableName().".dmaj, ".
					  "dossier.id, cabinet, numero, dnaiss, sexe, taille, actif, dossier.dmaj ".
					  "from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".$this->getTableName().
					  ".id = dossier.id  AND dossier.actif='oui' ".
					  "and mesure_suivi_controle_annuel='1' ".
					  "GROUP by numero order by numero";
			$result = $this->findAnyRows($query);
//echo $query;
			if($result == false) return false;
			return $this->buildRowArray($result);
		}


		
		function getdernierRappel($id, $derniere_gly_date){
		    $query = "SELECT id, sortir_rappel ".
					 "from ".$this->getTableName()." where id='$id' AND derniere_gly_date='$derniere_gly_date'";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getExamName(){
			return "derniere_gly_date";
		}	

		function getRappelName(){
			return "";
		}	

		function getFindDernierExamQuery($object){
			return "select * from ".$this->getTableName()." where id ='$object->id' ORDER BY `date` DESC limit 0,1";

		}
	}
?>
