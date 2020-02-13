<?php

require_once("tools/date.php");

class DepistageCancerColon{
	  var $id;
	  var $date;
	  var $ant_pere_type;
	  var $ant_pere_age;
	  var $ant_mere_type;
	  var $ant_mere_age;
	  var $ant_fratrie_type;
	  var $ant_fratrie_age;
	  var $ant_collat_type;
	  var $ant_collat_age;
	  var $ant_enfants_type;
	  var $ant_enfants_age;
	  var $just_ant_fam;
	  var $just_ant_polype;
	  var $just_ant_cr_colique;
	  var $just_ant_sg_selles;
	  var $colos_date;
	  var $colos_polypes;
	  var $colos_dysplasie;
	  var $rappel_colos_period;
	  var $sortir_rappel;
	  var $raison_sortie;


	function DepistageCancerColon(
					 $id = NULL,
					 $date = NULL,
					 $ant_pere_type = array(),
					 $ant_pere_age = NULL,
					 $ant_mere_type = array(),
					 $ant_mere_age = NULL,
					 $ant_fratrie_type = array(),
					 $ant_fratrie_age = NULL,
					 $ant_collat_type = array(),
					 $ant_collat_age = NULL,
					 $ant_enfants_type = array(),
					 $ant_enfants_age = NULL,
					 $just_ant_fam = NULL,
					 $just_ant_polype = NULL,
					 $just_ant_cr_colique = NULL,
					 $just_ant_sg_selles = NULL,
					 $colos_date = NULL,
					 $colos_polypes = NULL,
					 $colos_dysplasie = NULL,
					 $rappel_colos_period = 0,
					 $sortir_rappel=NULL,
					 $raison_sortie=NULL){
		 $this->id = $id;
		 $this->date = $date;
		 $this->ant_pere_type = $ant_pere_type;
		 $this->ant_pere_age = $ant_pere_age;
		 $this->ant_mere_type = $ant_mere_type;
		 $this->ant_mere_age = $ant_mere_age;
		 $this->ant_fratrie_type = $ant_fratrie_type;
		 $this->ant_fratrie_age = $ant_fratrie_age;
		 $this->ant_collat_type = $ant_collat_type;
		 $this->ant_collat_age = $ant_collat_age;
		 $this->ant_enfants_type = $ant_enfants_type;
		 $this->ant_enfants_age = $ant_enfants_age;
		 $this->just_ant_fam = $just_ant_fam;
		 $this->just_ant_polype = $just_ant_polype;
		 $this->just_ant_cr_colique = $just_ant_cr_colique;
		 $this->just_ant_sg_selles = $just_ant_sg_selles;
		 $this->colos_date = $colos_date;
		 $this->colos_polypes = $colos_polypes;
		 $this->colos_dysplasie = $colos_dysplasie;
		 $this->rappel_colos_period = $rappel_colos_period;
		 $this->sortir_rappel = $sortir_rappel;
		 $this->raison_sortie = $raison_sortie;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->date." ".
			$this->ant_pere_type." ".
			$this->ant_pere_age." ".
			$this->ant_mere_type." ".
			$this->ant_mere_age." ".
			$this->ant_fratrie_type." ".
			$this->ant_fratrie_age." ".
			$this->ant_collat_type." ".
			$this->ant_collat_age." ".
			$this->ant_enfants_type." ".
			$this->ant_enfants_age." ".
			$this->just_ant_fam." ".
			$this->just_ant_polype." ".
			$this->just_ant_cr_colique." ".
			$this->just_ant_sg_selles." ".
			$this->colos_date." ".
			$this->colos_polypes." ".
			$this->colos_dysplasie." ".
			$this->rappel_colos_period." ".
			$this->sortir_rappel." ".
			$this->raison_sortie;
	}

	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->date = dateToMysqlDate($clone->date);
		$clone->colos_date = dateToMysqlDate($clone->colos_date);
		return $clone;
	}

	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->date = mysqlDateTodate($clone->date);
		$clone->colos_date = mysqlDateTodate($clone->colos_date);
		return $clone;
	}


	function check(){
		$errors = array();
		$i = 0;
		if(!isValidDate($this->date)) $errors[$i++] = "La date du dépistage est invalide";
		if(strlen($this->ant_pere_age)!=0) {
			if ($this->ant_pere_age > 150 or $this->ant_pere_age <= 0) $errors[$i++] = "L'age du père est invalide";
		}
		else $this->ant_pere_age =  NULL;
		
		if(strlen($this->ant_mere_age)!=0){	
			if($this->ant_mere_age > 150 or $this->ant_mere_age <= 0) $errors[$i++] = "L'age de la mère est invalide";
		}
		else $this->ant_mere_age = NULL;
		
		if(strlen($this->ant_fratrie_age)!=0){
			if($this->ant_fratrie_age > 150 or $this->ant_fratrie_age <= 0) $errors[$i++] = "L'age du frère ou soeur est invalide";
		}
		else $this->ant_fratrie_age = NULL;
		
		if(strlen($this->ant_collat_age)!=0){		
			if($this->ant_collat_age > 150 or $this->ant_collat_age <= 0) $errors[$i++] = "L'age de l'oncle ou tante est invalide";
		}
		else $this->ant_collat_age = NULL;
		
		if(strlen($this->ant_enfants_age)!=0){
			if($this->ant_enfants_age > 150 or $this->ant_enfants_age <= 0) $errors[$i++] = "L'age de l'enfant est invalide";
		}
		else $this->ant_enfants_age = NULL;
		
		if (($this-> colos_date)!=false)
		{
			if(!isValidDate($this->colos_date)) $errors[$i++] = "La date de la coloscopie est invalide";
		}
		if($this->rappel_colos_period < 0) $errors[$i++] = "La date du dépistage est invalide";
		return $errors;
	}
	
	function isOutdated($month =0){
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->colos_date;
			if(is_null($elderDate)) return false;
			if($elderDate == "") return false;
			if($this->rappel_colos_period==0)
			    return false;

			if(strpos($this->rappel_colos_period, ".")===false){
			    $annee=$this->rappel_colos_period;
			    $mois=0;
			}
			else{
				list($annee, $mois)=explode(".", $this->rappel_colos_period);
				$mois=$mois*12/10;
				
				while($mois>12){
					$mois=$mois/10;
				}
				$mois=round($mois);
			}

			$refDate = increaseDateBy($elderDate,0,$mois,$annee);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);

			if(compare($refDate,$actualDate)>0) return false;
	
			return $refDate;
	    }
	}

}
 ?>
