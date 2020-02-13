<?php

  include 'conn.php';
 
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'glyc_valeur';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
	$cabinet = isset($_GET['cabinet']) ? mysql_real_escape_string($_GET['cabinet']) : '';
  $dintegration =  isset($_GET['dintegration'])? strval($_GET['dintegration'])   : '2010-01-01';
  $allvals=isset($_GET['allvals']) ? intval($_GET['allvals']) : 0;
  $val=isset($_GET['val']) ? intval($_GET['val']) : 1;

  $table = "liste_exam_".$cabinet."_u";   
  
  
  $sql = "SELECT  dossier as glyc_dossier, MAX( date_exam) as glyc_date, resultat1 as glyc_valeur FROM $table "; 


  $where =" WHERE  type_exam='glycemie' "  ; 
if( ($val==1) && ($allvals==0))
    $where = $where. " and resultat1 >=1.1 and resultat1 <1.26"; 
 if ($val==2)
    $where = $where. " and resultat1 >=1.26"; 
 if( ($val==1) && ($allvals==1))
    $where = $where. " and resultat1 <1.26"; 
	$offset = ($page-1)*$rows;
	$result = array();

	$rs = mysql_query("select count(DISTINCT dossier) from $table " .$where);
	$row = mysql_fetch_row($rs);
	$result["total"] = $row[0];
	$sql2 = " order by $sort $order limit $offset,$rows ";
  if($sort=="glyc_valeur")
      $sql2 = " order by $sort *1 $order limit $offset,$rows ";

	
	$sql = $sql.$where." group by dossier ". $sql2;
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
    //sql2 hba1c
  $dossier = $rows["glyc_dossier"];   
  $sql2 = "SELECT  MAX( date_exam) as hba1c_date, resultat1 as hba1c_valeur FROM $table ".
        " WHERE dossier='$dossier'  and type_exam='hba1c' ";

  $rs2 = mysql_query($sql2);
  $row2 = mysql_fetch_row($rs2);
  $rows["hba1c_date"]=$row2[0];
  $rows["hba1c_valeur"]= $row2[1];
    
	
 
		array_push($items, $rows);
	}
	$result["rows"] = $items;
  
   
	echo json_encode($result);

?>
