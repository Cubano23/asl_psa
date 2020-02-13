<?php

require_once("tools/date.php");

class Conges{
	  var $id;
	  var $nom;
	  var $prenom;
	  var $inf_login;
	  var $date_debut;
	  var $date_fin;
	  var $nature;
	  var $prec;


	function Conges(
					 $id = NULL,
					 $nom = NULL,
					 $prenom = NULL,
					 $inf_login = NULL,
					 $date_debut = NULL,
					 $date_fin = NULL,
					 $nature = NULL,
					 $prec = NULL){
		 $this->id = $id;
		 $this->nom = $nom;
		 $this->prenom = $prenom;
		 $this->inf_login = $inf_login;
		 $this->date_debut = $date_debut;
		 $this->date_fin = $date_fin;
		 $this->nature = $nature;
		 $this->prec = $prec;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->nom." ".
			$this->prenom." ".
			$this->inf_login." ".
			$this->date_debut." ".
			$this->date_fin." ".
			$this->nature." ".
			$this->prec;
	}

	function check(){
		$errors = array();
		$i = 0;
		if(!isValidDate($this->date_debut)) $errors[$i++] = "La date de début est invalide";		

		if(!isValidDate($this->date_fin)) $errors[$i++] = "La date de fin est invalide";		
		
		return $errors;
	}
	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->date_debut = dateToMysqlDate($clone->date_debut);
		$clone->date_fin = dateToMysqlDate($clone->date_fin);
		return $clone;
	}

	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->date_debut = mysqlDateTodate($clone->date_debut);
		$clone->date_fin = mysqlDateTodate($clone->date_fin);
		return $clone;
	}


}
 ?>
