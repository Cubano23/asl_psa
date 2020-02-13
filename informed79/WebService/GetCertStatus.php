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

	//on recupère le cookie de session
	$cookie=$_COOKIE["sessionids"];
 
	$wsdl    = 'http://api.idshost.priv/pki.wsdl';
 
 	$options = array('compression'=>true,'exceptions'=>true,'trace'=>true);

	$answer="OK";	
	$auth = GetUserId( $answer);
	$requester = $auth->Authentifier; 
 
	//On construit la requête de demande
	$request = new GetCertificateRetrievalStatusRequest();
 
	//ici on choisira toutes les unités, tous les patients et on demande lignes dont le champ extra Contient Ouverture
  if($appli=="")
      $appli="psaet.asalee.fr";
	$request->Application=$appli;
	$request->AuthCookie=$cookie;
	$request->Requester=$requester;
	$request->OwnerFilter=$ownerfilter;
  $request->OrganizationUnitFilter=$oufilter;
	$request->IndexFilter=$indexfilter;
	$request->RequesterFilter=$requesterfilter;



	//on invoque le webservice permettant de récupérer les lignes de log
	$service = new SoapClient($wsdl, $options);
 	try
	{
		//On appelle la méthode GetLogLines
		$reponse = $service->GetCertificateRetrievalStatus($request);
	}
	catch (Exception $e) {
	    echo 'Exception reçue : '. $e->getMessage();
	 }
	// On traite la réponse
	if (is_soap_fault($reponse)) {
    		echo utf8_decode($service->__getLastResponse());
	}
	else {
    	//Particularité de soap call qui retourne un tableau associatif en cas de réponse multiple
	    //On récupère donc la partie qui nous intéresse.
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