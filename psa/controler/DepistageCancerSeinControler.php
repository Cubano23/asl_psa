<?php 
	
	
	require_once("bean/DepistageCancerSein.php");
	require_once("persistence/DepistageCancerSeinMapper.php");
	require_once("GenericControler.php");
	require_once("bean/ControlerParams.php");
	
	class DepistageCancerSeinControler extends GenericControler{
	
		var $mappingTable;
	
		
		function DepistageCancerSeinControler(){
			$this->mappingTable = 
			array(
			"URL_MANAGE"=>"view/cancersein/managedepistagesein.php",
			"URL_NEW"=>"view/cancersein/newdepistagesein.php",
//			"URL_AFTER_CREATE"=>new ControlerParams("DepistageCancerSeinControler",ACTION_MANAGE,true),
//			"URL_AFTER_UPDATE"=>new ControlerParams("DepistageCancerSeinControler",ACTION_MANAGE,true),
			"URL_AFTER_CREATE"=>"view/cancersein/viewdepistageseinaftercreate.php",
			"URL_AFTER_UPDATE"=>"view/cancersein/viewdepistageseinaftercreate.php",
			"URL_AFTER_FIND_VIEW"=>"view/cancersein/viewdepistagesein.php",
			"URL_AFTER_FIND_EDIT"=>"view/cancersein/newdepistagesein.php",
			"URL_AFTER_DELETE"=>new ControlerParams("DepistageCancerSeinControler",ACTION_MANAGE,true),
			"URL_AFTER_LIST"=>"view/cancersein/listdepistagesein.php",
			"URL_AFTER_FIND_LIST_DOSSIER"=>"view/cancersein/listdepistageseinbydossier.php",
			"URL_MANAGE_OUTDATED"=>"view/cancersein/managealertecancersein.php",
			"URL_AFTER_LIST_OUTDATED"=>"view/cancersein/listcancerseinalerte.php",
			"URL_ON_CALLBACK_FAIL"=>"view/error.php");
			
			
		}
		
		function getSignature(){
			return "Dépistage du cancer du sein";
		}
		
		function callBack($dossier,$depistageCancerSein){
			$sexe = $dossier->sexe;
			if( strtoupper($sexe) == "F") return;
			return "Ce patient est un homme";
		}
		
		function start() {
			$this->genericControler("DepistageCancerSeinControler","depistageCancerSein","DepistageCancerSein","DepistageCancerSeinMapper",$this->mappingTable,$this);
		}
	}
?> 
