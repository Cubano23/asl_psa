<?php
  session_start();
  include 'conn.php';
 
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'cabinet';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
  $cabinet = isset($_POST['csearch']) ? clean($_POST['csearch'], 1) : '';
  $nsearch = isset($_POST['nsearch']) ? clean($_POST['nsearch'], 1) : '1';
  $table='dossier';
  if($cabinet=="Chizé")
      $cabinet = "Chize";

  $where = " where cabinet LIKE '$cabinet%' ";
  
  //Filtre  Nir chiffré
  $where2 = "";
  if($nsearch=='1')
      $where2=" and encnir !='' ";
  if($nsearch=='2')
      $where2=" and encnir ='' ";
 // $where = $where.$where2;
  
 // error_log($nsearch.",".$where);
  
	$offset = ($page-1)*$rows;
	$result = array();

	$rs = mysql_query("select count(distinct cabinet) from  $table " .$where);
	$row = mysql_fetch_row($rs);
	$result["total"] = $row[0];
	$sql2 = " order by $sort $order limit $offset,$rows";
	$sql = "select count(*) as dossiers, cabinet from $table  ";
	
	$sql = $sql.$where." group by cabinet ".$sql2;
  
	$rs = mysql_query($sql);
	
	$items = array();
	while($row = mysql_fetch_object($rs))
	{
		$rows = (array)$row;
		foreach( $rows as $key => $value)
		{
  
    
      if ($key=="cabinet")
      {
/*          $sql5 = "select count(*) from  dossier where cabinet= '$value' "; 
          $rs2 = mysql_query($sql5);    
        	$row2 = mysql_fetch_row($rs2);
          $rows["dossiers"] = $row2[0];
  */
          $sql5 = "select count(*) from  dossier where cabinet= '$value' and encnir!='' "; 
          $rs2 = mysql_query($sql5);    
        	$row2 = mysql_fetch_row($rs2);
          $rows["encnirs"] = $row2[0];
          
          $sql5 = "select count(*) from  dossier where cabinet= '$value' and encnir!='' and dconsentement>'0000-00-00' "; 
          $rs2 = mysql_query($sql5);    
        	$row2 = mysql_fetch_row($rs2);
          $rows["dconsent"] = $row2[0];          
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
