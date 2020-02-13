<?php

require_once("tools/date.php");

class Frais{
	  var $id;
	  var $date_demande;
	  var $infirmiere;
	  var $inf_login;
	  var $date_frais;
	  var $nature;
	  var $motif;
	  var $montant;
	  var $autre_calcul;
	  var $pj;


	function Frais(
					 $id = NULL,
					 $date_demande = NULL,
					 $infirmiere = NULL,
					 $inf_login = NULL,
					 $date_frais = NULL,
					 $nature = NULL,
					 $motif = NULL,
					 $montant = NULL,
					 $autre_calcul = NULL,
					 $pj = NULL){
		 $this->id = $id;
		 $this->date_demande = $date_demande;
		 $this->infirmiere = $infirmiere;
		 $this->inf_login = $inf_login;
		 $this->date_frais = $date_frais;
		 $this->nature = $nature;
		 $this->motif = $motif;
		 $this->montant = $montant;
		 $this->autre_calcul = $autre_calcul;
		 $this->pj = $pj;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->date_demande." ".
			$this->infirmiere." ".
			$this->inf_login." ".
			$this->date_frais." ".
			$this->nature." ".
			$this->motif." ".
			$this->montant." ".
			$this->autre_calcul." ".
			$this->pj;
	}

	function check(){
		$errors = array();
		$i = 0;
		if(!isValidDate($this->date_frais)) $errors[$i++] = "La date des frais est invalide";		

		return $errors;
	}
	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->date_frais = dateToMysqlDate($clone->date_frais);
		return $clone;
	}

	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->date_frais = mysqlDateTodate($clone->date_frais);

		return $clone;
	}


}
 ?>

