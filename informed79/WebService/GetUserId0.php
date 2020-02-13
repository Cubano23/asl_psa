<?php
/*
* Description : exemple de connexion au web service d'authentification
* Auteur : b.aynes@ids-assistance.com
*	30/03/2011
*/
 error_reporting(E_ERROR);//EA 05-01-2015
class authenticationToken {
    // Public var
    public $Authentifier;
    public $Privilege;
    public $AuthMethod;
    public $AuthProvider;
}

  //$base=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
  require_once("/home/informed/www/informed79/inclus/connectannuaire.php");  
//require_once("connectannuaire.php");

 function GetUserId(& $answer)
 {
    $wsdl	= 'http://api.idshost.priv/authentication.wsdl';
    $options	= array(
        'compression'=>true,
        'exceptions'=>false,
        'trace'=>true
  );

  //on récupère le cookie de session
  $cookie=$_COOKIE["sessionids"];

   //on invoque le web service permettant de retrouver les paramètres de l'utilisateur actuellement connecté
  $service = new SoapClient($wsdl, $options);
 
  //On crée le Token
  $authenticationToken= new authenticationToken();
  //On appelle la méthode AuthGetUserId
  $authenticationToken = $service->AuthGetUserId($cookie);
 
  // On traite la réponse
  if (is_soap_fault($authenticationToken)) 
  {                                                             
      $answer =     utf8_decode($service->__getLastResponse());
      return null;
  } 
  else 
  {
    //On récupère l’authentifiant de l’utilisateur connecté
    $answer = "OK";
       
    //Ajouter le tratement de CPS
    $idtype= substr($authenticationToken->Authentifier,0,2);
//error_log("getuserid:".$authenticationToken."\n", 3, "/home/asalee/my-errors.log");    
    if($idtype=="00")
    {
        $idcps = substr($authenticationToken->Authentifier,2); 
        $con = DoConnect();
       	$query= "select login from habilitations where idcps='$idcps'";
	      $result=mysql_query($query, $con);

     
	     $row = mysql_fetch_row( $result );
       
       $authenticationToken->Authentifier = "00". $row[0];
//error_log("getuserid/cps:".$authenticationToken."\n", 3, "/home/asalee/my-errors.log");         
    }
        
       
    
    return $authenticationToken;
  }  
  }
  function GetUserSessionVars()
  {
  
    session_start();	
   	$answer="00";
	  $auth = GetUserId( $answer);
    $userID = substr($auth->Authentifier,2);
    $con = DoConnect(true);
    $query= "select * from identifications where login='$userID'";
	  $rs=mysql_query($query, $con);

    
  	while($row = mysql_fetch_object($rs))
	 {
		$rows = (array)$row;
		foreach( $rows as $key => $value)
		{
            
			if(!is_null($value))
			{
		
				$value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
        $_SESSION['id.'.$key]=$value;
  //      error_log($_SESSION['id.'.$key]);
			}
		}
	}  
   $_SESSION['allowedcabinets']=array();
   $query= "select * from allowedcabinets where login='$userID'";
	 $rs=mysql_query($query, $con);

    
   while($row = mysql_fetch_object($rs))
	 {
		$rows = (array)$row;
		foreach( $rows as $key => $value)
		{
            
			if(!is_null($value))
			{
		
        if($key=='cabinet')
        {
    				$value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
             array_push( $_SESSION['allowedcabinets'],strtolower($value));
        }             
  //      error_log($_SESSION['id.'.$key]);
			}
		}
	}  

     DoClose($con);
       foreach($_SESSION['allowedcabinets'] as $v)
       {
       
                     //   error_log($v);
       
       }                                                                 
  
  }
?>
