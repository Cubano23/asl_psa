<?php 
	
	require_once("bean/EvaluationPatient.php");
	require_once("persistence/EvaluationPatientMapper.php");
	require_once("bean/ControlerParams.php");
	require_once("GenericControler.php");
	
	class EvaluationPatientControler extends GenericControler{
	
		var $mappingTable;
		
		function EvaluationPatientControler() {
			$this->mappingTable = 
			array(
			"URL_MANAGE"=>"manageevaluationpatient",
			"URL_NEW"=>"view/evaluation/newevaluationpatient.php",
			"URL_AFTER_CREATE"=>new ControlerParams("EvaluationPatientControler",ACTION_MANAGE,true),
			"URL_AFTER_UPDATE"=>new ControlerParams("EvaluationPatientControler",ACTION_MANAGE,true),
			"URL_AFTER_FIND_VIEW"=>"view/evaluation/viewevaluationpatient.php",
			"URL_AFTER_FIND_EDIT"=>"view/evaluation/newevaluationpatient.php",
			"URL_AFTER_DELETE"=>new ControlerParams("EvaluationPatientControler",ACTION_MANAGE,true),
			"URL_AFTER_LIST"=>"listevaluationpatient",
			"URL_ON_CALLBACK_FAIL"=>"view/");
		}
	

		function start() {
			$this->genericControler("EvaluationPatientControler","evaluationPatient","EvaluationPatient","EvaluationPatientMapper",$this->mappingTable);
		}
	}
?> 