<?php
class EvalContinue{
	  var $id;
	  var $numero_eval;
	  var $date;
	  var $suivi;
	  var $causes;
	  var $terminologie;
	  var $comprendre_traitement;
	  var $appliquer_traitement;
	  var $risques;
	  var $gravite;
	  var $mesures;
	  var $appliquer;
	  var $connaitre_equilibre;
	  var $appliquer_equilibre;
	  var $activite;
	  var $autre;


	function EvalContinue(
					 $id = "",
					 $numero_eval = "",
					 $date = "",
					 $suivi = "",
					 $causes = "",
					 $terminologie = "",
					 $comprendre_traitement = "",
					 $appliquer_traitement = "",
					 $risques = "",
					 $gravite = "",
					 $mesures = "",
					 $appliquer = "",
					 $connaitre_equilibre = "",
					 $appliquer_equilibre = "",
					 $activite = "",
					 $autre = ""
					 ){
		 $this->id = $id;
		 $this->numero_eval = $numero_eval;
		 $this->date = $date;
		 $this->suivi = $suivi;
		 $this->causes = $causes;
		 $this->terminologie = $terminologie;
		 $this->comprendre_traitement = $comprendre_traitement;
		 $this->appliquer_traitement = $appliquer_traitement;
		 $this->risques = $risques;
		 $this->gravite = $gravite;
		 $this->mesures = $mesures;
		 $this->appliquer = $appliquer;
		 $this->connaitre_equilibre = $connaitre_equilibre;
		 $this->appliquer_equilibre = $appliquer_equilibre;
		 $this->activite = $activite;
		 $this->autre = $autre;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->numero_eval." ".
			$this->date." ".
			$this->suivi." ".
			$this->causes." ".
			$this->terminologie." ".
			$this->comprendre_traitement." ".
			$this->appliquer_traitement." ".
			$this->risques." ".
			$this->gravite." ".
			$this->mesures." ".
			$this->appliquer." ".
			$this->connaitre_equilibre." ".
			$this->appliquer_equilibre." ".
			$this->activite." ".
			$this->autre;

	}

	function check(){
		if($this->date!=""){
			if(!isValidDate($this->date)) $errors[$i++] = "La date de l'évaluation continue d'éducation est invalide";
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
