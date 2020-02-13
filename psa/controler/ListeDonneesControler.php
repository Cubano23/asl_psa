<?php 
	
	require_once("bean/ListeDonnees.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/ListeDonneesMapper.php");
	require_once("GenericControler.php");
	
	class ListeDonneesControler{
	
		var $mappingTable;
		
		function ListeDonneesControler() {
			$this->mappingTable = 
			array(
			"URL_NEW"=>"view/cardiovasculaire/newlistedonnees.php",
			"URL_AFTER_CREATE"=>"view/cardiovasculaire/viewlistedonneesaftercreate.php",
			"URL_AFTER_UPDATE"=>"view/cardiovasculaire/viewlistedonneesaftercreate.php",
			"URL_AFTER_LIST"=>"view/cardiovasculaire/viewlistedonnees.php",
			"URL_ON_CALLBACK_FAIL"=>"view/");
		}
	

		function start(){

			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $dossier;
			global $ListeDonnees;


			if(array_key_exists("outDateReference",$objects))
				$outDateReference = $objects["outDateReference"];

			global $dossier;
			if(array_key_exists("dossier",$objects))
				$dossier = $objects["dossier"];

			if(array_key_exists("ListeDonnees",$objects))
				$ListeDonnees = $objects["ListeDonnees"];


			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","ListeDonneesControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$ListeDonneesMapper = new ListeDonneesMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);


			switch($param->action){

				case ACTION_NEW:

					$ListeDonnees = new ListeDonnees();

					$ListeDonnees->cabinet = $account->cabinet;
						
					$result = $ListeDonneesMapper->getValeurs($account);

					if($result == false){
						if($ListeDonneesMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
					}
					else{
						$result=$result[0];

						$ListeDonnees->antecedants = $result['antecedants'];
						$ListeDonnees->Chol = $result['Chol'];
						$ListeDonnees->dChol= $result['dChol'];
						$ListeDonnees->HDL = $result['HDL'];
						$ListeDonnees->dHDL = $result['dHDL'];
						$ListeDonnees->LDL = $result['LDL'];
						$ListeDonnees->dLDL = $result['dLDL'];
						$ListeDonnees->triglycerides = $result['triglycerides'];
						$ListeDonnees->dtriglycerides = $result['dtriglycerides'];
						$ListeDonnees->traitement = $result['traitement'];
						$ListeDonnees->dosage = $result['dosage'];
						$ListeDonnees->HTA = $result['HTA'];
						$ListeDonnees->TaSys = $result['TaSys'];
						$ListeDonnees->TaDia = $result['TaDia'];
						$ListeDonnees->dTA = $result['dTA'];
						$ListeDonnees->hypertenseur3 = $result['hypertenseur3'];
						$ListeDonnees->automesure = $result['automesure'];
						$ListeDonnees->diuretique = $result['diuretique'];
						$ListeDonnees->HVG=$result['HVG'];
						$ListeDonnees->surcharge_ventricule = $result['surcharge_ventricule'];
						$ListeDonnees->sokolov = $result['sokolov'];
						$ListeDonnees->dsokolov=$result['dsokolov'];
						$ListeDonnees->Creat=$result['Creat'];
						$ListeDonnees->dCreat=$result['dCreat'];
						$ListeDonnees->kaliemie=$result['kaliemie'];
						$ListeDonnees->dkaliemie=$result['dkaliemie'];
						$ListeDonnees->proteinurie = $result['proteinurie'];
						$ListeDonnees->dproteinurie=$result['dproteinurie'];
						$ListeDonnees->hematurie = $result['hematurie'];
						$ListeDonnees->dhematurie = $result['dhematurie'];
						$ListeDonnees->dFond = $result['dFond'];
						$ListeDonnees->dECG = $result['dECG'];
						$ListeDonnees->tabac = $result['tabac'];
						$ListeDonnees->darret = $result['darret'];
						$ListeDonnees->poids = $result['poids'];
						$ListeDonnees->dpoids = $result['dpoids'];
						$ListeDonnees->activite = $result['activite'];
						$ListeDonnees->pouls = $result['pouls'];
						$ListeDonnees->dpouls = $result['dpouls'];
						$ListeDonnees->alcool = $result['alcool'];
						$ListeDonnees->glycemie = $result['glycemie'];
						$ListeDonnees->dgly = $result['dgly'];
						$ListeDonnees->exam_cardio = $result['exam_cardio'];
		
					}


					forward($this->mappingTable["URL_NEW"]);
					break;

				case ACTION_SAVE:
						exitIfNull($ListeDonnees);
						exitIfNullOrEmpty($ListeDonnees->cabinet);

						$errors = $ListeDonnees->check();

						if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);

						$result = $ListeDonneesMapper->findObject($ListeDonnees->beforeSerialisation($account));

						if($result == false){

							if(($ListeDonneesMapper->lastError != BAD_MATCH)&&($ListeDonneesMapper->lastError!=PARAM)) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");

							$result = $ListeDonneesMapper->createObject($ListeDonnees->beforeSerialisation($account));

							if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création");
							forward($this->mappingTable["URL_AFTER_CREATE"]);
						}
						else{
							$result = $ListeDonneesMapper->updateObject($ListeDonnees->beforeSerialisation($account));

							if($result == false) {
								if($ListeDonneesMapper->lastError != NOTHING_UPDATED)
									forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour");
							}
							forward($this->mappingTable["URL_AFTER_UPDATE"]);
						}
						break;


				case ACTION_LIST :
					$result = $ListeDonneesMapper->getDonnees();

					if($result == false){
						if($ListeDonneesMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_AFTER_LIST"],"Pas d'enregistrements trouvés");
						else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
					}
					global $rowsList;
					$rowsList = array_natsort($result,"nom_cab","nom_cab");

					forward($this->mappingTable["URL_AFTER_LIST"]);
				

				default:
					echo("ACTION IS NULL");
					break;
			}
		}

	}
?> 
