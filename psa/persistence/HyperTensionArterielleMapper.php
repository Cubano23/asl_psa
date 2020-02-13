<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/HyperTensionArterielle.php");
	
	class HyperTensionArterielleMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
	
		function getKeysMap(){
			return array("id"=>"id","date"=>"date");
		}
		
		function getTableName(){
			return "hyper_tension";
		}
	
		function getLedgerName(){
			return "HyperTensionArterielleMapper";
		}
	
		function getObject(){
			return new HyperTensionArterielle();
		}
		
		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;
			return "select * from ".$this->getTableName()." where id=".$propertiesArray["id"];
		}
		
		
// 		function getExpiredExams($cabinet,$period=0){
//			$query =  "select ".$this->getTableName().".id, `date`, ant_pere_type, ant_pere_age, ant_mere_type".
//			        ", ant_mere_age, ant_fratrie_type, ant_fratrie_age, ant_collat_type, ant_collat_age, ".
//			        "ant_enfants_type, ant_enfants_age, just_ant_fam, just_ant_polype, just_ant_cr_colique, ".
//			        "just_ant_sg_selles, max(colos_date) as colos_date, colos_polypes, colos_dysplasie, rappel_colos_period".
//					", ".$this->getTableName().".dmaj, ".
//					"dossier.id, cabinet, numero, dnaiss, sexe, taille, actif, dossier.dmaj ".
//					"from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".$this->getTableName().
//					".id = dossier.id  AND dossier.actif='oui' "./* and ".
//					"((`colos_date` is not NULL and DATE_ADD(`colos_date`, INTERVAL 12*rappel_colos_period MONTH) <= DATE_ADD(CURDATE(), ".
//					"INTERVAL $period MONTH)))  */"GROUP by numero order by numero";
//			$result = $this->findAnyRows($query);
   
//			if($result == false) return false;
//			return $this->buildRowArray($result);
//		}

		function getdernierExams($id){
			$query =  "select id, date, poids, max(dpoids) as dpoids, TaSys, TaDia, TA_mode, obj_tension, max(dtension) as dtension, ".
					  "max(dcoeur) as dcoeur, max(dartere) as dartere, ".
					  "max(dpouls) as dpouls, max(dsouffle) as dsouffle, ".
					  "Creat, max(dcreat) as dcreat, proteinurie, max(dproteinurie) as dproteinurie, hematurie, ".
					  "max(dhematurie) as dhematurie, glycemie, max(dglycemie) as dglycemie, kaliemie, max(dkaliemie) as dkaliemie, ".
					  "max(dChol) as dChol, HDL, max(dLDL) as dLDL, LDL, max(dfond) as dfond, max(dECG) as dECG, ".
					  "hta_instable, hta_tritherapie, hta_complique, tabac, hyperlipidemie, alcool, dconsult, degre_satisfaction, ".
					  "qualite_vie, iatrogenie, deliv_trait, regul_prises, cpt_rendu ".
					  "from ".$this->getTableName()." where id = '$id' GROUP by id";
					  
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}


		// Return expired exams from any type
		function getExpiredExams($cabinet,$period=0){
			$query =  "select ".$this->getTableName().".id, date, poids, max(dpoids) as dpoids, TaSys, TaDia, TA_mode, ".
					  "obj_tension, max(dtension) as dtension, ".
					  "max(dcoeur) as dcoeur, max(dartere) as dartere, ".
					  "max(dpouls) as dpouls, max(dsouffle) as dsouffle, ".
					  "Creat, max(".$this->getTableName().".dcreat) as dcreat, proteinurie, max(dproteinurie) as dproteinurie, hematurie, ".
					  "max(dhematurie) as dhematurie, glycemie, max(dglycemie) as dglycemie, kaliemie, max(dkaliemie) as dkaliemie, ".
					  "max(dChol) as dChol, HDL, max(dLDL) as dLDL, LDL, max(dfond) as dfond, max(dECG) as dECG, ".
					  "hta_instable, hta_tritherapie, hta_complique, tabac, hyperlipidemie, alcool, dconsult, degre_satisfaction, ".
					  "qualite_vie, iatrogenie, deliv_trait, regul_prises, cpt_rendu, ".$this->getTableName().".dmaj, ".
					  "dossier.id, cabinet, numero, dnaiss, sexe, taille, actif, dossier.dmaj ".
					  "from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".$this->getTableName().
					  ".id = dossier.id  AND dossier.actif='oui' ".
					  " GROUP by numero order by numero";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}


		//Retourne la valeur du dernier poids enregistré
		function getPoids($id, $dpoids)
		{
			$query =  "select poids ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dpoids='$dpoids' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getTension($id, $dtension)
		{
			$query =  "select  TaSys, TaDia ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dtension='$dtension' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}


		function getCreat($id, $dcreat)
		{
			$query =  "select Creat ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dcreat='$dcreat' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getProteinurie($id, $dproteinurie)
		{
			$query =  "select proteinurie ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dproteinurie='$dproteinurie' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getHematurie($id, $dhematurie)
		{
			$query =  "select hematurie ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dhematurie='$dhematurie' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getGlycemie($id, $dglycemie)
		{
			$query =  "select glycemie ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dglycemie='$dglycemie' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getKaliemie($id, $dkaliemie)
		{
			$query =  "select kaliemie ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dkaliemie='$dkaliemie' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getHDL($id, $dChol)
		{
			$query =  "select  HDL ".
					  "from ".$this->getTableName().
					  " where id = '$id'  AND dChol='$dChol' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getLDL($id, $dLDL)
		{
			$query =  "select LDL ".
					  "from ".$this->getTableName().
					  " where id = '$id'  AND dLDL='$dLDL' ".
					  " GROUP by id ";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

	}
?>
