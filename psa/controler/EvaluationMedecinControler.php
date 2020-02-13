<?php 
	
	require_once("bean/EvaluationMedecin.php");
	require_once("persistence/EvaluationMedecinMapper.php");
	require_once("bean/ControlerParams.php");
	require_once("DateGenericControler.php");
	
	class EvaluationMedecinControler extends DateGenericControler{
	
		var $mappingTable;
		
		function EvaluationMedecinControler() {
			$this->mappingTable = 
			array(
			"URL_MANAGE"=>"manageevaluationmedecin",
			"URL_NEW"=>"view/evaluation/newevaluationmedecin.php",
			"URL_AFTER_CREATE"=>new ControlerParams("EvaluationMedecinControler",ACTION_MANAGE,true),
			"URL_AFTER_UPDATE"=>new ControlerParams("EvaluationMedecinControler",ACTION_MANAGE,true),
			"URL_AFTER_FIND_VIEW"=>"view/evaluation/viewevaluationmedecin.php",
			"URL_AFTER_FIND_EDIT"=>"view/evaluation/newevaluationmedecin.php",
			"URL_AFTER_DELETE"=>new ControlerParams("EvaluationMedecinControler",ACTION_MANAGE,true),
			"URL_AFTER_LIST"=>"listevaluationmedecin",
			"URL_ON_CALLBACK_FAIL"=>"view/");
		}
	

		function start() {
			$this->DateGenericControler("EvaluationMedecinControler","evaluationMedecin","EvaluationMedecin","EvaluationMedecinMapper",$this->mappingTable);
		}
	}
?> 