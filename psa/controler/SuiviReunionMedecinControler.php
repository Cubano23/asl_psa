<?php 
	
	require_once("bean/SuiviReunionMedecin.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/SuiviReunionMedecinMapper.php");
	require_once("GenericControler.php");

	require_once ("Config.php");
	$config = new Config();
	require_once($config->rest_path . '/GetCabsAndLogins.php') ;
	
class SuiviReunionMedecinControler{
	
	var $mappingTable;
		
		function SuiviReunionMedecinControler() {
			$this->mappingTable = 
			array(
			"URL_MANAGE"=>"view/suiviReunionMedecin/managesuivi.php",
			
			"URL_AFTER_CREATE"=>"view/suiviReunionMedecin/viewsuivi.php",
			#"URL_AFTER_CREATE"=>new ControlerParams("SuiviReunionMedecinControler",ACTION_FIND,true),
			"URL_AFTER_UPDATE"=>new ControlerParams("SuiviReunionMedecinControler",ACTION_MANAGE,true),
			"URL_NEW"=>"view/suiviReunionMedecin/newsuivi.php",
		//	"URL_AFTER_FIND_VIEW"=>new ControlerParams("SuiviHebdomadaireControler",ACTION_MANAGE,true,NULL,NULL,NULL,"Cette fonction n'est pas implémentée"),
		//	"URL_AFTER_FIND_EDIT"=>new ControlerParams("SuiviHebdomadaireControler",ACTION_MANAGE,true,NULL,NULL,NULL,"Cette fonction n'est pas implémentée"),
			"URL_AFTER_FIND_VIEW"=>"view/suiviReunionMedecin/viewsuivi.php",
			"URL_AFTER_FIND_EDIT"=>"view/suiviReunionMedecin/newsuivi.php",
			"URL_AFTER_DELETE"=>"",
			"URL_AFTER_LIST"=>"listsuiviReunionMedecin",
			"URL_ON_CALLBACK_FAIL"=>"view/",
			"URL_AFTER_DELETE"=>new ControlerParams("SuiviReunionMedecinControler",ACTION_MANAGE,true));
		}
	

		function start() {
			//$this->genericControler("SuiviHebdomadaireControler","suiviHebdomadaire","SuiviHebdomadaire","SuiviHebdomadaireMapper",$this->mappingTable);
			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $dossier;
			global $SuiviReunionMedecin;
			global $outDateReference;
			global $SuiviReunionMedecinList;
			global $Rowlist;
			global $medecins;


			if(array_key_exists("outDateReference",$objects))
				$outDateReference = $objects["outDateReference"];

			global $SuiviReunionMedecin;
			if(array_key_exists("SuiviReunionMedecin",$objects))
				$SuiviReunionMedecin = $objects["SuiviReunionMedecin"];


			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","SuiviReunionMedecinControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$SuiviReunionMedecinMapper = new SuiviReunionMedecinMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);

