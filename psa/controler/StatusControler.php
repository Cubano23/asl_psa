<?php

require_once("bean/DemandeFraisSuivi.php");

$id = $_GET['id'];
$id_status = $_GET['id_status'];
error_log("Je suis dans le constructeur");
runUpStatus($id_status, $id);
               
function runUpStatus($id_status, $id)
{
    $fraisSuivi = new DemandeFraisSuivi();
    $fraisSuivi->getById($id);
    
    $fraisSuivi->id_status = $id_status;
    $fraisSuivi->save();
}

?>