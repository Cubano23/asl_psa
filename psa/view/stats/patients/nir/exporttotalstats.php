<?php
  session_start();
  include 'conn.php';
 
	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'cabinet';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
  $dsearch = intval(isset($_POST['dsearch']) ? clean($_POST['dsearch'], 1) : '1') ; //date consentement
  $nsearch = intval(isset($_POST['nsearch']) ? clean($_POST['nsearch'], 1) : '1'); //nir saisi
  
  $where = "  ";
  $table='dossier';
  //Filtre  Nir chiffré
  $where2 = "";
	$result = array();


	$sql2 = " order by id ";
	$sql = "select id, dconsentement,encnir from $table  where cabinet!='ztest' ";
  $fp =  fopen("totalstats.csv", "w+");
  if($nsearch==0)
          fprintf($fp,"id;dconsent;encnir\n"  );
  else
          fprintf($fp,"id;dconsent\n"  );
          
  switch ($nsearch)
  {
  
        case 0:  break; 
        case 1: $where = "and encnir!='' ";break;
        case 2: $where = "and encnir ='' ";break; 
  
  }
          
	switch($dsearch)
  {
        case 0: break;
        case 1: $where = $where. " and dconsentement!='0000-00-00' ";break;
        case 2: $where = " and dconsentement ='0000-00-00' ";break; 
  }
  
  
	
   $sql = $sql.$where.$sql2;
 //error_log($sql); 
	$rs = mysql_query($sql);
	
	$items = array();
	while($row = mysql_fetch_object($rs))
	{
    $nirsaisi='';
		$rows = (array)$row;
		foreach( $rows as $key => $value)
		{
  
    
      if ($key=="encnir")
      {
          if ($value=='')
              $nirsaisi="Non";
          else
              $nirsaisi="Oui";
          
      }
		
		}
    if($nsearch==0)
          fprintf($fp,"%s;%s;%s\n", strval($rows["id"]),strval($rows["dconsentement"]), $nirsaisi);
    else    
            fprintf($fp,"%s;%s\n",  strval($rows["id"]),strval($rows["dconsentement"]));                                           
	}
  fclose($fp);
  
  echo json_encode($result);
?>
