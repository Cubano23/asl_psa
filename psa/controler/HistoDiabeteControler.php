<?php 
	
	require_once("bean/HistoDiabete.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/HistoDiabeteMapper.php");
	require_once("GenericControler.php");
	require_once("tools/formulas.php");
	
	class HistoDiabeteControler{
	
		var $mappingTable;
		
		function HistoDiabeteControler() {
			$this->mappingTable = 
			array(
			"URL_AFTER_LIST"=>"view/diabete/suivi/histoexam.php",
			"URL_ON_CALLBACK_FAIL"=>"view/");
		}
	

		function start(){

			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $dossier;
			global $liste_exam;

			if(array_key_exists("outDateReference",$objects))
				$outDateReference = $objects["outDateReference"];

			global $dossier;
			if(array_key_exists("dossier",$objects))
				$dossier = $objects["dossier"];

			if(array_key_exists("HistoDiabete",$objects))
				$HistoDiabete = $objects["HistoDiabete"];

			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","HistoDiabeteControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$HistoDiabeteMapper = new HistoDiabeteMapper($cf->getConnection());
			$dossierMapper = new DossierMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);


			switch($param->action){
				case ACTION_LIST:
					exitIfNull($dossier);
					exitIfNull($HistoDiabete);
					exitIfNullOrEmpty($HistoDiabete->type_exam);
					$type_exam=$HistoDiabete->type_exam;
					
					global $affiche_resultat;
					$affiche_resultat=1;
					
					if(($type_exam=="foeil")||($type_exam=="ecg")||($type_exam=="ExaFil")||
						($type_exam=="ExaPieds")||($type_exam=="dentiste")){
						$affiche_resultat=0;
					}

					global $liste_resultats;
					$liste_resultats=array();
					
					$result = $HistoDiabeteMapper->ListeExams($HistoDiabete->beforeSerialisation($account), $dossier);
					if($result == false){//Aucune ligne trouvée
						if($HistoDiabeteMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"dossier non trouvé");
					}
					
					
					foreach($result as $tab){
						$HistoDiabete = new HistoDiabete($type_exam, $tab["date"], $tab["valeur"]);
						$HistoDiabete = $HistoDiabete->afterDeserialisation($account);
						$liste_resultats[]=$HistoDiabete;
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
