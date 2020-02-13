<?php
/*
* Description : exemple d'insertion d'une ligne de journal
* Auteur : b.aynes@ids-assistance.com
* 29/06/2010
*/

class logLine{
    //Public variables
    public $LogDate;
    public $Application;
    public $PageName;
    public $Requester;
    public $OrganizationUnit;
    public $AuthCookie;
    public $Unit;
    public $Patient;
    public $AccessType;
    public $Extra;
}

 
/*

Accesstype

Valeur	Op�ration	Origine
0	Consultation	Application
1	Cr�ation	Application
2	Modification	Application
3	Suppression	Application
4	Connexion d'un utilisateur	Authentification
5	D�connexion d'un utilisateur	Authentification
6	Demande de certificat	PKI
7	Demande de r�vocation	PKI
8	Retrait d'un certificat	PKI
9	R�vocation effective	PKI
*/


  function LogAccess($application, $pageName, $requester, $unit, $patient, $accessType, $extra)
	{
	$fname="/home/asalee/trace-errors.log"; 

	//on r�cup�re le cookie de session
	$cookie=$_COOKIE["sessionids"];
 
 
	$wsdl    = 'http://api.idshost.priv/log.wsdl';
	$options = array('compression'=>true,'exceptions'=>false,'trace'=>true);
 
 
        //On suppose que l'on a d�j� r�cup�r� l'authentifiant a l'aide du service d'authentification
        // (m�thode AuthGetUserId($cookie)ici en session
	//        $requester = $_SESSION['authentifier'];
        //On construit la ligne de log
        $logline = new logLine();
        
        if($application=="")
            $logline->Application=$_SERVER['SERVER_NAME'];  //EA 17-12-2014
        else
                $logline->Application=$application; //www.asalee.fr/psa
        $logline->PageName=$pageName; //"index.php";
        $logline->Requester=$requester;
	 $logline->OrganizationUnit = isset($_SESSION['ou'])?$_SESSION['ou']:"";
        $logline->AuthCookie=$cookie;
        $logline->Unit=$unit; //"unite1";
        $logline->Patient=utf8_encode ($patient); //"M. Martin";
        $logline->AccessType = $accessType;
        $logline->Extra = utf8_encode ($extra); //"Cr�ation d'un Cr de visite";
 
                //on invoque le webservice permettant d'ins�rer une ligne de log
                $service = new SoapClient($wsdl, $options);
 
                //On appelle la m�thode LogAccess
                $reponse = $service->LogAccess($logline);
                // On traite la r�ponse
                if (is_soap_fault($reponse)) {

				error_log("Error Tracing:".$service->__getLastResponse()."\n", 3,$fname );
				error_log("Requester:".$requester."\n", 3,$fname );
				error_log("Application:".$application."\n", 3,$fname );
				error_log("Page:".$pageName."\n", 3,$fname );
				error_log("Cookie:".$cookie."\n", 3,$fname );
				error_log("Unit:".$unit."\n", 3,$fname );
				error_log("Patient:".$patient."\n", 3,$fname );


				return false;
                } else
		  {
                     if ($reponse=="true")
               	{
			//	error_log("Good Tracing\n", 3, $fname);
				return true;		
                	}                
			else
               	{
				//error_log("Error Tracing. No idea\n", 3, $fname);
				return false;		
                	}                

                }
	}
?>