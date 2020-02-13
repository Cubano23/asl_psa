
<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/EvaluationMedecin.php");
	
	class EvaluationMedecinMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "";
		}
	
		function getKeysMap(){
			return array("name"=>"name","date"=>"date");
		}
		
		function getTableName(){
			return "evaluation_medecin";
		}
	
		function getLedgerName(){
			return "EvaluationMedecinMapper";
		}
	
		function getObject(){
			return new EvaluationMedecin();
		}
	}
?>
