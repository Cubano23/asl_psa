	<?php


  
  
	require_once("bean/ControlerParams.php");
	require_once("bean/Biologie.php");
	require_once("bean/DepistageDiabete.php");
	require_once("bean/OutdateReference.php");
	require_once("persistence/BiologieMapper.php");
	require_once("persistence/DepistageDiabeteMapper.php");
	require_once("persistence/DossierMapper.php");		
	require_once("persistence/ConnectionFactory.php");
	require_once("tools/arrays.php");
	
	class DepistageDiabeteControler {
		var $mappingTable;
		
		function getSignature(){
			return "";
		}

		function DepistageDiabeteControler(){
			$this->mappingTable = 
				array(
				"URL_MANAGE"=>"view/diabete/depistage/managedepistagediabete.php",
				"URL_NEW"=>"view/diabete/depistage/newdepistagediabete.php",
//				"URL_AFTER_CREATE"=>new ControlerParams("DepistageDiabeteControler",ACTION_MANAGE,true),
//				"URL_AFTER_UPDATE"=>new ControlerParams("DepistageDiabeteControler",ACTION_MANAGE,true),
				"URL_AFTER_CREATE"=>"view/diabete/depistage/viewdepistagediabieteaftercreate.php",
				"URL_AFTER_UPDATE"=>"view/diabete/depistage/viewdepistagediabieteaftercreate.php",
				"URL_AFTER_FIND_VIEW"=>"view/diabete/depistage/viewdepistagediabete.php",
				"URL_AFTER_FIND_EDIT"=>"view/diabete/depistage/newdepistagediabete.php",
				"URL_AFTER_DELETE"=>new ControlerParams("DepistageDiabeteControler",ACTION_MANAGE,true),
				"URL_ON_CALLBACK_FAIL"=>"view/",
				"URL_MANAGE_OUTDATED"=>"view/diabete/depistage/managealertedepistagediabete.php",
				"URL_AFTER_LIST_OUTDATED"=>"view/diabete/depistage/listdepistagediabete.php",
				"URL_AFTER_LIST"=>"listdiabete",
				"URL_AFTER_FIND_LIST_DOSSIER"=>"listdiabetebydossier");
		}

		function start(){
			
			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;		
			global $poids;		
			global $glycemie;		

			global $outDateReference;
			if(array_key_exists("outDateReference",$objects))
				$outDateReference = $objects["outDateReference"];

			global $dossier;
			if(array_key_exists("dossier",$objects))
				$dossier = $objects["dossier"];	

			global $depistageDiabete;
			if(array_key_exists("depistageDiabete",$objects))
				$depistageDiabete = $objects["depistageDiabete"];	

			if(array_key_exists("poids",$objects))
				$poids = $objects["poids"];	

			if(array_key_exists("glycemie",$objects))
				$glycemie = $objects["glycemie"];	

			// declare global variables that might be usefull for the view
			global $currentObjectName;
			global $currentObjectClass;						
			global $signature;
			$signature = $this->getSignature();$signature;
			$currentObjectName = "depistageDiabete";
			$currentObjectClass = "DepistageDiabete";
			
			// create ledger for this controler
			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","DepistageDiabeteControler");
			
			//Create connection factory
			$cfactory = new ConnectionFactory();
	
			//create mappers
			$dossierMapper = new DossierMapper($cfactory->getConnection());
			$objectMapper = new DepistageDiabeteMapper($cfactory->getConnection());
			$BiologieMapper = new BiologieMapper($cfactory->getConnection());

			switch($param->action){
				case ACTION_MANAGE:							
					$dossier = new Dossier();								
					$depistageDiabete = new DepistageDiabete();
					$depistageDiabete->date= date("d/m/Y");

					$poids = new Biologie();										
					$glycemie = new Biologie();										

					if(!$param->isParam1Valid()) 
						forward($this->mappingTable["URL_MANAGE"]);
					else
					{
					    switch($param->param1){
							case PARAM_OUTDATED:
								$outDateReference = new OutDateReference();
								forward($this->mappingTable["URL_MANAGE_OUTDATED"]);
							default:
								forward($this->mappingTable["URL_MANAGE"]);
						}

					}
					break;
					
				case ACTION_FIND:

					if(!$param->isParam1Valid()) 
						forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");
					exitIfNull($dossier);
					exitIfNull($depistageDiabete);
					exitIfNullOrEmpty($depistageDiabete->date);				
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);
					executeCallBack("",$dossier,$depistageDiabete,$this->mappingTable["URL_MANAGE"]);
					$depistageDiabete->id = $dossier->id;
					$result = $objectMapper->findObject($depistageDiabete->beforeSerialisation($account));

					if($result == false)
					{
						if($objectMapper->lastError == BAD_MATCH) 
							forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement n'existe pas");
						else 
							forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
					}
					$depistageDiabete = $result->afterDeserialisation($account);

					$result=$BiologieMapper->findExam($result->date, $dossier->id, "poids");

					$poids=new Biologie();
					$poids->date_exam=$result["date_exam"];
					$poids->resultat1=$result["resultat1"];
					$poids->id=$dossier->id;
					$poids->type_exam="poids";
					$poids->numero=$result["numero"];

					$glycemie=new Biologie();
					$glycemie->date_exam=$result["date_exam"];
					$glycemie->resultat1=$result["resultat1"];
					$glycemie->id=$dossier->id;
					$glycemie->type_exam="glycemie";
					$glycemie->numero=$result["numero"];

					executeCallBack("",$dossier,$depistageDiabete,$this->mappingTable["URL_MANAGE"]);
					if($param->param1 == PARAM_EDIT){
						forward($this->mappingTable["URL_AFTER_FIND_EDIT"]);
					}
					else 
						forward($this->mappingTable["URL_AFTER_FIND_VIEW"]);
					break;
					
				
				case ACTION_NEW:
					exitIfNull($dossier);
					exitIfNull($depistageDiabete);
					exitIfNullOrEmpty($depistageDiabete->date);								
					if(!isValidDate($depistageDiabete->date))
						forward($this->mappingTable["URL_MANAGE"],"La date du dépistage est invalide");
					$dossier = checkDossierActif($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);
					executeCallBack("",$dossier,$depistageDiabete,$this->mappingTable["URL_MANAGE"]);
					$depistageDiabete->id = $dossier->id;

					$poids->id=$dossier->id;
					$poids->type_exam="poids";

					$glycemie->id=$dossier->id;
					$glycemie->type_exam="glycemie";
					
					$result = $objectMapper->findObject($depistageDiabete->beforeSerialisation($account));
					if($result == false){
						if($objectMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"dossier non trouvé");					
					}
					else
						forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement existe dejà, Appuyer sur Modifier");

					global $dernierExam;
					$dernierExam = new DepistageDiabete();
					
					$cle=$objectMapper->getForeignKey();
					$dernierExam->$cle = $depistageDiabete->$cle;

					$dernierExam = $objectMapper->findDernierExam($dernierExam);
					
					if($dernierExam!==false){
						$dernierExam = $dernierExam->afterDeserialisation($account);
					}			
          

          				
					executeCallBack("",$dossier,$depistageDiabete,$this->mappingTable["URL_MANAGE"]);
					forward($this->mappingTable["URL_NEW"]);
					break;
					
							
					
				case ACTION_SAVE:
					exitIfNull($dossier);
					exitIfNull($depistageDiabete);
					exitIfNullOrEmpty($depistageDiabete->date);
					// $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);
					executeCallBack("",$dossier,$depistageDiabete,$this->mappingTable["URL_MANAGE"]);					
					
					// print_r($poids);die;
					$depistageDiabete->id = $dossier->id;
					$depistageDiabete->dpoids=$poids->date_exam;
					$depistageDiabete->poids=$poids->resultat1;
					$poids->id=$dossier->id;

					$depistageDiabete->derniere_gly_date=$glycemie->date_exam;
					$depistageDiabete->derniere_gly_resultat=$glycemie->resultat1;
					$glycemie->id=$dossier->id;
					
					executeCallBack("",$dossier,$depistageDiabete,$this->mappingTable["URL_MANAGE"]);
					$errors = $depistageDiabete->check();
					if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);

					$result = $dossierMapper->updateObject($dossier->beforeSerialisation($account));
					
					$liste_exam=array("poids", "glycemie");
					
					foreach($liste_exam as $exam){
						if($$exam->date_exam!=""){
							$result = $BiologieMapper->findExamSaisi($$exam->beforeSerialisation($account));									

							$maj=1;
							if($result!==false){//Un examen a été trouvé. 
								if($result["resultat1"]!=$$exam->resultat1){//Le poids est différent=> il faut faire une maj
									$$exam->numero=$result["numero"];
								}
								else{//L'exam enregistré est identique=> pas de maj
									$maj=0;
								}
							}
							
							if($maj==1){
								$result = $BiologieMapper->findObject($$exam->beforeSerialisation($account));									

								if($result==false){//Aucun poids créé avec le même identifiant
									$result = $BiologieMapper->createObject($$exam->beforeSerialisation($account));
								}
								else{//Déjà un poids créé avec le même identifiant=>maj
									$result = $BiologieMapper->updateObject($$exam->beforeSerialisation($account));
								}
							}
						}
					}
					
					$result = $objectMapper->findObject($depistageDiabete->beforeSerialisation($account));
					
					if($result == false){

						if(($objectMapper->lastError != BAD_MATCH)&&($objectMapper->lastError!=PARAM)) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");

						$result = $objectMapper->createObject($depistageDiabete->beforeSerialisation($account));

						if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création");
						forward($this->mappingTable["URL_AFTER_CREATE"]);
					}
					else{
						
						$result = $objectMapper->updateObject($depistageDiabete->beforeSerialisation($account));

						if($result == false) {
							if($objectMapper->lastError != NOTHING_UPDATED)
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour");
						}
						forward($this->mappingTable["URL_AFTER_UPDATE"]);
					}
					break;

				case ACTION_DELETE:
					exitIfNull($dossier);
					exitIfNull($depistageDiabete);
					exitIfNullOrEmpty($depistageDiabete->date);
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
					$depistageDiabete->id = $dossier->id;
					$result = $objectMapper->deleteObject($depistageDiabete->beforeSerialisation($account));
					if($result == false){
						if($objectMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
					}
					forward($this->mappingTable["URL_AFTER_DELETE"]);
					
					
				case ACTION_LIST:
					set_time_limit(1200);//ea
					switch($param->param1){
						case PARAM_LIST_BY_DOSSIER:
							$result = $objectMapper->getObjectsByDossier($account->cabinet, $dossier->numero);
							if($result == false){
								if($objectMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
							}
							global $rowsList;
							$rowsList = array_natsort($result,"numero","numero");

							forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER"]);
						case PARAM_LIST_BY_DOSSIER_TOOLTIP:
							$result = $objectMapper->getObjectsByDossier($account->cabinet, $dossier->numero);
							if($result == false){
								if($objectMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER_TOOLTIP"],"Pas d'enregistrements trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
							}
							global $rowsList;
							$rowsList = array_natsort($result,"numero","numero");

							forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER_TOOLTIP"]);
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

								foreach($rowsList as $pos=>$donnees){
									$id=$donnees["dossier_id"];
							
									$liste_exam=array("glycemie"=>"derniere_gly_date");
									
									foreach($liste_exam as $code=>$champ){
										$result=$BiologieMapper->findExam(date("Y-m-d"), $id, $code);
										if(strpos($result["date_exam"],"/")!==false){
											$result["date_exam"]=explode("/", $result["date_exam"]);
											$result["date_exam"]=$result["date_exam"][2]."-".$result["date_exam"][1]."-".$result["date_exam"][0];
										}
										$rowsList[$pos][$champ]=$result["date_exam"];
									}
								}

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


							forward($this->mappingTable["URL_AFTER_LIST"]);
					}
					



				default:
					echo("ACTION IS NULL");
					break;
			}
		}
	}

?>
