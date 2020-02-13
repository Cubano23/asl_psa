<?php 
	require_once("bean/SatisfactionPatient.php");
	require_once("SelfManagedMapper.php");

	class SatisfactionPatientMapper extends SelfManagedMapper{
	
		function getLedgerName(){
			return "SatisfactionPatientMapper";
		}

		function getTableName(){
			return "satisf_patient2009";
		}

		function getKeysMap(){
			return array("no_questionnaire"=>"no_questionnaire");
		}

		function getForeignKey(){
			return "no_questionnaire";
		}

		function getObject(){
			return new SatisfactionPatient();
		}
		
		function getFindQuery($SatisfactionPatient){
			return "select * from satisf_patient ".
				"where no_questionnaire='$SatisfactionPatient->no_questionnaire'";
		}
	
	}

?>
