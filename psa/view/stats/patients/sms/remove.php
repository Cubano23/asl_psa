<?php
	

	include 'conn.php';

	$id = intval($_POST["id"]);


//	$sql = "delete from $table where cabinet='$cabinet'";
//	error_log($sql);
//	$result = @mysql_query($sql);

 	$sql = "update $table set  recordstatus=1 where id= $id ";
//	error_log($sql);

	$result = @mysql_query($sql);
  
  
/*  
  $xLog=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
  require_once("$xLog/WebService/AsaleeLog.php");
  LogAccess("psaet.asalee.fr", "cab_remove", $UserIDLog, 'na', $cabinet,  3, "Effacer Cabinet: ".$answerLog."/".$result);  
*/
  
	if ($result){
		echo json_encode(array('success'=>true));
	} 
	else {
		echo json_encode(array('msg'=>'Erreur'));
	}
?>
