<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/CardioVasculaireDepart.php");
	
	class CardioVasculaireDepartMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
	
		function getKeysMap(){
			return array("id"=>"id","date"=>"date");
		}
		
		function getTableName(){
			return "cardio_vasculaire_depart";
		}
	
		function getLedgerName(){
			return "CardioVasculaireDepartMapper";
		}
	
		function getObject(){
			return new CardioVasculaireDepart();
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
					 "max(darret) as darret, max(spirometrie_date) as spirometrie_date, max(spirometrie_status) as spirometrie_status, ".
					 "max(spirometrie_CVF) as spirometrie_CVF, max(spirometrie_VEMS) as spirometrie_VEMS, max(spirometrie_DEP) as spirometrie_DEP, max(spirometrie_rapport_VEMS_CVF) as spirometrie_rapport_VEMS_CVF, ".
					 "max(dpoids) as dpoids, max(dpouls) as dpouls, max(dgly) as dgly, max(exam_cardio) as exam_cardio ".
					 "from ".$this->getTableName()." where id = '$id' GROUP by id";
					  
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		// Return expired exams from any type
		function getExpiredExams($cabinet,$period=0){
			$query =  "select ".$this->getTableName().".id, max(date) as date, antecedants, ".
					  "Chol, max(dChol) as dChol, HDL, max(dHDL) as dHDL, ".
					  "LDL, max(dLDL) as dLDL, triglycerides, max(dtriglycerides) ".
					  "as dtriglycerides, traitement, dosage, HTA, TaSys, TaDia, ".
					  "max(dTA) as dTA, TA_mode, hypertenseur3, automesure, ".
					  "diuretique, HVG, surcharge_ventricule, sokolov, ".
					  "max(dsokolov) as dsokolov, ".
					  "Creat, max(".$this->getTableName().".dCreat) as dCreat, ".
					  "kaliemie, max(dkaliemie) as dkaliemie, proteinurie, ".
					  "max(dproteinurie) as dproteinurie, hematurie, ".
					  "max(dhematurie) as dhematurie, max(dFond) as dFond, ".
					  "max(dECG) as dECG, tabac, darret, poids, max(dpoids) as dpoids, ".
					  "spirometrie_date, spirometrie_status, max(spirometrie_CVF) as spirometrie_CVF, ".
					  "max(spirometrie_VEMS) as spirometrie_VEMS, max(spirometrie_DEP) as spirometrie_DEP, max(spirometrie_rapport_VEMS_CVF) as spirometrie_rapport_VEMS_CVF, ".
					  "activite, pouls, max(dpouls) as dpouls, ".
					  "alcool, glycemie, max(dgly) as dgly, ".
					  "max(exam_cardio) as exam_cardio, sortir_rappel, ".
					  $this->getTableName().".dmaj, ".
					  "dossier.id, cabinet, numero, dnaiss, sexe, taille, actif, ".
					  "sortir_rappel, raison_sortie, dossier.dmaj ".
					  "from ".$this->getTableName().",dossier where cabinet='$cabinet' ".
					  "and ".$this->getTableName().
					  ".id = dossier.id  AND dossier.actif='oui' ".
					  "and DATE_ADD(dnaiss, interval 75 YEAR)>=CURDATE()".
					  " GROUP by numero order by numero";
			$result = $this->findAnyRows($query);
		// echo $query;

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		//Vérification si la personne est sortie du RCVA
		function getdernierRappel($id, $date){
		    $query = "SELECT id, sortir_rappel ".
					 "from ".$this->getTableName()." where id='$id' AND date='$date'";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getObjectsSpiroByCabinet($cabinet){
		$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".".$this->getForeignKey()." = dossier.id AND dossier.actif='oui' AND spirometrie_date!='0000-00-00' GROUP BY ".
				"dossier.numero";
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


		//Retourne la valeur et date du dernier poids enregistré
		function getPoidsRCVA($id)
		{
			$query =  "select date_format(date_exam, '%d/%m/%Y') as date_exam, ".
					  "resultat1 from liste_exam where ".
					  "id = '$id' order by date_exam DESC limit 0, 1";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getSpirometrieRCVA($id)
		{
			$query =  "select date_format(date_exam, '%d/%m/%Y') as date_exam, ".
					  "resultat1 from liste_exam where ".
					  "id = '$id' order by date_exam DESC limit 0, 1";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		

		//Retourne la valeur et date du dernier exam enregistré
		function getExam($id, $exam)
		{
			$query =  "select date_format(date_exam, '%d/%m/%Y') as date_exam, ".
					  "resultat1, date_exam as dexam from liste_exam where ".
					  "id = '$id' and type_exam='$exam' ".
					  "order by dexam DESC limit 0, 1";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
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
					  " order by dmaj DESC limit 0,1";
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
			$query =  "select  antecedants, traitement, dosage, hypertenseur3, automesure, ".
					  "diuretique, HVG, activite, tabac, nbrtabac, spirometrie_status, spirometrie_CVF, spirometrie_VEMS, spirometrie_DEP, max(spirometrie_rapport_VEMS_CVF) as spirometrie_rapport_VEMS_CVF ".
					  "from ".$this->getTableName()." where id = '$id' ORDER BY date DESC limit 0,1";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getAntecedants($id){
			$query =  "select  antecedants ".
					  "from ".$this->getTableName()." ".
					  "where id = '$id' and antecedants!='' and antecedants is not NULL ".
					  "ORDER BY date DESC limit 0,1";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
							
		function getTraitement($id){
			$query =  "select  traitement, dosage, activite ".
					  "from ".$this->getTableName()." where id = '$id' ORDER BY date DESC limit 0,1";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
							
		function getHypertenseur3($id){
			$query =  "select  hypertenseur3 ".
					  "from ".$this->getTableName()." ".
					  "where id = '$id' and hypertenseur3!='' and hypertenseur3 is not NULL ".
					  "ORDER BY date DESC limit 0,1";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
							
		function getAutomes($id){
			$query =  "select  automesure ".
					  "from ".$this->getTableName()." ".
					  "where id = '$id' and automesure!='' and automesure is not NULL ".
					  "ORDER BY date DESC limit 0,1";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
							
		function getDiuretique($id){
			$query =  "select  diuretique ".
					  "from ".$this->getTableName()." ".
					  "where id = '$id' and diuretique!='' and diuretique is not NULL ".
					  "ORDER BY date DESC limit 0,1";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
							
		function getHVG($id){
			$query =  "select  HVG ".
					  "from ".$this->getTableName()." ".
					  "where id = '$id' and HVG!='' and HVG is not NULL ".
					  "ORDER BY date DESC limit 0,1";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
							
		function getTabac($id){
			$query =  "select  tabac, nbrtabac, darret ".
					  "from ".$this->getTableName()." ".
					  "where id = '$id' and tabac!='' and tabac is not NULL ".
					  "ORDER BY date DESC limit 0,1";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getnbrtabac($id){

			$query =  "select  nbrtabac ".
					  "from ".$this->getTableName()." where id = '$id' and nbrtabac!='' and nbrtabac is not NULL ".
					  "ORDER BY date DESC limit 0,1";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getSpirometrie_status($id){

			$query =  "select  spirometrie_status ".
					  "from ".$this->getTableName()." where id = '$id' and spirometrie_status!='' and spirometrie_status is not NULL ".
					  "ORDER BY date DESC limit 0,1";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getSpirometrie_CVF($id){

			$query =  "select  spirometrie_CVF ".
					  "from ".$this->getTableName()." where id = '$id' and spirometrie_CVF!='' and spirometrie_CVF is not NULL ".
					  "ORDER BY date DESC limit 0,1";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getSpirometrie_VEMS($id){

			$query =  "select  spirometrie_VEMS ".
					  "from ".$this->getTableName()." where id = '$id' and spirometrie_VEMS!='' and spirometrie_VEMS is not NULL ".
					  "ORDER BY date DESC limit 0,1";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getSpirometrie_DEP($id){

			$query =  "select  spirometrie_DEP ".
					  "from ".$this->getTableName()." where id = '$id' and spirometrie_DEP!='' and spirometrie_DEP is not NULL ".
					  "ORDER BY date DESC limit 0,1";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getSpirometrie_Rapport_VEMS_CVF($id){

			$query =  "select spirometrie_rapport_VEMS_CVF ".
					  "from ".$this->getTableName()." where id = '$id' and spirometrie_rapport_VEMS_CVF!='' and spirometrie_rapport_VEMS_CVF is not NULL ".
					  "ORDER BY date DESC limit 0,1";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}


		
		function getAlcool($id){
			$query =  "select  alcool ".
					  "from ".$this->getTableName()." ".
					  "where id = '$id' and alcool!='' and alcool is not NULL ".
					  "ORDER BY date DESC limit 0,1";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getHTA($id){
			$query =  "select  HTA ".
					  "from ".$this->getTableName()." ".
					  "where id = '$id' and HTA!='' and HTA is not NULL ".
					  "ORDER BY date DESC limit 0,1";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		function getExamCardio($id, $exam_cardio){
			$query = "SELECT * from ".$this->getTableName()." where id='$id' and exam_cardio='$exam_cardio'";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return true;
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

		function getPremierSpirometrie($id)
		{
			$query =  "select spirometrie_date, spirometrie_rapport_VEMS_CVF, spirometrie_status ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id' AND spirometrie_date>'0000-00-00' ORDER BY spirometrie_date";
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
			$query =  "select Creat, dCreat ".
					  "from ".$this->getTableName()." where ".
					  "id = '$id'  AND dcreat>'0000-00-00' ORDER BY dCreat ";
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
					  "tabac, nbrtabac, darret, spirometrie_date, spirometrie_status, spirometrie_CVF, spirometrie_VEMS, spirometrie_DEP, spirometrie_rapport_VEMS_CVF  from ".$this->getTableName()." where id = '$id' ORDER BY date ";

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
		
		function purgeexam($object){
			if($object->dpoids>'0000-00-00')
				$poids = $this->getPoidsRCVA($object->id, $object->dpoids);
			else
				$poids=false;
			
			if($poids!=false){
				$object->dpoids=$object->poids="";				
			}
			
			if($object->spirometrie_date>'0000-00-00')
				$spirometrie = $this->getSpirometrieRCVA($object->id, $object->spirometrie_date);
			else
				$spirometrie=false;
			
			if($spirometrie!=false){
				$object->spirometrie_date=$object->spirometrie="";				
			}
	
			if($object->dTA>'0000-00-00')
				$tension=$this->getTensionRCVA($object->id, $object->dTA);
			else
				$tension=false;

			if($tension!=false){
				$object->TaSys=$object->TaDia=$object->HTA=$object->TA_mode=$object->dTA="";
			}
				
			if($object->dCreat>'0000-00-00')
				$creat=$this->getCreatRCVA($object->id, $object->dCreat);
			else
				$creat=false;
			
			if($creat!=false){
				$object->dCreat=$object->Creat="";
			}
	
			if($object->dproteinurie>'0000-00-00')
				$proteinurie=$this->getProteinurieRCVA($object->id, $object->dproteinurie);
			else
				$proteinurie=false;
			
			if($proteinurie!=false){
				$object->proteinurie=$object->dproteinurie="";
			}
			
			if($object->dhematurie>'0000-00-00')
				$hematurie=$this->getHematurieRCVA($object->id, $object->dhematurie);
			else
				$hematurie=false;
			
			if($hematurie!=false){
				$object->dhematurie=$object->hematurie="";
			}

			if($object->dgly>'0000-00-00')
				$glycemie=$this->getGlycemieRCVA($object->id, $object->dgly);
			else
				$glycemie=false;
			
			if($glycemie!=false){
				$object->glycemie=$object->dgly="";				
			}

			if($object->dkaliemie>'0000-00-00')
				$kaliemie=$this->getKaliemieRCVA($object->id, $object->dkaliemie);
			else
				$kaliemie=false;
			
			if($kaliemie!=false){
				$object->kaliemie=$object->dkaliemie="";
			}

			if($object->dHDL>"0000-00-00")
				$HDL=$this->getHDLRCVA($object->id, $object->dHDL);
			else
				$HDL=false;
				
			if($HDL!=false){
				$object->HDL=$object->dHDL="";
			}
			
			if($object->dLDL>"0000-00-00")
				$LDL=$this->getLDLRCVA($object->id, $object->dLDL);
			else
				$LDL=false;
				
			if($LDL!=false){
				$object->LDL=$object->dLDL="";
			}

			if($object->dsokolov>"0000-00-00")
				$sokolov=$this->getsokolov($object->id, $object->dsokolov);
			else
				$sokolov=false;
				
			if($sokolov!=false){
				$object->sokolov=$object->dsokolov="";
			}

			if($object->dChol>"0000-00-00")
				$Chol=$this->getChol($object->id, $object->dChol);
			else
				$Chol=false;
				
			if($Chol!=false){
				$object->Chol=$object->dChol="";
			}

			if($object->dtriglycerides>"0000-00-00")
				$triglycerides=$this->getTriglycerides($object->id, $object->dtriglycerides);
			else
				$triglycerides=false;
				
			if($triglycerides!=false){
				$object->triglycerides=$object->dtriglycerides="";
			}

	
			if($object->dpouls>"0000-00-00")
				$pouls=$this->getPouls($object->id, $object->dpouls);
			else
				$pouls=false;
				
			if($pouls!=false){
				$object->pouls=$object->dpouls="";
			}			
		
			if($object->exam_cardio>'0000-00-00')
				$exam_cardio=$this->getExamCardio($object->id, $object->exam_cardio);
			else
				$exam_cardio=false;
				
			if($exam_cardio!=false){
				$object->exam_cardio='';
			}


			$autreExam=$this->getAutreExam($object->id);
			
			$autreExam=$autreExam[count($autreExam)-1];
			
			if(($autreExam['traitement']==$object->traitement)&&($autreExam['dosage']==$object->dosage)){
				$object->traitement="";
				$object->dosage="";
			}
			
			if($autreExam['antecedants']==$object->antecedants){
				$object->antecedants="";
			}
			
			if($autreExam['automesure']==$object->automesure){
				$object->automesure="";
			}
			
			if($autreExam['diuretique']==$object->diuretique){
				$object->diuretique='';
			}
			
			if($autreExam['HVG']==$object->HVG){
				$object->HVG="";
			}
			
			if($autreExam['activite']==$object->activite){
				$object->activite="";
			}




			if(($object->dpoids=="")&&($object->poids=="")&&($object->TaSys=="")&&($object->TaDia=="")&&
				($object->spirometrie_date=="")&&($object->spirometrie=="")&&($object->spirometrie_status=="")&&($object->getSpirometrie_CVF=="")&&
				($object->HTA=="")&&($object->TA_mode=="")&&($object->dCreat=='')&&($object->Creat=="")&&
				($object->proteinurie=='')&&($object->dproteinurie=="")&&($object->dhematurie=='')&&
				($object->hematurie=="")&&($object->glycemie=="")&&($object->dgly=="")&&
				($object->kaliemie=='')&&($object->dkaliemie=="")&&($object->HDL=="")&&($object->dHDL=="")&&
				($object->LDL=="")&&($object->dLDL=="")&&($object->sokolov=='')&&($object->dsokolov=="")&&
				($object->Chol=="")&&($object->dChol=="")&&($object->triglycerides=='')&&
				($object->dtriglycerides=="")&&($object->pouls=='')&&($object->dpouls=="")&&
				($object->exam_cardio=='')&&($object->traitement=="")&&($object->dosage=="")&&
				($object->antecedants=="")&&($object->automesure=="")&&($object->diuretique=="")&&
				($object->HVG=="")&&($object->activite=="")){
					
				return false;
			}
			else{
				return $object;
			}
		}

	}
?>

