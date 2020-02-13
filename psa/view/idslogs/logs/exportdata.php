<?php

require_once "Config.php";
$config = new Config();

require_once($config->webservice_path . "/GetUserId.php");
require_once($config->webservice_path . "/GetLog.php");

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
$reqfilter = isset($_POST['reqfilter']) ? $_POST['reqfilter'] : '*';
$patientfilter = isset($_POST['patientfilter']) ? $_POST['patientfilter'] : '*';
$extrafilter = isset($_POST['extrafilter']) ? $_POST['extrafilter'] : '*';
$mindate = isset($_POST['mindate']) ? $_POST['mindate'] : '2013-09-01';
$maxdate = isset($_POST['maxdate']) ? $_POST['maxdate'] : '2114-09-01';

$reqfilter = ($reqfilter=='')?"*":$reqfilter;
$patientfilter = ($patientfilter=='')?"*":$patientfilter;
if ($extrafilter=='') $extrafilter = "*";
if ($extrafilter!='*')$extrafilter= "%".$extrafilter."%";
if($mindate=='') $mindate= '2013-09-01';
if($maxdate=='') $maxdate= '2114-09-01';


$offset = ($page-1)*$rows;
$statusLines = array();
$result = array();
$items = array();

$reqfilter="03angom";
$statusLines =     GetIdsLogEx("","*", $reqfilter, "*", $patientfilter, $extrafilter, $mindate, $maxdate ); //  GetIdsLog("");
if($statusLines)
{
    $fp = fopen("idsqlogs.csv","w");

    $count = count($statusLines);
    $result["total"] = $count;
    /*    if($count<=$rows)
                 $rows = $count;*/
    $offset = 0;
    $rows = $count;
    for  ($i = 0; $i<$rows; $i++ )
    {
        $logLine = $statusLines [$i + $offset ];
        if($logLine->LogDate!="")
            fprintf($fp,"$logLine->LogDate;$logLine->AccessType;$logLine->Requester;$logLine->PageName;$logLine->Patient;$logLine->Unit;$logLine->Extra\n" );

    }
    fclose($fp);

}


?>
