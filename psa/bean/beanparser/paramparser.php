<?php 
	require_once("bean/AutreConsultCardio.php");
	require_once("bean/Biologie.php");
	require_once("bean/CardioVasculaireDepart.php");
	require_once("bean/Conges.php");
	require_once("bean/Dossier.php");
	require_once("bean/DepistageDiabete.php");
	require_once("bean/DepistageCancerColon.php");
	require_once("bean/DepistageCancerSein.php");
	require_once("bean/DepistageCancerUterus.php");
	require_once("bean/diageduc.php");
	require_once("bean/Epices.php");
	require_once("bean/EvalContinue.php");
	require_once("bean/EvaluationMedecin.php");
	require_once("bean/EvaluationInfirmier.php");
	require_once("bean/EvaluationPatient.php");
//	require_once("bean/FraisKm.php");
	require_once("bean/FicheCabinet.php");
	require_once("bean/FondOeil.php");
	require_once("bean/Frais.php");
	require_once("bean/GraphBean.php");
	require_once("bean/Hemocult.php");
	require_once("bean/HistoDiabete.php");
	require_once("bean/HistoRCVA.php");
	require_once("bean/HyperTensionArterielle.php");
//	require_once("bean/ListeHemocult.php");
	require_once("bean/ListeDonnees.php");
	require_once("bean/OutdateReference.php");
	require_once("bean/PoserQuestion.php");
	require_once("bean/PremiereConsultCardio.php");
	require_once("bean/QuestionnaireMedecin.php");
	require_once("bean/SatisfactionPatient.php");
	require_once("bean/SuiviDiabete.php");
	require_once("bean/SuiviDiabeteA.php");
	require_once("bean/SuiviDiabeteS.php");
	require_once("bean/SuiviDiabete4.php");
	require_once("bean/SuiviHebdomadaire.php");
	require_once("bean/SuiviHebdomadaireTempsPasse.php");
	require_once("bean/SuiviReunionMedecin.php");
	require_once("bean/SuiviHebdomadaire2007.php");
	require_once("bean/TensionArterielle.php");
	require_once("bean/TensionArterielleManagement.php");
	require_once("bean/TroubleCognitif.php");
	require_once("bean/SevrageTabac.php");
	require_once("log/LedgerFactory.php");
	require_once("bean/Fragilite.php");
	//require_once("bean/Cabinet.php");
	//require_once("bean/Utilisateur.php");
	require_once("bean/DepistageAOMI.php");
	require_once("bean/EntretienAnnuel.php");
	require_once("bean/ActivitePhysique.php");

	// Parse an HTTP request. Create an array of objects according to the request parmeters names.
	// Return false of $parametersMap is not set or if no objects found
	function parseParameter($parametersMap){				
		$sep = ":";
		$ledger = getLedger("HTML HELPER","PARAMETER PARSER");

		if(!isset($parametersMap)) return false;
		// this array will hold the collected objects
		$objectMap = array();

		foreach ($parametersMap as $name => $value){
			$ledger->write(I,"parseParameter","parsing $name value $value");
			// if the the attrubute name does not contain two separators meaning it's not a recognised attribute
			if(substr_count($name,$sep)!=2) {
				$ledger->write(E,"parseParameter","Can't parse $name value $value");
				continue;
			}
			// Extract class name,object name and property name from attribute
			list($className,$objectName,$property) = explode($sep,$name);

			// Test if extracted data are valid
			if(!isset($className) and !isset($objectName) and !isset($property)){
				$ledger->write(E,"parseParameter","Invalid property");
				continue;
			}
			if(!class_exists($className)){
				$ledger->write(E,"parseParameter","Class $className do not exist");
				continue;
			}
			
			$ledger->write(I,"parseParameter","Parsed Params: class name = $className,object name = $objectName, property = $property");
			
			// create the object if it's not created yet			
			if(!isset($objectMap[$objectName])){				
				$ledger->write(I,"parseParameter","Creating object of type $className");
				$objectMap[$objectName] = new $className();				
			}
			// obtain a reference to the object
			$currentObject = &$objectMap[$objectName];
			// get the object attributes references
			$properties = get_object_vars ( $currentObject );
			
			if(is_null($properties)) continue;			
			// Fill the object attribute
			$currentObject->$property = $value;								
		}
		
		if(count($objectMap) == 0) {
			$ledger->write(E,"parseParameter","Objects map is empty");
			return false;				
		}
		
		return $objectMap;				
	}
?>

