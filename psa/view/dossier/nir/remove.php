<?php
	 function toiso($src)
	{
		$utf8=html_entity_decode($src);
		 $iso8859=utf8_decode($utf8);
		return $iso8859;
	}

	include 'conn.php';

	$numero = toiso($_REQUEST['numero']);
  $cabinet = $_SESSION["cabinet"];
 /* $rs = mysql_query("select id from numero='$numero' and cabinet='$cabinet'");
	$row = mysql_fetch_row($rs);
  $id = $row[0];
//	$sql = "delete from $table,depistage_colon,depistage_diabete,depistage_sein,evaluation_infirmier,evaluation_medecin,evaluation_patient,suivi_diabete where dossier.id=$id  and depistage_colon.id = $id and depistage_diabete.id = $id and depistage_sein.id = $id and evaluation_infirmier.id = $id and evaluation_medecin.id = $id and evaluation_patient.id = $id and suivi_diabete.dossier_id= $id";
*/
  $sql = "delete from $table where numero='$numero' and cabinet = '$cabinet'";
//	error_log($sql);
	$result = @mysql_query($sql);
  
  
	if ($result){
		echo json_encode(array('success'=>true));
	} 
	else {
		echo json_encode(array('msg'=>'Erreur'));
	}
?>
