<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/SuiviDiabeteA.php");
	
	class SuiviDiabeteAMapper extends SelfManagedMapper{
		
		function getKeysMap(){
			return array("suivi_diabete_id"=>"suivi_diabete_id");
		}
			
		function getTableName(){
			return "suivi_diabete_a";
		}
	
		function getLedgerName(){
			return "SuiviDiabeteAMapper";
		}
	
		function getObject(){
			return new SuiviDiabeteA();
		}
			

	}
?>