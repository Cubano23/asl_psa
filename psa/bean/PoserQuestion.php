<?php


class PoserQuestion{
	  var $titre;
	  var $corps;
	  var $infirmiere;
	  var $cabinet;
	  var $date;

	function FicheCabinet(
					 $titre = NULL,
					 $corps = NULL,
					 $infirmiere = NULL,
					 $cabinet = NULL,
					 $date = NULL){
					 
		 $this->titre = $titre;
		 $this->corps = $corps;
		 $this->infirmiere = $infirmiere;
		 $this->cabinet = $cabinet;
		 $this->date = $date;
	}

	 function toString(){
		 return 
			$this->titre." ".
			$this->corps." ".
			$this->infirmiere." ".
			$this->cabinet." ".
			$this->date;
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


	function check(){
		$errors = array();
		$i = 0;

		if(empty($this->titre)) $errors[$i++]="Veuillez préciser un sujet";
		
		if(empty($this->corps)) $errors[$i++]="Veuillez préciser votre question";

		return $errors;
	}


}
 ?>
