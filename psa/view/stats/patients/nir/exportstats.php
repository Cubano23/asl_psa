<?php
  session_start();
  include 'conn.php';
 
	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'cabinet';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
  $where = "  ";
  $table='dossier';
  //Filtre  Nir chiffré
  $where2 = "";
	$result = array();


	$sql2 = " order by $sort $order ";
	$sql = "select count(*) as dossiers, cabinet from $table  ";
	
	$sql = $sql.$where." group by cabinet ".$sql2;
  $fp =  fopen("statsnirexport.csv", "w+");
  fprintf($fp,"cabinet;encnirs;dconsent;dossiers\n"  );
  
	$rs = mysql_query($sql);
	
	$items = array();
	while($row = mysql_fetch_object($rs))
	{
    $thecabinet='';
		$rows = (array)$row;
		foreach( $rows as $key => $value)
		{
  
    
      if ($key=="cabinet")
      {
      
      
          $sql5 = "select count(*) from  dossier where cabinet= '$value' and encnir!='' "; 
          $rs2 = mysql_query($sql5);    
        	$row2 = mysql_fetch_row($rs2);
          $rows["encnirs"] = $row2[0];
          
          $sql5 = "select count(*) from  dossier where cabinet= '$value' and encnir!='' and dconsentement!='0000-00-00' "; 
          $rs2 = mysql_query($sql5);    
        	$row2 = mysql_fetch_row($rs2);
          $rows["dconsent"] = $row2[0];
          
 				  $value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
				  $rows[$key] = $value;
          
      }
    
    
            
		
		}
     fprintf($fp,"%s;%s;%s;%s\n", utf8_decode ($rows["cabinet"]), strval($rows["encnirs"]),strval($rows["dconsent"]),strval($rows["dossiers"])                                           
            );

	}
  fclose($fp);
  
  echo json_encode($result);
?>
