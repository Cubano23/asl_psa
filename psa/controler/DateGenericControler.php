<?php 

	require_once("persistence/ConnectionFactory.php");
	require_once("tools/arrays.php");
	
	class DateGenericControler {
	
		function getSignature(){
			return "";
		}

		function DateGenericControler($controlerName,$objectName,$objectClass,$mapperClass,$mappingTable,$callbackObject=""){
			
			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;		
		
			
			global $$objectName;
			if(array_key_exists("$objectName",$objects))
				$$objectName = $objects["$objectName"];	

			// declare global variables that might be usefull for the view
			global $currentObjectName;
			global $currentObjectClass;						
			global $signature;
			$signature = $this->getSignature();
			$currentObjectName = $objectName;
			$currentObjectClass = $objectClass;
			
			// create ledger for this controler
			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler",$controlerName);
			
			//Create connection factory
			$cfactory = new ConnectionFactory();
	
			//create mappers			
			$objectMapper = new $mapperClass($cfactory->getConnection());
			
			switch($param->action){
				case ACTION_MANAGE:												
					$$objectName = new $objectClass();
					$$objectName->date= date("d/m/Y");
					forward($mappingTable["URL_MANAGE"]);
					break;
					
				
				
				case ACTION_NEW:													
					exitIfNull($$objectName);			
					exitIfNullOrEmpty($$objectName->date);								
					
					$errors = $$objectName->check();
					if(count($errors) !=0) forward($mappingTable["URL_MANAGE"],$errors);
					
					if(!isValidDate($$objectName->date))
						forward($mappingTable["URL_MANAGE"],"La date de l'évaluation est invalide");															
					
					$result = $objectMapper->findObject($$objectName->beforeSerialisation($$objectName));
					if($result == false){
						if($objectMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");					
					}
					else
						forward($mappingTable["URL_MANAGE"],"Cet enregistrement existe dejà");
					
					forward($mappingTable["URL_NEW"]);
					break;
							
					
				case ACTION_SAVE:					
					exitIfNull($$objectName);
					exitIfNullOrEmpty($$objectName->date);
															
					$errors = $$objectName->check();
					if(count($errors) !=0) forward($mappingTable["URL_NEW"],$errors);
					$result = $objectMapper->findObject($$objectName->beforeSerialisation($$objectName));				
					if($result == false){
						if($objectMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");
						$result = $objectMapper->createObject($$objectName->beforeSerialisation($account));
						if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"create failed");
						forward($mappingTable["URL_AFTER_CREATE"]);
					}
					else{
						$result = $objectMapper->updateObject($$objectName->beforeSerialisation($account));
						if($result == false) {
							if($objectMapper->lastError != NOTHING_UPDATED)
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"update failed");
						}
						forward($mappingTable["URL_AFTER_UPDATE"]);
					}
					break;
					
				case ACTION_DELETE:
					
					exitIfNull($$objectName);
					exitIfNullOrEmpty($$objectName->date);
										
					$result = $objectMapper->deleteObject($$objectName->beforeSerialisation($account));
					if($result == false){
						if($objectMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
					}
					forward($mappingTable["URL_AFTER_DELETE"]);
				
				case ACTION_LIST:			
					$result = $objectMapper->getObjectsByCabinet($account->cabinet);
					if($result == false){
						if($objectMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
						else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");				
					}
					global $rowsList;
					$rowsList = array_natsort($result,"numero","numero");

					
					//print_r($rowsList);
					forward($mappingTable["URL_AFTER_LIST"]);
					
				default:
					echo("ACTION IS NULL");
					break;
			}
		}
	}

?>