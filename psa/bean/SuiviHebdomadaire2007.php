<?php
class SuiviHebdomadaire2007{
	  var $cabinet;
	  var $date;
	  var $info_asalee;
	  var $info_dossiermed;
	  var $nb_consult_suividiab;
	  var $tps_consult_suividiab;
	  var $nb_consult_depdiab;
	  var $tps_consult_depdiab;
	  var $nb_consult_depcancer;
	  var $tps_consult_depcancer;
	  var $nb_consult_memoire;
	  var $tps_consult_memoire;
	  var $nb_consult_autota;
	  var $tps_consult_autota;
	  var $nb_consult_hta;
	  var $tps_consult_hta;
	  var $nb_consult_autre;
	  var $tps_consult_autre;
	  var $ecg;
	  var $autoformation;
	  var $formation;
	  var $stagiaires;
	  var $reunion;
	  var $telephone;
	  var $autre;


	function SuiviHebdomadaire(
					 $cabinet = "",
					 $date = "",
					 $info_asalee = 0,
				  	 $info_dossiermed = 0,
				     $nb_consult_suividiab = 0,
				  	 $tps_consult_suividiab = 0,
				  	 $nb_consult_depdiab = 0,
				  	 $tps_consult_depdiab = 0,
				  	 $nb_consult_depcancer = 0,
				  	 $tps_consult_depcancer = 0,
				  	 $nb_consult_memoire = 0,
				  	 $tps_consult_memoire = 0,
				  	 $nb_consult_autota = 0,
				  	 $tps_consult_autota = 0,
				  	 $nb_consult_hta = 0,
				  	 $tps_consult_hta = 0,
				  	 $nb_consult_autre = 0,
				  	 $tps_consult_autre = 0,
				  	 $ecg = 0,
				  	 $autoformation = 0,
				  	 $formation = 0,
				  	 $stagiaires = 0,
				  	 $reunion = 0,
				  	 $telephone = 0,
				  	 $autre = 0){
		 $this->cabinet=$cabinet;
		 $this->date = $date;
		 $this->info_asalee = $info_asalee;
		 $this->info_dossiermed = $inf_dossiermed;
		 $this->nb_consult_suividiab = $nb_consult_suividiab;
		 $this->tps_consult_suividiab = $tps_consult_suividiab;
		 $this->nb_consult_depdiab = $nb_consult_depdiab;
		 $this->tps_consult_depdiab = $tps_consult_depdiab;
		 $this->nb_consult_depcancer = $nb_consult_depcancer;
		 $this->tps_consult_depcancer = $tps_consult_depcancer;
		 $this->nb_consult_memoire = $nb_consult_memoire;
		 $this->tps_consult_memoire = $tps_consult_memoire;
		 $this->nb_consult_autota = $nb_consult_autota;
		 $this->tps_consult_autota = $tps_consult_autota;
		 $this->nb_consult_hta = $nb_consult_hta;
		 $this->tps_consult_hta = $tps_consult_hta;
		 $this->nb_consult_autre = $nb_consult_autre;
		 $this->tps_consult_autre = $tps_consult_autre;
		 $this->ecg = $ecg;
		 $this->autoformation = $autoformation;
		 $this->formation = $formation;
		 $this->stagiaires = $stagiaires;
		 $this->reunion = $reunion;
		 $this->telephone = $telephone;
		 $this->autre = $autre;
	}

	 function toString(){
		 return 
			 $this->cabinet." ".
			 $this->date." ".
			 $this->info_asalee." ".
			 $this->info_dossiermed." ".
			 $this->nb_consult_suividiab." ".
			 $this->tps_consult_suividiab." ".
			 $this->nb_consult_depdiab." ".
			 $this->tps_consult_depdiab." ".
			 $this->nb_consult_depcancer." ".
			 $this->tps_consult_depcancer." ".
			 $this->nb_consult_memoire." ".
			 $this->tps_consult_memoire." ".
			 $this->nb_consult_autota." ".
			 $this->tps_consult_autota." ".
			 $this->nb_consult_hta." ".
			 $this->tps_consult_hta." ".
			 $this->nb_consult_autre." ".
			 $this->tps_consult_autre." ".
			 $this->ecg." ".
			 $this->autoformation." ".
			 $this->formation." ".
			 $this->stagiaires." ".
			 $this->reunion." ".
			 $this->telephone." ".
			 $this->autre;
	}
	
