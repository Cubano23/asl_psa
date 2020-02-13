<?php


class PlanningInfirmieres {
	  

	/**
	 * listing des planning par infirmière dans toute la table planning, 
	 * va chercher aussi les données cabinet 
	 * @param  [type] $infirmiere [description]
	 * @return [type]             [description]
	 */
	static function getRecordsByInfirmiere($infirmiere){

	 	$sql = "select * from planning_infirmieres where infirmiere='$infirmiere' ";
	 	$result = mysql_query($sql);

	 	while($row = mysql_fetch_assoc($result)){
				
				$row['infosCab'] = AccountMapper::getFullInfosByCab($row['cabinet']);
				$rowsList[] = $row;
				#var_dump($rowsList);
			}
		return $rowsList;
	 }
	
	/**
	 * récupération des info infirmiere pour un seule personne
	 * @param  string $login cle pour identifier l'inf
	 * @return [type]        [description]
	 */
	 static function getPlanningByInfirmiereAndCab($login,$cabinet){
	 	$sql = "select * from planning_infirmieres where infirmiere='$login' and cabinet='$cabinet' ";
	 	$result = mysql_query($sql);
	 	#echo $sql;
	 	$row = mysql_fetch_array($result);
	 	$row['infosCab'] = AccountMapper::getFullInfosByCab($row['cabinet']);
	 	return $row;
	 }

	 /**
	  * enregitrement des datas planning infimere depuis la page planning_infirmiere.php
	  * @param  [type] $infirmiere [description]
	  * @param  [type] $cabinet    [description]
	  * @param  [type] $lundi      [description]
	  * @param  [type] $mardi      [description]
	  * @param  [type] $mercredi   [description]
	  * @param  [type] $jeudi      [description]
	  * @param  [type] $vendredi   [description]
	  * @param  [type] $samedi     [description]
	  * @return [type]             [description]
	  */
	 static function recordPlanning($infirmiere,$cabinet,$lundi,$mardi,$mercredi,$jeudi,$vendredi,$samedi){
	 	#echo $infirmiere. '@'.$cabinet;
	 	if(!empty($infirmiere) && !empty($cabinet)){
	 		$sql = "REPLACE planning_infirmieres set
	 		infirmiere = '$infirmiere',
	 		cabinet = '$cabinet',
	 		lundi = '$lundi',
	 		mardi = '$mardi',
	 		mercredi = '$mercredi',
	 		jeudi = '$jeudi',
	 		vendredi = '$vendredi',
	 		samedi = '$samedi'
	 		";
	 		#echo $sql;
			return(mysql_query($sql));
		}
		 	
		
		else{
			return false;
		}
	}
}
 ?>
