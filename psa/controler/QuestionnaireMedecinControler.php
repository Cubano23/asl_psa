<?php 
	
	require_once("bean/QuestionnaireMedecin.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/QuestionnaireMedecinMapper.php");
	require_once("persistence/ConnectionFactory.php");

class QuestionnaireMedecinControler {
	
	var $mappingTable;
	
		
		function QuestionnaireMedecinControler() {
			$this->mappingTable = 
			array(

			"URL_NEW"=>"view/questionnairemedecin/newquestionnairemedecin.php",
			"URL_AFTER_CREATE"=>"view/questionnairemedecin/viewquestionnairemedecinaftercreate.php",
			"URL_ON_CALLBACK_FAIL"=>"view/");
		}
	

		function start() {
			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;

			global $QuestionnaireMedecin;

			if(array_key_exists("QuestionnaireMedecin",$objects))
				$QuestionnaireMedecin = $objects["QuestionnaireMedecin"];
			else
				$QuestionnaireMedecin = new QuestionnaireMedecin();


			// create ledger for this controler
			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","QuestionnaireMedecinControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$QuestionnaireMedecinMapper = new QuestionnaireMedecinMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);
			switch($param->action){
				case ACTION_MANAGE:
					$QuestionnaireMedecin = new QuestionnaireMedecin();
					forward($this->mappingTable["URL_MANAGE"]);
					break;

				case ACTION_NEW:
//					exitIfNull($SatisfactionPatient);
					$QuestionnaireMedecin = new QuestionnaireMedecin();

					$QuestionnaireMedecin->medecin=$_SESSION['login'];

					$result=$QuestionnaireMedecinMapper->findObject($QuestionnaireMedecin->beforeSerialisation($account));

					if($result == false){
						if($QuestionnaireMedecinMapper->lastError == BAD_MATCH) {
							$result=$QuestionnaireMedecinMapper->getCoordonnees();
		
							$QuestionnaireMedecin->nom=$result['nom_complet'];
							$QuestionnaireMedecin->prenom=$result['prenom'];
		
							forward($this->mappingTable["URL_NEW"]);
						}
						else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
					}

					$QuestionnaireMedecin = $result->afterDeserialisation($account);

					forward($this->mappingTable["URL_NEW"]);

					break;

				case ACTION_SAVE:
					exitIfNull($QuestionnaireMedecin);

					$errors = $QuestionnaireMedecin->check();

					if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);

					$result=$QuestionnaireMedecinMapper->findObject($QuestionnaireMedecin->beforeSerialisation($account));

					if($result == false){
						if($QuestionnaireMedecinMapper->lastError == BAD_MATCH) {
							$result = $QuestionnaireMedecinMapper->createObject($QuestionnaireMedecin->beforeSerialisation($account));
							if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"erreur lors de la création");
						}
						else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
					}
					else{
						$result = $QuestionnaireMedecinMapper->updateObject($QuestionnaireMedecin->beforeSerialisation($account));
						if($result == false) {
							if($QuestionnaireMedecinMapper->lastError != NOTHING_UPDATED)
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"update failed");
						}
					}

					$mail=$QuestionnaireMedecinMapper->getmail($QuestionnaireMedecin);
					
					
					$result=$QuestionnaireMedecinMapper->getCoordonnees();

					if($result['courriel']=='NULL'){
						$connecte="";
					}
					else{
						$connecte="\"".$result['prenom']." ".$result['nom_complet']."\" <".$result['courriel'].">";
					}
					
					$mail_confirm="<html><body>Bonjour,<br><br>
									Nous avons bien enregistré votre questionnaire EPP.<br><br>
									Vous pouvez à tout moment modifier ce questionnaire sur <a href='".
									"https://www.asalee.fr/psam'>https://www.asalee.fr/psam</a>.<br><br>".
									"Détail des données saisies : <br>$mail";
									
					$mail_gautier="<html><body>Bonjour,<br><br>
									Questionnaire saisi par le Dr ".$result['prenom']." ".$result['nom_complet'].
									" le ".date("d/m/Y")." : <br><br>$mail";
					
					$entete_confirm="From: \"Questionnaire EPP\" <epp@asalee.fr>\n".
							"MIME-Version: 1.0\n".
							"Bcc: \"Amaury Derville\" <aderville@isas.fr>, \"Xavier Guillon\" <xguillon@asalee.fr>\n".
							"Content-Type: text/html";

					$entete_gautier="From: \"Questionnaire EPP\" <epp@asalee.fr>\n".
							"MIME-Version: 1.0\n".
							"Cc: \"Isabelle Rambault-Amoros\" <dr.isabelle.rambaultamoros@wanadoo.fr>, \"René Fernandez\" <dr.rene.fernandez@wanadoo.fr>\n".
							"Bcc: \"Amaury Derville\" <aderville@isas.fr>, \"Xavier Guillon\" <xguillon@asalee.fr>\n".
							"Content-Type: text/html";

					$sujet="Asalée - Questionnaire EPP";

					mail("\"Jean Gautier\" <j.gautier@medsyn.fr>", $sujet, $mail_gautier, $entete_gautier);
					
					if($connecte!=""){
						mail($connecte, $sujet, $mail_confirm, $entete_confirm);
					}

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
