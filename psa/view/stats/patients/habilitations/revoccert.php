<?php


function data_uri($file, $mime)
{
    $contents = file_get_contents($file);
    $base64   = base64_encode($contents);
    return ('data:' . $mime . ';base64,' . $base64);
}

/*

*/

require_once ("Config.php");
$config = new Config();


require($config->webservice_path . "/LogAccess.php");
require($config->webservice_path . "/GetUserId.php");
require($config->webservice_path . "/CertRevocate.php");

$con = DoConnect();
$login = $_REQUEST['login'];
$ndx =intval($_REQUEST['revocindex']);
$comment = utf8_encode ($_REQUEST['revoccomment']);
$testmode = $_REQUEST['revoccerttest'];


error_log("test revocation $comment" );

$phase = 0;
$answer = "OK";
$result = true;

while ( ($phase <10) && ($result==true))
{

    switch ($phase)
    {
        case 0:

            $req="SELECT  nom, prenom ,email , telephone ".
                " from identifications where login='$login'";

            $result=mysql_query($req);
            if(!result)
            {
                $answer = mysql_error();

            }
            break;

        case 1:
            list( $nom, $prenom, $email, $telephone)=mysql_fetch_row($result);
            // Générer les demandes

            $auth = GetUserId( $answer);

            $UserID = $auth->Authentifier;
            $app = "psaet";
            $demande="";
            $answer="OK";
            if(!isset($testmode))
                CertRevocate("psaet.asalee.fr", $UserID,  $app, $login, $ndx, $comment,   $answer );
            //               $answer!="OK" ;
            if($answer!="OK")
                $result = false;
            else
                $result = true;

            break;

        case 2:

            break;
        case 3:
            break;
        case 4:
/*            if(!isset($testmode))
            {
                $req="INSERT INTO `certificats`(`owner`, `ownermail`, `organisation`, `token`, `lot`) VALUES ('".$login."','".$email."','".$app."','".$otp."',$ndx)";
                $res=mysql_query($req);
                if(!res)
                    $answer = $answer."\nerreur SQL:".mysql_error()."\n";

            }*/
            break;



    }
    $phase++;
}




// Log WebService


LogAccess("psaet.asalee.fr", "revocert", $UserID, 'na', $infirmiere, 1, "Revocation de certificat utilisateur ". $login.":".$answer);

//   echo json_encode(array('success'=>true));

if($result==true)
{
    echo json_encode(array('success'=>true,
        'msg'=>$answer,
        'otp'=>$otp,
        'index'=>$ndx
    ));
//      error_log($answer);
}
else
{
    echo json_encode(array(
        'msg'=>$answer));
//         error_log("NOK");
}
?>
