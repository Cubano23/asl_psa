<?php
//EA 25-03-2016 recordstatus à 1 et historisation

include 'conn.php';
include '../cab/update_pat.php';
$id =    $_REQUEST['id'];
$cabinet =    $_REQUEST['cabinet'];

//	$sql = "delete from $table where id='$id'";
$sql = "update $table set  recordstatus=1 where id= $id";
$result = @mysql_query($sql);
if($result)
{

    if(!empty($cabinet))
        update_patients($cabinet, -1);

    $sql = "INSERT INTO `historique_medecin`( `medid`,  `actualstatus`) VALUES ( $id, 1)";
    @mysql_query($sql);
}

require_once ("Config.php");
$config = new Config();

if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
{
    require_once($config->webservice_path . "/AsaleeLog.php");
//  LogAccess("psaet.asalee.fr", "mg_remove", $UserIDLog, 'na', $id,  3, "Effacer Medecin Traitant: ".$answerLog."/".$result);
}



if ($result){
    echo json_encode(array('success'=>true));
}
else {
    echo json_encode(array('msg'=>'Erreur'));
}
?>
