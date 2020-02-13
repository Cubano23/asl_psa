<?php


include 'conn.php';

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'cabinet';
$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
$cabinet = isset($_POST['cabsearch']) ? mysql_real_escape_string($_POST['cabsearch']) : '';
$hsearch = isset($_POST['hsearch']) ? intval($_POST['hsearch']) : 0;
$where=" where recordstatus=0 ";
if($hsearch==1)
    $where=" where recordstatus=1 ";
if($hsearch==2)
    $where=" where recordstatus>=0 ";

if($cabinet!='')
{
    $cabinet =  utf8_decode ( $cabinet);   //utf8 EA 20-05-2015
//   error_log($cabinet);

    $where2 =     " cabinet like '$cabinet%'  ";
    $where = $where." and".$where2;

}
//error_log($where);
$offset = ($page-1)*$rows;
$result = array();

$rs = mysql_query("select count(*) from $table" .$where);
$row = mysql_fetch_row($rs);
$result["total"] = $row[0];
$sql2 = " order by $sort $order limit $offset,$rows";
$sql = "select * from $table ";

$sql = $sql.$where.$sql2;
$rs = mysql_query($sql);

$items = array();
while($row = mysql_fetch_object($rs))
{
    $rows = (array)$row;
    foreach( $rows as $key => $value)
    {
        if(!is_null($value))
        {

            $value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
            $rows[$key] = $value;
        }
    }

    array_push($items, $rows);
}
$result["rows"] = $items;


require_once ("Config.php");
$config = new Config();

if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
{
    require_once($config->webservice_path . "/AsaleeLog.php");
    LogAccess("psaet.asalee.fr", "cab_getdata", $UserIDLog, 'na', $cabinet,  0, "Liste Cabinets:".$answerLog);
}


echo json_encode($result);

?>
