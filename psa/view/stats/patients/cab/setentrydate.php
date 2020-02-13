<?php


//select * from medecin where id not in (select medid from `historique_medecin` ) and cabinet in (select cabinet from account) and cabinet in (select cabinet from historique_account where recordstatus=0)
//

  $serveur="localhost";
  $idDB="informed";
  $mdpDB="no11iugX";
  $DB = "informed3"; 
# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter à la base");

	$table = "account";




  $sql = "select cabinet from account where cabinet not in (select  cabinet from historique_account) ";
	$res = mysql_query($sql);
  $cabinets = array();

  

    while($result=mysql_fetch_array($res)) 
    {
          $cabinets[]=$result[0]; 
    }
    
    
    foreach( $cabinets as $cab)
  {
              
      $sql = "SELECT dossier.cabinet as cabinet1, evaluation_infirmier.date as deval, evaluation_infirmier.id as pid1 ". 
          " FROM evaluation_infirmier, dossier WHERE evaluation_infirmier.id=dossier.id  and cabinet='$cab' " . 
          " ORDER BY evaluation_infirmier.date ASC LIMIT 1,1";

      $res = mysql_query($sql);

      if($result=mysql_fetch_array($res)) 
      {
          
          $deval=$result["deval"];
          echo  "$cab".":"."$deval\n";
          
          $sql2 = "INSERT IGNORE INTO `historique_account`( `cabinet`,  `actualstatus`, `dstatus`) VALUES ( '$cab', 0, '$deval')";
          echo $sql2."\n";
          @mysql_query($sql2);
      }
  }
/*


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


  $xLog=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
  require_once("$xLog/WebService/AsaleeLog.php");
  LogAccess("psaet.asalee.fr", "cab_getdata", $UserIDLog, 'na', $cabinet,  0, "Liste Cabinets:".$answerLog);  


	echo json_encode($result);

?>
