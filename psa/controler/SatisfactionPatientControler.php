<?php 
	
	require_once("bean/SatisfactionPatient.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/SatisfactionPatientMapper.php");
	require_once("persistence/ConnectionFactory.php");

class SatisfactionPatientControler {
	
	var $mappingTable;
	
		
		function SatisfactionPatientControler() {
			$this->mappingTable = 
			array(

			"URL_NEW"=>"view/satisfactionpatient/newsatisfactionpatient.php",
			"URL_AFTER_CREATE"=>"view/satisfactionpatient/viewsatisfactionpatientaftercreate.php",
			"URL_ON_CALLBACK_FAIL"=>"view/");
		}
	

		function start() {
			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;

			global $SatisfactionPatient;

			if(array_key_exists("SatisfactionPatient",$objects))
				$SatisfactionPatient = $objects["SatisfactionPatient"];
			else
				$SatisfactionPatient = new SatisfactionPatient();


			// create ledger for this controler
			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","SatisfactionPatientControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$SatisfactionPatientMapper = new SatisfactionPatientMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);
			switch($param->action){
				case ACTION_MANAGE:
					$SatisfactionPatient = new SatisfactionPatient();
					forward($this->mappingTable["URL_MANAGE"]);
					break;

				case ACTION_NEW:
//					exitIfNull($SatisfactionPatient);

					forward($this->mappingTable["URL_NEW"]);
					break;

				case ACTION_SAVE:
					exitIfNull($SatisfactionPatient);

					$errors = $SatisfactionPatient->check();
					if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);

					$result = $SatisfactionPatientMapper->createObject($SatisfactionPatient->beforeSerialisation($account));
					if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"erreur lors de la création");
						forward($this->mappingTable["URL_AFTER_CREATE"]);
					break;

				case ACTION_LIST:
					forward($this->mappingTable["URL_AFTER_LIST"]);
					break;

				case ACTION_FIND:
					forward($this->mappingTable["URL_AFTER_FIND"]);
					break;


				default:
					echo("ACTION IS NULL");
					break;
			}
		}
}
?>
