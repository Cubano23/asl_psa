<?php
require_once("tools/date.php");


class SevrageTabac {
	  
		var $id;
		var $date;
		var $numero;
		var $tabac;
		var $nbrtabac;
		var $type_tabac;
		var $ddebut;
		var $darret;
		var $spirometrie_date;
		var $spirometrie_CVF;
		var $spirometrie_VEMS;
		var $spirometrie_DEP;
		var $spirometrie_status;
		var $spirometrie_rapport_VEMS_CVF;
		var $co_test;
		var $co_ppm;
		var $fagerstrom;
		var $horn_stimulation;
		var $horn_plaisir;
		var $horn_relaxation;
		var $horn_anxiete;
		var $horn_besoin;
		var $horn_habitude;
		var $had_anxiete;
		var $had_depression;
		var $echelle_analogique;
		var $echelle_confiance;
		var $stade_motivationnel;
		var $poids;
	  	var $dpoids;
	  	var $activite;
	  	var $alcool;
	  	var $aspects_limitants;
		var $aspects_facilitants;
		var $objectifs_patient;
		var $diagnostic_educatif;
		

	  

	function SevrageTabac(){

		$this->id = NULL;
		$this->date = NULL;
		$this->numero = NULL;
		$this->tabac = NULL;
		$this->nbrtabac = NULL;
		$this->type_tabac = NULL;
		$this->ddebut = NULL;
		$this->darret = NULL;
		$this->spirometrie_date = NULL;
		$this->spirometrie_CVF = NULL;
		$this->spirometrie_VEMS = NULL;
		$this->spirometrie_DEP = NULL;
		$this->spirometrie_status = NULL;
		$this->spirometrie_rapport_VEMS_CVF = NULL;
		$this->dco_test = NULL;
		$this->co_ppm = NULL;
		$this->fagerstrom = NULL;
		$this->horn_stimulation = NULL;
		$this->horn_plaisir = NULL;
		$this->horn_relaxation = NULL;
		$this->horn_anxiete = NULL;
		$this->horn_besoin = NULL;
		$this->horn_habitude = NULL;
		$this->had_anxiete = NULL;
		$this->had_depression = NULL;
		$this->echelle_analogique = NULL;
		$this->echelle_confiance = NULL;
		$this->stade_motivationnel = NULL;
		$this->poids = NULL;
		$this->dpoids = NULL;
		$this->activite = NULL;
		$this->alcool = NULL;
		$this->aspects_limitants = NULL;
		$this->aspects_facilitants = NULL;
		$this->objectifs_patient = NULL;

			$this->id = $id;
			$this->date = $date;
			$this->numero = $numero;
			$this->tabac = $tabac;
			$this->nbrtabac = $nbrtabac;
			$this->type_tabac = $type_tabac;
			$this->ddebut = $ddebut;
			$this->darret = $darret;
			$this->spirometrie_date = $spirometrie_date;
			$this->spirometrie_CVF = $spirometrie_CVF;
			$this->spirometrie_VEMS = $spirometrie_VEMS;
			$this->spirometrie_DEP = $spirometrie_DEP;
			$this->spirometrie_status = $spirometrie_status;
			$this->spirometrie_rapport_VEMS_CVF = $spirometrie_rapport_VEMS_CVF;
			$this->dco_test = $dco_test;
			$this->co_ppm = $co_ppm;
			$this->fagerstrom = $fagerstrom;
			$this->horn_stimulation = $horn_stimulation;
			$this->horn_plaisir = $horn_plaisir;
			$this->horn_relaxation = $horn_relaxation;
			$this->horn_anxiete = $horn_anxiete;
			$this->horn_besoin = $horn_besoin;
			$this->horn_habitude = $horn_habitude;
			$this->had_anxiete = $had_anxiete;
			$this->had_depression = $had_depression;
			$this->echelle_analogique = $echelle_analogique;
			$this->echelle_confiance = $echelle_confiance;
			$this->stade_motivationnel = $stade_motivationnel;
			$this->poids = $poids;
			$this->dpoids = $dpoids;
			$this->activite = $activite;
			$this->alcool = $alcool;
			$this->aspects_limitants = $aspects_limitants;
			$this->aspects_facilitants = $aspects_facilitants;
			$this->objectifs_patient = $objectifs_patient;
		
	}


	


}
 
