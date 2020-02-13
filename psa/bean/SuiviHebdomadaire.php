<?php
class SuiviHebdomadaire{
	  var $cabinet;
	  var $date;
	  var $travail_base_h;
	  var $consult_indiv_h;
	  var $consult_indiv_n;
	  var $prevention_diabete_h;
	  var $prevention_autre_h;
	  var $prevention_autre_note;
	  var $seance_diabete_h;
	  var $seance_autre_h;
	  var $seance_autre_note;
	  var $suivi_armoire_h;
	  var $suivi_armoire_n;
	  var $aide_telephone;
	  var $aide_prep_matos;
	  var $aide_examen_compl;
	  var $aide_soins;
	  var $aide_formation;
	  var $aide_autre;


	function SuiviHebdomadaire(
					 $cabinet = "",
					 $date = "",
					 $travail_base_h = 0,
					 $consult_indiv_h = 0,
					 $consult_indiv_n = 0,
					 $prevention_diabete_h = 0,
					 $prevention_autre_h = 0,
					 $prevention_autre_note = 0,
					 $seance_diabete_h = 0,
					 $seance_autre_h = 0,
					 $seance_autre_note = 0,
					 $suivi_armoire_h = 0,
					 $suivi_armoire_n = 0,
					 $aide_telephone = 0,
					 $aide_prep_matos = 0,
					 $aide_examen_compl = 0,
					 $aide_soins = 0,
					 $aide_formation = 0,
					 $aide_autre = 0){
		 $this->cabinet=$cabinet;
		 $this->date = $date;
		 $this->travail_base_h = $travail_base_h;
		 $this->consult_indiv_h = $consult_indiv_h;
		 $this->consult_indiv_n = $consult_indiv_n;
		 $this->prevention_diabete_h = $prevention_diabete_h;
		 $this->prevention_autre_h = $prevention_autre_h;
		 $this->prevention_autre_note = $prevention_autre_note;
		 $this->seance_diabete_h = $seance_diabete_h;
		 $this->seance_autre_h = $seance_autre_h;
		 $this->seance_autre_note = $seance_autre_note;
		 $this->suivi_armoire_h = $suivi_armoire_h;
		 $this->suivi_armoire_n = $suivi_armoire_n;
		 $this->aide_telephone = $aide_telephone;
		 $this->aide_prep_matos = $aide_prep_matos;
		 $this->aide_examen_compl = $aide_examen_compl;
		 $this->aide_soins = $aide_soins;
		 $this->aide_formation = $aide_formation;
		 $this->aide_autre = $aide_autre;
	}

	 function toString(){
		 return 
			$this->cabinet." ".
			$this->date." ".
			$this->travail_base_h." ".
			$this->consult_indiv_h." ".
			$this->consult_indiv_n." ".
			$this->prevention_diabete_h." ".
			$this->prevention_autre_h." ".
			$this->prevention_autre_note." ".
			$this->seance_diabete_h." ".
			$this->seance_autre_h." ".
			$this->seance_autre_note." ".
			$this->suivi_armoire_h." ".
			$this->suivi_armoire_n." ".
			$this->aide_telephone." ".
			$this->aide_prep_matos." ".
			$this->aide_examen_compl." ".
			$this->aide_soins." ".
			$this->aide_formation." ".
			$this->aide_autre;
	}
	
	function getTotal(){
		return 	$this->travail_base_h +
				$this->consult_indiv_h +
				$this->prevention_diabete_h +
				$this->prevention_autre_h +
				$this->seance_diabete_h +
				$this->seance_autre_h +
				$this->suivi_armoire_h +
				$this->aide_telephone +
				$this->aide_prep_matos +
				$this->aide_examen_compl +
				$this->aide_soins +
				$this->aide_formation +
				$this->aide_autre;
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
	
	function check(){}
}
 ?>
