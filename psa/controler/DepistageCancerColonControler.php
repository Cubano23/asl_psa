<?php 
	
	require_once("bean/DepistageCancerColon.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/DepistageCancerColonMapper.php");
	require_once("GenericControler.php");
	
	class DepistageCancerColonControler extends GenericControler{
	
		var $mappingTable;
		
		function DepistageCancerColonControler() {
			$this->mappingTable = 
			array(
			"URL_MANAGE"=>"view/cancercolon/managedepistagecolon.php",
			"URL_NEW"=>"view/cancercolon/newdepistagecolon.php",
//			"URL_AFTER_CREATE"=>new ControlerParams("DepistageCancerColonControler",ACTION_MANAGE,true),
//			"URL_AFTER_UPDATE"=>new ControlerParams("DepistageCancerColonControler",ACTION_MANAGE,true),
			"URL_AFTER_CREATE"=>"view/cancercolon/viewdepistagecolonaftercreate.php",
			"URL_AFTER_UPDATE"=>"view/cancercolon/viewdepistagecolonaftercreate.php",
			"URL_AFTER_FIND_VIEW"=>"view/cancercolon/viewdepistagecolon.php",
			"URL_AFTER_FIND_EDIT"=>"view/cancercolon/newdepistagecolon.php",
			"URL_AFTER_DELETE"=>new ControlerParams("DepistageCancerColonControler",ACTION_MANAGE,true),
			"URL_AFTER_LIST"=>"view/cancercolon/listdepistagecolon.php",
			"URL_AFTER_FIND_LIST_DOSSIER"=>"view/cancercolon/listdepistagecolonbydossier.php",
			"URL_MANAGE_OUTDATED"=>"view/cancercolon/managealertecancercolon.php",
			"URL_AFTER_LIST_OUTDATED"=>"view/cancercolon/listcancercolonalerte.php",
			"URL_ON_CALLBACK_FAIL"=>"view/");
		}
	

		function start() {
			$this->genericControler("DepistageCancerColonControler","depistageCancerColon","DepistageCancerColon","DepistageCancerColonMapper",$this->mappingTable);
		}
	}
?> 
