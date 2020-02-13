<?php

require_once("tools/date.php");

class TensionArterielle{
	  var $id;
	  var $date;
	  var $momment_journee;
	  var $indice;
	  var $systole;
	  var $group_id;
	  var $diastole;


	function TensionArterielle(
					 $id = NULL,
					 $date = NULL,
					 $momment_journee = NULL,
					 $indice = NULL,
					 $systole = 0,
					 $group_id = NULL,
					 $diastole = 0){
		 $this->id = $id;
		 $this->date = $date;
		 $this->momment_journee = $momment_journee;
		 $this->indice = $indice;
		 $this->systole = $systole;
		 $this->group_id = $group_id;
		 $this->diastole = $diastole;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->date." ".
			$this->momment_journee." ".
			$this->indice." ".
			$this->systole." ".
			$this->group_id." ".
			$this->diastole;
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

	function check($account){
		$errorsArray = array();
#	if (!isValidDate($this->date)) {
#		   $errorsArray[] ="La date de mesure n'est pas valide : $this->date";
#		}
#		if (($this->momment_journee <> "Matin" ) and ($this->momment_journee <> "Soir") ) {
#		   $errorsArray[] ="Le moment doit être Matin ou Soir : $this->momment_journee";
#		}
#		if (!is_numeric($this->indice) or ($this->indice < 0 ) or ($this->indice > 2)) {
#		   $errorsArray[] ="L'indice de mesure doit être 0, 1 ou 2";
#		}
		if (!is_numeric($this->systole)) {
		   $errorsArray[] ="La mesure de systole doit &ecirc;tre num&eacute;rique : $this->systole";
		}
		if (!is_numeric($this->diastole)) {
		   $errorsArray[] ="La mesure de diastole doit &ecirc;tre num&eacute;rique : $this->diastole";
		}
		$this->systole = round($this->systole);
		$this->diastole = round($this->diastole);
		
		if(($this->systole < 50) or ($this->systole > 300)) {
		   $errorsArray[] ="La systole doit &ecirc;tre comprise entre 50 et 300 : $this->systole";
		}
		if(($this->diastole < 15) or ($this->diastole > 160)) {
		   $errorsArray[] ="La diastole doit &ecirc;tre comprise entre 15 et 160 : $this->diastole";
		}
		if($this->diastole > $this->systole) {
		   $errorsArray[] ="La diastole ne peut être sup&eacute;rieure à la systole : $this->diastole > $this->systole";			
		}
		return $errorsArray;		
	}
}
 ?>
