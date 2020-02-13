<?php
    
  include 'getConsultDate.php';
  include 'conn.php';
  
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'ldl_valeur';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
	$cabinet = isset($_GET['cabinet']) ? mysql_real_escape_string($_GET['cabinet']) : '';
  $dintegration =  isset($_GET['dintegration'])? strval($_GET['dintegration'])   : '2010-01-01';
  $reportfile =  isset($_POST['reportfile'])? strval($_POST['dintegration']): '';
  $allvals=isset($_GET['allvals']) ? intval($_GET['allvals']) : 0;
	$table = "liste_exam, dossier";

     
  
  
  $sql = "SELECT  dossier.numero as ldl_dossier, dossier.dmaj as ldl_consultation, dossier.dnaiss as dnaiss, MAX( liste_exam.date_exam) as ldl_date, liste_exam.resultat1 as ldl_valeur, liste_exam.id as ldl_id  FROM liste_exam,dossier "; 


  $where =" WHERE  dossier.cabinet = '$cabinet' and liste_exam.id=dossier.id and DATE(liste_exam.dmaj)= '$dintegration' and liste_exam.type_exam='ldl' "  ; 
 if ($allvals==0)
    $where = $where. " and liste_exam.resultat1 >=2 "; 
	$offset = ($page-1)*$rows;
	$result = array();

	$rs = mysql_query("select count(DISTINCT dossier.numero) from $table " .$where);
	$row = mysql_fetch_row($rs);
	$result["total"] = $row[0];
	$sql2 = " order by $sort $order limit $offset,$rows ";
  if($sort=="ldl_valeur")
      $sql2 = " order by $sort *1 $order limit $offset,$rows ";

	
	$sql = $sql.$where." group by dossier.numero ". $sql2;
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
        if($key=="dnaiss")
        {
                $p_strDate = $value;
                list($Y,$m,$d)    = explode("-",$p_strDate);
                date_default_timezone_set('Europe/Berlin');
                $age =  date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y ;
                $rows["ldl_age"] = $age; 
        }
        else
				      $rows[$key] = $value;
			}
		}
    $id =$rows["ldl_id"];

    $rows["ldl_consultation"] = getMaxConsultDate($id);
		array_push($items, $rows);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>
