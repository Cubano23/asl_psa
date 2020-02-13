<?php

require_once("tools/date.php");

class DepistageCancerUterus{
	  var $id;
	  var $date;
	  var $date_frottis;
	  var $frottis_normal;
	  var $date_rappel;
	  var $avis_medecin;
	  var $sortir_rappel;
	  var $raison_sortie;


	function DepistageCancerUterus(
					 $id = NULL,
					 $date = NULL,
					 $date_frottis = NULL,
					 $frottis_normal = NULL,
					 $date_rappel = NULL,
					 $avis_medecin = NULL,
					 $sortir_rappel=NULL,
					 $raison_sortie=NULL){
		 $this->id = $id;
		 $this->date = $date;
		 $this->date_frottis = $date_frottis;
		 $this->frottis_normal = $frottis_normal;
		 $this->date_rappel = $date_rappel;
		 $this->avis_medecin = $avis_medecin;
		 $this->sortir_rappel = $sortir_rappel;
		 $this->raison_sortie = $raison_sortie;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->date." ".
			$this->date_frottis." ".
			$this->frottis_normal." ".
			$this->date_rappel." ".
			$this->avis_medecin." ".
			$this->sortir_rappel." ".
			$this->raison_sortie;
	}

	function check(){
		$errors = array();
		$i = 0;
		if(!isValidDate($this->date)) $errors[$i++] = "La date du dépistage est invalide";		
		
		if (($this->date_frottis)!=false)
		{
			if(!isValidDate($this->date_frottis)) $errors[$i++] = "La date du frottis est invalide";
			else
			{
				if (($this->date_rappel)!=false)
					{
						if(!isValidDateFuture($this->date_rappel,$this->date_frottis)) $errors[$i++] = "La date de rappel de frottis est invalide";
						if(getDiffInMonth($this->date_rappel,$this->date_frottis)>37) $errors[$i++]="La date de rappel doit être dans moins de 3 ans";
					}
			}
		}


		if($this->frottis_normal!="oui")
		{
			if($this->frottis_normal!="non")
		    	$errors[$i++] = "Indiquez si le frottis est normal ou non";
		}

		return $errors;
	}
	
	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->date = dateToMysqlDate($clone->date);
		$clone->date_frottis = dateToMysqlDate($clone->date_frottis);
		$clone->date_rappel = dateToMysqlDate($clone->date_rappel);
		return $clone;
	}

	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->date = mysqlDateTodate($clone->date);
		$clone->date_frottis = mysqlDateTodate($clone->date_frottis);
		$clone->date_rappel = mysqlDateTodate($clone->date_rappel);
		return $clone;
	}


	function isOutdated($month =0){
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->date_rappel;
			if(is_null($elderDate)) return false;
			if($elderDate == "") return false;
			$refDate = increaseDateBy($elderDate,0,0,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;

			return $refDate;
	    }
	    else{
	        return false;
	    }
	}

}
 ?>
