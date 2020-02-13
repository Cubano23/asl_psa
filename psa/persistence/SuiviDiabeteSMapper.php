<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/SuiviDiabeteS.php");
	
	class SuiviDiabeteSMapper extends SelfManagedMapper{
		
		function getKeysMap(){
			return array("suivi_diabete_id"=>"suivi_diabete_id");
		}
			
		function getTableName(){
			return "suivi_diabete_s";
		}
	
		function getLedgerName(){
			return "SuiviDiabeteSMapper";
		}
	
		function getObject(){
			return new SuiviDiabeteS();
		}
			
	}
?>