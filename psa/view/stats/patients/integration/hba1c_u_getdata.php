<?php
  
  include 'conn.php';
 
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'hba1c_valeur';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
	$cabinet = isset($_GET['cabinet']) ? mysql_real_escape_string($_GET['cabinet']) : '';
  $allvals=isset($_GET['allvals']) ? intval($_GET['allvals']) : 0;
	$table = "liste_exam, dossier";

     
  $table = "liste_exam_".$cabinet."_u";
  
  $sql = "SELECT  dossier as hba1c_dossier,   MAX( date_exam) as hba1c_date, resultat1 as hba1c_valeur FROM $table"; 


  $where =" WHERE  type_exam='hba1c' "  ; 
 if ($allvals==0)
    $where = $where. " and resultat1 >=8 "; 
	$offset = ($page-1)*$rows;
	$result = array();

	$rs = mysql_query("select count(DISTINCT dossier) from $table " .$where);
	$row = mysql_fetch_row($rs);
	$result["total"] = $row[0];
	$sql2 = " order by $sort $order limit $offset,$rows ";
  if($sort=="hba1c_valeur")
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

		array_push($items, $rows);
	}
	$result["rows"] = $items;

   
	echo json_encode($result);

?>
