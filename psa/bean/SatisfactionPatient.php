<?php
class SatisfactionPatient{
	  var $demande_consult;
	  var $motif_diabete;
	  var $motif_depistage;
	  var $motif_automesure;
	  var $motif_autre;
	  var $motif_rcva;
	  var $motif_hemoccult;
	  var $conseils_alimentaires;
	  var $adapter_vie_sante;
	  var $conseils_realisables;
	  var $compris_conseils;
	  var $qualite_conseils;
	  var $repondu_questions;
	  var $informations_ignorees;
	  var $temps_ecoute;
	  var $aise;
	  var $satisf_consult;
	  var $suivi_conseils;
	  var $concerne_sante;
	  var $revoir_inf;
	  var $commentaire;


	function SuiviHebdomadaire(
					 $demande_consult = NULL,
					 $motif_diabete = NULL,
					 $motif_depistage = NULL,
					 $motif_automesure = NULL,
					 $motif_autre = NULL,
					 $motif_rcva = NULL,
					 $motif_hemoccult = NULL,
					 $conseils_alimentaires = NULL,
					 $adapter_vie_sante = NULL,
					 $conseils_realisables = NULL,
					 $compris_conseils = NULL,
					 $qualite_conseils = NULL,
					 $repondu_questions = NULL,
					 $informations_ignorees = NULL,
					 $temps_ecoute = NULL,
					 $aise = NULL,
					 $satisf_consult = NULL,
					 $suivi_conseils = NULL,
					 $concerne_sante = NULL,
					 $revoir_inf = NULL,
					 $commentaire = NULL
					 ){
		 $this->demande_consult=$demande_consult;
		 $this->motif_diabete = $motif_diabete;
		 $this->motif_depistage = $motif_depistage;
		 $this->motif_automesure = $motif_automesure;
		 $this->motif_autre = $motif_autre;
		 $this->motif_rcva = $motif_rcva;
		 $this->motif_hemoccult = $motif_hemoccult;
		 $this->conseils_alimentaires = $conseils_alimentaires;
		 $this->adapter_vie_sante = $adapter_vie_sante;
		 $this->conseils_realisables = $conseils_realisables;
		 $this->compris_conseils = $compris_conseils;
		 $this->qualite_conseils = $qualite_conseils;
		 $this->repondu_questions = $repondu_questions;
		 $this->informations_ignorees = $informations_ignorees;
		 $this->temps_ecoute = $temps_ecoute;
		 $this->aise = $aise;
		 $this->satisf_consult = $satisf_consult;
		 $this->suivi_conseils = $suivi_conseils;
		 $this->concerne_sante = $concerne_sante;
		 $this->revoir_inf = $revoir_inf;
		 $this->commentaire = $commentaire;
	}

	 function toString(){
		 return 
			 $this->demande_consult." ".
			 $this->motif_diabete." ".
			 $this->motif_depistage." ".
			 $this->motif_automesure." ".
			 $this->motif_autre." ".
			 $this->motif_rcva." ".
			 $this->motif_hemoccult." ".
			 $this->conseils_alimentaires." ".
			 $this->adapter_vie_sante." ".
			 $this->conseils_realisables." ".
			 $this->compris_conseils." ".
			 $this->qualite_conseils." ".
			 $this->repondu_questions." ".
			 $this->informations_ignorees." ".
			 $this->temps_ecoute." ".
			 $this->aise." ".
			 $this->satisf_consult." ".
			 $this->suivi_conseils." ".
			 $this->concerne_sante." ".
			 $this->revoir_inf." ".
			 $this->commentaire;
	}
	

	function beforeSerialisation($account){
		$clone = clone $this;
		return $clone;
	}
		
	function afterDeserialisation($account){
		$clone = clone $this;
		return $clone;
	}
	
	function check(){}
}
 ?>
