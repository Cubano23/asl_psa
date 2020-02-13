<?php



 
class CertificateRetrievalStatus{
    //Public variables
    public $Requester;
    public $Owner;
    public $Organization;
    public $RetrievalDateTime;
    public $Status;
    public $Index;
    public $Comment;
}
 
class GetCertificateRetrievalStatusRequest {
    // Public var
    public $Application;
    public $Requester;
    public $AuthCookie;
    public $OwnerFilter;
    public $OrganizationUnitFilter;
    public $IndexFilter;
    public $RequesterFilter;
}


  function GetCertsStatusEx($appli, $ownerfilter, $oufilter, $indexfilter, $requesterfilter )
	{

	//on recup�re le cookie de session
	$cookie=$_COOKIE["sessionids"];
 
	$wsdl    = 'http://api.idshost.priv/pki.wsdl';
 
 	$options = array('compression'=>true,'exceptions'=>true,'trace'=>true);

	$answer="OK";	
	$auth = GetUserId( $answer);
	$requester = $auth->Authentifier; 
 
	//On construit la requ�te de demande
	$request = new GetCertificateRetrievalStatusRequest();
 
	//ici on choisira toutes les unit�s, tous les patients et on demande lignes dont le champ extra Contient Ouverture
  if($appli=="")
      $appli="psaet.asalee.fr";
	$request->Application=$appli;
	$request->AuthCookie=$cookie;
	$request->Requester=$requester;
	$request->OwnerFilter=$ownerfilter;
  $request->OrganizationUnitFilter=$oufilter;
	$request->IndexFilter=$indexfilter;
	$request->RequesterFilter=$requesterfilter;



	//on invoque le webservice permettant de r�cup�rer les lignes de log
	$service = new SoapClient($wsdl, $options);
 	try
	{
		//On appelle la m�thode GetLogLines
		$reponse = $service->GetCertificateRetrievalStatus($request);
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
	    $statusLines = $reponse->CertificateRetrievalStatus;
/*	    foreach ($statusLines as $statusLine) {
            echo $statusLine->RetrievalDateTime."-".$statusLine->Requester."-".$statusLine->Owner."-".$statusLine->OrganizationUnit."-".$statusLine->Index."-".$statusLine->Status."-".utf8_decode($statusLine->Comment);
            echo "<br/>";

		
            }*/
		return $statusLines;
	}
	return null;
	}
 

	function GetCertsStatus()
	{


  return GetCertsStatusEx("psaet.asalee.fr", "*", "*", "*", "*" );

	}
 
?>