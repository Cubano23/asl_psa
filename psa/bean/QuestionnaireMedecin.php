<?php
class QuestionnaireMedecin{
	  var $medecin;
	  var $nom;
	  var $prenom;
	  var $implic_initiation;
	  var $commentaire_implic_initiation;
	  var $implic_conception;
	  var $commentaire_implic_conception;
	  var $implic_recueil;
	  var $commentaire_implic_recueil;
	  var $implic_analyse;
	  var $commentaire_implic_analyse;
	  var $implic_mise_oeuvre;
	  var $commentaire_implic_mise_oeuvre;
	  var $implic_suivi;
	  var $commentaire_implic_suivi;
	  var $amelioration_pratique;
	  var $note_pratique;
	  var $organisation_soins;
	  var $note_soin;
	  var $utilite_patient;
	  var $note_patient;
//	  var $demarche_faisable;
//	  var $autres_actions;
	  var $satisfaction;
	  var $difficultes;
	  var $ameliorations;


	function QuestionnaireMedecin(
					  $medecin = NULL,
					  $nom = NULL,
					  $prenom = NULL,
					  $implic_initiation = NULL,
					  $commentaire_implic_initiation = NULL,
					  $implic_conception = NULL,
					  $commentaire_implic_conception = NULL,
					  $implic_recueil = NULL,
					  $commentaire_implic_recueil = NULL,
					  $implic_analyse = NULL,
					  $commentaire_implic_analyse = NULL,
					  $implic_mise_oeuvre = NULL,
					  $commentaire_implic_mise_oeuvre = NULL,
					  $implic_suivi = NULL,
					  $commentaire_implic_suivi = NULL,
					  $amelioration_pratique = NULL,
					  $note_pratique = NULL,
					  $organisation_soins = NULL,
					  $note_soin = NULL,
					  $utilite_patient = NULL,
					  $note_patient = NULL,
//					  $demarche_faisable = NULL,
//					  $autres_actions = NULL,
					  $satisfaction = NULL,
					  $difficultes = NULL,
					  $ameliorations = NULL
					 ){
		 $this->medecin = $medecin;
		 $this->nom = $nom;
		 $this->prenom = $prenom;
		 $this->implic_initiation = $implic_initiation;
		 $this->commentaire_implic_initiation = $commentaire_implic_initiation;
		 $this->implic_conception = $implic_conception;
		 $this->commentaire_implic_conception = $commentaire_implic_conception;
		 $this->implic_recueil = $implic_recueil;
		 $this->commentaire_implic_recueil = $commentaire_implic_recueil;
		 $this->implic_analyse = $implic_analyse;
		 $this->commentaire_implic_analyse = $commentaire_implic_analyse;
		 $this->implic_mise_oeuvre = $implic_mise_oeuvre;
		 $this->commentaire_implic_mise_oeuvre = $commentaire_implic_mise_oeuvre;
		 $this->implic_suivi = $implic_suivi;
		 $this->commentaire_implic_suivi = $commentaire_implic_suivi;
		 $this->amelioration_pratique = $amelioration_pratique;
		 $this->note_pratique = $note_pratique;
		 $this->organisation_soins = $organisation_soins;
		 $this->note_soin = $note_soin;
		 $this->utilite_patient = $utilite_patient;
		 $this->note_patient = $note_patient;
//		 $this->demarche_faisable = $demarche_faisable;
//		 $this->autres_actions = $autres_actions;
		 $this->satisfaction = $satisfaction;
		 $this->difficultes = $difficultes;
		 $this->ameliorations = $ameliorations;
	}

	 function toString(){
		 return 
			 $this->medecin." ".
			 $this->nom." ".
			 $this->prenom." ".
			 $this->implic_initiation." ".
			 $this->commentaire_implic_initiation." ".
			 $this->implic_conception." ".
			 $this->commentaire_implic_conception." ".
			 $this->implic_recueil." ".
			 $this->commentaire_implic_recueil." ".
			 $this->implic_analyse." ".
			 $this->commentaire_implic_analyse." ".
			 $this->implic_mise_oeuvre." ".
			 $this->commentaire_implic_mise_oeuvre." ".
			 $this->implic_suivi." ".
			 $this->commentaire_implic_suivi." ".
			 $this->amelioration_pratique." ".
			 $this->note_pratique." ".
			 $this->organisation_soins." ".
			 $this->note_soin." ".
			 $this->utilite_patient." ".
			 $this->note_patient." ".
//			 $this->demarche_faisable." ".
//			 $this->autres_actions." ".
			 $this->satisfaction." ".
			 $this->difficultes." ".
			 $this->ameliorations;
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