	function getTotal(){
		return
			 $this->info_asalee +
			 $this->info_dossiermed +
			 $this->tps_consult_suividiab +
			 $this->tps_consult_depdiab +
			 $this->tps_consult_depcancer +
			 $this->tps_consult_memoire +
			 $this->tps_consult_autota +
			 $this->tps_consult_hta +
			 $this->tps_consult_autre +
			 $this->ecg +
			 $this->autoformation +
			 $this->formation +
			 $this->stagiaires +
			 $this->reunion +
			 $this->telephone +
			 $this->autre;

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
	
	function check(){
		$errors = array();
		$i = 0;
		
		if(!isValidDate($this->date)) $errors[$i++] = "La date du suivi est invalide";

		if((!empty($this->info_asalee))&&(!is_numeric($this->info_asalee)))$errors[$i++]="Le nombre d'heures de travail informatique sur Asalée est invalide";

		if((!empty($this->info_dossiermed))&&(!is_numeric($this->info_dossiermed))) $errors[$i++]="Le nombre d'heures de travail informatique sur les dossiers médicaux est invalide";

		if((!empty($this->tps_consult_suividiab))&&(!is_numeric($this->tps_consult_suividiab))) $errors[$i++]="Le nombre d'heures de consultation de suivi Diabète est invalide";

		if((!empty($this->tps_consult_depdiab))&&(!is_numeric($this->tps_consult_depdiab))) $errors[$i++]="Le nombre d'heures de consultation de dépistage diabète est invalide";

		if((!empty($this->tps_consult_depcancer))&&(!is_numeric($this->tps_consult_depcancer))) $errors[$i++]="Le nombre d'heures de consultation de dépistage cancer est invalide";

		if((!empty($this->tps_consult_memoire))&&(!is_numeric($this->tps_consult_memoire))) $errors[$i++]="Le nombre d'heures de consultation de tests de mémoire est invalide";

		if((!empty($this->tps_consult_autota))&&(!is_numeric($this->tps_consult_autota))) $errors[$i++]="Le nombre d'heures de consultation d'auto-mesure TA est invalide";

		if((!empty($this->tps_consult_hta))&&(!is_numeric($this->tps_consult_hta))) $errors[$i++]="Le nombre d'heures de consultation de HTA est invalide";

		if((!empty($this->tps_consult_autre))&&(!is_numeric($this->tps_consult_autre))) $errors[$i++]="Le nombre d'heures d'autres consultation  est invalide";

		if((!empty($this->ecg))&&(!is_numeric($this->ecg))) $errors[$i++]="Le nombre d'heures d'ECG est invalide";

		if((!empty($this->autoformation))&&(!is_numeric($this->autoformation))) $errors[$i++]="Le nombre d'heures d'autoformation est invalide";

		if((!empty($this->formation))&&(!is_numeric($this->formation))) $errors[$i++]="Le nombre d'heures de formation est invalide";

		if((!empty($this->stagiaire))&&(!is_numeric($this->stagiaire))) $errors[$i++]="Le nombre d'heures d'encadrement de stagiaires est invalide";

		if((!empty($this->reunion))&&(!is_numeric($this->reunion))) $errors[$i++]="Le nombre d'heures de réunion est invalide";

		if((!empty($this->telephone))&&(!is_numeric($this->telephone))) $errors[$i++]="Le nombre d'heures de téléphone est invalide";

		if((!empty($this->autres))&&(!is_numeric($this->autres))) $errors[$i++]="Le nombre d'heures \"autres\" est invalide";

		if(!empty($this->nb_consult_suividiab) &&
			(empty($this->tps_consult_suividiab)))
				$errors[$i++]="S'il y a des consultations de suivi diabète, précisez le temps qu'elles ont duré";
				
		if(empty($this->nb_consult_suividiab) &&
			(!empty($this->tps_consult_suividiab)))
				$errors[$i++]="S'il y a des consultations de suivi diabète, précisez leur nombre";

		if(!empty($this->nb_consult_depdiab) &&
			(empty($this->tps_consult_depdiab)))
				$errors[$i++]="S'il y a des consultations de dépistage diabète, précisez le temps qu'elles ont duré";

		if(empty($this->nb_consult_depdiab) &&
			(!empty($this->tps_consult_depdiab)))
				$errors[$i++]="S'il y a des consultations de dépistage diabète, précisez leur nombre";

		if(!empty($this->nb_consult_depcancer) &&
			(empty($this->tps_consult_depcancer)))
				$errors[$i++]="S'il y a des consultations de dépistage cancer, précisez le temps qu'elles ont duré";

		if(empty($this->nb_consult_depcancer) &&
			(!empty($this->tps_consult_depcancer)))
				$errors[$i++]="S'il y a des consultations de dépistage cancer, précisez leur nombre";

		if(!empty($this->nb_consult_memoire) &&
			(empty($this->tps_consult_memoire)))
				$errors[$i++]="S'il y a des consultations de test mémoire, précisez le temps qu'elles ont duré";

		if(empty($this->nb_consult_memoire) &&
			(!empty($this->tps_consult_memoire)))
				$errors[$i++]="S'il y a des consultations de test mémoire, précisez leur nombre";

		if(!empty($this->nb_consult_autota) &&
			(empty($this->tps_consult_autota)))
				$errors[$i++]="S'il y a des consultations d'auto-mesure TA, précisez le temps qu'elles ont duré";

		if(empty($this->nb_consult_autota) &&
			(!empty($this->tps_consult_autota)))
				$errors[$i++]="S'il y a des consultations d'auto-mesure TA, précisez leur nombre";

		if(!empty($this->nb_consult_hta) &&
			(empty($this->tps_consult_hta)))
				$errors[$i++]="S'il y a des consultations de HTA, précisez le temps qu'elles ont duré";

		if(empty($this->nb_consult_hta) &&
			(!empty($this->tps_consult_hta)))
				$errors[$i++]="S'il y a des consultations de hta, précisez leur nombre";

		if(!empty($this->nb_consult_autre) &&
			(empty($this->tps_consult_autre)))
				$errors[$i++]="S'il y a d'autres consultations, précisez le temps qu'elles ont duré";

		if(empty($this->nb_consult_autre) &&
			(!empty($this->tps_consult_autre)))
				$errors[$i++]="S'il y a d'autres consultations, précisez leur nombre";
				
		return $errors;
	}
}
 ?>
