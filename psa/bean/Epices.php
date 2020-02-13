<?php
class Epices{
	  var $id;
	  var $date;
	  var $travailleur_social;
	  var $complementaire;
	  var $couple;
	  var $proprietaire;
	  var $difficulte;
	  var $sport;
	  var $spectacle;
	  var $vacances;
	  var $famille;
	  var $hebergement;
	  var $materiel;


	function Epices(
					 $id = "",
					 $date = "",
					 $travailleur_social = "",
					 $complementaire = "",
					 $couple = "",
					 $proprietaire = "",
					 $difficulte = "",
					 $sport = "",
					 $spectacle = "",
					 $vacances = "",
					 $famille = "",
					 $hebergement = "",
					 $materiel = ""
					 ){
		 $this->id = $id;
		 $this->date = $date;
		 $this->travailleur_social = $travailleur_social;
		 $this->complementaire = $complementaire;
		 $this->couple = $couple;
		 $this->proprietaire = $proprietaire;
		 $this->difficulte = $difficulte;
		 $this->sport = $sport;
		 $this->spectacle = $spectacle;
		 $this->vacances = $vacances;
		 $this->famille = $famille;
		 $this->hebergement = $hebergement;
		 $this->materiel = $materiel;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->date." ".
			$this->travailleur_social." ".
			$this->complementaire." ".
			$this->couple." ".
			$this->proprietaire." ".
			$this->difficulte." ".
			$this->sport." ".
			$this->spectacle." ".
			$this->vacances." ".
			$this->famille." ".
			$this->hebergement." ".
			$this->materiel;

	}

	function check(){
		if($this->date!=""){
			if(!isValidDate($this->date)) $errors[$i++] = "La date du questionnaire complémentaire est invalide";
		}
	
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
