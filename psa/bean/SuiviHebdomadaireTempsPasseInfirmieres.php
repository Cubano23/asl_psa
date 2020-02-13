<?php


class SuiviHebdomadaireTempsPasseInfirmieres{
	  
	 public $cabinet;
	 public $semaine;
	 public $infirmiere;
	 public $duree;


	 static function getRecordsByCabinet($cabinet,$semaine){

	 	if(strpos($semaine,"/")){
	 		$st = explode("/",$semaine);
	 		$semaine = $st[2].'-'.$st[1].'-'.$st[0]; 
	 	}

	 	$sql = "select infirmiere,duree from suivi_hebdo_temps_passe_infirmiere where cabinet='$cabinet' and semaine='$semaine' ";
	 	$result = mysql_query($sql);
	 	#echo $sql;
	 	while($row = mysql_fetch_assoc($result)){
				$rowsList[] = $row;
			}
		return $rowsList;
	 }
	
	 static function recordTempsPasseInfirmiere($cabinet,$infirmiere,$semaine,$duree){

	 	$sql = "REPLACE INTO suivi_hebdo_temps_passe_infirmiere set 
	 			semaine = '$semaine',
	 			cabinet = '$cabinet',
	 			infirmiere = '$infirmiere',
	 			duree = '$duree'
	 			";
	 	#echo $sql;
	 	$result = mysql_query($sql);



	 }


}
 ?>
