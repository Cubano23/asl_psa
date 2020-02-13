<?php


require_once("/home/informed/asl_psa/informed79/inclus/connectannuaire.php");

  $con = DoConnect(true);

  
  $table = "identifications, allowedcabinets";

	$cabinet = isset($_REQUEST['cabinet']) ? strval($_REQUEST['cabinet']) : '';
  $result = array();
  $json="";
  if($cabinet!='')
  {
//	$where=" where identifications.login=allowedcabinets.login and allowedcabinets.cabinet='$cabinet'   AND allowedcabinets.recordstatus='0' AND identifications.recordstatus='0'";
    $where=" where identifications.login=allowedcabinets.login and allowedcabinets.cabinet='$cabinet'   AND allowedcabinets.recordstatus='0' AND identifications.recordstatus='0' AND (not identifications.profession like '%decin%')";
	$result = array();

	$sql = "select identifications.login,nom,prenom,email,telephone,profession,type,status, allowedcabinets.recordstatus from $table ".$where ;
	
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
        $json =    array("status"=>1, "msg"=>"Pas d'Infirmiers");
  
  }
   else
        $json =    array("status"=>2, "msg"=>mysql_error($con));

  }
  else
        $json =    array("status"=>3, "msg"=>"Cabinet Vide");
   header("Content Type: application/json");
   echo json_encode($json);
?>