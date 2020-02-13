<?php
require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/connectannuaire.php");

$con = DoConnect();

$login =    $_REQUEST['login'];
$sql = "delete from `habilitations`where login='$login'";

$result = @mysql_query($sql);
if ($result){
    $sql = "delete from `identifications` where login='$login'";
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
else {
    $answer = "Err Identifications " + mysql_error();
    echo json_encode(array('msg'=>$answer));
}

require_once ("Config.php");
$config = new Config();

if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
{
    require_once($config->webservice_path . "/AsaleeLog.php");
    LogAccess("psaet.asalee.fr", "hab_remove", $UserIDLog, 'na', $login,  3, "Effacer Habilitation: ".$answerLog."/".$result);
}


?>
