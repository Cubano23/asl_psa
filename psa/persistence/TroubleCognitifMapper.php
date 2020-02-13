<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/TroubleCognitif.php");
	
	class TroubleCognitifMapper extends SelfManagedMapper{
		
		function getForeignKey(){
			return "id";
		}
		
		function getKeysMap(){
			return array("id"=>"id","date"=>"date");
		}
			
		function getTableName(){
			return "trouble_cognitif";
		}
	
		function getLedgerName(){
			return "TroubleCognitifMapper";
		}
	
		function getObject(){
			return new TroubleCognitif();
		}
		
  	function getObjectsByCabinet($cabinet){
		$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".id = dossier.id AND dossier.actif='oui' GROUP BY ".
				"dossier.numero ORDER BY dossier.numero";
   
		$result = $this->findAnyRows($query);
		if($result == false) return false;
		$rowsList = "";
		$count = 0;
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			$rowsList[$count] = $row;
			$count = $count + 1;
		}

		return $rowsList;
	}

		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;	
			return "select * from ".$this->getTableName()." where id=".$propertiesArray["id"];
		}
		



		// Return expired exams from any type
		function getExpiredExams($cabinet,$period=0){
			$query =  "select ".$this->getTableName().".id, max(date) as date, suivi_type, max(date_rappel) as date_rappel, dep_type, raison_dep, ".
					  "mmse_annee, mmse_saison, mmse_mois, mmse_jour_mois, mmse_jour_semaine, mmse_nom_hop, mmse_nom_ville, ".
					  "mmse_nom_dep, mmse_region, mmse_etage, mmse_cigare1, mmse_fleur1, mmse_porte1, mmse_93, ".
					  "mmse_86, mmse_79, mmse_72, mmse_65, mmse_monde, mmse_cigare2, mmse_fleur2, mmse_porte2, ".
					  "mmse_crayon, mmse_montre, mmse_repete_phrase, mmse_feuille_prise, mmse_feuille_pliee, ".
					  "mmse_feuille_jetee, mmse_fermer_yeux, mmse_ecrit_phrase, mmse_copie_dessin, gds_satisf, ".
					  "gds_renonce_act, gds_vie_vide, gds_ennui, gds_avenir_opt, gds_cata, gds_bonne_humeur, ".
					  "gds_besoin_aide, gds_prefere_seul, gds_mauvaise_mem, gds_heureux_vivre, gds_bon_rien, ".
					  "gds_energie, gds_desespere_sit, gds_sit_autres_best, iadl_telephone, iadl_transport, ".
					  "iadl_med, iadl_budget, horloge, sortir_rappel, raison_sortie, ".$this->getTableName().".dmaj ".
					  "id, cabinet, numero, dnaiss, sexe, taille, actif, dossier.dmaj ".
					  "from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".$this->getTableName().
					  ".id = dossier.id  AND dossier.actif='oui' "./*and ".
					  "(date_rappel is not NULL and date_rappel <= DATE_ADD(CURDATE(), ".
					  "INTERVAL $period MONTH)) */"GROUP by numero order by numero";
			$result = $this->findAnyRows($query);
//echo $query;
			if($result == false) return false;
			return $this->buildRowArray($result);
		}	

		function getdernierRappel($id, $date){
		    $query = "SELECT id, date_rappel, sortir_rappel ".
					 "from ".$this->getTableName()." where id='$id' AND date='$date'";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		
	}

?>
