<?php

require_once("tools/date.php");

class DepistageCancerSein{
	  var $id;
	  var $date;
	  var $ant_fam_mere;
	  var $ant_fam_soeur;
	  var $ant_fam_tante;
	  var $ant_fam_grandmere;
	  var $ant_fam_fille;
	  var $dep_type;
	  var $mamograph_date;
	  var $rappel_mammographie;
	  var $sortir_rappel;
	  var $raison_sortie;


	function DepistageCancerSein(
					 $id = NULL,
					 $date = NULL,
					 $ant_fam_mere = NULL,
					 $ant_fam_soeur = NULL,
					 $ant_fam_tante = NULL,
					 $ant_fam_grandmere = NULL,
					 $ant_fam_fille = NULL,
					 $dep_type = NULL,
					 $mamograph_date = NULL,
					 $rappel_mammographie = NULL,
					 $sortir_rappel=NULL,
					 $raison_sortie=NULL){
		 $this->id = $id;
		 $this->date = $date;
		 $this->ant_fam_mere = $ant_fam_mere;
		 $this->ant_fam_soeur = $ant_fam_soeur;
		 $this->ant_fam_tante = $ant_fam_tante;
		 $this->ant_fam_grandmere = $ant_fam_grandmere;
		 $this->ant_fam_fille = $ant_fam_fille;
		 $this->dep_type = $dep_type;
		 $this->mamograph_date = $mamograph_date;
		 $this->rappel_mammographie = $rappel_mammographie;
		 $this->sortir_rappel = $sortir_rappel;
		 $this->raison_sortie = $raison_sortie;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->date." ".
			$this->ant_fam_mere." ".
			$this->ant_fam_soeur." ".
			$this->ant_fam_tante." ".
			$this->ant_fam_grandmere." ".
			$this->ant_fam_fille." ".
			$this->dep_type." ".
			$this->mamograph_date." ".
			$this->rappel_mammographie." ".
			$this->sortir_rappel." ".
			$this->raison_sortie;
	}

	function check(){
		$errors = array();
		$i = 0;
		if(!isValidDate($this->date)) $errors[$i++] = "La date du dépistage est invalide";		
		
		if (($this->mamograph_date)!=false)
		{
			if(!isValidDate($this->mamograph_date)) $errors[$i++] = "La date de la mammographie est invalide";
			else
			{
				if (($this->rappel_mammographie)!=false)
					{
						if(!isValidDateFuture($this->rappel_mammographie,$this->mamograph_date)) $errors[$i++] = "La date de rappel de mammographie est invalide";
					}
			}
		}



		if($this->dep_type!="coll")
		{
			if($this->dep_type!="indiv") 
		    	$errors[$i++] = "Choisissez le type de dépistage";
		}
		return $errors;
	}
	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->date = dateToMysqlDate($clone->date);
		$clone->mamograph_date = dateToMysqlDate($clone->mamograph_date);
		$clone->rappel_mammographie = dateToMysqlDate($clone->rappel_mammographie);
		return $clone;
	}

	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->date = mysqlDateTodate($clone->date);
		$clone->mamograph_date = mysqlDateTodate($clone->mamograph_date);
		$clone->rappel_mammographie = mysqlDateTodate($clone->rappel_mammographie);
		return $clone;
	}
	function isOutdated($month =0){
	
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->rappel_mammographie;
	//		echo "<br>";
	//		print_r($this);
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return "ND";
			$refDate = increaseDateBy($elderDate,0,0,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;

	//		echo "refdate : ".$refDate." elderdate : ".$elderDate."<br>";
			return $refDate;
	    }
	    else{
	        return false;
	    }
	}


}
 ?>
