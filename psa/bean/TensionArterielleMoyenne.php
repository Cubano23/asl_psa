<?php

require_once("tools/date.php");

class TensionArterielleMoyenne{
	  var $id;
	  var $group_id;
	  var $date_debut;
	  var $nombre_jours;
	  var $moyenne_sys_matin;
	  var $moyenne_sys_soir;
	  var $moyenne_sys;
	  var $moyenne_dia_matin;
	  var $moyenne_dia_soir;
	  var $moyenne_dia;


	function TensionArterielleMoyenne(
					 $id = NULL,
					 $group_id = NULL,
					 $date_debut = NULL,
					 $nombre_jours = NULL,
					 $moyenne_sys_matin = NULL,
					 $moyenne_sys_soir = NULL,
					 $moyenne_sys = NULL,
					 $moyenne_dia_matin = NULL,
					 $moyenne_dia_soir = NULL,
					 $moyenne_dia = NULL){
		 $this->id = $id;
		 $this->group_id = $group_id;
		 $this->date_debut = $date_debut;
		 $this->nombre_jours = $nombre_jours;
		 $this->moyenne_sys_matin = $moyenne_sys_matin;
		 $this->moyenne_sys_soir = $moyenne_sys_soir;
		 $this->moyenne_sys = $moyenne_sys;
		 $this->moyenne_dia_matin = $moyenne_dia_matin;
		 $this->moyenne_dia_soir = $moyenne_dia_soir;
		 $this->moyenne_dia = $moyenne_dia;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->group_id." ".
			$this->date_debut." ".
			$this->nombre_jours." ".
			$this->moyenne_sys_matin." ".
			$this->moyenne_sys_soir." ".
			$this->moyenne_sys." ".
			$this->moyenne_dia_matin." ".
			$this->moyenne_dia_soir." ".
			$this->moyenne_dia;
	}

	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->date_debut = dateToMysqlDate($clone->date_debut);
		return $clone;
	}

	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->date_debut = mysqlDateTodate($clone->date_debut);
		return $clone;
	}


}
 ?>
