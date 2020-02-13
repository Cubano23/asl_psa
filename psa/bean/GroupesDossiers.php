<?php


class GroupesDossiers {
	  
	public $id_groupe;
	public $cabinet;
	public $libelle;
	public $commentaire;
	public $dossiers;
	public $id_dossiers;
	public $created_at;

	
	static function listeGroupesByCab($cabinet, $is_actif = TRUE){

	 	$sql = "select * from groupes where cabinet='$cabinet' AND is_actif='".$is_actif."'";
	 	#echo $sql;
	 	$result = mysql_query($sql);
	 	$rows = array();
	 	while($row = mysql_fetch_assoc($result)){
				
				$rowsList[] = $row;
				
			}
		
		return $rowsList;
	 }
	
	
	static function getGroupeById($id){

	 	$sql = "select * from groupes where id_groupe='$id' ";
	 	$result = mysql_query($sql);
	 	$row = mysql_fetch_array($result);
	 	return $row;
	}



	/**
	 * création d'un groupe de consultation
	 * @param [type] $groupe [description]
	 */
	 static function add($groupe){
	 	#echo $infirmiere. '@'.$cabinet;
	 		$sql = "INSERT INTO groupes set
	 		cabinet = '$groupe->cabinet',
	 		libelle = '$groupe->libelle',
	 		commentaire = '$groupe->commentaire',
	 		dossiers = '$groupe->dossiers',
	 		id_dossiers = '$groupe->id_dossiers',
	 		is_actif='1',
	 		created_at = now()
	 		";
	 		#echo $sql;exit;
			return(mysql_query($sql));

		 
	}

	/**
	 * création d'un groupe de consultation
	 * @param [type] $groupe [description]
	 */
	 static function save($groupe){
	 	#echo $infirmiere. '@'.$cabinet;
	 		$sql = "UPDATE groupes set
	 		libelle = '$groupe->libelle',
	 		commentaire = '$groupe->commentaire',
	 		dossiers = '$groupe->dossiers',
	 		id_dossiers = '$groupe->id_dossiers'
	 		where id_groupe = '$groupe->id_groupe'
	 		";
	 		#echo $sql;exit;
			return(mysql_query($sql));	 
	}


	static function disableGroupeById($id, $cabinet) {
		$sql = "UPDATE groupes set
	 		is_actif = '0'
	 		where id_groupe = '".$id."' AND cabinet='".$cabinet."'";
			return(mysql_query($sql));	 
	}


}
 ?>
