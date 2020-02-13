<?php
/*
* Description : exemple de connexion au web service SMS
*	EA 01/06/2016
*/

 //error_reporting(E_ERROR);//EA 05-01-2015

 class SendSmsIdsIn {

    public $Application;
    public $MobileNumber;
	  public $Requester; 
	  public $AuthCookie;
    public $From;
    public $Body;

}

class SendSmsIdsOut {
    public $SmsId;
}

function SendSms($application, $mobilenumber,  $requester,  $from, $body, &$message , & $smsid)
{
      $wsdl = 'http://api.idshost.priv/sendsms.wsdl';

      $options = array(
        'compression' => true,
        'exceptions' => false,
        'trace' => true
      );

 
 //on recupère le cookie de session
//      if($cookie==null)
      $cookie=$_COOKIE["sessionids"];
 
      $request = new SendSmsIdsIn();
      $request->Application = $application;
      $request->MobileNumber = str_replace(' ','', $mobilenumber);
      $request->From = $from;// <= 11 caractères 
      $request->Body = $body;// <= 160 caractères
      $request->Requester = $requester;// <= 11 caractères 
      $request->AuthCookie = $cookie;// <= 160 caractères
      
      

      $service = new SoapClient($wsdl, $options);

      $reponse = $service->SmsSend($request);
// On traite la réponse
      if (is_soap_fault($reponse)) {
              $message =  $service->__getLastResponse();
              error_log("Envoi SMS" + $message);
              return false;
              }
      else 
      {
    // SMS Id
          $message = "OK";
          $smsid = $reponse;
//          error_log ("smdid ".   print_r( $reponse, true ) ); 
          $service = null;
          return true;
       }

}

 class SmsStatusIn {

    public $Application;
	  public $Requester; 
	  public $AuthCookie;
    public $SmsId;

}
 
 class SmsStatusOut {
        public	$SmsStatus;
}

function GetSmsStatus($application,  $smsid,  &$message , & $status )
{
      $wsdl = 'http://api.idshost.priv/sendsms.wsdl';

      $options = array(
        'compression' => true,
        'exceptions' => false,
        'trace' => true
      );

 
 //on recupère le cookie de session
//      if($cookie==null)
      $cookie=$_COOKIE["sessionids"];
 
      $request = new SmsStatusIn();
      $request->Application = $application;
      $request->Requester = $requester; 
      $request->AuthCookie = $cookie;
      $request->SmsId = $smsid;
      

      $service = new SoapClient($wsdl, $options);

      $reponse = $service->SmsStatus($request);
// On traite la réponse
      if (is_soap_fault($reponse)) {
              $message =  $service->__getLastResponse();
              error_log("SMS Status" + $message);
              return false;
              }
      else 
      {
    // SMS Id
          $message = "OK";
          $status = $reponse;
          return true;
       }

}



?>
