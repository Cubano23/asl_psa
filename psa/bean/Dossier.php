<?php
require_once("tools/date.php");

class Dossier{
	  var $id;
	  var $cabinet;
	  var $numero;
	  var $dnaiss;
	  var $sexe;
	  var $taille;
	  var $actif;
	  var $dconsentement;

	function Dossier(
					 $id = NULL,
					 $cabinet = NULL,
					 $numero = NULL,
					 $dnaiss = NULL,
					 $sexe = NULL,
					 $taille = NULL,
					 $actif = NULL,
					 $dconsentement = NULL){
		 $this->id = $id;
		 $this->cabinet = $cabinet;
		 $this->numero = $numero;
		 $this->dnaiss = $dnaiss;
		 $this->sexe = $sexe;
		 $this->taille = $taille;
		 $this->actif = $actif;
		 $this->dconsentement = $dconsentement;
	}
	 function toString(){
		 return 
			$this->id." ".
			$this->cabinet." ".
			$this->numero." ".
			$this->dnaiss." ".
			$this->sexe." ".
			$this->taille." ".
			$this->actif." ".
			$this->dconsentement;
	}
	
	function getAge(){
		list($D,$M,$Y) = explode("/",$this->dnaiss);
		$age = date('Y') - $Y;
		$currentMonth = date("m");		
		$currentDay = date("d");
		if($currentMonth < $M) $age--;
		else if(($currentMonth == $M) and ($currentDay<$D)) $age--;
		return $age;
	}
	
	function check(){
		$errors = array();
		$i = 0;
		if(is_null($this->numero) or strlen($this->numero)==0) $errors[$i++] = "Le numero de dossier est invalide";
		if(!isValidDate($this->dnaiss)) $errors[$i++] = "La date de naissance est invalide";
		if(!empty($this->dconsentement)){
			if(!isValidDate($this->dconsentement)) $errors[$i++] = "La date de consentement est invalide";
		}

		if(!empty($this->taille) and (!is_numeric($this->taille) or ($this->taille < 0) or ($this->taille > 280)))		
			$errors[$i++] ="La taille doit etre entre 0 et 280";
		
		return $errors;
	}
				
	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->dnaiss = dateToMysqlDate($clone->dnaiss);
		$clone->dconsentement = dateToMysqlDate($clone->dconsentement);
		return $clone;
	}
		
	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->dnaiss = mysqlDateTodate($clone->dnaiss);	
		$clone->dconsentement = mysqlDateTodate($clone->dconsentement);		
		return $clone;
	}
}
 ?>