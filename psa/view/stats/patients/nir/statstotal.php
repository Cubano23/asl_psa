<?php
  session_start();
  include 'conn.php';
  $table='dossier'; 

//  $where = "";// where cabinet!='ztest' and cabinet!='touaregs'  ";
  
	$result = array();
  $items = array();
  $rows= array();
  
	$rs = mysql_query("select count(*) from  $table " );
	$row = mysql_fetch_row($rs);
	$rows["dossiers"] = $row[0];
  
  $where = " where encnir!='' ";
	$rs = mysql_query("select count(*) from  $table " .$where);
	$row = mysql_fetch_row($rs);
	$rows["encnirs"] = $row[0];

  $where = " where encnir!='' and dconsentement>'0000-00-00' ";
	$rs = mysql_query("select count(*) from  $table " .$where);
	$row = mysql_fetch_row($rs);
	$rows["dconsent"] = $row[0];



  
  array_push($items, $rows);
  $result["total"] = 1;
  $result["rows"] = $items;
	echo json_encode($result);

?>
