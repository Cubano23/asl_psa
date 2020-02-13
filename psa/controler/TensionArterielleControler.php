<?php 

	require_once("bean/Dossier.php");	
	require_once("bean/TensionArterielle.php");	
	require_once("bean/TensionArterielleManagement.php");	
	require_once("bean/TensionArterielleMoyenne.php");	
	require_once("persistence/DossierMapper.php");
	require_once("persistence/TensionArterielleMapper.php");
	require_once("persistence/TensionArterielleMoyenneMapper.php");
	require_once("persistence/ConnectionFactory.php");	
	
	class TensionArterielleControler {
	
		var $mappingTable;
		
		function TensionArterielleControler(){
			$this->mappingTable = array(
			"URL_MANAGE"=>"view/tensionarterielle/managetensionarterielle.php",
			"URL_NEW"=>"view/tensionarterielle/newtensionarterielle.php",
			"URL_AFTER_CREATE"=>"view/tensionarterielle/tensionarteriellemoyenne.php",
			"URL_AFTER_UPDATE"=>new ControlerParams("DossierControler",ACTION_MANAGE,true),
			"URL_AFTER_FIND_VIEW"=>"view/dossier/updatedossier.php",
			"URL_AFTER_FIND_EDIT"=>"view/dossier/updatedossier.php",
			"URL_AFTER_FIND"=>"view/tensionarterielle/viewtensionarterielle.php",
			"URL_AFTER_LIST"=>"view/tensionarterielle/listtensionarterielle.php",
			"URL_LIST_TENSION_ARTERIELLE"=>"view/tensionarterielle/listtensionarteriellebycabinet.php");
		}
		
		function start() {
			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;	
							
			global $dossier;
			global $tensionArterielle;
			global $tensionArterielleManagement;
			global $tensionArterielleMoyenne;
			global $tensionArterielleMoyenneList;
	
			if(array_key_exists("dossier",$objects))
				$dossier = $objects["dossier"];	
				
			if(array_key_exists("tensionArterielleManagement",$objects))
				$tensionArterielleManagement = $objects["tensionArterielleManagement"];	

				
			// create ledger for this controler
			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","TensionArterielle");
				
			//Create connection factory
			$cf = new ConnectionFactory();
		
			//create mappers
			$dossierMapper = new DossierMapper($cf->getConnection());			
			$tensionArterielleMapper = new TensionArterielleMapper($cf->getConnection());
			$tensionArterielleMoyenneMapper = new TensionArterielleMoyenneMapper($cf->getConnection());
			
			$ledger->writeArray(I,"Start","Control Parameters = ",$param);
			switch($param->action){
				case ACTION_MANAGE:					
					$dossier = new Dossier();				
					$tensionArterielleManagement = new TensionArterielleManagement();
					forward($this->mappingTable["URL_MANAGE"]);
					break;
				
				case ACTION_NEW:
					exitIfNull($dossier);
					exitIfNull($tensionArterielleManagement);
					exitIfNullOrEmpty($tensionArterielleManagement->dateDebut);
					exitIfNullOrEmpty($tensionArterielleManagement->nombreJours);
					$dossier = checkDossierActif($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);		

					$result = $tensionArterielleMapper->checkDateAvailable($dossier->id, dateToMysqlDate($tensionArterielleManagement->dateDebut));
					if($result == false){

						forward($this->mappingTable["URL_MANAGE"],"Une automesure a d�j� �t� enregistr�e pour le jour indiqu� pour ce patient.<br/> 
							Vous ne pouvez cr�er deux consultations le m�me jour.");
					}
					
					for($i = 0;$i < $tensionArterielleManagement->nombreJours; $i++){
						for($j = 0;$j < 3; $j++){
							$taMatinName = "ta".$i."matin".$j;
							$taSoirName = "ta".$i."soir".$j;
							global $$taMatinName;
							global $$taSoirName;
							$$taMatinName = new TensionArterielle();
							$$taSoirName = new TensionArterielle();							
							$$taMatinName->date = increaseDateBy($tensionArterielleManagement->dateDebut,$i);
							$$taSoirName->date = increaseDateBy($tensionArterielleManagement->dateDebut,$i);
						}
					}
					forward($this->mappingTable["URL_NEW"]);
					break;
						
				case ACTION_SAVE:
					exitIfNull($dossier);
					exitIfNull($tensionArterielleManagement);
					exitIfNullOrEmpty($tensionArterielleManagement->dateDebut);
					exitIfNullOrEmpty($tensionArterielleManagement->nombreJours);
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
					$errorsArray = array();
					$taArray = array();
					$sumDiaMatin = 0;
					$sumSysMatin = 0;
					$sumDiaSoir = 0;
					$sumSysSoir = 0;					
					for($i = 0;$i < $tensionArterielleManagement->nombreJours; $i++){
						for($j = 0;$j < 3; $j++){
							$taMatinName = "ta".$i."matin".$j;
							$taSoirName = "ta".$i."soir".$j;
							if(array_key_exists($taMatinName,$objects)) {
								global $$taMatinName;								
								$$taMatinName = $objects["$taMatinName"];
						    }	
							else {
								// ....
								$errorsArray[] = "absence de la $j&deg; mesure du Matin pour jour $i"; 
							}		
							if(array_key_exists($taSoirName,$objects)) {
								global $$taSoirName;
								$$taSoirName = $objects["$taSoirName"];	
						    }	
							else {
								// ....
								$errorsArray[] = "absence de la $j&deg; mesure du Soir pour jour $i"; 
							}

							$errorsArray = array_merge($errorsArray, $$taMatinName->check($account));
							$errorsArray = array_merge($errorsArray, $$taSoirName->check($account));

							if(sizeof($errorsArray)==0) {
								$taArray[$i]['Matin'][$j] = $$taMatinName;
								$taArray[$i]['Soir'][$j] = $$taSoirName;
								$sumDiaMatin += $$taMatinName->diastole;								
								$sumSysMatin += $$taMatinName->systole;																
								$sumDiaSoir += $$taSoirName->diastole;								
								$sumSysSoir += $$taSoirName->systole;								
							}								
						}
					}
					# Errors found
					if(sizeof($errorsArray)>0) {
					   forward($this->mappingTable["URL_NEW"], $errorsArray);						
					   exit;
					}

					# Compute average values
					$avgDiaMatin = round($sumDiaMatin / $tensionArterielleManagement->nombreJours / 3);
					$avgSysMatin = round($sumSysMatin / $tensionArterielleManagement->nombreJours / 3);					
					$avgDiaSoir = round($sumDiaSoir / $tensionArterielleManagement->nombreJours / 3);
					$avgSysSoir = round($sumSysSoir / $tensionArterielleManagement->nombreJours / 3);					
					$avgDia = round(($sumDiaMatin + $sumDiaSoir) / $tensionArterielleManagement->nombreJours / 6);
					$avgSys = round(($sumSysMatin + $sumSysSoir) / $tensionArterielleManagement->nombreJours / 6);
					
					# Database update - averages
					
 			   	    $group_id = $tensionArterielleMoyenneMapper->getGroupId($dossier);
					$tensionArterielleMoyenne = new TensionArterielleMoyenne();		
 				    $tensionArterielleMoyenne->id = $dossier->id;
				    $tensionArterielleMoyenne->group_id = $group_id;	
					$tensionArterielleMoyenne->date_debut = $tensionArterielleManagement->dateDebut;
					$tensionArterielleMoyenne->nombre_jours = $tensionArterielleManagement->nombreJours;
					$tensionArterielleMoyenne->moyenne_dia_matin = $avgDiaMatin;
					$tensionArterielleMoyenne->moyenne_dia_soir = $avgDiaSoir;
					$tensionArterielleMoyenne->moyenne_sys_matin = $avgSysMatin;
					$tensionArterielleMoyenne->moyenne_sys_soir = $avgSysSoir;
					$tensionArterielleMoyenne->moyenne_sys = $avgSys;
					$tensionArterielleMoyenne->moyenne_dia = $avgDia;
					
					$result = $tensionArterielleMoyenneMapper->createObject($tensionArterielleMoyenne->beforeSerialisation($account));
					if($result == false) {
					   forward(URL_CONTROLER_PERSISTENCE_ERROR,"create failed (average)");
					   exit;
					}			   	    
							
					
					# Database update - details		
								 
					foreach($taArray as $jour => $taJour) {
						$datejour = increaseDateBy($tensionArterielleManagement->dateDebut,$jour);
						foreach($taJour as $moment => $taJourMoment) {
							foreach($taJourMoment as $indice => $taValeur) {
							   # create object to insert into the database
							   $tensionArterielle = new TensionArterielle();		
							   $tensionArterielle->id = $dossier->id;
							   $tensionArterielle->date = $datejour;
							   $tensionArterielle->momment_journee = $moment;
							   $tensionArterielle->indice = $indice;
							   $tensionArterielle->systole = $taValeur->systole;
							   $tensionArterielle->diastole	= $taValeur->diastole;	
							   $tensionArterielle->group_id = $group_id;	
							   
							   $result = $tensionArterielleMapper->createObject($tensionArterielle->beforeSerialisation($account));
					           if($result == false) {
								   forward(URL_CONTROLER_PERSISTENCE_ERROR,"create failed (detail)");
								   exit;
					           }
							}
						}
			        }                    			 
				    forward($this->mappingTable["URL_AFTER_CREATE"]);						
				
					break;
					
				case ACTION_LIST:
					exitIfNull($dossier);	
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);
					$tensionArterielleMoyenne = new TensionArterielleMoyenne();
					$tensionArterielleMoyenne->id = $dossier->id;
					$result = $tensionArterielleMoyenneMapper->findObjects($tensionArterielleMoyenne);
					if($result == false){
						if($tensionArterielleMoyenneMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_AFTER_LIST"],"Aucune automesure ant�rieure de tension art�rielle");
						else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");				
					}

					$tensionArterielleMoyenneList = $result;
					for($i=0;$i<count($tensionArterielleMoyenneList);$i++){
						$tensionArterielleMoyenneList[$i] = $tensionArterielleMoyenneList[$i]->afterDeserialisation($account);
					}
					forward($this->mappingTable["URL_AFTER_LIST"]);				
					break;

				case ACTION_MAIN:
					$result = $tensionArterielleMapper->getObjectsByCabinet($account->cabinet);

					if($result == false){
						if($tensionArterielleMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_MANAGE"],"Aucun resultat trouv&eacute; !");
						else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");				
					}
					global $rowsList;
					$rowsList = array_natsort($result,"numero", "numero");
		
					

					forward($this->mappingTable["URL_LIST_TENSION_ARTERIELLE"]);
					break;
					
				case ACTION_FIND:
					exitIfNull($dossier);
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);
					$tensionArterielleMoyenne = new TensionArterielleMoyenne();
					$tensionArterielleMoyenne->id = $dossier->id;
					$result = $tensionArterielleMoyenneMapper->findObjects($tensionArterielleMoyenne);
					if($result == false){
						if($tensionArterielleMoyenneMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_MANAGE"],"Pas d'enregistrements trouv�s");
						else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");				
					}

					$tensionArterielleMoyenneList = $result;

					for($i=0;$i<count($tensionArterielleMoyenneList);$i++){
						$tensionArterielleMoyenneList[$i] = $tensionArterielleMoyenneList[$i]->afterDeserialisation($account);
						
					}
					forward($this->mappingTable["URL_AFTER_FIND"]);				
					break;
					
				case ACTION_DELETE:
					exitIfNull($dossier);

					exitIfNull($tensionArterielleManagement);
					exitIfNullOrEmpty($tensionArterielleManagement->group_id);
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

					$tensionArterielleMoyenne = new TensionArterielleMoyenne();
					$tensionArterielleMoyenne->id = $dossier->id;
					$result = $tensionArterielleMoyenneMapper->deleteObject($dossier->id, $tensionArterielleManagement->group_id);
					if($result == false){
						forward(URL_CONTROLER_PERSISTENCE_ERROR,"Erreur lors de la suppression (valeurs moyennes)");				
					}

					$result = $tensionArterielleMapper->deleteObject($dossier->id, $tensionArterielleManagement->group_id);
					if($result == false){
						forward(URL_CONTROLER_PERSISTENCE_ERROR,"Erreur lors de la suppression (valeurs par jour)");				
					}

					$tensionArterielleMoyenne = new TensionArterielleMoyenne();
					$tensionArterielleMoyenne->id = $dossier->id;
					$result = $tensionArterielleMoyenneMapper->findObjects($tensionArterielleMoyenne);
					if($result == false){
						if($tensionArterielleMoyenneMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_AFTER_LIST"],"Aucune automesure ant�rieure de tension art�rielle");
						else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");				
					}

					$tensionArterielleMoyenneList = $result;
					for($i=0;$i<count($tensionArterielleMoyenneList);$i++){
						$tensionArterielleMoyenneList[$i] = $tensionArterielleMoyenneList[$i]->afterDeserialisation($account);
					}
					forward($this->mappingTable["URL_AFTER_LIST"]);				
				break;
				default:
					echo("ACTION IS NULL");
					break;
			}
		}
	}

?>
