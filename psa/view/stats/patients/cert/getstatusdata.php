<?php
require_once ("Config.php");
$config = new Config();

require_once($config->webservice_path . "/GetUserId.php");
require_once($config->webservice_path . "/GetCertStatus.php");

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'dret';
$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
$reqfilter = isset($_POST['reqfilter']) ? $_POST['reqfilter'] : '*';
$ownerfilter = isset($_POST['ownerfilter']) ? $_POST['ownerfilter'] : '*';


$reqfilter = ($reqfilter=='')?"*":$reqfilter;
$ownerfilter = ($ownerfilter=='')?"*":$ownerfilter;


$offset = ($page-1)*$rows;

$result = array();
$items = array();
$statusLines =  GetCertsStatusEx("", $ownerfilter, "*", "*", $reqfilter );
if($order=='desc')
    $statusLines = array_reverse($statusLines);

if($statusLines)
{
    $count = count($statusLines);
    $result["total"] = $count;
    /*    if($count<=$rows)
                 $rows = $count;*/
    for  ($i = 0; $i<$rows; $i++ )
    {
        $statusLine = $statusLines [$i + $offset ];
        $row = array();
        $row["dret"] = $statusLine->RetrievalDateTime;
        $row["certowner"] =$statusLine->Owner;
        $row["certorganisation"] =$statusLine->OrganizationUnit;
        $row["certindex"] = $statusLine->Index;
        $row["certstatus"] = $statusLine->Status;
        $row["certcomment"] = utf8_decode($statusLine->Comment);
        $row["certrequester"] = $statusLine->Requester;
        if($statusLine->Owner!="")
            array_push($items, $row);

    }

    $result["rows"] = $items;
}
echo json_encode($result);

?>
