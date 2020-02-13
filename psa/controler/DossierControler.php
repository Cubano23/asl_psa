<?php

	require_once("bean/Dossier.php");	
	require_once("persistence/DossierMapper.php");
	require_once("persistence/CardioVasculaireDepartMapper.php");
	require_once("persistence/DepistageCancerColonMapper.php");
	require_once("persistence/DepistageDiabeteMapper.php");
	require_once("persistence/DepistageCancerSeinMapper.php");
	require_once("persistence/DepistageCancerUterusMapper.php");
	require_once("persistence/EvaluationInfirmierMapper.php");
	require_once("persistence/HemocultMapper.php");
	require_once("persistence/HyperTensionArterielleMapper.php");
	require_once("persistence/SuiviDiabeteMapper.php");
	require_once("persistence/SevrageTabacMapper.php");
	require_once("persistence/TensionArterielleMoyenneMapper.php");
	require_once("persistence/TroubleCognitifMapper.php");
	require_once("persistence/ConnectionFactory.php");
	require_once("tools/arrays.php");	
	require_once("controler/UtilityControler.php");
	require_once("bean/DepistageAOMI.php");
	
	class DossierControler {
	
		var $mappingTable;
		
		function getForward($param,$url){
			if($param == PARAM_STAND_ALONE)							
				return $this->mappingTable[$url."_STA"];
			else 
				return $this->mappingTable[$url];
		}
		
		function dForward($param,$url,$message=NULL,$resetRequest=false){						
				forward($this->getForward($param,$url),$message,$resetRequest);			
		}
		
		function DossierControler(){
			$this->mappingTable = array(
			"URL_MANAGE"=>"view/dossier/managedossier.php",
			"URL_MANAGE_LIST_EXAM"=>"view/dossier/managelistexamdossier.php",
			"URL_MANAGE_STA"=>"managedossiersta",
			
			"URL_NEW"=>"view/dossier/newdossier.php",
			"URL_NEW_STA"=>"newdossiersta",
			
//			"URL_AFTER_CREATE"=>new ControlerParams("DossierControler",ACTION_MANAGE,true),
			"URL_AFTER_CREATE"=>"view/dossier/viewdossieraftercreate.php",
			"URL_AFTER_CREATE_STA"=>new ControlerParams("DossierControler",ACTION_MANAGE,false,NULL,NULL,PARAM_STAND_ALONE),
			
			"URL_AFTER_DELETE"=>new ControlerParams("DossierControler",ACTION_MANAGE,true),
			"URL_AFTER_DELETE_STA"=>new ControlerParams("DossierControler",ACTION_MANAGE,false,NULL,NULL,PARAM_STAND_ALONE),			
			
			"URL_CONFIRM_UPDATE"=>"view/dossier/confirmupdate.php",
			"URL_CONFIRM_UPDATE_STA"=>"confirmdossiersta",
			
//			"URL_AFTER_UPDATE"=>new ControlerParams("DossierControler",ACTION_MANAGE,true),
			"URL_AFTER_UPDATE"=>"view/dossier/viewdossierafterupdate.php",
			"URL_AFTER_UPDATE_STA"=>new ControlerParams("DossierControler",ACTION_MANAGE,false,NULL,NULL,PARAM_STAND_ALONE),			
			
			"URL_AFTER_FIND_VIEW"=>"view/dossier/newdossier.php",
			"URL_AFTER_FIND_VIEW_STA"=>"newdossiersta",
			
			"URL_AFTER_FIND_EDIT"=>"view/dossier/updatedossier.php",
			"URL_AFTER_FIND_EDIT_STA"=>"newdossiersta",
			
			"URL_AFTER_LIST"=>"view/dossier/listdossiers.php",
			"URL_AFTER_LIST_STA"=>"afterlistdossiersta",

			"URL_AFTER_VIEW_LIST_EXAM"=>"view/dossier/viewlistexamdossier.php");
			
			
		}
		
		function start() {
			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;			
			global $dossier;
			global $dossiers;
			global $CardioVasculaire;
			global $CardioVasculaireList;
			global $DepistageCancerSein;
			global $DepistageCancerSeinList;
			global $DepistageCancerColon;
			global $DepistageCancerColonList;
			global $DepistageDiabete;
			global $DepistageDiabeteList;
			global $DepistageCancerUterus;
			global $DepistageCancerUterusList;
			global $EvaluationInfirmier;
			global $EvaluationInfirmierList;
			global $Hemocult;
			global $HemocultList;
			global $HyperTensionArterielle;
			global $HyperTensionArterielleList;
			global $TroubleCognitif;
			global $TroubleCognitifList;
			global $TensionArterielleMoyenne;
			global $tensionArterielleMoyenneList;
			global $suiviDiabete;
			global $suiviDiabeteList;
			global $sevrageTabacList;

			global $liste_historique;
			$dep_aomi = new DepistageAOMI();


			if(array_key_exists("dossier",$objects))
				$dossier = $objects["dossier"];	
				
			// create ledger for this controler
			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","DossierControler");
				
			//Create connection factory
			$cf = new ConnectionFactory();
		
			//create mappers
			$dossierMapper = new DossierMapper($cf->getConnection());
			$CardioVasculaireMapper = new CardioVasculaireDepartMapper($cf->getConnection());
			$DepistageCancerSeinMapper = new DepistageCancerSeinMapper($cf->getConnection());
			$DepistageCancerUterusMapper = new DepistageCancerUterusMapper($cf->getConnection());
			$DepistageCancerColonMapper = new DepistageCancerColonMapper($cf->getConnection());
			$DepistageDiabeteMapper = new DepistageDiabeteMapper($cf->getConnection());
			$EvaluationInfirmierMapper = new EvaluationInfirmierMapper ($cf->getConnection());
			$HemocultMapper = new HemocultMapper ($cf->getConnection());
			$HyperTensionArterielleMapper = new HyperTensionArterielleMapper ($cf->getConnection());
			$suiviDiabeteMapper = new SuiviDiabeteMapper($cf->getConnection());
			$TensionArterielleMoyenneMapper = new TensionArterielleMoyenneMapper($cf->getConnection());
			$TroubleCognitifMapper = new TroubleCognitifMapper($cf->getConnection());
			$SevrageTabacMapper = new SevrageTabacMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);
			switch($param->action){
				case ACTION_MANAGE:							
					if($param->param3 != PARAM_STAND_ALONE)							
						$dossier = new Dossier();
					$this->dForward($param->param3,"URL_MANAGE");
					break;

				case ACTION_MANAGE_LIST_EXAM:
					if($param->param3 != PARAM_STAND_ALONE)
						$dossier = new Dossier();
					$suiviDiabete = new SuiviDiabete();
					$DepistageCancerSein = new DepistageCancerSein();
					$this->dForward($param->param3,"URL_MANAGE_LIST_EXAM");
					break;

				case ACTION_CONSULT_EVT:
					exitIfNull($dossier);
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->getForward($param->param3,"URL_MANAGE_LIST_EXAM"));

					//recherche des suivis du diab¿te
					$suiviDiabete->dossier_id = $dossier->id;
	 
					$result = $suiviDiabeteMapper->findObjects($suiviDiabete);
					if($result == false){
						if($suiviDiabeteMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error (suivi diab)");
					}
					else {
						$suiviDiabeteList = $result;
						for($i=0;$i<count($suiviDiabeteList);$i++){
							$suiviDiabeteList[$i] = $suiviDiabeteList[$i]->afterDeserialisation($account);
						}
					}


					//recherche des d¿pistages sein
					$DepistageCancerSein->id = $dossier->id;
					$result = $DepistageCancerSeinMapper->findObjects($DepistageCancerSein);

					if($result == false){
						if($DepistageCancerSeinMapper->lastError != BAD_MATCH) {forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error (dep sein)");}
					}
					else {
						$DepistageCancerSeinList = $result;
						for($i=0;$i<count($DepistageCancerSeinList);$i++){
							$DepistageCancerSeinList[$i] = $DepistageCancerSeinList[$i]->afterDeserialisation($account);
						}
					}

					//recherche des d¿pistages ut¿rus
					$DepistageCancerUterus->id = $dossier->id;
					$result = $DepistageCancerUterusMapper->findObjects($DepistageCancerUterus);

					if($result == false){
						if($DepistageCancerUterusMapper->lastError != BAD_MATCH) {forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error (dep uterus)");}
					}
					else {
						$DepistageCancerUterusList = $result;
						for($i=0;$i<count($DepistageCancerUterusList);$i++){
							$DepistageCancerUterusList[$i] = $DepistageCancerUterusList[$i]->afterDeserialisation($account);
						}
					}


					//recherche des d¿pistages colon
					$DepistageCancerColon->id = $dossier->id;
					$result = $DepistageCancerColonMapper->findObjects($DepistageCancerColon);

					if($result == false){
						if($DepistageCancerColonMapper->lastError != BAD_MATCH) {forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error (dep colon)");}
					}
					else {
						$DepistageCancerColonList = $result;
						for($i=0;$i<count($DepistageCancerColonList);$i++){
							$DepistageCancerColonList[$i] = $DepistageCancerColonList[$i]->afterDeserialisation($account);
						}
					}


					//recherche des Troubles cognitifs
					$TroubleCognitif->id = $dossier->id;
					$result = $TroubleCognitifMapper->findObjects($TroubleCognitif);

					if($result == false){
						if($TroubleCognitifMapper->lastError != BAD_MATCH) {forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error (cognitif)");}
					}
					else {
						$TroubleCognitifList = $result;
						for($i=0;$i<count($TroubleCognitifList);$i++){
							$TroubleCognitifList[$i] = $TroubleCognitifList[$i]->afterDeserialisation($account);
						}
					}


					//recherche des d¿pistages diab¿te
					$DepistageDiabete->id = $dossier->id;
					$result = $DepistageDiabeteMapper->findObjects($DepistageDiabete);

					if($result == false){
						if($DepistageDiabeteMapper->lastError != BAD_MATCH) {forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error (dep diab)");}
					}
					else {
						$DepistageDiabeteList = $result;
						for($i=0;$i<count($DepistageDiabeteList);$i++){
							$DepistageDiabeteList[$i] = $DepistageDiabeteList[$i]->afterDeserialisation($account);
						}
					}


					//recherche des tension art¿rielles moyennes
					$TensionArterielleMoyenne->id = $dossier->id;
					$result = $TensionArterielleMoyenneMapper->findObjects($TensionArterielleMoyenne);

					if($result == false){
						if($TensionArterielleMoyenneMapper->lastError != BAD_MATCH) {forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error (TA1)");}
					}
					else {
						$tensionArterielleMoyenneList = $result;
						for($i=0;$i<count($tensionArterielleMoyenneList);$i++){
							$tensionArterielleMoyenneList[$i] = $tensionArterielleMoyenneList[$i]->afterDeserialisation($account);
						}
					}


					//recherche des suivis H¿mocult
					$Hemocult->id = $dossier->id;
					$result = $HemocultMapper->findObjects($Hemocult);

					if($result == false){
						if($HemocultMapper->lastError != BAD_MATCH) {forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error (TA2)");}
					}
					else {
						$HemocultList = $result;
						for($i=0;$i<count($HemocultList);$i++){
							$HemocultList[$i] = $HemocultList[$i]->afterDeserialisation($account);
						}
					}


					//recherche des suivis HTA
					$HyperTensionArterielle->id = $dossier->id;
					$result = $HyperTensionArterielleMapper->findObjects($HyperTensionArterielle);

					if($result == false){
						if($HyperTensionArterielleMapper->lastError != BAD_MATCH) {forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error (TA3)");}
					}
					else {
						$HyperTensionArterielleList = $result;
						for($i=0;$i<count($HyperTensionArterielleList);$i++){
							$HyperTensionArterielleList[$i] = $HyperTensionArterielleList[$i]->afterDeserialisation($account);
						}
					}


					//recherche des suivis RCVA
					$CardioVasculaire->id = $dossier->id;
					$result = $CardioVasculaireMapper->findObjects($CardioVasculaire);

					if($result == false){
						if($CardioVasculaireMapper->lastError != BAD_MATCH) {forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error (TA4)");}
					}
					else {
						$CardioVasculaireList = $result;
						for($i=0;$i<count($CardioVasculaireList);$i++){
							$CardioVasculaireList[$i] = $CardioVasculaireList[$i]->afterDeserialisation($account);
						}
					}


					//recherche des ¿valuation infirmi¿re
					$EvaluationInfirmier->id = $dossier->id;
					$result = $EvaluationInfirmierMapper->findObjects($EvaluationInfirmier);

					if($result == false){
						if($EvaluationInfirmierMapper->lastError != BAD_MATCH) {forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error (eval inf)");}
					}
					else {
						$EvaluationInfirmierList = $result;
						for($i=0;$i<count($EvaluationInfirmierList);$i++){
							$EvaluationInfirmierList[$i] = $EvaluationInfirmierList[$i]->afterDeserialisation($account);
						}
					}

					
					//recherche des sevrage tabagiques
					$SevrageTabac->id = $dossier->id;
					$sevrageTabacList = $SevrageTabacMapper->listSevragesByDossier($SevrageTabac->id);
					
					#var_dump($result);exit;

					if($result == false){
//						if($SevrageTabac->lastError != BAD_MATCH) {forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error (TA5)");}
					}
					else {
						$sevragetabacList = $result;	
					}
					
					#var_dump($SevragetabacList);exit;

					// Recherche de l'historique des dépistages de l'AOMI
                    $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

					$this->dForward($param->param3,"URL_AFTER_VIEW_LIST_EXAM");
					break;

				case ACTION_FIND:
					if(!$param->isParam1Valid()) $this->dForward($param->param3,URL_CONTROLER_PARAMS_ERROR,"Invalid param1");
					exitIfNull($dossier);							
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->getForward($param->param3,"URL_MANAGE"));						
					if($param->param1 == PARAM_EDIT) $this->dForward($param->param3,"URL_AFTER_FIND_EDIT");
					else $this->dForward($param->param3,"URL_AFTER_FIND_VIEW");
					break;
				
				case ACTION_NEW:												
					exitIfNull($dossier);																			
					$dossier->cabinet = $account->cabinet;
					$result = $dossierMapper->findObject($dossier->beforeSerialisation($account));					
					if($result == FALSE){		
						if($dossierMapper->lastError != BAD_MATCH) $this->dForward($param->param3,URL_CONTROLER_PERSISTENCE_ERROR,"find failed");																						
					}
					else 
						$this->dForward($param->param3,"URL_MANAGE","Ce num¿ro de dossier existe dej¿");	
					$this->dForward($param->param3,"URL_NEW");
					break;
														
				case ACTION_SAVE:
					exitIfNull($dossier);			
					$dossier->cabinet = $account->cabinet;
					$errors = $dossier->check();

					if(count($errors) != 0)
						$this->dForward($param->param3,"URL_NEW",$errors);

					if($_POST['numerorigine']!=$dossier->numero){
								$result = $dossierMapper->isValidNumber($dossier->beforeSerialisation($account));

								if($result)$this->dForward($param->param3,"URL_AFTER_CREATE","Ce num¿ro de dossier existe dej¿<br/>La modification demand¿e ne peut donc pas ¿tre prise en compte");
					}


					$result = $dossierMapper->findObject($dossier->beforeSerialisation($account));									
					
					if($result == false){ ##### le dossier n'existe pas

						if(empty($dossier->id)){
							$result = $dossierMapper->createObject($dossier->beforeSerialisation($account));
						}else{
							$result = $dossierMapper->getUpdateByIdQuery($dossier->beforeSerialisation($account));	
						}
						
						if($result == false) $this->dForward($param->param3,URL_CONTROLER_PERSISTENCE_ERROR,"create failed");
						$this->dForward($param->param3,"URL_AFTER_CREATE");
					}

					else{ ##### le dossier existe 

						if($param->param1 != PARAM_FORCE_UPDATE){
							$result = $dossierMapper->haveChilds($dossier);							
							if($result == true)
								$this->dForward($param->param3,"URL_CONFIRM_UPDATE");
						}
						
						$result = $dossierMapper->updateObject($dossier->beforeSerialisation($account));
						if($result == false and $dossierMapper->lastError != NOTHING_UPDATED) {	
							$this->dForward($param->param3,URL_CONTROLER_PERSISTENCE_ERROR,"update failed");
						}
						$this->dForward($param->param3,"URL_AFTER_UPDATE");
										
					}

					break;
				
				
					
				case ACTION_DELETE:
					exitIfNull($dossier);
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->getForward($param->param3,"URL_MANAGE"));
					$result = $dossierMapper->haveChilds($dossier);

					// Vérification pour voir s'il ne s'agit pas d'une suppression d'une saisie dépistage
					switch ($param->param1)
					{
						case "SuppressionDepistage":
							$dep_aomi->id = $param->param2;
							$dep_aomi->deleteById();
                            $param->action = ACTION_CONSULT_EVT;
                            $this->start();
					}

					if($result == true){
						$this->dForward($param->param3,"URL_MANAGE","Ce dossier ¿ des documents associ¿s, il ne peut etre effac¿");
					}
					else {
						$result = $dossierMapper->deleteObject($dossier);
						if($result == false and $dossierMapper->lastError != NOTHING_DELETED)
							$this->dForward($param->param3,URL_ERROR_PAGE,"Une erreur interne s'est produite");
						else
							$this->dForward($param->param3,"URL_AFTER_DELETE");
					}
					break;
				
				
				case ACTION_LIST:
					set_time_limit(1200); //EA
					
					if(isset($_GET["tri"])){
						$dossier = new Dossier();
					}
					exitIfNull($dossier);			
					$dossier->cabinet = $account->cabinet;
					$result = $dossierMapper->findObjects($dossier->beforeSerialisation($account),$account->cabinet);
					if($result == false){
						if($dossierMapper->lastError == BAD_MATCH) $this->dForward($param->param3,"URL_MANAGE","Pas d'enregistrements trouv¿s");
						else $this->dForward($param->param3,URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");				
					}			
								
					for ($i = 0; $i < count($result); $i++){
						$result[$i] = $result[$i]->afterDeserialisation($account);
					}
					
					$dossiers = arrayOfobjects_natsort($result,"numero","numero");					 
					
					$this->dForward($param->param3,"URL_AFTER_LIST");
						
				default:
					echo("ACTION IS NULL");
					break;
			}
		}
	

		






	}

?>

