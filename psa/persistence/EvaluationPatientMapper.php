
<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/EvaluationPatient.php");
	
	class EvaluationPatientMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
		
		function getKeysMap(){
			return array("id"=>"id","date"=>"date");
		}
		
		function getTableName(){
			return "evaluation_patient";
		}
	
		function getLedgerName(){
			return "EvaluationPatientMapper";
		}
	
		function getObject(){
			return new EvaluationPatient();
		}
	}
?>
