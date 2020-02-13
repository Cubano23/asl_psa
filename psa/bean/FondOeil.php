<?php
require_once("tools/date.php");

class FondOeil{
	  var $id;
	  var $date;
	  var $oeil;
	  var $fichier;


	function FondOeil(
					 $id = NULL,
					 $date = NULL,
					 $oeil = NULL,
					 $fichier = NULL){
		 $this->id = $id;
		 $this->date = $date;
		 $this->oeil = $oeil;
		 $this->fichier = $fichier;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->date." ".
			$this->oeil." ".
			$this->fichier;
	}
	
	
	function check(){
		$errors = array();
		$i = 0;
		if(($this->oeil!="D")and ($this->oeil!="G")) $errors[$i++] = "Indiquez s'il s'agit d'un oeil Droit ou Gauche";

		if(!isValidDate($this->date)) $errors[$i++] = "Indiquez la date du fond d'oeil";

		return $errors;
	}
				
	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->date = dateToMysqlDate($clone->date);
		return $clone;
	}
		
	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->date = mysqlDateTodate($clone->date);
		return $clone;
	}
}
 ?>
