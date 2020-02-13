<?php

    require_once("bean/Dossier.php");    	
	require_once("persistence/DossierMapper.php");
	require_once("persistence/CardioVasculaireDepartMapper.php");
	require_once("persistence/DepistageCancerColonMapper.php");
	require_once("persistence/DepistageDiabeteMapper.php");
	require_once("persistence/DepistageCancerSeinMapper.php");
	require_once("persistence/DepistageCancerUterusMapper.php");
	require_once("persistence/EvaluationInfirmierMapper.php");
	require_once("persistence/HemocultMapper.php");
	require_once("persistence/HyperTensionArterielleMapper.php");
	require_once("persistence/SuiviDiabeteMapper.php");
	require_once("persistence/SevrageTabacMapper.php");
	require_once("persistence/TensionArterielleMoyenneMapper.php");
	require_once("persistence/TroubleCognitifMapper.php");
	require_once("persistence/ConnectionFactory.php");
	require_once("tools/arrays.php");	
	require_once("controler/UtilityControler.php");
	require_once("bean/DepistageAOMI.php");
	require_once("bean/TransfertDossier.php");
	
	class TransfertDossierControler {
	
		var $mappingTable;
		
		function getForward($param,$url){
			if($param == PARAM_STAND_ALONE)							
				return $this->mappingTable[$url."_STA"];
			else 
				return $this->mappingTable[$url];
		}
		
		function dForward($param,$url,$message=NULL,$resetRequest=false){						
				forward($this->getForward($param,$url),$message,$resetRequest);			
		}
		
		function TransfertDossierControler(){
			$this->mappingTable = array(
			"URL_MANAGE"=>"view/dossier/transfert_dossier/viewtransfertdossier.php",
            "URL_MANAGE_LIST_EXAM"=>"view/dossier/managelistexamdossier.php",
            "URL_MANAGE_STA"=>"managedossiersta",

            "URL_NEW"=>"view/dossier/newdossier.php",
            "URL_NEW_STA"=>"newdossiersta",

			"URL_AFTER_CREATE"=>"view/dossier/transfert_dossier/transfertsuccess.php",
            //"URL_AFTER_CREATE"=>"view/dossier/viewdossieraftercreate.php",
			"URL_AFTER_CREATE_STA"=>new ControlerParams("TransfertDossierControler",ACTION_MANAGE,false,NULL,NULL,PARAM_STAND_ALONE),

			"URL_AFTER_DELETE"=>new ControlerParams("TransfertDossierControler",ACTION_MANAGE,true),
            "URL_AFTER_DELETE_STA"=>new ControlerParams("TransfertDossierControler",ACTION_MANAGE,false,NULL,NULL,PARAM_STAND_ALONE),

            "URL_CONFIRM_UPDATE"=>"view/dossier/confirmupdate.php",
            "URL_CONFIRM_UPDATE_STA"=>"confirmdossiersta",

//			"URL_AFTER_UPDATE"=>new ControlerParams("TransfertDossierControler",ACTION_MANAGE,true),
            "URL_AFTER_UPDATE"=>"view/dossier/viewdossierafterupdate.php",
            "URL_AFTER_UPDATE_STA"=>new ControlerParams("TransfertDossierControler",ACTION_MANAGE,false,NULL,NULL,PARAM_STAND_ALONE),

            "URL_AFTER_FIND_VIEW"=>"view/dossier/newdossier.php",
            "URL_AFTER_FIND_VIEW_STA"=>"newdossiersta",

            "URL_AFTER_FIND_EDIT"=>"view/dossier/updatedossier.php",
            "URL_AFTER_FIND_EDIT_STA"=>"newdossiersta",

            "URL_AFTER_LIST"=>"view/dossier/listdossiers.php",
            "URL_AFTER_LIST_STA"=>"afterlistdossiersta",

            "URL_AFTER_VIEW_LIST_EXAM"=>"view/dossier/viewlistexamdossier.php");
			
			
		}
		
		function start() {
			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;			
			global $dossier;
            global $dossiers;



			if(array_key_exists("dossier",$objects)){
				$dossier = $objects["dossier"];
			}
			// create ledger for this controler
			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","TransfertDossierControler");
				
			//Create connection factory
			$cf = new ConnectionFactory();
			$dossierMapper = new DossierMapper($cf->getConnection());
            $ledger->writeArray(I,"Start","Control Parameters = ",$param);
            
			switch($param->action){
				case ACTION_UPDATE:

					$transfertD = new TransfertDossier();                 
                   
					$errors = $transfertD->processTransfert();
					if(count($errors) == 0){
						$this->dForward($param->param3,"URL_MANAGE",'Dossier transféré');
					}else{
						// error here
						$this->dForward($param->param3,"URL_MANAGE",$errors[0]);
					}
                    break;

				case ACTION_MANAGE:							
                    forward($this->mappingTable["URL_MANAGE"]);
					break;

				default:
					echo("ACTION IS NULL");
					break;
			}
		}
	}

?>

