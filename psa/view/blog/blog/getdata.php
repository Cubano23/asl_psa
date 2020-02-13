<?php


  include 'conn.php';
 
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
  $where="";


  $ssearch = isset($_POST['ssearch']) ? clean($_POST['ssearch'], 1) : '';
  $tsearch = isset($_POST['tsearch']) ? clean($_POST['tsearch'], 1) : '0';
  

  $where = " where sujet LIKE '%$ssearch%' ";
  
  //Filtre  Date consentement
  $where2 = "";
  if($tsearch!=0)
        $where2=" and type = $tsearch ";

  $where = $where.$where2;

 
  
	$offset = ($page-1)*$rows;
	$result = array();

	$rs = mysql_query("select count(*) from $table " .$where);
	$row = mysql_fetch_row($rs);
	$result["total"] = $row[0];
	$sql2 = " order by $sort $order limit $offset,$rows";
	$sql = "select * from $table ";
	
	$sql = $sql.$where.$sql2;
	$rs = mysql_query($sql);


 //error_log($sql);
	
	$items = array();
	while($row = mysql_fetch_object($rs))
	{
		$rows = (array)$row;
		foreach( $rows as $key => $value)
		{
            
      if (($key=="dmaj")
      ||   ($key=="dcreat")
      )
      {
   
   
            $value = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $value);
      }
			if(!is_null($value))
			{
		
				$value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
				$rows[$key] = $value;
        
			}
		}

		array_push($items, $rows);
	}
	$result["rows"] = $items;
  
//  require_once("/home/informed/www/informed79/WebService/AsaleeLog.php");
//  LogAccess("", "dossier_getdata", $UserIDLog, 'na', $cabinet,  0, "Liste Dossiers:".$answerLog);  

  
  
	echo json_encode($result);

?>
