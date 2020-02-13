<?php

require_once("tools/date.php");

class Hemocult{
	  var $id;
	  var $date;
	  var $date_convoc;
	  var $date_plaquette;
	  var $date_resultat;
	  var $resultat;
	  var $rappel;
	  var $date_rappel;
	  var $sortir_rappel;
	  var $raison_sortie;


	function ListeHemocult(
					 $id = NULL,
					 $date = NULL,
					 $date_convoc = NULL,
					 $date_plaquette = NULL,
					 $date_resultat = NULL,
					 $resultat = NULL,
					 $date_rappel = NULL,
					 $rappel = NULL,
					 $sortir_rappel = NULL,
					 $raison_sortie = NULL){
		 $this->id = $id;
		 $this->date = $date;
		 $this->date_convoc = $date_convoc;
		 $this->date_plaquette = $date_plaquette;
		 $this->date_resultat = $date_resultat;
		 $this->resultat = $resultat;
		 $this->date_rappel = $date_rappel;
		 $this->rappel = $rappel;
		 $this->sortir_rappel = $sortir_rappel;
		 $this->raison_sortie = $raison_sortie;
		 
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->date." ".
			$this->date_convoc." ".
			$this->date_plaquette." ".
			$this->date_resultat." ".
			$this->resultat." ".
			$this->date_rappel." ".
			$this->rappel." ".
			$this->sortir_rappel." ".
			$this->raison_sortie;
	}

	function check(){
		$errors = array();
		$i = 0;
		if(!isValidDate($this->date)) $errors[$i++] = "La date du dépistage est invalide";
		
		if($this->date_convoc!=''){
			if(!isValidDate($this->date_convoc)) $errors[$i++] = "La date de convocation est invalide";
		}

		if($this->date_plaquette!=''){
			if(!isValidDate($this->date_plaquette)) $errors[$i++] = "La date de remise des plaquettes est invalide";
		}
		
		if(($this->date_resultat!='')||($this->resultat=='1')||($this->resultat=='0')){
			if(!isValidDate($this->date_resultat)) $errors[$i++] = "La date de résultat est invalide";
			
			if(($this->resultat!='1')&&($this->resultat!='0')){
			    $errors[$i++]="Veuillez préciser si le résultat est positif ou négatif";
			}
		}

		if($this->date_rappel!=''){
			if(!isValidDateFuture($this->date_rappel, $this->date_resultat)) $errors[$i++] = "La date de rappel est invalide";
		}
		
		return $errors;
	}
	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->date = dateToMysqlDate($clone->date);
		$clone->date_convoc = dateToMysqlDate($clone->date_convoc);
		$clone->date_plaquette = dateToMysqlDate($clone->date_plaquette);
		$clone->date_resultat = dateToMysqlDate($clone->date_resultat);
		$clone->date_rappel = dateToMysqlDate($clone->date_rappel);
		return $clone;
	}

	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->date = mysqlDateTodate($clone->date);
		$clone->date_convoc = mysqlDateTodate($clone->date_convoc);
		$clone->date_plaquette = mysqlDateTodate($clone->date_plaquette);
		$clone->date_resultat = mysqlDateTodate($clone->date_resultat);
		$clone->date_rappel = mysqlDateTodate($clone->date_rappel);
		return $clone;
	}
	function isOutdated($month =0){
	
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->date_rappel;
// print_r($this);
			if(is_null($elderDate)) return false;
			if($elderDate == "") return false;

/*			if($this->rappel==0){echo "ko3";
			    return false;}
*/
			// $annee=$this->rappel;
			// $mois=0;

			// $refDate = increaseDateBy($elderDate,0,$mois,$annee);
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
