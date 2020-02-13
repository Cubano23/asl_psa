<?php
/*
  $dirname = '../psa/view/integration/log';
  $cpt= "Compte-rendu integration ";
            $dirname ="./";
            $cpt="";
  $dir = opendir($dirname); 
  $items = array();
  $rows = array();
  
  
  while($file = readdir($dir)) {
  
  $pos = strpos($file, cpt);
$pos=true;
	if($pos==true)
	{
    $sstr = substr($file, 0, strlen($cpt));
        $tokens= strtok (  $sstr , " " ); 
		     $rows["cabinet"]= $token$[0];
         $rows["date_integration"]="XX";
         $rows["compte_rendu"]= $file;
         array_push($items, $rows);
	}

 }
closedir($dir);
  $result["rows"] = $items;
  */
  
  session_start();
  $cabinet = $_SESSION["cabinet"];
  include 'conn.php';
 
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'dintegration';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
  if($cabinet=="Chizé")
      $cabinet = "Chize";

 $where = " where cabinet = '$cabinet' ";
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
  
  
  
  
	echo json_encode($result);

?>
