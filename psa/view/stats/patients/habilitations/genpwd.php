<?php

require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/hashedpwd.php");
require_once($config->webservice_path . "/LogAccess.php");
require_once($config->webservice_path . "/GetUserId.php");
require_once($config->webservice_path . "/SendSms.php");


$con = DoConnect();

$login = $_REQUEST['login'];
$testmode = $_REQUEST['pwdtest'];
//  $sendconfirm = $_REQUEST['certsendemail'];
$telephone = $_REQUEST['telephone'];
$sendsms = $_REQUEST['pwdsendsms'];

$phase = 0;
$answer = "OK";
$result = true;
$consonnes = array("b", "c", "d", "f", "g","h","j","k","l","m","n","p","q","r","s","t","v","w","x","z" );
$voyelles = array("a","e","i","o","u","y");
$p="";
$hp="";
$dc="0000-00-00";
$hp2="";
$dc2="0000-00-00";
$salt="";
$count=0;
$hashed="";
$exists=0;
$fp = fopen("passwords.csv","a+");
while ( ($phase <10) && ($result==true))
{

    switch ($phase)
    {
        case 0:

            $p =    strtoupper($consonnes[rand(0,19)]).$voyelles[rand(0,5)].$consonnes[rand(0,19)].$consonnes[rand(0,19)].$voyelles[rand(0,5)].$consonnes[rand(0,19)].rand(0,9).rand(0,9);


            break;

        case 1:
            $req="SELECT  hpassword, dcreat,hpassword2,dcreat2,salt,count".
                " from `hpasswords` where login='$login'";
            $result=mysql_query($req);
            if(!$result)
            {
                $result=false;
                $answer = mysql_error();
            }
            break;

        case 2:
            if(mysql_num_rows($result) == 0)
            {
                $exists = 0;
                $hashed = generateHashedPassword( $p, $salt, $count );
            }
            else
            {
                list($hp, $dc, $hp2, $dc2, $salt, $count ) = mysql_fetch_row($result);
                $hashed =  getHashedPassword( $p, $salt, $count );
                $exists = 1;
            }
            if(isset($testmode))
                $phase = 100;
            break;

        case 3:
            if($exists==0)
            {
                $req =   "INSERT INTO `hpasswords`(`login`, `hpassword`, `dcreat` ,`salt`, `count`) VALUES ('$login','$hashed',NOW(), '$salt',$count)";
            }
            else
            {

                $req =   "UPDATE `hpasswords` SET `hpassword`='$hashed',`dcreat`=NOW(),`hpassword2`='$hp',`dcreat2`='$dc',`hpassword3`='$hp2',`dcreat3`='$dc2',`salt`='$salt',`count`=$count WHERE `login`='$login'";
            }
            $result=mysql_query($req);
            if(!$result)
            {
                $result=false;
                $answer = mysql_error();
            }
            break;


        case 4:

            $auth = GetUserId( $answer);

            $UserID = $auth->Authentifier;
            $app = "psaet";
            $demande="";
            $answer="OK";
            break;


        case 5:
            fputs($fp, $login."@asalee.fr;".$p."\n");
            break;

        case 6:
            if(isset($sendsms))
            {
                $smsmes="";
                $smsid = "";
                SendSms("psaet.asalee.fr", $telephone,  $UserID ,  "Asalee",

                    "Equipe Asalee \n\n".
                    "Mot de Passe:\n $p",


                    $smsmes , $smsid);

                $answer = $answer . "\n". "Envoi Sms: $smsmes , id: $smsid";


            }
            break;

    }

    $phase++;
}

fclose($fp);

// Log WebService

if(!isset($testmode))
    LogAccess("psaet.asalee.fr", "genpwd", $UserID, 'na', $login, 1, "Mot de Passe certificat ". $login.":".$answer);

//   echo json_encode(array('success'=>true));

if($result==true)
{
    echo json_encode(array('success'=>true,
        'msg'=>$answer,
        'pwd'=>$p,
        'hashed'=>$hashed,
        'salt'=>$salt,
        'count'=>$count
    ));
}
else
    echo json_encode(array(
        'msg'=>$answer));

?>
