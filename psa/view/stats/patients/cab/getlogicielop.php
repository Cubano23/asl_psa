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

	$row["applevel"] = 0;
	$row["applevel_t"] = "Non";
  $row["selected"] = true;
	array_push($items, $row);
  $row["applevel"] = 1;
  $row["applevel_t"] = "Oui";
  array_push($items, $row);
  
	echo json_encode($items);

?>
