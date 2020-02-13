<?php

include 'conn.php';

$cabinet = $_SESSION['cabinet'];
$numero = $_REQUEST['numero'];

$sql = "UPDATE `dossier` SET `encnir`='' ".
    "where numero='$numero' and cabinet = '$cabinet'";


$result = @mysql_query($sql);

require_once ("Config.php");
$config = new Config();
require_once($config->webservice_path ."/AsaleeLog.php");
LogAccess("", "nir_remove", $UserIDLog, 'na', $numero,  3, "Cabinet:".$cabinet." /".$result);



if ($result){
    echo json_encode(array('success'=>true));
}
else {
    echo json_encode(array('msg'=>'Erreur'));
}
?>
