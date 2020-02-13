<?php

require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/connectannuaire.php");

$con = DoConnect();
$table = "certificats";

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'dmaj';
$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
$login = isset($_POST['login']) ? $_POST['login'] : '';

$where=" ";
if ($login!='')
    $where = " where owner='$login' ";

$offset = ($page-1)*$rows;
$result = array();

$rs = mysql_query("select count(*) from $table" .$where, $con);
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

echo json_encode($result);

?>
