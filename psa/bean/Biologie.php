<?php

require_once("tools/date.php");

class Biologie{
	  var $id;
	  var $numero;
	  var $type_exam;
	  var $date_exam;
	  var $resultat1;
	  var $resultat2;


	function Biologie(
					 $id = NULL,
					 $numero = NULL,
					 $type_exam = NULL,
					 $date_exam = NULL,
					 $resultat1 = NULL,
					 $resultat2 = NULL){
		 $this->id = $id;
		 $this->numero = $numero;
		 $this->type_exam = $type_exam;
		 $this->date_exam = $date_exam;
		 $this->resultat1 = $resultat1;
		 $this->resultat2 = $resultat2;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->numero." ".
			$this->type_exam." ".
			$this->date_exam." ".
			$this->resultat1." ".
			$this->resultat2;
	}

	function check(){
		$errors = array();
		$i = 0;
		if(!isValidDate($this->date_exam)) $errors[$i++] = "La date du $this->type_exam est invalide";		
		
		return $errors;
	}
	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->date_exam = dateToMysqlDate($clone->date_exam);
		return $clone;
	}

	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->date_exam = mysqlDateTodate($clone->date_exam);
		return $clone;
	}
	function isOutdated($month =0){
	
		return false;
	}


}
 ?>
