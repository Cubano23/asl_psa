<?php

  include 'getConsultDate.php';
  include 'conn.php';
 
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'glyc_valeur';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
	$cabinet = isset($_GET['cabinet']) ? mysql_real_escape_string($_GET['cabinet']) : '';
  $dintegration =  isset($_GET['dintegration'])? strval($_GET['dintegration'])   : '2010-01-01';
  $allvals=isset($_GET['allvals']) ? intval($_GET['allvals']) : 0;
  $val=isset($_GET['val']) ? intval($_GET['val']) : 1;
	$table = "liste_exam, dossier";

     
  
  
  $sql = "SELECT  dossier.numero as glyc_dossier, dossier.dmaj as glyc_consultation, dossier.dnaiss as dnaiss, MAX( liste_exam.date_exam) as glyc_date, liste_exam.resultat1 as glyc_valeur, liste_exam.id as glyc_id FROM liste_exam,dossier "; 


  $where =" WHERE  dossier.cabinet = '$cabinet' and liste_exam.id=dossier.id and DATE(liste_exam.dmaj)= '$dintegration' and liste_exam.type_exam='glycemie' "  ; 
if( ($val==1) && ($allvals==0))
    $where = $where. " and liste_exam.resultat1 >=1.1 and liste_exam.resultat1 <1.26"; 
 if ($val==2)
    $where = $where. " and liste_exam.resultat1 >=1.26"; 
 if( ($val==1) && ($allvals==1))
    $where = $where. " and liste_exam.resultat1 <1.26"; 
	$offset = ($page-1)*$rows;
	$result = array();

	$rs = mysql_query("select count(DISTINCT dossier.numero) from $table " .$where);
	$row = mysql_fetch_row($rs);
	$result["total"] = $row[0];
	$sql2 = " order by $sort $order limit $offset,$rows ";
  if($sort=="glyc_valeur")
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
                $rows["glyc_age"] = $age; 
        }
        else
				      $rows[$key] = $value;
              
			}
		}
    //sql2 hba1c
  $id = $rows["glyc_id"];   
  $sql2 = "SELECT  MAX( liste_exam.date_exam) as hba1c_date, liste_exam.resultat1 as hba1c_valeur FROM liste_exam,dossier ".
        " WHERE liste_exam.id='$id'  and liste_exam.type_exam='hba1c' ";

  $rs2 = mysql_query($sql2);
  $row2 = mysql_fetch_row($rs2);
  $rows["hba1c_date"]=$row2[0];
  $rows["hba1c_valeur"]= $row2[1];
  
 
   $rows["glyc_consultation"]=getMaxConsultDate($id); 	
 //   if (isset($rows["glyc_consultation"]) )
		        array_push($items, $rows);
	}
	$result["rows"] = $items;

 
	echo json_encode($result);

?>
