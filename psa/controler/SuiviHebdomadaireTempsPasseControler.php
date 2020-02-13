<?php
error_log("----------- ant 1 ");
	error_reporting(E_ALL);
error_log("----------- ant 2 ");
	require_once("bean/SuiviHebdomadaireTempsPasse.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/SuiviHebdomadaireTempsPasseMapper.php");
	require_once("GenericControler.php");
	require_once("tools/date.php");
	require_once('bean/SuiviHebdomadaireTempsPasseInfirmieres.php');

	require_once("bean/EvaluationInfirmier.php");
	require_once("persistence/EvaluationInfirmierMapper.php");
	require_once("bean/SuiviReunionMedecin.php");
	require_once("persistence/SuiviReunionMedecinMapper.php");
error_log("----------- ant 2 ");
	
class SuiviHebdomadaireTempsPasseControler{
	
	var $mappingTable;
		
		function SuiviHebdomadaireTempsPasseControler() {
			$this->mappingTable = 
			array(
			"URL_MANAGE"=>"view/suivihebdomadaireTempsPasse/managesuivihebdomadaire.php",
			"URL_NEW"=>"view/suivihebdomadaireTempsPasse/newsuivihebdomadaire.php",
			"URL_AFTER_CREATE"=>new ControlerParams("SuiviHebdomadaireTempsPasseControler",ACTION_LIST,true),
			"URL_AFTER_UPDATE"=>new ControlerParams("SuiviHebdomadaireTempsPasseControler",ACTION_LIST,true),
		//	"URL_AFTER_FIND_VIEW"=>new ControlerParams("SuiviHebdomadaireControler",ACTION_MANAGE,true,NULL,NULL,NULL,"Cette fonction n'est pas impl&eacute;ment&eacute;e"),
		//	"URL_AFTER_FIND_EDIT"=>new ControlerParams("SuiviHebdomadaireControler",ACTION_MANAGE,true,NULL,NULL,NULL,"Cette fonction n'est pas impl&eacute;ment&eacute;e"),
			"URL_AFTER_FIND_VIEW"=>"view/suivihebdomadaireTempsPasse/viewsuivihebdomadaire.php",
			"URL_AFTER_FIND_EDIT"=>"view/suivihebdomadaireTempsPasse/newsuivihebdomadaire.php",
			"URL_AFTER_DELETE"=>"",
			"URL_AFTER_LIST"=>"listsuivihebdomadaireTempsPasse",
			"URL_ON_CALLBACK_FAIL"=>"view/",
			"URL_AFTER_DELETE"=>new ControlerParams("SuiviHebdomadaireTempsPasseControler",ACTION_MANAGE,true));
		}
	

