<?php

require_once("/home/informed/asl_psa/informed79/inclus/connectannuaire.php");

  
  $con = DoConnect(true);

  
  $table = "allowedcabinets";

	$login = isset($_REQUEST['login']) ? strval($_REQUEST['login']) : '';
  $result = array();
  $json="";
  if($login!='')
  {
	$where=" where allowedcabinets.login ='$login' AND allowedcabinets.recordstatus='0'";
	$result = array();

	$sql = "select cabinet from $table ".$where ;
	
	$rs = mysql_query($sql, $con);
	//error_log($sql);
	$items = array();
  if($rs!=null)
  {
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
  if(count($items)>0)
        $json =    array("status"=>0, "msg"=>"Ok", "items"=>$items);
  else
        $json =    array("status"=>1, "msg"=>"Pas de cabinets");
  
  }
   else
        $json =    array("status"=>2, "msg"=>mysql_error($con));

  }
  else
        $json =    array("status"=>3, "msg"=>"Login Vide");
   header("Content Type: application/json");
   echo json_encode($json);
?>