			switch($param->action){
				case ACTION_MANAGE:
					$SuiviReunionMedecin = new SuiviReunionMedecin();
					$SuiviReunionMedecin->date= date("d/m/Y");

					forward($this->mappingTable["URL_MANAGE"]);
					break;

				case ACTION_FIND:
				
						if(!$param->isParam1Valid())
							forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1 af");
							exitIfNull($SuiviReunionMedecin);
							exitIfNullOrEmpty($SuiviReunionMedecin->date);

						$SuiviReunionMedecin->cabinet = $account->cabinet;

						global $infirmieres;
						$infirmieres = GetLoginsByCab($_SESSION['cabinet'],$status);
						#echo '<pre> '; print_r($infirmieres); echo '</pre>';
						$result = $SuiviReunionMedecinMapper->getObjectsByCabinetAndDate($SuiviReunionMedecin->cabinet,dateToMysqlDate($SuiviReunionMedecin->date));

						global $medecins;
						$medecins = $SuiviReunionMedecinMapper->getMedecinsOfthisCabinet($SuiviReunionMedecin->cabinet);
						#var_dump($medecins);
						global $Rowlist;
						$Rowlist = $result;
						
						if($result == false)
						{
							if($SuiviReunionMedecinMapper->lastError == BAD_MATCH)
								forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement n'existe pas");
							else
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
						}

						if($param->param1 == PARAM_EDIT){
							forward($this->mappingTable["URL_AFTER_FIND_EDIT"]);
							}
							else{
							forward($this->mappingTable["URL_AFTER_FIND_VIEW"]);
							}
						break;

				case ACTION_NEW:
				
						exitIfNull($SuiviReunionMedecin);
						exitIfNullOrEmpty($SuiviReunionMedecin->date);
						if(!isValidDate($SuiviReunionMedecin->date))
							forward($this->mappingTable["URL_MANAGE"],"La date du suivi est invalide");
						$SuiviReunionMedecin->cabinet = $account->cabinet;
						$result = $SuiviReunionMedecinMapper->getObjectsByCabinetAndDate($SuiviReunionMedecin->cabinet,dateToMysqlDate($SuiviReunionMedecin->date));
						global $medecins;
						$medecins = $SuiviReunionMedecinMapper->getMedecinsOfthisCabinet($SuiviReunionMedecin->cabinet);

						if($result == false){
							if($SuiviReunionMedecinMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"impossible de trouver le dossier");
						}
						else
							forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement existe d&eacute;j&agrave;, Appuyez sur modifier");
						forward($this->mappingTable["URL_NEW"]);
						break;


				case ACTION_SAVE:
					


					exitIfNull($SuiviReunionMedecin);
					exitIfNullOrEmpty($SuiviReunionMedecin->date);
	
						if(count($errors) !=0)
							forward($this->mappingTable["URL_NEW"],$errors);

						$result = $SuiviReunionMedecinMapper->getObjectsByCabinetAndDate($SuiviReunionMedecin->cabinet,dateToMysqlDate($SuiviReunionMedecin->date));
						
						global $Rowlist;
						$Rowlist = $result;
						if(count($result) != 0) ///// AJOUT /////
						{
							//delete
							$result = $SuiviReunionMedecinMapper->deleteObject($SuiviReunionMedecin->beforeSerialisation($account));
						}

						$i = 0;

						while(!empty($_POST['date_reunion_'.$i]) && $_POST['duree_'.$i]!='') {

							#echo '<p>'.$i.' : '.$_POST['duree_'.$i].' -> '.serialize($_POST['medecin_'.$i]).'</p>';

							// conversion des id_mg en nom prenom de medecin (ancienne version)
							$medecins = array();
							foreach($_POST['id_mg_'.$i] as $mg){
								#echo $mg;#
								$infosMedecin = $SuiviReunionMedecinMapper->getMedecinById($mg);
								$nomMedecin = $infosMedecin['prenom'].' '.$infosMedecin['nom'];
								array_push($medecins,$nomMedecin);
							}
							
							#var_dump($medecins);exit;
							#$nomMedecin[] = $infosMedecin['prenom'].' '.$infosMedecin['nom'];
							#echo $nomMedecin;
							$SuiviReunionMedecin = new SuiviReunionMedecin(
								$_POST['SuiviReunionMedecin:SuiviReunionMedecin:cabinet'],
								$_POST['SuiviReunionMedecin:SuiviReunionMedecin:date'],
								$_POST['date_reunion_'.$i],
								$_POST['duree_'.$i],
								implode($medecins,','),
								implode($_POST['infirmiere_'.$i],','),
								$_POST['motif_'.$i],
								implode($_POST['id_mg_'.$i],',')
								);	

								
								#var_dump($SuiviReunionMedecin);exit;
								// si il manque le médecin ou l'infirmière on renvoi avec message error
								if(!empty($_POST['date_reunion_'.$i])){
									if(empty($_POST['id_mg_'.$i])){
										$medecins = $SuiviReunionMedecinMapper->getMedecinsOfthisCabinet($SuiviReunionMedecin->cabinet);
										$errors ='Enregistrement non effectu&eacute;, veuillez selectionner un m&eacute;decin';
										forward($this->mappingTable["URL_NEW"],$errors);
									}
								}
								
								$i++;
						
								$result = $SuiviReunionMedecinMapper->createObject($SuiviReunionMedecin->beforeSerialisation($account));
								
						}

						if($result == false)
							forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la cr&eacute;ation ".serialize($SuiviReunionMedecin));
						$result = $SuiviReunionMedecinMapper->getObjectsByCabinetAndDate($SuiviReunionMedecin->cabinet,dateToMysqlDate($SuiviReunionMedecin->date));
						$Rowlist = $result;
						forward($this->mappingTable["URL_AFTER_CREATE"]);

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
					set_time_limit(120);
					switch($param->param1){
						default:
							$result = $SuiviReunionMedecinMapper->getObjectsByCabinet($account->cabinet);
							if($result == false){
								if($SuiviReunionMedecinMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Aucun enregistrement trouv&eacute;");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
							}
							else{
							global $rowsList;

							#$rowsList = array_natsort($result,"date","date");
							$rowsList = $result;
							#echo '<pre>';var_dump($rowsList);echo '</pre>';
							forward($this->mappingTable["URL_AFTER_LIST"]);
							}
					}




				default:
					echo("ACTION IS NULL");
					break;
			}

		}
}
?>