		function start() {
//			$this->genericControler("SuiviHebdomadaireControler","suiviHebdomadaire","SuiviHebdomadaire","SuiviHebdomadaireMapper",$this->mappingTable);



			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $dossier;
			global $SuiviHebdomadaireTempsPasse;
			global $saisieInfirmiere;
			global $SuiviReunionMedecin;


			global $outDateReference;
			if(array_key_exists("outDateReference",$objects))
				$outDateReference = $objects["outDateReference"];

			global $SuiviHebdomadaireTempsPasse;
			if(array_key_exists("SuiviHebdomadaireTempsPasse",$objects))
				$SuiviHebdomadaireTempsPasse = $objects["SuiviHebdomadaireTempsPasse"];

			
			if(array_key_exists("dossier",$objects))
				$dossier = $objects["dossier"];	

			global $evaluationInfirmier;
			if(array_key_exists("evaluationInfirmier",$objects))
				$evaluationInfirmier = $objects["evaluationInfirmier"];	

			global $SuiviReunionMedecin;
			if(array_key_exists("SuiviReunionMedecin",$objects))
				$SuiviReunionMedecin = $objects["SuiviReunionMedecin"];	

			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","SuiviHebdomadaireTempsPasseControler");

			//Create connection factory
			$cf = new ConnectionFactory();


			//create mappers
			$dossierMapper = new DossierMapper($cf->getConnection());
			$SuiviHebdomadaireTempsPasseMapper = new SuiviHebdomadaireTempsPasseMapper($cf->getConnection());
			$evaluationInfirmierMapper = new EvaluationInfirmierMapper($cf->getConnection());
			$SuiviReunionMedecinMapper = new SuiviReunionMedecinMapper($cf->getConnection());
			
			#var_dump($param->action);

			#$ledger->writeArray(I,"Start","Control Parameters = ",$param);



			switch($param->action){
				case ACTION_MANAGE:
					$SuiviHebdomadaireTempsPasse = new SuiviHebdomadaireTempsPasse();
					$SuiviHebdomadaireTempsPasse->date= date("d/m/Y");
					forward($this->mappingTable["URL_MANAGE"]);
					break;

				case ACTION_FIND:

						if(!$param->isParam1Valid())
							forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");
						exitIfNull($SuiviHebdomadaireTempsPasse);
						exitIfNullOrEmpty($SuiviHebdomadaireTempsPasse->date);

						$SuiviHebdomadaireTempsPasse->cabinet = $account->cabinet;
						$evaluationInfirmier->date = dateToMysqlDate($SuiviHebdomadaireTempsPasse->date);
						$saisieInfirmiere = $evaluationInfirmierMapper->getObjectsByCabinetAndDate($account->cabinet, $evaluationInfirmier->date);
						
						$SuiviReunionMedecin = $SuiviReunionMedecinMapper->getObjectsByCabinetAndDateForHebdo($account->cabinet, $evaluationInfirmier->date);
					

						$result = $SuiviHebdomadaireTempsPasseMapper->findObject($SuiviHebdomadaireTempsPasse->beforeSerialisation($account));
						
						if($result == false)
						{
						 	$SuiviHebdomadaireTempsPasseMapper->createObject($SuiviHebdomadaireTempsPasse->beforeSerialisation($account));
						 	$saisieInfirmiere = $evaluationInfirmierMapper->getObjectsByCabinetAndDate($account->cabinet, $evaluationInfirmier->date);

						// 	if($SuiviHebdomadaireTempsPasseMapper->lastError == BAD_MATCH) 
						// 		forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement n'existe pas");
						// 	else 
						// 		forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
						 	
						 	forward($this->mappingTable["URL_AFTER_FIND_VIEW"]);
						}
						$SuiviHebdomadaireTempsPasse = $result->afterDeserialisation($account);
						
						if($param->param1 == PARAM_EDIT)
							forward($this->mappingTable["URL_AFTER_FIND_EDIT"]);
						else
							forward($this->mappingTable["URL_AFTER_FIND_VIEW"]);
						break;



				case ACTION_NEW:

						exitIfNull($SuiviHebdomadaireTempsPasse);
						exitIfNullOrEmpty($SuiviHebdomadaireTempsPasse->date);
						if(!isValidDate($SuiviHebdomadaireTempsPasse->date))
							forward($this->mappingTable["URL_MANAGE"],"La date du suivi est invalide");
						$SuiviHebdomadaireTempsPasse->cabinet = $account->cabinet;
						$result = $SuiviHebdomadaireTempsPasseMapper->findObject($SuiviHebdomadaireTempsPasse->beforeSerialisation($account));

						if($result == false){
							if($SuiviHebdomadaireTempsPasseMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"impossible de trouver le dossier");
						}
						else
							forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement existe dejà, Appuyez sur modifier");
						forward($this->mappingTable["URL_NEW"]);
						break;


				case ACTION_SAVE:

						exitIfNull($SuiviHebdomadaireTempsPasse);
						exitIfNullOrEmpty($SuiviHebdomadaireTempsPasse->date);
						
						$errors = $SuiviHebdomadaireTempsPasse->check();

						if(count($errors) !=0){
						$SuiviHebdomadaireTempsPasse->cabinet = $account->cabinet;
						$evaluationInfirmier->date = dateToMysqlDate($SuiviHebdomadaireTempsPasse->date);
						$saisieInfirmiere = $evaluationInfirmierMapper->getObjectsByCabinetAndDate($account->cabinet, $evaluationInfirmier->date);
						$SuiviReunionMedecin = $SuiviReunionMedecinMapper->getObjectsByCabinetAndDate($account->cabinet, $evaluationInfirmier->date);

						#var_dump($SuiviReunionMedecin);

						forward($this->mappingTable["URL_NEW"],$errors);	
						}	
						$result = $SuiviHebdomadaireTempsPasseMapper->findObject($SuiviHebdomadaireTempsPasse->beforeSerialisation($account));


						if($result == false)
						{
							if($SuiviHebdomadaireTempsPasseMapper->lastError != BAD_MATCH)
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");
							$result = $SuiviHebdomadaireTempsPasseMapper->createObject($SuiviHebdomadaireTempsPasse->beforeSerialisation($account));
							if($result == false)
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la cr&eacute;ation");
							forward($this->mappingTable["URL_AFTER_CREATE"]);

						}
						else
						{
							$result = $SuiviHebdomadaireTempsPasseMapper->updateObject($SuiviHebdomadaireTempsPasse->beforeSerialisation($account));
							#echo '<pre>';var_dump($_POST);echo '</pre>';exit;
							if($result == false) {
								if($SuiviHebdomadaireTempsPasseMapper->lastError != NOTHING_UPDATED)
									forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour");
							}

							$SuiviHebdomadaireTempsPasse->cabinet = $account->cabinet;
							$evaluationInfirmier->date = dateToMysqlDate($SuiviHebdomadaireTempsPasse->date);
							$saisieInfirmiere = $evaluationInfirmierMapper->getObjectsByCabinetAndDate($account->cabinet, $evaluationInfirmier->date);
							$SuiviReunionMedecin = $SuiviReunionMedecinMapper->getObjectsByCabinetAndDateForHebdo($account->cabinet, $evaluationInfirmier->date);
							$result = $SuiviHebdomadaireTempsPasseMapper->findObject($SuiviHebdomadaireTempsPasse->beforeSerialisation($account));

							// gestion du temps passé par infirmières

							// herve gestion des infirmieres
							
							#var_dump($_POST);
							foreach($_POST as $key => $value){

								if(strpos($key,"Inf")){
									$infirmiere = str_replace("tpsInf_","",$key);
									if($value!=''){
										SuiviHebdomadaireTempsPasseInfirmieres::recordTempsPasseInfirmiere($account->cabinet,$infirmiere,$result->date,$value);
									}
									
								}
							}
							

							forward($this->mappingTable["URL_AFTER_FIND_VIEW"]);
						

							




						}

							
						break;

				case ACTION_DELETE:
					exitIfNull($SuiviHebdomadaireTempsPasse);
					exitIfNullOrEmpty($SuiviHebdomadaireTempsPasse->date);
					//$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
					//$$objectName->cabinet = $account->cabinet;
					$result = $SuiviHebdomadaireTempsPasseMapper->deleteObject($SuiviHebdomadaireTempsPasse->beforeSerialisation($account));
					if($result == false){
						if($SuiviHebdomadaireTempsPasseMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
					}
					forward($this->mappingTable["URL_AFTER_DELETE"]);

				case ACTION_LIST:
					set_time_limit(1200);//Ea
				
					switch($param->param1){
						default:
						
						#$result = $SuiviHebdomadaireTempsPasseMapper->getObjectsByCabinet($account->cabinet);
						#$result = $evaluationInfirmierMapper->getObjectsByCabinetAndDateDistinct($account->cabinet);

						//liste des lundis depuis le 02/07/2012
						$result = $SuiviHebdomadaireTempsPasseMapper->listeDesLundisDepuis02072012();
						
						#$result = $evaluationInfirmierMapper->getObjectsByCabinetAndDateDistinct($account->cabinet);
						#echo '<pre>';var_dump($result);echo '</pre>';
						

							if($result == false){
								if($SuiviHebdomadaireTempsPasseMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouv&eacute;s");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
							}
							global $rowsList;

							#$rowsList = array_natsort($result,"date","date");
							$rowsList = $result;
							

							forward($this->mappingTable["URL_AFTER_LIST"]);
					}

					echo 'LLLLLL';


				default:
					echo("ACTION IS NULL");
					break;
			}

		}
}
?>
