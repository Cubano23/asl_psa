<?php
 
class certRequest{
    public $Application;
    public $Requester;
    public $AuthCookie;
    public $OrganizationUnit;
    public $Owner;
    public $Identifier;
    public $Privilege;
    public $Profile;
    public $Duration;
    public $AuthenticationMask;
    public $Number;
    public $Comment;
}
class certResponse{
    public $Index;
    public $OTP;
}


function CertRequest($application, $requester,  $ou, $owner, $mel, $duree , & $answer, & $ndx, & $otp )
{
 
	//on récupère le cookie de session
	$cookie=$_COOKIE["sessionids"];


	$wsdl    = 'http://api.idshost.priv/pki.wsdl';
	$options = array('compression'=>true,'exceptions'=>false,'trace'=>true);
 
	//On prépare la requete
	$CertRequest = new certRequest();
 
	$CertRequest->Application = $application;
	$CertRequest->Requester = $requester;
	$CertRequest->AuthCookie = $cookie;
	$CertRequest->OrganizationUnit=$ou;
	$CertRequest->Owner="03".$owner;
	$CertRequest->Identifier=$mel;
	$CertRequest->Privilege=254;
	$CertRequest->Profile=0;
	$CertRequest->Duration=$duree * 365;
	$CertRequest->AuthenticationMask=63;
	$CertRequest->Number=100;
	$CertRequest->Comment=$mel;
 
	//on soumet la demande
	$service = new SoapClient($wsdl, $options);
 
	//On appelle la méthode 
	$response = $service->CertRequest($CertRequest);
 
	// On traite la réponse
	if (is_soap_fault($response)) {
	    $answer =  $service->__getLastResponse();
	   return false;
	}
	else 
	{
  	    $answer = "OK";
	    $ndx = $response->Index;
	    $otp = $response->OTP;
	    return true;
	}
}
?>
