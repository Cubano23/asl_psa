<?php 
	
	require_once("bean/Biologie.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/BiologieMapper.php");
	require_once("GenericControler.php");
	require_once("tools/formulas.php");
	
	class HistoBiologieControler{
	
		var $mappingTable;
		
		function HistoBiologieControler() {
			$this->mappingTable = 
			array(
			"URL_MANAGE"=>"view/biologie/managebiologie.php",
			"URL_AFTER_LIST"=>"view/biologie/histoexam.php",
			"URL_LISTE_EXAM"=>"view/biologie/listeexam.php",
			"URL_MODIF_EXAM"=>"view/biologie/modifbiologie.php",
			"URL_AFTER_CREATE"=>"view/biologie/viewbiologieaftercreate.php",
			"URL_AFTER_UPDATE"=>"view/biologie/viewbiologieafterupdate.php",
			"URL_ON_CALLBACK_FAIL"=>"view/");
		}
	

		function start(){

			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $dossier;
			global $liste_exam;
			global $Biologie;

			if(array_key_exists("outDateReference",$objects))
				$outDateReference = $objects["outDateReference"];

			global $dossier;
			if(array_key_exists("dossier",$objects))
				$dossier = $objects["dossier"];

			if(array_key_exists("Biologie",$objects))
				$Biologie = $objects["Biologie"];

			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","HistoBiologieControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$BiologieMapper = new BiologieMapper($cf->getConnection());
			$dossierMapper = new DossierMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);


			switch($param->action){
				case ACTION_MANAGE:
					$dossier=new Dossier();
					
					forward($this->mappingTable["URL_MANAGE"]);
				
				
				case ACTION_FIND:
					exitIfNull($dossier);
					exitIfNull($Biologie);
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

					$Biologie=$BiologieMapper->getExam($dossier, $Biologie);
					$Biologie=$Biologie->afterDeserialisation($account);
					forward($this->mappingTable["URL_MODIF_EXAM"]);
				break;


				case ACTION_LIST:

					if(isset($param->param1)&&($param->param1==PARAM_ANY)){//Liste de tous les exams saisis pour 1 dossier
						exitIfNull($dossier);
						$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);
						
						$Biologie->id=$dossier->id;
						global $liste_resultats;
						$liste_resultats=array();
						
						$result = $BiologieMapper->ListeTousExams($dossier);
						
						if($result == false){//Aucune ligne trouvée
							if($BiologieMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Aucun examen trouvé");
						}
						// print_r($result);
						global $liste_exam;
						
						$liste_exam=array("creat"=>"liste_creat", "albu"=>"liste_albu", "fond"=>"liste_fond", 
										  "ECG"=>"liste_ecg", "dent"=>"liste_dent", "pied"=>"liste_pied", 
										  "monofil"=>"liste_monofil", "poids"=>"liste_poids", "spirometrie"=>"liste_spirometrie", 
										  "systole"=>"liste_systole", "diastole"=>"liste_diastole", 
										  "HDL"=>"liste_hdl", "LDL"=>"liste_ldl", "Chol"=>"liste_chol", 
										  "HBA1c"=>"liste_hba", "glycemie"=>"liste_gly", 
										  "hematurie"=>"liste_hematurie", "pouls"=>"liste_pouls", 
										  "proteinurie"=>"liste_proteinurie", "triglycerides"=>"liste_tri",
										  "kaliemie"=>"liste_kaliemie");	
						
						foreach($liste_exam as $liste){
							global $$liste;
						}
						
						global $liste_dates;
						$liste_dates=array();
						
						foreach($result as $exam){
							if(isset($liste_exam[$exam->type_exam])){
								$liste=$liste_exam[$exam->type_exam];
								
								if(isset($$liste)){
									$liste_temp=$$liste;
								}
								else{
									$liste_temp="";
								}
								
								$liste_temp[$exam->date_exam]=$exam;
								$$liste=$liste_temp;
								
								if(!in_array($exam->date_exam, $liste_dates)){
									$liste_dates[]=$exam->date_exam;
								}
								
							}
						}
						
						rsort($liste_dates);

						forward($this->mappingTable["URL_LISTE_EXAM"]);
					
					}
					else{//historique d'un type d'exam dans les tooltip
						
						exitIfNull($dossier);
						exitIfNull($Biologie);
						exitIfNullOrEmpty($Biologie->type_exam);
						
						$Biologie->id=$dossier->id;
						global $liste_resultats;
						$liste_resultats=array();
						
						$result = $BiologieMapper->ListeExams($Biologie->beforeSerialisation($account), $dossier);
						
						if($result == false){//Aucune ligne trouvée
							if($BiologieMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"dossier non trouvé");
						}
						
						if($Biologie->type_exam=="systole"){
							$result2 = $BiologieMapper->ListeDiastole($dossier->id);
						}
						
						foreach($result as $tab){
							if($Biologie->type_exam=="systole"){
								if(isset($result2[$tab["date_exam"]])){
									$tab["resultat1"]=$tab["resultat1"]."/".$result2[$tab["date_exam"]];
									$Biologie = new Biologie($dossier->id, "", $Biologie->type_exam, $tab["date_exam"], $tab["resultat1"]);
									$Biologie = $Biologie->afterDeserialisation($account);
									$liste_resultats[]=$Biologie;
								}
							}
							else{
								$Biologie = new Biologie($dossier->id, "", $Biologie->type_exam, $tab["date_exam"], $tab["resultat1"], $tab["resultat2"]);
								$Biologie = $Biologie->afterDeserialisation($account);
								$liste_resultats[]=$Biologie;
							}
						}


						forward($this->mappingTable["URL_AFTER_LIST"]);
					}

					break;

				case ACTION_SAVE:
					exitIfNull($dossier);
					exitIfNull($Biologie);
					exitIfNullOrEmpty($Biologie->date_exam);								
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);
					
					$Biologie->id=$dossier->id;

					$result = $BiologieMapper->findObject($Biologie->beforeSerialisation($account));									
					
					if($result == false){
						if($BiologieMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");
						$result = $BiologieMapper->createObject($Biologie->beforeSerialisation($account));
						if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"erreur lors de la création");
						
					
						forward($this->mappingTable["URL_AFTER_CREATE"]);
					}
					else{
						$result = $BiologieMapper->updateObject($Biologie->beforeSerialisation($account));
						if($result == false) {
							if($BiologieMapper->lastError != NOTHING_UPDATED){
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"update failed");	
							}
						}
						forward($this->mappingTable["URL_AFTER_UPDATE"]);
					}

				break;

				default:
					echo("ACTION IS NULL");
					break;
			}
		}

	}
?> 
