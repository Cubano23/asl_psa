<?php 
	
	require_once("bean/SuiviHebdomadaire2007.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/SuiviHebdomadaire2007Mapper.php");
	require_once("GenericControler.php");
	
class SuiviHebdomadaire2007Controler{
	
	var $mappingTable;
	
	var $mappingTable;
		
		function SuiviHebdomadaire2007Controler() {
			$this->mappingTable = 
			array(
			"URL_MANAGE"=>"view/suivihebdomadaire2007/managesuivihebdomadaire.php",
			"URL_NEW"=>"view/suivihebdomadaire2007/newsuivihebdomadaire.php",
			"URL_AFTER_CREATE"=>new ControlerParams("SuiviHebdomadaire2007Controler",ACTION_MANAGE,true),
			"URL_AFTER_UPDATE"=>new ControlerParams("SuiviHebdomadaire2007Controler",ACTION_MANAGE,true),
		//	"URL_AFTER_FIND_VIEW"=>new ControlerParams("SuiviHebdomadaireControler",ACTION_MANAGE,true,NULL,NULL,NULL,"Cette fonction n'est pas implémentée"),
		//	"URL_AFTER_FIND_EDIT"=>new ControlerParams("SuiviHebdomadaireControler",ACTION_MANAGE,true,NULL,NULL,NULL,"Cette fonction n'est pas implémentée"),
			"URL_AFTER_FIND_VIEW"=>"view/suivihebdomadaire2007/viewsuivihebdomadaire.php",
			"URL_AFTER_FIND_EDIT"=>"view/suivihebdomadaire2007/newsuivihebdomadaire.php",
			"URL_AFTER_DELETE"=>"",
			"URL_AFTER_LIST"=>"listsuivihebdomadaire2007",
			"URL_ON_CALLBACK_FAIL"=>"view/",
			"URL_AFTER_DELETE"=>new ControlerParams("SuiviHebdomadaire2007Controler",ACTION_MANAGE,true));
		}
	

		function start() {
//			$this->genericControler("SuiviHebdomadaireControler","suiviHebdomadaire","SuiviHebdomadaire","SuiviHebdomadaireMapper",$this->mappingTable);



			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $dossier;
			global $SuiviHebdomadaire2007;
			global $outDateReference;
			global $SuiviHebdomadaire2007List;


			if(array_key_exists("outDateReference",$objects))
				$outDateReference = $objects["outDateReference"];

			global $SuiviHebdomadaire2007;
			if(array_key_exists("SuiviHebdomadaire2007",$objects))
				$SuiviHebdomadaire2007 = $objects["SuiviHebdomadaire2007"];


			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","SuiviHebdomadaire2007Controler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$SuiviHebdomadaire2007Mapper = new SuiviHebdomadaire2007Mapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);


			switch($param->action){
				case ACTION_MANAGE:
					$SuiviHebdomadaire2007 = new SuiviHebdomadaire2007();
					$SuiviHebdomadaire2007->date= date("d/m/Y");

					forward($this->mappingTable["URL_MANAGE"]);
					break;

				case ACTION_FIND:
						if(!$param->isParam1Valid())
							forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");
						exitIfNull($SuiviHebdomadaire2007);
						exitIfNullOrEmpty($SuiviHebdomadaire2007->date);

						$SuiviHebdomadaire2007->cabinet = $account->cabinet;
						
						$result = $SuiviHebdomadaire2007Mapper->findObject($SuiviHebdomadaire2007->beforeSerialisation($account));

						if($result == false)
						{
							if($SuiviHebdomadaire2007Mapper->lastError == BAD_MATCH)
								forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement n'existe pas");
							else
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
						}
						$SuiviHebdomadaire2007 = $result->afterDeserialisation($account);

						if($param->param1 == PARAM_EDIT)
							forward($this->mappingTable["URL_AFTER_FIND_EDIT"]);
						else
							forward($this->mappingTable["URL_AFTER_FIND_VIEW"]);
						break;

				case ACTION_NEW:
						exitIfNull($SuiviHebdomadaire2007);
						exitIfNullOrEmpty($SuiviHebdomadaire2007->date);
						if(!isValidDate($SuiviHebdomadaire2007->date))
							forward($this->mappingTable["URL_MANAGE"],"La date du suivi est invalide");
						$SuiviHebdomadaire2007->cabinet = $account->cabinet;
						$result = $SuiviHebdomadaire2007Mapper->findObject($SuiviHebdomadaire2007->beforeSerialisation($account));

						if($result == false){
							if($SuiviHebdomadaire2007Mapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"impossible de trouver le dossier");
						}
						else
							forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement existe dejà, Appuyez sur modifier");
						forward($this->mappingTable["URL_NEW"]);
						break;


				case ACTION_SAVE:
						exitIfNull($SuiviHebdomadaire2007);
						exitIfNullOrEmpty($SuiviHebdomadaire2007->date);

						$errors = $SuiviHebdomadaire2007->check();
						if(count($errors) !=0)
							forward($this->mappingTable["URL_NEW"],$errors);
						$result = $SuiviHebdomadaire2007Mapper->findObject($SuiviHebdomadaire2007->beforeSerialisation($account));
						if($result == false)
						{
							if($SuiviHebdomadaire2007Mapper->lastError != BAD_MATCH)
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");
							$result = $SuiviHebdomadaire2007Mapper->createObject($SuiviHebdomadaire2007->beforeSerialisation($account));
							if($result == false)
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création");
							forward($this->mappingTable["URL_AFTER_CREATE"]);
						}
						else
						{
							$result = $SuiviHebdomadaire2007Mapper->updateObject($SuiviHebdomadaire2007->beforeSerialisation($account));
							if($result == false) {
								if($SuiviHebdomadaire2007Mapper->lastError != NOTHING_UPDATED)
									forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour");
							}
							forward($this->mappingTable["URL_AFTER_UPDATE"]);
						}
						break;

				case ACTION_DELETE:
					exitIfNull($SuiviHebdomadaire2007);
					exitIfNullOrEmpty($SuiviHebdomadaire2007->date);
					//$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
					//$$objectName->cabinet = $account->cabinet;
					$result = $SuiviHebdomadaire2007Mapper->deleteObject($SuiviHebdomadaire2007->beforeSerialisation($account));
					if($result == false){
						if($SuiviHebdomadaire2007Mapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
					}
					forward($this->mappingTable["URL_AFTER_DELETE"]);

				case ACTION_LIST:
					set_time_limit(1200); //EA
					switch($param->param1){
						default:
							$result = $SuiviHebdomadaire2007Mapper->getObjectsByCabinet($account->cabinet);
							if($result == false){
								if($SuiviHebdomadaire2007Mapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
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
