<?php



 
class logLine{
    //Public variables
    public $LogDate;
    public $Application;
    public $PageName;
    public $Requester;
    public $AuthCookie;
    public $Unit;
    public $Patient;
    public $AccessType;
    public $Extra;
}
 
class getLogLinesRequest {
    // Public var
    public $Application;
    public $Requester;
    public $AuthCookie;
    public $ReqFilter;
    public $OrganizationUnitFilter;
    public $UnitFilter;
    public $PatientFilter;
    public $MinTimeFilter;
    public $MaxTimeFilter;
    public $ExtraFilter;
}



	function GetIdsLogEx($appli,$oufilter, $reqfilter, $unitfilter, $patientfilter, $extrafilter, $mindate, $maxdate )
	{

	//on recup�re le cookie de session
	$cookie=$_COOKIE["sessionids"];
 
	$wsdl    = 'http://api.idshost.priv/log.wsdl';
 
	//on map notre objet log line avec le complex type $logline du wsdl
	$classmap = array('logLine' => 'logLine');
 
	$options = array('compression'=>true,'exceptions'=>true,'trace'=>true/*,'classmap' => $classmap*/);
 
	//On suppose que l'on a d�j� r�cup�r� l'authentifiant a l'aide du service d'authentification (m�thode AuthGetUserId($cookie)
	$requester = $_SESSION['authentifier'];
 
	//On construit la requ�te de demande
	$request = new getLogLinesRequest();
 
	//ici on choisira toutes les unit�s, tous les patients et on demande lignes dont le champ extra Contient Ouverture
  if($appli=="")
            $request->Application=$_SERVER['SERVER_NAME'];  //EA 17-12-2014
        else
            $request->Application=$appli; 
	$request->AuthCookie=$cookie;

	$answer="OK";	
	$auth = GetUserId( $answer);
	$requester = $auth->Authentifier;

	$request->Requester=$requester;
	$request->OrganizationUnitFilter=$oufilter;
	$request->ReqFilter=$reqfilter;

	$request->UnitFilter=$unitfilter;
	$request->PatientFilter=$patientfilter;
	$request->ExtraFilter=$extrafilter;
	$request->MinTimeFilter=$mindate;
	$request->MaxTimeFilter=$maxdate;

	

	//on invoque le webservice permettant de r�cup�rer les lignes de log
	$service = new SoapClient($wsdl, $options);
 	try
	{
		//On appelle la m�thode GetLogLines
		$reponse = $service->GetLogLines($request);
	}
	catch (Exception $e) {
	    echo 'Exception re�ue : '. $e->getMessage();
	 }

	// On traite la r�ponse
	if (is_soap_fault($reponse)) {

    		echo utf8_decode($service->__getLastResponse());
	}
	else {
    	//Particularit� de soap call qui retourne un tableau associatif en cas de r�ponse multiple
	    //On r�cup�re donc la partie qui nous int�resse.
	    $logLines = $reponse->logLine;

		return $logLines;
	}
	return null;
	}



	function GetIdsLog($appli)
	{
  /*
	//on recup�re le cookie de session
	$cookie=$_COOKIE["sessionids"];
 
	$wsdl    = 'http://api.idshost.priv/log.wsdl';
 
	//on map notre objet log line avec le complex type $logline du wsdl
	$classmap = array('logLine' => 'logLine');
 
	$options = array('compression'=>true,'exceptions'=>true,'trace'=>true);
 
	//On suppose que l'on a d�j� r�cup�r� l'authentifiant a l'aide du service d'authentification (m�thode AuthGetUserId($cookie)
	$requester = $_SESSION['authentifier'];
 
	//On construit la requ�te de demande
	$request = new getLogLinesRequest();
 
	//ici on choisira toutes les unit�s, tous les patients et on demande lignes dont le champ extra Contient Ouverture
	$request->Application=$appli;
	$request->AuthCookie=$cookie;

	$answer="OK";	
	$auth = GetUserId( $answer);
	$requester = $auth->Authentifier;

	$request->Requester=$requester;
	$request->OrganizationUnitFilter="*";
	$request->ReqFilter="*";

	$request->UnitFilter="*";
	$request->PatientFilter="*";
	$request->ExtraFilter="*";
	$request->MinTimeFilter="2010-09-01";
	$request->MaxTimeFilter="2114-10-30";

	

	//on invoque le webservice permettant de r�cup�rer les lignes de log
	$service = new SoapClient($wsdl, $options);
 	try
	{
		//On appelle la m�thode GetLogLines
		$reponse = $service->GetLogLines($request);
	}
	catch (Exception $e) {
	    echo 'Exception re�ue : '. $e->getMessage();
	 }

	// On traite la r�ponse
	if (is_soap_fault($reponse)) {

    		echo utf8_decode($service->__getLastResponse());
	}
	else {
    	//Particularit� de soap call qui retourne un tableau associatif en cas de r�ponse multiple
	    //On r�cup�re donc la partie qui nous int�resse.
	    $logLines = $reponse->logLine;


		return $logLines;
	}
	return null;
*/
     return GetIdsLogEx($appli,"*", "*", "*", "*", "*", "2013-09-01", "2114-10-30" );
	}
 
?>