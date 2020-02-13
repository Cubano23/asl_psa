<?php 
	
	
	require_once("bean/Hemocult.php");
	require_once("persistence/HemocultMapper.php");
	require_once("GenericControler.php");
	require_once("bean/ControlerParams.php");
	
	class HemocultControler extends GenericControler{
	
		var $mappingTable;
	
		
		function hemocultControler(){
			$this->mappingTable = 
			array(
			"URL_MANAGE"=>"view/hemocult/managehemocult.php",
			"URL_NEW"=>"view/hemocult/newhemocult.php",
//			"URL_AFTER_CREATE"=>new ControlerParams("DepistageCancerSeinControler",ACTION_MANAGE,true),
//			"URL_AFTER_UPDATE"=>new ControlerParams("DepistageCancerSeinControler",ACTION_MANAGE,true),
			"URL_AFTER_CREATE"=>"view/hemocult/viewhemocultaftercreate.php",
			"URL_AFTER_UPDATE"=>"view/hemocult/viewhemocultaftercreate.php",
			"URL_AFTER_FIND_VIEW"=>"view/hemocult/viewhemocult.php",
			"URL_AFTER_FIND_EDIT"=>"view/hemocult/newhemocult.php",
			"URL_AFTER_DELETE"=>new ControlerParams("HemocultControler",ACTION_MANAGE,true),
			"URL_AFTER_LIST"=>"view/hemocult/listhemocult.php",
			"URL_AFTER_FIND_LIST_DOSSIER"=>"view/hemocult/listhemocultbydossier.php",
			"URL_MANAGE_OUTDATED"=>"view/hemocult/managealertehemocult.php",
			"URL_AFTER_LIST_OUTDATED"=>"view/hemocult/listhemocultalerte.php",
			"URL_ON_CALLBACK_FAIL"=>"view/error.php");
			
			
		}
		
		function start() {
			$this->genericControler("HemocultControler","Hemocult","Hemocult","HemocultMapper",$this->mappingTable);
		}
	}
?> 
