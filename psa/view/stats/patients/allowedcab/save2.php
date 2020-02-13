<?php

require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/connectannuaire.php") ;

$con = DoConnect(true);

$table = "allowedcabinets";
$login = $_REQUEST['login'];
$cabinet = isset($_REQUEST['cabinet']) ? strval($_REQUEST['cabinet']) : '';
if( ($cabinet!='') && ($login!=''))
{
    $sql = " INSERT IGNORE INTO $table(`login`, `cabinet`) VALUES ('$login', '$cabinet') ".
        " ON DUPLICATE KEY UPDATE recordstatus=0 ";
    ;

    $result = @mysql_query($sql, $con);



    if ($result)
    {

        $sql = "INSERT INTO `historique_allowedcabinets`( `login`, `cabinet`, `actualstatus`) VALUES ( '$login','$cabinet', 0)";
        @mysql_query($sql, $con);
        echo json_encode(array('success'=>true));
    }
    else
    {
        $answer = "Err Allowed Cab " + mysql_error();
        echo json_encode(array('msg'=>$answer));
    }
    require_once ("Config.php");
    $config = new Config();

    if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
    {
        require_once($config->webservice_path . "/AsaleeLog.php");
        LogAccess("psaet.asalee.fr", "allowedcab_save", $UserIDLog, 'na', $login,  1, "Nouvelle Infirmi�re/Cabinet: ".$answerLog."/".$result);
    }

}


?>
