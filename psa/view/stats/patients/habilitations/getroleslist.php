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

	$row["role"] = 0;
	$row["role_t"] = "Interdit";
  $row["selected"] = true;
	array_push($items, $row);
  if($admin_level==1)
  {
  	 $row["role"] = 1;
	   $row["role_t"] = "SuperAdmin";
	   array_push($items, $row);
  }
  /*            Court Circuit
	$row["role"] = 2;
	$row["role_t"] = "Administrateur";
	array_push($items, $row);
  */
  if($admin_level==1)
  {
    	$row["role"] = 3;
	    $row["role_t"] = "Exploitant";
	    array_push($items, $row);
  }
  
	echo json_encode($items);

?>
