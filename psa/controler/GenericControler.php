<?php 

	require_once("persistence/DossierMapper.php");		
	require_once("bean/OutdateReference.php");
	require_once("persistence/ConnectionFactory.php");
	require_once("tools/arrays.php");
	
	class GenericControler {
	
		function getSignature(){
			return "";
		}

		function genericControler($controlerName,$objectName,$objectClass,$mapperClass,$mappingTable,$callbackObject=""){
			
			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;		

			global $outDateReference;
			if(array_key_exists("outDateReference",$objects))
				$outDateReference = $objects["outDateReference"];

			global $dossier;
			if(array_key_exists("dossier",$objects))
				$dossier = $objects["dossier"];	

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
			$dossierMapper = new DossierMapper($cfactory->getConnection());
			$objectMapper = new $mapperClass($cfactory->getConnection());

			switch($param->action){
				case ACTION_MANAGE:							
					$dossier = new Dossier();								
					$$objectName = new $objectClass();
					$$objectName->date= date("d/m/Y");

					if(!$param->isParam1Valid()) 
						forward($mappingTable["URL_MANAGE"]);
					else
					{
					    switch($param->param1){
							case PARAM_OUTDATED:
								$outDateReference = new OutDateReference();
								forward($this->mappingTable["URL_MANAGE_OUTDATED"]);
							default:
								forward($mappingTable["URL_MANAGE"]);
						}

					}
					break;
					
				case ACTION_FIND:
					if ($objectName !="suiviHebdomadaire")
					{
						if(!$param->isParam1Valid()) 
							forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");
						exitIfNull($dossier);
						exitIfNull($$objectName);
						exitIfNullOrEmpty($$objectName->date);				
						$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$mappingTable["URL_MANAGE"]);
						executeCallBack($callbackObject,$dossier,$$objectName,$mappingTable["URL_MANAGE"]);
						$$objectName->id = $dossier->id;
						$result = $objectMapper->findObject($$objectName->beforeSerialisation($account));

						if($result == false)
						{
							if($objectMapper->lastError == BAD_MATCH) 
								forward($mappingTable["URL_MANAGE"],"Cet enregistrement n'existe pas");
							else 
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
						}
						$$objectName = $result->afterDeserialisation($account);

						executeCallBack($callbackObject,$dossier,$$objectName,$mappingTable["URL_MANAGE"]);
						if($param->param1 == PARAM_EDIT){
							forward($mappingTable["URL_AFTER_FIND_EDIT"]);
						}
						else 
							forward($mappingTable["URL_AFTER_FIND_VIEW"]);
						break;
					}
					else
					{
						if(!$param->isParam1Valid()) 
							forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");
						exitIfNull($$objectName);
						exitIfNullOrEmpty($$objectName->date);
						$$objectName->cabinet = $account->cabinet;
						executeCallBack($callbackObject,$dossier,$$objectName,$mappingTable["URL_MANAGE"]);
						$result = $objectMapper->findObject($$objectName->beforeSerialisation($account));

						if($result == false)
						{
							if($objectMapper->lastError == BAD_MATCH)
								forward($mappingTable["URL_MANAGE"],"Cet enregistrement n'existe pas");
							else
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
						}
						$$objectName = $result->afterDeserialisation($account);
						executeCallBack($callbackObject,$dossier,$$objectName,$mappingTable["URL_MANAGE"]);
						if($param->param1 == PARAM_EDIT)
							forward($mappingTable["URL_AFTER_FIND_EDIT"]);
						else
							forward($mappingTable["URL_AFTER_FIND_VIEW"]);
						break;
					}
				
				case ACTION_NEW:
					if ($objectName !="suiviHebdomadaire")
					{

						exitIfNull($dossier);
						exitIfNull($$objectName);
						exitIfNullOrEmpty($$objectName->date);								
						if(!isValidDate($$objectName->date))
							forward($mappingTable["URL_MANAGE"],"La date du dépistage est invalide");
						$dossier = checkDossierActif($dossier,$dossierMapper,$account->cabinet,true,$mappingTable["URL_MANAGE"]);
						executeCallBack($callbackObject,$dossier,$$objectName,$mappingTable["URL_MANAGE"]);
						$$objectName->id = $dossier->id;
						
						$result = $objectMapper->findObject($$objectName->beforeSerialisation($account));
						if($result == false){
							if($objectMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"dossier non trouvé");					
						}
						else
							forward($mappingTable["URL_MANAGE"],"Cet enregistrement existe dejà, Appuyer sur Modifier");

						global $dernierExam;
						$dernierExam = new $objectClass();
						
						$cle=$objectMapper->getForeignKey();
						$dernierExam->$cle = $$objectName->$cle;

						$dernierExam = $objectMapper->findDernierExam($dernierExam);
						
						if($dernierExam!==false){
							$dernierExam = $dernierExam->afterDeserialisation($account);
						}							
						executeCallBack($callbackObject,$dossier,$$objectName,$mappingTable["URL_MANAGE"]);
						forward($mappingTable["URL_NEW"]);
						break;
					}
					else
					{
						
						exitIfNull($$objectName);
						exitIfNullOrEmpty($$objectName->date);								
						if(!isValidDate($$objectName->date))
							forward($mappingTable["URL_MANAGE"],"La date du suivi est invalide");
						$$objectName->cabinet = $account->cabinet;
						$result = $objectMapper->findObject($$objectName->beforeSerialisation($account));
						
						if($result == false){
							if($objectMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"impossible de trouver le dossier");					
						}
						else
							forward($mappingTable["URL_MANAGE"],"Cet enregistrement existe dejà, Appuyez sur modifier");
						forward($mappingTable["URL_NEW"]);
						break;
					}
							
					
				case ACTION_SAVE:
					if ($objectName !="suiviHebdomadaire")
					{
						exitIfNull($dossier);
						exitIfNull($$objectName);
						exitIfNullOrEmpty($$objectName->date);
						// $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$mappingTable["URL_MANAGE"]);
						executeCallBack($callbackObject,$dossier,$$objectName,$mappingTable["URL_MANAGE"]);					
						$$objectName->id = $dossier->id;
						
						executeCallBack($callbackObject,$dossier,$$objectName,$mappingTable["URL_MANAGE"]);
						$errors = $$objectName->check();
						if(count($errors) !=0) forward($mappingTable["URL_NEW"],$errors);

						$result = $dossierMapper->updateObject($dossier->beforeSerialisation($account));
						
						$result = $objectMapper->findObject($$objectName->beforeSerialisation($account));
						
						if($result == false){

							if(($objectMapper->lastError != BAD_MATCH)&&($objectMapper->lastError!=PARAM)) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");

							$result = $objectMapper->createObject($$objectName->beforeSerialisation($account));

							if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création");
							forward($mappingTable["URL_AFTER_CREATE"]);
						}
						else{
							
							$result = $objectMapper->updateObject($$objectName->beforeSerialisation($account));

							if($result == false) {
								if($objectMapper->lastError != NOTHING_UPDATED)
									forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour");
							}
							forward($mappingTable["URL_AFTER_UPDATE"]);
						}
						break;
					}
					else
					{
						exitIfNull($$objectName);
						exitIfNullOrEmpty($$objectName->date);
						executeCallBack($callbackObject,$dossier,$$objectName,$mappingTable["URL_MANAGE"]);					
						executeCallBack($callbackObject,$dossier,$$objectName,$mappingTable["URL_MANAGE"]);
						$errors = $$objectName->check();
						if(count($errors) !=0) 
							forward($mappingTable["URL_NEW"],$errors);
						$result = $objectMapper->findObject($$objectName->beforeSerialisation($account));				
						if($result == false)
						{
							if($objectMapper->lastError != BAD_MATCH) 
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");
							$result = $objectMapper->createObject($$objectName->beforeSerialisation($account));
							if($result == false) 
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création");
							forward($mappingTable["URL_AFTER_CREATE"]);
						}
						else
						{
							$result = $objectMapper->updateObject($$objectName->beforeSerialisation($account));
							if($result == false) {
								if($objectMapper->lastError != NOTHING_UPDATED)
									forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour");
							}
							forward($mappingTable["URL_AFTER_UPDATE"]);
						}
						break;
					}

				case ACTION_DELETE:
				if ($objectName !="suiviHebdomadaire")
					{
					exitIfNull($dossier);
					exitIfNull($$objectName);
					exitIfNullOrEmpty($$objectName->date);
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
					$$objectName->id = $dossier->id;
					$result = $objectMapper->deleteObject($$objectName->beforeSerialisation($account));
					if($result == false){
						if($objectMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
					}
					forward($mappingTable["URL_AFTER_DELETE"]);
					}
				else
				{
					//exitIfNull($dossier);
					exitIfNull($$objectName);
					exitIfNullOrEmpty($$objectName->date);
					//$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
					//$$objectName->cabinet = $account->cabinet;
					$result = $objectMapper->deleteObject($$objectName->beforeSerialisation($account));
					if($result == false){
						if($objectMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
					}
					forward($mappingTable["URL_AFTER_DELETE"]);
				}
				
				case ACTION_LIST:
					set_time_limit(1200);//EA
					switch($param->param1){
						case PARAM_LIST_BY_DOSSIER:
							$result = $objectMapper->getObjectsByDossier($account->cabinet, $dossier->numero);
							if($result == false){
								if($objectMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
							}
							global $rowsList;
							$rowsList = array_natsort($result,"numero","numero");

							forward($mappingTable["URL_AFTER_FIND_LIST_DOSSIER"]);
						case PARAM_LIST_BY_DOSSIER_TOOLTIP:
							$result = $objectMapper->getObjectsByDossier($account->cabinet, $dossier->numero);
							if($result == false){
								if($objectMapper->lastError == BAD_MATCH) forward($mappingTable["URL_AFTER_FIND_LIST_DOSSIER_TOOLTIP"],"Pas d'enregistrements trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
							}
							global $rowsList;
							$rowsList = array_natsort($result,"numero","numero");

							forward($mappingTable["URL_AFTER_FIND_LIST_DOSSIER_TOOLTIP"]);
	      				case PARAM_OUTDATED:
							exitIfNull($outDateReference);

							$result = $objectMapper->getExpiredExams($account->cabinet,$outDateReference->period);

							if($result == false){
								if($objectMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_MANAGE_OUTDATED"],"Pas d'enregistrements trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
							}
							else
								if(count($result)==0) forward($this->mappingTable["URL_MANAGE_OUTDATED"],"Pas d'enregistrements trouvés");

								global $rowsList;
								$rowsList = array_natsort($result,"numero","numero");

					        	$exam=$objectMapper->getExamName();
					        	$rappel=$objectMapper->getRappelName();

							  	for($i=0;$i<count($rowsList);$i++){
									$result = $objectMapper->getdernierRappel($rowsList[$i]['id'], $rowsList[$i][$exam]);

									if($result == false){
										if($objectMapper->lastError == BAD_MATCH) $result=0;
										else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
									}


									if($result!=0)
									{
										$date_rappel = array_natsort($result,"id","id");

										$date_rappel=$date_rappel[0];

										if($rappel!=''){
											$rowsList[$i][$rappel]=$date_rappel[$rappel];
										}
										$rowsList[$i]['sortir_rappel']=$date_rappel['sortir_rappel'];

									}

							  	}

							forward($this->mappingTable["URL_AFTER_LIST_OUTDATED"]);
							break;
						
						default:
							$result = $objectMapper->getObjectsByCabinet($account->cabinet);
							if($result == false){
								if($objectMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
							}
							global $rowsList;
							$rowsList = array_natsort($result,"numero","numero");


							forward($mappingTable["URL_AFTER_LIST"]);
					}
					



				default:
					echo("ACTION IS NULL");
					break;
			}
		}
	}

?>
