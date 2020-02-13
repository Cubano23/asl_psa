<?php
function toiso($src)
{
    $utf8=html_entity_decode($src);
    $iso8859=utf8_decode($utf8);
    return $iso8859;
}

require_once ("Config.php");
$config = new Config();

include 'conn.php';

$cabinet = toiso ($_REQUEST['cabinet']);


//	$sql = "delete from $table where cabinet='$cabinet'";
//	error_log($sql);
//	$result = @mysql_query($sql);

$sql = "update $table set  recordstatus=1 where cabinet= '$cabinet' ";
//	error_log($sql);

$result = @mysql_query($sql);

if($result)
{
    $sql = "INSERT INTO `historique_account`( `cabinet`,  `actualstatus`) VALUES ( '$cabinet', 1)";
    @mysql_query($sql);
}

require($config->erp_path . "/WebService/erp.php");

if ($result){
    $rows = array();
    $req = "select id from account where cabinet='$cabinet'";
    $rs = mysql_query($req);
    $row = mysql_fetch_row($rs);
    $rows["id"] = $row[0];
    $rows["cabinet"]=$cabinet;
    $result = Erp_Cabinet_Delete($rows);
}


if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
{
    require_once($config->webservice_path . "/AsaleeLog.php");
    LogAccess("psaet.asalee.fr", "cab_remove", $UserIDLog, 'na', $cabinet,  3, "Effacer Cabinet: ".$answerLog."/".$result);
}


if ($result){
    echo json_encode(array('success'=>true));
}
else {
    echo json_encode(array('msg'=>'Erreur'));
}
?>
