<?php 
	
	require_once("bean/SuiviHebdomadaire.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/SuiviHebdomadaireMapper.php");
	require_once("GenericControler.php");
	
class SuiviHebdomadaireControler{
	
	//var $mappingTable;
	
	var $mappingTable;
		
		function SuiviHebdomadaireControler() {
			$this->mappingTable = 
			array(
			"URL_MANAGE"=>"view/evaluation/managesuivihebdomadaire.php",
			"URL_NEW"=>"view/evaluation/newsuivihebdomadaire.php",
			"URL_AFTER_CREATE"=>new ControlerParams("SuiviHebdomadaireControler",ACTION_MANAGE,true),
			"URL_AFTER_UPDATE"=>new ControlerParams("SuiviHebdomadaireControler",ACTION_MANAGE,true),
		//	"URL_AFTER_FIND_VIEW"=>new ControlerParams("SuiviHebdomadaireControler",ACTION_MANAGE,true,NULL,NULL,NULL,"Cette fonction n'est pas implémentée"),
		//	"URL_AFTER_FIND_EDIT"=>new ControlerParams("SuiviHebdomadaireControler",ACTION_MANAGE,true,NULL,NULL,NULL,"Cette fonction n'est pas implémentée"),
			"URL_AFTER_FIND_VIEW"=>"view/evaluation/viewsuivihebdomadaire.php",
			"URL_AFTER_FIND_EDIT"=>"view/evaluation/editsuivihebdomadaire.php",
			"URL_AFTER_DELETE"=>"",
			"URL_AFTER_LIST"=>"listsuivihebdomadaire",
			"URL_ON_CALLBACK_FAIL"=>"view/",
			"URL_AFTER_DELETE"=>new ControlerParams("SuiviHebdomadaireControler",ACTION_MANAGE,true));
		}
	

		function start() {
//			$this->genericControler("SuiviHebdomadaireControler","suiviHebdomadaire","SuiviHebdomadaire","SuiviHebdomadaireMapper",$this->mappingTable);



			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $dossier;
			global $SuiviHebdomadaire;
			global $outDateReference;
			global $SuiviHebdomadaireList;


			if(array_key_exists("outDateReference",$objects))
				$outDateReference = $objects["outDateReference"];

			global $SuiviHebdomadaire;
			if(array_key_exists("SuiviHebdomadaire",$objects))
				$SuiviHebdomadaire = $objects["SuiviHebdomadaire"];


			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","SuiviHebdomadaireControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$SuiviHebdomadaireMapper = new SuiviHebdomadaireMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);


			switch($param->action){
				case ACTION_MANAGE:
					$SuiviHebdomadaire = new SuiviHebdomadaire();
					$SuiviHebdomadaire->date= date("d/m/Y");

					forward($this->mappingTable["URL_MANAGE"]);
					break;

				case ACTION_FIND:
						if(!$param->isParam1Valid())
							forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");
						exitIfNull($SuiviHebdomadaire);
						exitIfNullOrEmpty($SuiviHebdomadaire->date);

						$SuiviHebdomadaire->cabinet = $account->cabinet;
						
						$result = $SuiviHebdomadaireMapper->findObject($SuiviHebdomadaire->beforeSerialisation($account));

						if($result == false)
						{
							if($SuiviHebdomadaireMapper->lastError == BAD_MATCH)
								forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement n'existe pas");
							else
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
						}
						$SuiviHebdomadaire = $result->afterDeserialisation($account);

						if($param->param1 == PARAM_EDIT)
							forward($this->mappingTable["URL_AFTER_FIND_EDIT"]);
						else
							forward($this->mappingTable["URL_AFTER_FIND_VIEW"]);
						break;

				case ACTION_NEW:
						exitIfNull($SuiviHebdomadaire);
						exitIfNullOrEmpty($SuiviHebdomadaire->date);
						if(!isValidDate($SuiviHebdomadaire->date))
							forward($this->mappingTable["URL_MANAGE"],"La date du suivi est invalide");
						$SuiviHebdomadaire->cabinet = $account->cabinet;
						$result = $SuiviHebdomadaireMapper->findObject($SuiviHebdomadaire->beforeSerialisation($account));

						if($result == false){
							if($SuiviHebdomadaireMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"impossible de trouver le dossier");
						}
						else
							forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement existe dejà, Appuyez sur modifier");
						forward($this->mappingTable["URL_NEW"]);
						break;


				case ACTION_SAVE:
						exitIfNull($SuiviHebdomadaire);
						exitIfNullOrEmpty($SuiviHebdomadaire->date);

						$errors = $SuiviHebdomadaire->check();
						if(count($errors) !=0)
							forward($this->mappingTable["URL_NEW"],$errors);
						$result = $SuiviHebdomadaireMapper->findObject($SuiviHebdomadaire->beforeSerialisation($account));
						if($result == false)
						{
							if($SuiviHebdomadaireMapper->lastError != BAD_MATCH)
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");
							$result = $SuiviHebdomadaireMapper->createObject($SuiviHebdomadaire->beforeSerialisation($account));
							if($result == false)
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création");
							forward($this->mappingTable["URL_AFTER_CREATE"]);
						}
						else
						{
							$result = $SuiviHebdomadaireMapper->updateObject($SuiviHebdomadaire->beforeSerialisation($account));
							if($result == false) {
								if($SuiviHebdomadaireMapper->lastError != NOTHING_UPDATED)
									forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour");
							}
							forward($this->mappingTable["URL_AFTER_UPDATE"]);
						}
						break;

				case ACTION_DELETE:
					exitIfNull($SuiviHebdomadaire);
					exitIfNullOrEmpty($SuiviHebdomadaire->date);
					//$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
					//$$objectName->cabinet = $account->cabinet;
					$result = $SuiviHebdomadaireMapper->deleteObject($SuiviHebdomadaire->beforeSerialisation($account));
					if($result == false){
						if($SuiviHebdomadaireMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
					}
					forward($this->mappingTable["URL_AFTER_DELETE"]);

				case ACTION_LIST:
					switch($param->param1){
						default:
							$result = $SuiviHebdomadaireMapper->getObjectsByCabinet($account->cabinet);
							if($result == false){
								if($SuiviHebdomadaireMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
							}
							global $rowsList;
							$rowsList = array_natsort($result,"date","date");


							forward($this->mappingTable["URL_AFTER_LIST"]);
					}




				default:
					echo("ACTION IS NULL");
					break;
			}

		}
}
?>
