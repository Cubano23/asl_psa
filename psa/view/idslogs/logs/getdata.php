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
$statusLines =     GetIdsLogEx("","*", $reqfilter, "*", $patientfilter, $extrafilter, $mindate, $maxdate ); //  GetIdsLog("");
if($statusLines)
{


    $count = count($statusLines);
    $result["total"] = $count;
    /*    if($count<=$rows)
                 $rows = $count;*/
    for  ($i = 0; $i<$rows; $i++ )
    {
        $logLine = $statusLines [$i + $offset ];
        $row = array();
        $row["dret"] = $logLine->LogDate;
        $row["accesstype"] =$logLine->AccessType;
        $row["requester"] =$logLine->Requester;
        $row["pagename"] = $logLine->PageName;
        $row["patient"] = $logLine->Patient;
        $row["unit"] = $logLine->Unit;
        $row["extra"] = $logLine->Extra;
        if($logLine->LogDate!="")
            array_push($items, $row);
    }

    $result["rows"] = $items;
}
echo json_encode($result);

?>
