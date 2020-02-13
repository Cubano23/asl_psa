<?php 
	//echo("<!-- ASALEE VERSION 2.0 -->");
	// This could be considered as a filter. At the end a forward is made to another controler.
	// This controler check if user is logged in and if expected attributes are found in the session.
	// It also parse the request into objects.
	
	// The following variables will be exposed to the next controler:
	// 1 - $account: the account object found in the session.
	// 2 - $objects: parsed objects from the request
	// 3 - $param: ControlerParams object. (contains the next controler name and the next action to take).
  
  
  error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE &  ~E_WARNING); //EA 28-04-2014
  
	require_once("persistence/ConnectionFactory.php");
	require_once("bean/ControlerParams.php");
	require_once("bean/beanparser/paramparser.php");
	require_once("log/LedgerFactory.php");
	require_once("bean/Account.php");
	require_once("ControlerTools.php");
	
	define("URL_ERROR_PAGE","view/error.php");
	define("ACTION_CONTROLER","ActionControler.php");
	define("URL_CONTROLER_PARAMS_ERROR","view/error.php");
	define("URL_UNDEFINED_CONTROLER","view/error.php");
	define("URL_PARSING_ERROR","view/error.php");
	define("URL_EXPECTED_OBJECT_NOT_FOUND","view/error.php");
	define("URL_CONTROLER_PERSISTENCE_ERROR","view/error.php");
	define("URL_ERROR_FINDING_DOSSIER","view/error.php");
	define("URL_ERROR_PROPERTY_ERROR","view/error.php");
	define("URL_CONTROLER_OBJECT_EXIST_ERROR","view/error.php");
	define("URL_NO_OBJECTS_FOUND","view/error.php");

	global $account;
	global $objects;
	global $param;
		
	// global variables
	$account = "";
	$objects = "";
	$param  = "";	
 
	function checkDossier($dossier,$dossierMapper,$cabinet,$exit=true,$forwardTo=URL_ERROR_FINDING_DOSSIER){
		global $account;
		if(!is_null($dossier->id) and $dossier->id != ""){
			$aDossier = $dossierMapper->findDossierById($dossier->beforeSerialisation($account));
			if(is_array($aDossier)){
				if(count($aDossier) != 1) { 
					$aDossier = false;
					$dossierMapper->lastError = "Le nombre de resultat n'est pas 1 :".count($aDossier);
				}								
				else
					$aDossier = $aDossier[0];
			}			
		}
		else{		
			$dossier->cabinet = $cabinet;
			$aDossier = $dossierMapper->findObject($dossier->beforeSerialisation($account));						
		}
		if($aDossier == false) {
			if(!$exit) return $dossierMapper->lastError;
			forward($forwardTo,"Le numéro de dossier n'est pas trouvé");
		}		
		
		if($aDossier->cabinet != $cabinet){
			if(!$exit) return "Ce dossier n'appartient pas au cabinet $cabinet";
			forward($forwardTo,"erreur: Ce dossier n'appartient pas au cabinet $cabinet");
		}
		
		return $aDossier->afterDeserialisation($account);
	}

	function checkDossierActif($dossier,$dossierMapper,$cabinet,$exit=true,$forwardTo=URL_ERROR_FINDING_DOSSIER){
		global $account;
		
		if(!is_null($dossier->id) and $dossier->id != ""){
			$aDossier = $dossierMapper->findDossierById($dossier->beforeSerialisation($account));
			if(is_array($aDossier)){
				if(count($aDossier) != 1) {
					$aDossier = false;
					$dossierMapper->lastError = "Le nombre de resultat n'est pas 1 :".count($aDossier);
				}
				else
					$aDossier = $aDossier[0];
			}
		}
		else{
			$dossier->cabinet = $cabinet;
			$aDossier = $dossierMapper->findObject($dossier->beforeSerialisation($account));
		}
		if($aDossier == false) {
			if(!$exit) return $dossierMapper->lastError;
			forward($forwardTo,"Le numéro de dossier n'est pas trouvé");
		}
		
		if($aDossier->actif=='non')
		    forward($forwardTo, "Le dossier est inactif");

		if($aDossier->cabinet != $cabinet){
			if(!$exit) return "Ce dossier n'appartient pas au cabinet $cabinet";
			forward($forwardTo,"erreur: Ce dossier n'appartient pas au cabinet $cabinet");
		}

		return $aDossier->afterDeserialisation($account);
	}


	function executeCallBack($callbackObject,$dossier,$object,$forwardTo){
		if($callbackObject == "" ) return true;
		$result = $callbackObject->callBack($dossier,$object); 		
		if(!is_null($result)) forward($forwardTo,$result);
		return $result;
	}
	
	function exitIfNull($object){
		#echo '<p>PSA -> ';var_dump($object);
		if(is_null($object)) forward(URL_EXPECTED_OBJECT_NOT_FOUND,"Objet recherché non trouvé");
	}
	
	function exitIfNullOrEmpty($var){
		if(is_null($var)) forward(URL_ERROR_PROPERTY_ERROR,"Property is null");
		if($var == "") forward(URL_ERROR_PROPERTY_ERROR,"Property is empty");		
	}
	
	function actionControler(){

		global $account;
		global $objects;
		global $param;
		
		// create leder for this controler
		$ledgerFactory = new LedgerFactory();
		$ledger = $ledgerFactory->getLedger("Controler","ActionControler");
		
		$ledger->writeArray(I,"ActionControler Preparations","Request Params =",$_REQUEST);
		// resume the session
		session_start();
		$ledger->writeArrayOfObjects(I,"ActionControler Preparations","Session Params =",$_SESSION);
		
		// Retrieve account from session and check if it is valid
		$s_account = &$_SESSION["account"];
		$account = $s_account;
		
		if(is_null($account)) {
			$ledger->write(E,"ActionControler Preparations","User not logged in - no account object in session");
			forward(new ControlerParams("LoginControler",ACTION_MANAGE),"Vous n'êtes pas identifiés");
		}
		if(get_class($account)!="Account") {
			$ledger->write(E,"ActionControler Preparations","User not logged in - account object in session is not of type Account");
			forward(new ControlerParams("LoginControler",ACTION_MANAGE),"Vous n'êtes pas identifiés");
		}
		if(!isset($account->cabinet) or strlen($account->cabinet)==0){
			$ledger->write(E,"ActionControler Preparations","User not logged in - cabinet is not set");
			forward(new ControlerParams("LoginControler",ACTION_MANAGE),"Vous n'êtes pas identifiés");
		}
		
		$ledger->write(I,"ActionControler Preparations","Account = ".$account->toString());
		
		// Parse parameters
		$objects = parseParameter($_REQUEST);
	
		if($objects == false){
			$ledger->write(E,"ActionControler Preparations","Error parsing parameters");
			forward(URL_PARSING_ERROR,"Parsing error");
		}
		$ledger->writeArrayOfObjects(I,"ActionControler Preparations","Parsed Objects",$objects);
		
		// Check for the param object	
		if(!array_key_exists("param",$objects)) {
			$ledger->write(E,"ActionControler Preparations","No Controler Parameters Found");
			forward(URL_CONTROLER_PARAMS_ERROR,"ActionControler error");
		}
		
		$param = $objects["param"];
		
		// Check for the next controler
		if(is_null($param->controler)){
			$ledger->write(E,"ActionControler Preparations","No Controler Specified");
			forward(URL_UNDEFINED_CONTROLER,"ActionControler error");
		}
		
		// forward the request to the next controler.
		$ledger->write(I,"ActionControler Preparations","Forwarding to ".$param->controler.".php");
		require_once($param->controler.".php");
		$forwardControler = new $param->controler();
		$forwardControler->start();
	}
		#var_dump($_REQUEST);
	// execute the controler		
	actionControler();
?>
