<?php 
	
	
	require_once("bean/DepistageCancerUterus.php");
	require_once("persistence/DepistageCancerUterusMapper.php");
	require_once("GenericControler.php");
	require_once("bean/ControlerParams.php");
	
	class DepistageCancerUterusControler extends GenericControler{
	
		var $mappingTable;
	
		
		function DepistageCancerUterusControler(){
			$this->mappingTable = 
			array(
			"URL_MANAGE"=>"view/canceruterus/managedepistageuterus.php",
			"URL_NEW"=>"view/canceruterus/newdepistageuterus.php",
//			"URL_AFTER_CREATE"=>new ControlerParams("DepistageCancerSeinControler",ACTION_MANAGE,true),
//			"URL_AFTER_UPDATE"=>new ControlerParams("DepistageCancerSeinControler",ACTION_MANAGE,true),
			"URL_AFTER_CREATE"=>"view/canceruterus/viewdepistageuterusaftercreate.php",
			"URL_AFTER_UPDATE"=>"view/canceruterus/viewdepistageuterusaftercreate.php",
			"URL_AFTER_FIND_VIEW"=>"view/canceruterus/viewdepistageuterus.php",
			"URL_AFTER_FIND_EDIT"=>"view/canceruterus/newdepistageuterus.php",
			"URL_AFTER_DELETE"=>new ControlerParams("DepistageCancerUterusControler",ACTION_MANAGE,true),
			"URL_AFTER_LIST"=>"view/canceruterus/listdepistageuterus.php",
			"URL_AFTER_FIND_LIST_DOSSIER"=>"view/canceruterus/listdepistageuterusbydossier.php",
			"URL_MANAGE_OUTDATED"=>"view/canceruterus/managealertecanceruterus.php",
			"URL_AFTER_LIST_OUTDATED"=>"view/canceruterus/listcanceruterusalerte.php",
			"URL_ON_CALLBACK_FAIL"=>"view/error.php");
			
			
		}
		
		function getSignature(){
			return "Dépistage du cancer de l'uterus";
		}
		
		function callBack($dossier,$depistageCancerUterus){
			$sexe = $dossier->sexe;
			if( strtoupper($sexe) == "F") return;
			return "Ce patient est un homme";
		}
		
		function start() {
			$this->genericControler("DepistageCancerUterusControler","depistageCancerUterus","DepistageCancerUterus","DepistageCancerUterusMapper",$this->mappingTable,$this);
		}
	}
?> 
