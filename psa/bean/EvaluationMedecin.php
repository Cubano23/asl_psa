<?php
class EvaluationMedecin{
	  var $name;
	  var $date;
	  var $degre_satisfaction;
	  var $duree_freq_consult;
	  var $satisfaction_pat;


	function EvaluationMedecin(
					 $name = "",
					 $date = "",
					 $degre_satisfaction = "",
					 $duree_freq_consult = "",
					 $satisfaction_pat = ""){
		 $this->name = $name;
		 $this->date = $date;
		 $this->degre_satisfaction = $degre_satisfaction;
		 $this->duree_freq_consult = $duree_freq_consult;
		 $this->satisfaction_pat = $satisfaction_pat;
	}

	 function toString(){
		 return 
			$this->name." ".
			$this->date." ".
			$this->degre_satisfaction." ".
			$this->duree_freq_consult." ".
			$this->satisfaction_pat;
	}

	function check(){
		$errors = array();
		$i = 0;
		
		if(is_null($this->name) or strlen($this->name)==0)
			$errors[$i++] ="Le nom du medecin est incorrect";
		if(!isValidDate($this->date))
			$errors[$i++] ="La date de l'évaluation est invalide";
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
