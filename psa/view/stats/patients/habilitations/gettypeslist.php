<?php

  require_once('../filterdomain.php');
  $admin_level = getPsaetLevel();
  if( ($admin_level!=1) && ($admin_level!=2))
  {
      echo" Option Interdite";
      die;
  }


	$items = array();
	$row =   array();

	$row["type"] = 0;
	$row["type_t"] = utf8_encode("Salarié");
  $row["selected"] = true;
	array_push($items, $row);
  $row["type"] = 1;
	$row["type_t"] = utf8_encode("Libéral");
  array_push($items, $row);
 	$row["type"] = 2;
	$row["type_t"] = "Autre";
  array_push($items, $row);
	echo json_encode($items);

?>
