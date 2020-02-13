<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/PremiereConsultCardio.php");
	
	class PremiereConsultCardioMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
	
		function getKeysMap(){
			return array("id"=>"id","date"=>"date");
		}
		
		function getTableName(){
			return "cardio_premiere_consult";
		}
	
		function getLedgerName(){
			return "PremiereConsultCardioMapper";
		}
	
		function getObject(){
			return new PremiereConsultCardio();
		}
		
		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;
			return "select * from ".$this->getTableName()." where id=".$propertiesArray["id"];
		}
		

		function getdernierExamsHTA($id){
			$query = "select max(dpoids) as dpoids, max(dtension) as dTA, max(dcoeur) as dcoeur, max(dcreat) as dcreat, ".
					 "max(dproteinurie) as dproteinurie, max(dhematurie) as dhematurie, max(dglycemie) as dgly, ".
					 "max(dkaliemie) as dkaliemie, max(dChol) as dHDL, max(dLDL) as dLDL, max(dfond) as dFond, ".
					 "max(dECG) as dECG, max(tabac) as tabac, max(alcool) as alcool ".
					 "from hyper_tension where id = '$id' GROUP by id";
					  
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getdernierExamsRCVA($id){
			$query = "SELECT max(dChol) as dChol, max(dHDL) as dHDL, max(dLDL) as dLDL, max(dtriglycerides) as dtriglycerides, ".
					 "max(dTA) as dTA, max(dsokolov) as dsokolov, max(dCreat) as dCreat, max(dkaliemie) as dkaliemie, ".
					 "max(dproteinurie) as dproteinurie, max(dhematurie) as dhematurie, max(dFond) as dFond, max(dECG) as dECG, ".
					 "max(darret) as darret, max(dpoids) as dpoids, max(dpouls) as dpouls, max(dgly) as dgly, max(exam_cardio) as exam_cardio ".
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
		function getPoidsRCVA($id, $dpoids)
		{
			$query =  "select poids ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dpoids='$dpoids' ".
					  "GROUP BY id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getPoidsHTA($id, $dpoids)
		{
			$query =  "select poids ".
					  "from hyper_tension where ".
					  "id = '$id'  AND dpoids='$dpoids' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getTensionRCVA($id, $dtension)
		{
			$query =  "select  TaSys, TaDia, HTA, TA_mode ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dTA='$dtension' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getTensionHTA($id, $dtension)
		{
			$query =  "select  TaSys, TaDia ".
					  "from hyper_tension where ".
					  "id = '$id'  AND dtension='$dtension' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getCreatRCVA($id, $dcreat)
		{
			$query =  "select Creat ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dcreat='$dcreat' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getCreatHTA($id, $dcreat)
		{
			$query =  "select Creat ".
					  "from hyper_tension where ".
					  "id = '$id'  AND dcreat='$dcreat' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getProteinurieRCVA($id, $dproteinurie)
		{
			$query =  "select proteinurie ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dproteinurie='$dproteinurie' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		function getProteinurieHTA($id, $dproteinurie)
		{
			$query =  "select proteinurie ".
					  "from hyper_tension where ".
					  "id = '$id'  AND dproteinurie='$dproteinurie' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getHematurieRCVA($id, $dhematurie)
		{
			$query =  "select hematurie ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dhematurie='$dhematurie' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getHematurieHTA($id, $dhematurie)
		{
			$query =  "select hematurie ".
					  "from hyper_tension where ".
					  "id = '$id'  AND dhematurie='$dhematurie' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getGlycemieRCVA($id, $dglycemie)
		{
			$query =  "select glycemie ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dgly='$dglycemie' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getGlycemieDiab($id)
		{
			$query =  "select derniere_gly_date as dgly, derniere_gly_resultat as glycemie ".
					  "from depistage_diabete where ".
					  "id = '$id'  ".
					  " order by derniere_gly_date desc";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getGlycemieHTA($id, $dglycemie)
		{
			$query =  "select glycemie ".
					  "from hyper_tension where ".
					  "id = '$id'  AND dglycemie='$dglycemie' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getKaliemieRCVA($id, $dkaliemie)
		{
			$query =  "select kaliemie ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dkaliemie='$dkaliemie' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getKaliemieHTA($id, $dkaliemie)
		{
			$query =  "select kaliemie ".
					  "from hyper_tension where ".
					  "id = '$id'  AND dkaliemie='$dkaliemie' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getHDLRCVA($id, $dHDL)
		{
			$query =  "select  HDL ".
					  "from ".$this->getTableName().
					  " where id = '$id'  AND dHDL='$dHDL' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getHDLHTA($id, $dHDL)
		{
			$query =  "select  HDL ".
					  "from hyper_tension where id = '$id'  AND dChol='$dHDL' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getLDLRCVA($id, $dLDL)
		{
			$query =  "select  LDL ".
					  "from ".$this->getTableName().
					  " where id = '$id'  AND dLDL='$dLDL' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getLDLHTA($id, $dLDL)
		{
			$query =  "select  LDL ".
					  "from hyper_tension where id = '$id'  AND dLDL='$dLDL' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getSokolov($id, $dsokolov)
		{
			$query =  "select  surcharge_ventricule, sokolov ".
					  "from ".$this->getTableName()." where id = '$id'  AND dsokolov='$dsokolov' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getChol($id, $dChol)
		{
			$query =  "select  Chol ".
					  "from ".$this->getTableName()." where id = '$id'  AND dChol='$dChol' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getTriglycerides($id, $dtriglycerides)
		{
			$query =  "select  triglycerides ".
					  "from ".$this->getTableName()." where id = '$id'  AND dtriglycerides='$dtriglycerides' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getPouls($id, $dpouls)
		{
			$query =  "select  pouls ".
					  "from ".$this->getTableName()." where id = '$id'  AND dpouls='$dpouls' ".
					  " GROUP by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getAutreExam($id){
			$query =  "select  antecedants, traitement, dosage, hypertenseur3, automesure, diuretique, HVG, activite ".
					  "from ".$this->getTableName()." where id = '$id' ORDER BY date";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		//Retourne la valeur du premier poids enregistré
		function getPremierPoids($id)
		{
			$query =  "select dpoids, poids ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id' AND dpoids>'0000-00-00' ORDER BY dpoids";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		

		function getPremierTension($id)
		{
			$query =  "select  TaSys, TaDia, HTA, dTA ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dTA>'0000-00-00' ORDER BY dTA";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getPremierCreat($id)
		{
			$query =  "select Creat, dCreat".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dcreat>'0000-00-00' ORDER BY dCreat";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getPremierProteinurie($id)
		{
			$query =  "select proteinurie, dproteinurie ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dproteinurie>'0000-00-00' ORDER BY dproteinurie";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getPremierHematurie($id)
		{
			$query =  "select hematurie, dhematurie ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dhematurie>'0000-00-00' ORDER BY dhematurie";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getPremierGlycemie($id)
		{
			$query =  "select glycemie, dgly ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dgly>'0000-00-00' ORDER BY dgly";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getPremierKaliemie($id)
		{
			$query =  "select kaliemie, dkaliemie ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dkaliemie>'0000-00-00' ORDER BY dkaliemie";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getPremierHDL($id)
		{
			$query =  "select  HDL, dHDL ".
					  "from ".$this->getTableName().
					  " where id = '$id'  AND dHDL>'0000-00-00' ORDER BY dHDL";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getPremierLDL($id)
		{
			$query =  "select  LDL, dLDL ".
					  "from ".$this->getTableName().
					  " where id = '$id'  AND dLDL>'0000-00-00' ORDER BY dLDL";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getPremierSokolov($id)
		{
			$query =  "select  surcharge_ventricule, sokolov, dsokolov ".
					  "from ".$this->getTableName()." where id = '$id'  AND dsokolov>'0000-00-00' ".
					  "ORDER BY dsokolov";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getPremierChol($id)
		{
			$query =  "select  Chol, dChol ".
					  "from ".$this->getTableName()." where id = '$id'  AND dChol>'0000-00-00' ".
					  "ORDER BY dChol";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getPremierTriglycerides($id)
		{
			$query =  "select  triglycerides, dtriglycerides ".
					  "from ".$this->getTableName()." where id = '$id'  AND dtriglycerides>'0000-00-00' ".
					  "ORDER BY dtriglycerides";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getPremierPouls($id)
		{
			$query =  "select  pouls, dpouls ".
					  "from ".$this->getTableName()." where id = '$id'  AND dpouls>'0000-00-00' ".
					  "ORDER BY dpouls";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getPremierExamCardio($id)
		{
			$query =  "select  min(exam_cardio) as exam_cardio ".
					  "from ".$this->getTableName()." where id = '$id'  AND exam_cardio>'0000-00-00' ".
					  "group by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getPremierFond($id)
		{
			$query =  "select  min(dFond) as dFond ".
					  "from ".$this->getTableName()." where id = '$id'  AND dFond>'0000-00-00' ".
					  "group by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getPremierECG($id)
		{
			$query =  "select  min(dECG) as dECG ".
					  "from ".$this->getTableName()." where id = '$id'  AND dECG>'0000-00-00' ".
					  "group by id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getPremierAutreExam($id){
			$query =  "select  antecedants, traitement, dosage, hypertenseur3, automesure, diuretique, HVG, activite, ".
					  "tabac, darret from ".$this->getTableName()." where id = '$id' ORDER BY date ";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}


		
		function getdepdiab($id){
			$query = "select * from depistage_diabete where id='$id'";
			
			$result= $this->findAnyRows($query);
			
			if($result==false) return false;
			else return true;
		}

		function getsuividiab($id){
			$query = "select * from suivi_diabete where dossier_id='$id'";
			
			$result= $this->findAnyRows($query);
			
			if($result==false) return false;
			else return true;
		}

		function getsein($id){
			$query = "select * from depistage_sein where id='$id'";
			
			$result= $this->findAnyRows($query);
			
			if($result==false) return false;
			else return true;
		}

		function getcolon($id){
			$query = "select * from depistage_colon where id='$id'";
			
			$result= $this->findAnyRows($query);
			
			if($result==false) return false;
			else return true;
		}


		function getuterus($id){
			$query = "select * from depistage_uterus where id='$id'";
			
			$result= $this->findAnyRows($query);
			
			if($result==false) return false;
			else return true;
		}

		function getcognitif($id){
			$query = "select * from trouble_cognitif where id='$id'";
			
			$result= $this->findAnyRows($query);
			
			if($result==false) return false;
			else return true;
		}

		function getautomesure($id){
			$query = "select * from tension_arterielle_moyenne where id='$id'";
			
			$result= $this->findAnyRows($query);
			
			if($result==false) return false;
			else return true;
		}
	}
?>
