<?php


// EA 23-03-2018 ajout erp
require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/connectannuaire.php");

$con = DoConnect();

$table = "identifications";
$login = $_REQUEST['login'];
$prenom = $_REQUEST['prenom'];
$nom =    $_REQUEST['nom'];

$telephone=   $_REQUEST['telephone'];
$email=   $_REQUEST['email'];
$profession=   $_REQUEST['profession'];
$type=   intval($_REQUEST['type']);
$pass=   $_REQUEST['pass'];
$psa=   intval($_REQUEST['psa']);
$psae=   intval($_REQUEST['psae']);
$psv=   intval($_REQUEST['psv']);
$psaet=   intval($_REQUEST['psaet']);
$psvae=   intval($_REQUEST['psvae']);
$idcps=   $_REQUEST['idcps'];
$adeli=   $_REQUEST['adeli'];
$psar=   intval($_REQUEST['psar']);
$erp=   intval($_REQUEST['erp']);
  $psamed=   intval($_REQUEST['psamed']);

$sql = "UPDATE `identifications` SET `nom`='$nom',`prenom`='$prenom',`telephone`='$telephone',`email`='$email',`profession`='$profession', `adeli`='$adeli', `type`=$type WHERE  login='$login'";
$result = @mysql_query($sql);



require_once ("Config.php");
$config = new Config();

if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
{
    require_once($config->webservice_path . "/AsaleeLog.php");
    LogAccess("psaet.asalee.fr", "hab_update", $UserIDLog, 'na', $login,  2, "Modifier Habilitation: ".$answerLog."/".$result);
}


if ($result){


      $sql =" UPDATE `habilitations` SET `pass`='$pass',`psa`=$psa,`psae`=$psae,`psaet`=$psaet,`psv`=$psv,`psvae`=$psvae,`psar`=$psar,`erp`=$erp,`psamed`=$psamed,`idcps`='$idcps' WHERE  login='$login'";
    $result = @mysql_query($sql);
    if ($result){
        echo json_encode(array('success'=>true));
    }
    else
    {
        $answer = "Err Habilitations " + mysql_error();
        echo json_encode(array('msg'=>$answer));
    }
}
else
{
    $answer = "Err Identifications " + mysql_error();
    echo json_encode(array('msg'=>$answer));
}




?>
