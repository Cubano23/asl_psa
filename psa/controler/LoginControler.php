<?php
require_once("Config.php");

if($_SERVER['APPLICATION_ENV']=='dev-herve'){

}else {
    $config = new Config();
    require_once($config->webservice_path . '/GetUserId.php') ;
    GetUserSessionVars();
}


require_once("persistence/ConnectionFactory.php");
require_once("persistence/AccountMapper.php");
require_once("bean/ControlerParams.php");
require_once("bean/beanparser/paramparser.php");
require_once("log/LedgerFactory.php");
require_once("bean/Account.php");
require_once("ControlerTools.php");

class LoginControler {

    var $mappingTable;
    var $config;

    function LoginControler(){
        $url_voulu=$_SERVER['REQUEST_URI'];
        #print_r($_SERVER);
        #print_r($_REQUEST);
        global $parametre_redirect;
        $this->config = new Config();

        if(($_SERVER['REQUEST_URI']!= $this->config->psa_path. '/controler/LoginControler.php')||(!empty($_POST["controler_redirect"]))){

            if((isset($param->action) && ($param->action!=ACTION_FIND))||!isset($param->action))
            {
                if(isset($_REQUEST['controlerparams:param:controler']))
                {
                    $controler=$_REQUEST['controlerparams:param:controler'];
                }
                else
                {
                    $controler='';
                }
                if(isset($_REQUEST['controlerparams:param:action']))
                {
                    $action=$_REQUEST['controlerparams:param:action'];
                }
                else
                {
                    $action='';
                }
                if(isset($_REQUEST['controlerparams:param:param1']))
                {
                    $param1=$_REQUEST['controlerparams:param:param1'];
                }
                else
                {
                    $param1=NULL;
                }
                if(isset($_REQUEST['controlerparams:param:param2']))
                {
                    $param2=$_REQUEST['controlerparams:param:param2'];
                }
                else
                {
                    $param2=NULL;
                }
                if(isset($_REQUEST['controlerparams:param:param3']))
                {
                    $param3=$_REQUEST['controlerparams:param:param3'];
                }
                else
                {
                    $param3=NULL;
                }

                $parametre_redirect=new ControlerParams($controler,$action,false,$param1,$param2,$param3);
            }
            if(!empty($_POST['controler_redirect']))
            {
                $controler_redirect=$_POST['controler_redirect'];
                $action_redirect=$_POST['action_redirect'];
                $param1_redirect=$_POST['param1_redirect'];
                $param2_redirect=$_POST['param2_redirect'];
                $param3_redirect=$_POST['param3_redirect'];

                if($param1_redirect=='')
                {
                    $param1_redirect=NULL;
                }
                if($param2_redirect=='')
                {
                    $param2_redirect=NULL;
                }
                if($param3_redirect=='')
                {
                    $param3_redirect=NULL;
                }

                $parametre_redirect=new ControlerParams($controler_redirect,$action_redirect,false,$param1_redirect,$param2_redirect,$param3_redirect);
            }
        }
        else{
            $parametre_redirect="view/main.php";
        }

        $this->mappingTable= array(
            "URL_MANAGE_LOGIN" => "view/login/login.php",
            //		"URL_AFTER_LOGIN_OK" => "view/main.php",
            "URL_AFTER_LOGIN_OK" => $parametre_redirect,
            "URL_AFTER_LOGIN_NOK"=>"view/login/login.php" );
    }


    function start() {
        // variables inherited from ActionControler
        global $account;
        global $objects;
        global $param;
        global $parametre_redirect;

        $objects = parseParameter($_REQUEST);

        if($objects == false){
            $param = new ControlerParams();
            $param->action = ACTION_MANAGE;
        }
        else {
            // Check for the param object
            if(!array_key_exists("param",$objects)) {
                $param = new ControlerParams();
                $param->action = ACTION_MANAGE;
            }
            else $param = $objects["param"];
        }

        switch($param->action){

            default:
            case ACTION_MANAGE:
                $account = new Account();
                unset($_SESSION);
                forward($this->mappingTable["URL_MANAGE_LOGIN"]);
                break;

            case ACTION_FIND:
                session_start();

                if(!array_key_exists("account",$objects)) {
                    unset($_SESSION["account"]);
                    forward($this->mappingTable["URL_AFTER_LOGIN_NOK"],"LoginControler error...account is null");
                }

                $account = $objects["account"];

                //Create connection factory
                $cf = new ConnectionFactory();
                $accountMapper = new AccountMapper($cf->getConnection());
                $result = $accountMapper->findObject($account);

                $c = strtolower($account->cabinet);
                $a = $_SESSION['allowedcabinets'];



                if($_SERVER['APPLICATION_ENV']=='dev-herve'){
                    $result->password = $account->password = '';
                    $a[] = $c;
                }

                if(($result ===false)||((in_array($c, $a )==false) &&  (in_array('*', $a )==false)))
                {
//                    error_log("------------------------ We're on the WRONG path",0);
                    unset($_SESSION["account"]);
                    mysql_select_db($cf->getDBConnexion());
                    //log de connexion
                    if(!empty($_SERVER['HTTP_X_COMING_FROM']))
                        $IP = $_SERVER['HTTP_X_COMING_FROM'];
                    else $IP = $_SERVER['REMOTE_ADDR'];


                    $req="INSERT INTO connexion_portail SET portail='psa', login='".$objects["account"]->cabinet."', date_tentative='".date('YmdHis')."', ".
                        "retour='echec : erreur connexion', "." IP='$IP'";


                    mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
                    forward($this->mappingTable["URL_AFTER_LOGIN_NOK"],"Accès refusé. Verifiez votre nom de cabinet et mot de passe");
                }
                else
                {
//                    error_log("------------------------ We're on the RIGHT path",0);

//                    error_log(print_r($account,true),0);
//                    error_log("-------------- ",0);
//
//                    error_log(print_r($result,true),0);
//                    error_log("-------------- ",0);



                    $account->nom=$result->nom;
                    $account->cabinet=$result->cabinet;
                    $_SESSION["account"] = &$account;
                    $_SESSION["cabinet"]=$account->cabinet;

                    $answer="00";

                    if($_SERVER['APPLICATION_ENV']=='dev-herve'){
                        // do nothing here
                    }else{
                        $auth = GetUserId( $answer);

                    }

                    $UserID = substr($auth->Authentifier,2);
                    $_SESSION['nom']=$UserID;


                    //                    error_log("-------------- ",0);
                    //                    error_log(print_r($answer,true),0);
                    //                    error_log(print_r($auth,true),0);
                    //                    error_log(print_r($UserID,true),0);
                    //                    error_log("-------------- ",0);


                    mysql_select_db($cf->getDBConnexion());

                    //log de connexion
                    if(!empty($_SERVER['HTTP_X_COMING_FROM']))
                        $IP = $_SERVER['HTTP_X_COMING_FROM'];
                    else $IP = $_SERVER['REMOTE_ADDR'];

                    $req="INSERT INTO connexion_portail SET portail='psa', login='".$objects["account"]->cabinet."', date_tentative='".date('YmdHis')."', ".
                        "retour='connexion OK', IP='$IP'";
                    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");


                    //                    error_log("--------------------------------------- - ",0);
                    //                    error_log(print_r($this->mappingTable["URL_AFTER_LOGIN_OK"],true),0);
                    //                    error_log("--------------------------------------- - ",0);
                    //                    die;
                    forward($this->mappingTable["URL_AFTER_LOGIN_OK"]);
                }

                break;

        }
    }

}

$loginControler = new LoginControler();
$loginControler->start();
?>
