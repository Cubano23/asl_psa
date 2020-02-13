<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/SuiviDiabete4.php");
	
	class SuiviDiabete4Mapper extends SelfManagedMapper{
		
		function getKeysMap(){
			return array("suivi_diabete_id"=>"suivi_diabete_id");
		}
			
		function getTableName(){
			return "suivi_diabete_4";
		}
	
		function getLedgerName(){
			return "SuiviDiabete4Mapper";
		}
	
		function getObject(){
			return new SuiviDiabete4();
		}
			
	}
?>