<?php

// EA 25-06-2016  recorstatus
include 'conn.php';

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
$cabinet = isset($_POST['cabinet']) ? mysql_real_escape_string($_POST['cabinet']) : '';
$medsearch = isset($_POST['medsearch']) ? intval($_POST['medsearch']) : 0;
$where=" where recordstatus=0 ";
if($medsearch==1)
    $where=" where recordstatus=1 ";
if($medsearch==2)
    $where=" where recordstatus>=0 ";

if($cabinet!='')
{
    $cabinet =  utf8_decode ( $cabinet); //utf8 EA 20-05-2015
    $where2 =     " cabinet='$cabinet' ";
    $where = $where." and".$where2;

    $offset = ($page-1)*$rows;
    $result = array();

    $sql = "select count(*) from $table " .$where;
    $rs = mysql_query($sql);

    $row = mysql_fetch_row($rs);
    $result["total"] = $row[0];
    $sql2 = " order by $sort $order limit $offset,$rows";
    $sql = "select * from $table ";

    $sql = $sql.$where.$sql2;

    $rs = mysql_query($sql);
    $items = array();
    while($row = mysql_fetch_object($rs)){
        $rows = (array)$row;
        $medid=0;
        $recordstatus=0;
        foreach( $rows as $key => $value)
        {
            if(!is_null($value))
            {
                if($key=='id')
                    $medid = $value;
                if($key=='recordstatus')
                    $recordstatus=$value;

                $value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
                $rows[$key] = $value;
            }
        }

        {
            $sql2 ="select dstatus,medid from historique_medecin where medid=$medid and actualstatus=0 order by dstatus desc limit 1";
            $rs2=mysql_query($sql2);
            $row2=mysql_fetch_array($rs2);
            $value=$row2[0];
            $rows['ddebut']= substr($value,0,10);

        }
        if($recordstatus==1)
        {
            $sql2 ="select dstatus,medid from historique_medecin where medid=$medid and actualstatus=1 order by dstatus desc limit 1";
            $rs2=mysql_query($sql2);
            $row2=mysql_fetch_array($rs2);
            $value=$row2[0];
            $rows['dfin']= substr($value,0,10);

        }


        array_push($items, $rows);
    }
    $result["rows"] = $items;

    require_once ("Config.php");
    $config = new Config();

    if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
    {
        require_once($config->webservice_path . "/AsaleeLog.php");
//LogAccess("psaet.asalee.fr", "mg_getdata", $UserIDLog, 'na', $cabinet,  0, "Liste Medecins Traitants:".$answerLog);
    }



    echo json_encode($result);
}
?>
