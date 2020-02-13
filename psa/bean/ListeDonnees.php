<?php

require_once("tools/date.php");

class ListeDonnees{
	  var $cabinet;
	  var $antecedants;
	  var $Chol;
	  var $dChol;
	  var $HDL;
	  var $dHDL;
	  var $LDL;
	  var $dLDL;
	  var $triglycerides;
	  var $dtriglycerides;
	  var $traitement;
	  var $dosage;
	  var $HTA;
	  var $TaSys;
	  var $TaDia;
	  var $dTA;
	  var $hypertenseur3;
	  var $automesure;
	  var $diuretique;
	  var $HVG;
	  var $surcharge_ventricule;
	  var $sokolov;
	  var $dsokolov;
	  var $Creat;
	  var $dCreat;
	  var $kaliemie;
	  var $dkaliemie;
	  var $proteinurie;
	  var $dproteinurie;
	  var $hematurie;
	  var $dhematurie;
	  var $dFond;
	  var $dECG;
	  var $tabac;
	  var $darret;
	  var $poids;
	  var $dpoids;
	  var $activite;
	  var $pouls;
	  var $dpouls;
	  var $alcool;
	  var $glycemie;
	  var $dgly;
	  var $exam_cardio;


	function ListeDonnees(
					 $cabinet = NULL,
					 $antecedants = NULL,
					 $Chol = NULL,
					 $dChol = NULL,
					 $HDL = NULL,
					 $dHDL = NULL,
					 $LDL = NULL,
					 $dLDL = NULL,
					 $triglycerides = NULL,
					 $dtriglycerides = NULL,
					 $traitement = NULL,
					 $dosage = NULL,
					 $HTA = NULL,
					 $TaSys = NULL,
					 $TaDia = NULL,
					 $dTA = NULL,
					 $hypertenseur3 = NULL,
					 $automesure = NULL,
					 $diuretique = NULL,
					 $HVG = NULL,
					 $surcharge_ventricule = NULL,
					 $sokolov = NULL,
					 $dsokolov = NULL,
					 $Creat = NULL,
					 $dCreat = NULL,
					 $kaliemie = NULL,
					 $dkaliemie = NULL,
					 $proteinurie = NULL,
					 $dproteinurie = NULL,
					 $hematurie = NULL,
					 $dhematurie = NULL,
					 $dFond = NULL,
					 $dECG = NULL,
					 $tabac = NULL,
					 $darret = NULL,
					 $poids = NULL,
					 $dpoids = NULL,
					 $activite = NULL,
					 $pouls = NULL,
					 $dpouls = NULL,
					 $alcool = NULL,
					 $glycemie = NULL,
					 $dgly = NULL,
					 $exam_cardio = NULL){
		 $this->cabinet = $cabinet;
		 $this->antecedants = $antecedants;
		 $this->Chol = $Chol;
		 $this->dChol = $dChol;
		 $this->HDL = $HDL;
		 $this->dHDL = $dHDL;
		 $this->LDL = $LDL;
		 $this->dLDL = $dLDL;
		 $this->triglycerides = $triglycerides;
		 $this->dtriglycerides = $dtriglycerides;
		 $this->traitement = $traitement;
		 $this->dosage = $dosage;
		 $this->HTA = $HTA;
		 $this->TaSys = $TaSys;
		 $this->TaDia = $TaDia;
		 $this->dTA = $dTA;
		 $this->hypertenseur3 = $hypertenseur3;
		 $this->automesure = $automesure;
		 $this->diuretique = $diuretique;
		 $this->HVG = $HVG;
		 $this->surcharge_ventricule = $surcharge_ventricule;
		 $this->sokolov = $sokolov;
		 $this->dsokolov = $dsokolov;
		 $this->Creat = $Creat;
		 $this->dCreat = $dCreat;
		 $this->kaliemie = $kaliemie;
		 $this->dkaliemie = $dkaliemie;
		 $this->proteinurie = $proteinurie;
		 $this->dproteinurie = $dproteinurie;
		 $this->hematurie = $hematurie;
		 $this->dhematurie = $dhematurie;
		 $this->dFond = $dFond;
		 $this->dECG = $dECG;
		 $this->tabac = $tabac;
		 $this->darret = $darret;
		 $this->poids = $poids;
		 $this->dpoids = $dpoids;
		 $this->activite = $activite;
		 $this->pouls = $pouls;
		 $this->dpouls = $dpouls;
		 $this->alcool = $alcool;
		 $this->glycemie = $glycemie;
		 $this->dgly = $dgly;
		 $this->exam_cardio = $exam_cardio;
					 
	}

	 function toString(){
		 return 
			$this->cabinet." ".
			$this->antecedants." ".
			$this->Chol." ".
			$this->dChol." ".
			$this->HDL." ".
			$this->dHDL." ".
			$this->LDL." ".
			$this->dLDL." ".
			$this->triglycerides." ".
			$this->dtriglycerides." ".
			$this->traitement." ".
			$this->dosage." ".
			$this->HTA." ".
			$this->TaSys." ".
			$this->TaDia." ".
			$this->dTA." ".
			$this->hypertenseur3." ".
			$this->automesure." ".
			$this->diuretique." ".
			$this->HVG." ".
			$this->surcharge_ventricule." ".
			$this->sokolov." ".
			$this->dsokolov." ".
			$this->Creat." ".
			$this->dCreat." ".
			$this->kaliemie." ".
			$this->dkaliemie." ".
			$this->proteinurie." ".
			$this->dproteinurie." ".
			$this->hematurie." ".
			$this->dhematurie." ".
			$this->dFond." ".
			$this->dECG." ".
			$this->tabac." ".
			$this->darret." ".
			$this->poids." ".
			$this->dpoids." ".
			$this->activite." ".
			$this->pouls." ".
			$this->dpouls." ".
			$this->alcool." ".
			$this->glycemie." ".
			$this->dgly." ".
			$this->exam_cardio;
	}


	function check(){
		$errors = array();
		$i = 0;

		return $errors;
	}
	
	
	function beforeSerialisation($account){
		$clone = clone $this;
		
		return $clone;
	}

	function afterDeserialisation($account){
		$clone = clone $this;

		return $clone;
	}

}
 ?>
