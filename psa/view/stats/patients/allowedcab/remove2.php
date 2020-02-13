<?php
//EA 25-03-2016 recordstatus à 1 et historisation

require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/connectannuaire.php") ;

$con = DoConnect(true);

$id =    $_REQUEST['id'];
/*	$id =    $_POST['login'];
 	$cabinet = isset($_POST['cabinet']) ? strval($_POST['cabinet']) : '';*/


$table = "allowedcabinets";
//  $sql = "delete from `allowedcabinets`where id='$id' ";
$sql = "update $table set  recordstatus=1 where id= $id";
$result = @mysql_query($sql, $con);
if($result)
{
    $sql = "select login, cabinet from  $table where id= $id";

    $rs = @mysql_query($sql, $con);
    if($rs)
    {
        $row=mysql_fetch_array($rs);
        $login=$row[0];
        $cabinet = $row[1];
        $sql = "INSERT INTO `historique_allowedcabinets`( `login`, `cabinet`,  `actualstatus`) VALUES ( '$login', '$cabinet', 1)";
        @mysql_query($sql, $con);

    }
}
require_once ("Config.php");
$config = new Config();

if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
{
    require_once($config->webservice_path . "/AsaleeLog.php");
    LogAccess("psaet.asalee.fr", "inf_remove", $UserIDLog, 'na', $login,  3, "Enlever Infirmiere: ".$answerLog."/".$result);
}

if ($result){
    echo json_encode(array('success'=>true));
}
else
{
    $answer = "Err Allowed cabs " + mysql_error();
    echo json_encode(array('msg'=>$answer));
}

?>
