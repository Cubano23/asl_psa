<?php 
	
	require_once("bean/FicheCabinet.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/FicheCabinetMapper.php");
	require_once("persistence/ConnectionFactory.php");

	class FicheCabinetControler{
	
		var $mappingTable;
		
		function FicheCabinetControler() {
			$this->mappingTable = 
			array(
//			"URL_MANAGE"=>"view/cancercolon/managedepistagecolon.php",
			"URL_NEW"=>"view/fichecabinet/newfichecabinet.php",
//			"URL_AFTER_CREATE"=>new ControlerParams("DepistageCancerColonControler",ACTION_MANAGE,true),
//			"URL_AFTER_UPDATE"=>new ControlerParams("DepistageCancerColonControler",ACTION_MANAGE,true),
			"URL_AFTER_CREATE"=>"view/fichecabinet/viewfichecabinetaftercreate.php",
			"URL_AFTER_UPDATE"=>"view/fichecabinet/viewfichecabinetaftercreate.php",
//			"URL_AFTER_FIND_VIEW"=>"view/cancercolon/viewdepistagecolon.php",
//			"URL_AFTER_FIND_EDIT"=>"view/cancercolon/newdepistagecolon.php",
//			"URL_AFTER_DELETE"=>new ControlerParams("DepistageCancerColonControler",ACTION_MANAGE,true),
//			"URL_AFTER_LIST"=>"view/cancercolon/listdepistagecolon.php",
//			"URL_AFTER_FIND_LIST_DOSSIER"=>"view/cancercolon/listdepistagecolonbydossier.php",
			"URL_VIEW"=>"view/fichecabinet/viewfichecabinet.php",
			"URL_ON_CALLBACK_FAIL"=>"view/");

		}
	

		function start() {
			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $FicheCabinet;

			if(array_key_exists("FicheCabinet",$objects))
				$FicheCabinet = $objects["FicheCabinet"];


			// create ledger for this controler
			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","FicheCabinetControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$FicheCabinetMapper = new FicheCabinetMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);
			switch($param->action){
				case ACTION_MANAGE:

					break;

				case ACTION_FIND:
				    $FicheCabinet=New FicheCabinet();
					$FicheCabinet->cabinet = $account->cabinet;
					$result = $FicheCabinetMapper->findObject($FicheCabinet->beforeSerialisation($account));

					$FicheCabinet = $result->afterDeserialisation($account);

					forward($this->mappingTable["URL_NEW"]);

						break;



				case ACTION_SAVE:
					exitIfNull($FicheCabinet);

					$errors = $FicheCabinet->check();
					if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);

					$result = $FicheCabinetMapper->updateObject($FicheCabinet->beforeSerialisation($account));
					if($result == false) {
						if($FicheCabinetMapper->lastError != NOTHING_UPDATED)
							forward(URL_CONTROLER_PERSISTENCE_ERROR,"update failed");
					}
					forward($this->mappingTable["URL_AFTER_UPDATE"]);

					break;


				case ACTION_LIST:
					global $rowsList;
					$rowsList = $FicheCabinetMapper->getTousCabinets();
					
					if($rowsList==false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");

					forward($this->mappingTable["URL_VIEW"]);

						break;

				default:
					echo("ACTION IS NULL");
					break;
			}


		}
	}
?> 
