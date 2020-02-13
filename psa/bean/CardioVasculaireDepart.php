<?php

require_once("tools/date.php");

class CardioVasculaireDepart{
	  var $id;
	  var $date;
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
	  var $TA_mode;
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
	  var $nbrtabac;
	  var $darret;
	  var $spirometrie_date;
	  var $spirometrie_CVF;
	  var $spirometrie_VEMS;
	  var $spirometrie_DEP;
	  var $spirometrie_status;
	  var $spirometrie_rapport_VEMS_CVF;
	  var $spirometrie;
	  var $poids;
	  var $dpoids;
	  var $activite;
	  var $pouls;
	  var $dpouls;
	  var $alcool;
	  var $glycemie;
	  var $dgly;
	  var $exam_cardio;
	  var $sortir_rappel;
	  var $raison_sortie;


	function CardioVasculaireDepart(
					 $id = NULL,
					 $date = NULL,
					 $antecedants = NULL,
					 $Chol = NULL,
					 $dChol = NULL,
					 $HDL = NULL,
					 $dHDL = NULL,
					 $LDL = NULL,
					 $dLDL = NULL,
					 $triglycerides = NULL,
					 $dtriglycerides = NULL,
					 $traitement = array(),
					 $dosage = NULL,
					 $HTA = NULL,
					 $TaSys = NULL,
					 $TaDia = NULL,
					 $dTA = NULL,
					 $TA_mode = NULL,
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
					 $nbrtabac = NULL,
					 $darret = NULL,
					 $spirometrie_date = NULL,
	  				 $spirometrie_CVF = NULL,
	  				 $spirometrie_VEMS = NULL,
	  				 $spirometrie_DEP = NULL,
	  				 $spirometrie_status = NULL,
	  				 $spirometrie_rapport_VEMS_CVF = NULL,
	  				 $spirometrie = NULL,
					 $poids = NULL,
					 $dpoids = NULL,
					 $activite = NULL,
					 $pouls = NULL,
					 $dpouls = NULL,
					 $alcool = NULL,
					 $glycemie = NULL,
					 $dgly = NULL,
					 $exam_cardio = NULL,
					 $sortir_rappel = NULL,
					 $raison_sortie = NULL){
		 $this->id = $id;
		 $this->date = $date;
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
		 $this->TA_mode = $TA_mode;
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
		 $this->nbrtabac = $nbrtabac;
		 $this->darret = $darret;
		 $this->spirometrie_date = $spirometrie_date;
		 $this->spirometrie_CVF = $spirometrie_CVF;
		 $this->spirometrie_VEMS = $spirometrie_VEMS;
		 $this->spirometrie_DEP = $spirometrie_DEP;
		 $this->spirometrie_status = $spirometrie_status;
		 $this->spirometrie_rapport_VEMS_CVF = $spirometrie_rapport_VEMS_CVF;
		 $this->spirometrie = $spirometrie;
		 $this->poids = $poids;
		 $this->dpoids = $dpoids;
		 $this->activite = $activite;
		 $this->pouls = $pouls;
		 $this->dpouls = $dpouls;
		 $this->alcool = $alcool;
		 $this->glycemie = $glycemie;
		 $this->dgly = $dgly;
		 $this->exam_cardio = $exam_cardio;
		 $this->sortir_rappel = $sortir_rappel;
		 $this->raison_sortie = $raison_sortie;
					 
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->date." ".
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
			$this->TA_mode." ".
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
			$this->nbrtabac." ".
			$this->darret." ".
			$this->spirometrie_date." ".
		    $this->spirometrie_CVF." ".
		    $this->spirometrie_VEMS." ".
		    $this->spirometrie_DEP." ".
		    $this->spirometrie_status." ".
		    $this->spirometrie_rapport_VEMS_CVF." ".
		    $this->spirometrie." ".
			$this->poids." ".
			$this->dpoids." ".
			$this->activite." ".
			$this->pouls." ".
			$this->dpouls." ".
			$this->alcool." ".
			$this->glycemie." ".
			$this->dgly." ".
			$this->exam_cardio." ".
			$this->sortir_rappel." ".
			$this->raison_sortie;
	}

	function getClearance($dossier){
		require_once("tools/formulas.php");
		return getClearance($dossier->sexe,$this->poids,$dossier->getAge(),$this->Creat);
	}

	function getIMC($taille){		
		require_once("tools/formulas.php");
		return getIMC($this->poids,$taille);
	}

	function check(){
		$errors = array();
		$i = 0;
		if(!isValidDate($this->date)) $errors[$i++] = "La date du dépistage est invalide";		
		
		if($this->dChol == ""){
			if(($this->Chol != "") && ($this->Chol != 0)){
				$errors[$i++] ="Date cholestérol total n'est pas saisie";
			}				
		}
		else if($this->Chol == ""){
			$errors[$i++] ="Cholestérol total n'est pas saisi";
		}
		
		if($this->dChol != "" and $this->Chol != ""){
			if(!is_numeric($this->Chol) or $this->Chol<=0)
				$errors[$i++] ="Cholestérol total invalide";			
			if(!isValidDate($this->dChol))
				$errors[$i++] ="Date du cholestérol total invalide";
		}

	
		if($this->dHDL == ""){
			if(($this->HDL != "") && ($this->HDL != 0)){
				$errors[$i++] ="Date HDL n'est pas saisie";
			}				
		}
		else if($this->HDL == ""){
			$errors[$i++] ="HDL n'est pas saisi";
		}
		
		if($this->dHDL != "" and $this->HDL != ""){
			if(!is_numeric($this->HDL) or $this->HDL<=0)
				$errors[$i++] ="HDL invalide";			
			if(!isValidDate($this->dHDL))
				$errors[$i++] ="Date du HDL invalide";
		}

	
		if($this->dLDL == ""){
			if(($this->LDL != "") && ($this->LDL != 0)){
				$errors[$i++] ="Date LDL n'est pas saisie";
			}				
		}
		else if($this->LDL == ""){
			$errors[$i++] ="LDL n'est pas saisi";
		}
		
		if($this->dLDL != "" and $this->LDL != ""){
			if(!is_numeric($this->LDL) or $this->LDL<=0)
				$errors[$i++] ="LDL invalide";			
			if(!isValidDate($this->dLDL))
				$errors[$i++] ="Date du LDL invalide";
		}


	
		if($this->dtriglycerides == ""){
			if(($this->triglycerides != "") && ($this->triglycerides != 0)){
				$errors[$i++] ="Date triglycérides n'est pas saisie";
			}				
		}
		else if($this->triglycerides == ""){
			$errors[$i++] ="triglycérides n'est pas saisi";
		}
		
		if($this->dtriglycerides != "" and $this->triglycerides != ""){
			if(!is_numeric($this->triglycerides) or $this->triglycerides<=0)
				$errors[$i++] ="triglycérides invalide";			
			if(!isValidDate($this->dtriglycerides))
				$errors[$i++] ="Date des triglycérides invalide";
		}


		if(($this->TaSys!="") || ($this->TaDia!="")||($this->dTA!="")){
			if(!is_numeric($this->TaSys) or $this->TaSys<70 or $this->TaSys>300){
				$errors[$i++]="Systole invalide";
			}
			if(!is_numeric($this->TaDia) or $this->TaDia<35 or $this->TaDia>150){
				$errors[$i++]="Diastole invalide";
			}
			if(!isValidDate($this->dTA)){
				$errors[$i++]="Date de la tension invalide";
			}
		}


		if($this->dsokolov == ""){
			if(($this->sokolov != "") && ($this->sokolov != 0)){
				$errors[$i++] ="Date sokolov n'est pas saisie";
			}				
		}
		else if($this->sokolov == ""){
			$errors[$i++] ="sokolov n'est pas saisi";
		}
		
		if($this->dsokolov != "" and $this->sokolov != ""){
			if(!is_numeric($this->sokolov) or $this->sokolov<=0)
				$errors[$i++] ="sokolov invalide";			
			if(!isValidDate($this->dsokolov))
				$errors[$i++] ="Date du sokolov invalide";
		}

	
		if($this->dCreat == ""){
			if(($this->Creat != "") && ($this->Creat != 0)){
				$errors[$i++] ="Date Créatininémie n'est pas saisie";
			}				
		}
		else if($this->Creat == ""){
			$errors[$i++] ="Créatininémie n'est pas saisi";
		}
		
		if($this->dCreat != "" and $this->Creat != ""){
			if(!is_numeric($this->Creat) or $this->Creat<=0)
				$errors[$i++] ="Créatininémie invalide";			
			if(!isValidDate($this->dCreat))
				$errors[$i++] ="Date de la créatininémie invalide";
		}


	
		if($this->dkaliemie == ""){
			if(($this->kaliemie != "") && ($this->kaliemie != 0)){
				$errors[$i++] ="Date kaliémie n'est pas saisie";
			}				
		}
		else if($this->kaliemie == ""){
			$errors[$i++] ="kaliémie n'est pas saisi";
		}
		
		if($this->dkaliemie != "" and $this->kaliemie != ""){
			if(!is_numeric($this->kaliemie) or $this->kaliemie<=0)
				$errors[$i++] ="kaliémie invalide";			
			if(!isValidDate($this->dkaliemie))
				$errors[$i++] ="Date de la kaliémie invalide";
		}


		if($this->dproteinurie!="" or $this->proteinurie=="1"){
			if(!isValidDate($this->dproteinurie)){
				$errors[$i++]="Date de la protéinurie invalide";
			}
		}
		
		if($this->dhematurie!="" or $this->hematurie=="1"){
			if(!isValidDate($this->dhematurie)){
				$errors[$i++]="Date de l'hématurie invalide";
			}
		}
		
		if($this->dFond!=""){
			if(!isValidDate($this->dFond)){
				$errors[$i++]="Date fond d'oeil invalide";
			}
		}

		if($this->dECG!=""){
			if(!isValidDate($this->dECG)){
				$errors[$i++]="Date ECG invalide";
			}
		}

		if($this->darret!=""){
			if(!isValidDate($this->darret)){
				$errors[$i++]="Date d'arrêt du tabac invalide";
			}
		}


		if($this->spirometrie_date!=""){
			if(!isValidDate($this->spirometrie_date)){
				$errors[$i++]="Date de la spirométrie invalide";
			}
		}

	
		if($this->dpoids == ""){
			if(($this->poids != "") && ($this->poids != 0)){
				$errors[$i++] ="Date du poids n'est pas saisie";
			}				
		}
		else if($this->poids == ""){
			$errors[$i++] ="poids n'est pas saisi";
		}
		
		if($this->dpoids != "" and $this->dpoids != ""){
			if(!is_numeric($this->poids) or $this->poids<=30 or $this->poids>300)
				$errors[$i++] ="poids invalide";			
			if(!isValidDate($this->dpoids))
				$errors[$i++] ="Date du poids invalide";
		}



		if($this->activite!=""){
			if(!is_numeric($this->activite) or $this->activite<0 or $this->activite>100){
				$errors[$i++]="Nombre d'heures d'activité invalide";
			} 
		}


		if($this->dpouls == ""){
			if(($this->pouls != "") && ($this->pouls != 0)){
				$errors[$i++] ="Date de la fréquence cardiaque n'est pas saisie";
			}				
		}
		else if($this->pouls == ""){
			$errors[$i++] ="fréquence cardiaque n'est pas saisi";
		}
		
		if($this->dpouls != "" and $this->pouls != ""){
			if(!is_numeric($this->pouls) or $this->pouls<30 or $this->pouls>300)
				$errors[$i++] ="fréquence cardiaque invalide";			
			if(!isValidDate($this->dpouls))
				$errors[$i++] ="Date de la fréquence cardiaque invalide";
		}

	
		if($this->dgly == ""){
			if(($this->glycemie != "") && ($this->glycemie != 0)){
				$errors[$i++] ="Date glycémie n'est pas saisie";
			}				
		}
		else if($this->glycemie == ""){
			$errors[$i++] ="glycémie n'est pas saisi";
		}
		
		if($this->dgly != "" and $this->glycemie != ""){
			if(!is_numeric($this->glycemie) or $this->glycemie<=0)
				$errors[$i++] ="glycémie invalide";			
			if(!isValidDate($this->dgly))
				$errors[$i++] ="Date de la glycémie invalide";
		}

		if($this->exam_cardio!=""){
			if(!isValidDate($this->exam_cardio)){
				$errors[$i++]="Date de l'examen cardio-vasculaire invalide";
			}
		}

		if($this->nbrtabac!=''){
			if(!is_numeric($this->nbrtabac) or $this->nbrtabac<1 or $this->nbrtabac>100){
				$errors[$i++]="Nombre de paquets est invalide (doit être compris entre 1 et 100)";
			}			
		}


		// if(!is_numeric($this->spirometrie_CVF) or ($this->spirometrie_CVF<1 or $this->spirometrie_CVF>100)){
		if( !empty($this->spirometrie_CVF) && !is_numeric($this->spirometrie_CVF) ){
			#$errors[$i++]="La valeur Spirométrie CVF (doit être comprise entre 1 et 100)";
			$errors[$i++]="La valeur Spirométrie CVF doit être num&eacute;rique";
		}			
	

	
		#if(!is_numeric($this->spirometrie_VEMS) or ($this->spirometrie_VEMS<1 or $this->spirometrie_VEMS>100)){
		if( !empty($this->spirometrie_VEMS) && !is_numeric($this->spirometrie_VEMS) ){
			$errors[$i++]="La valeur Spirométrie VEMS doit être num&eacute;rique";
		}			
	

	
		#if(!is_numeric($this->spirometrie_DEP) or ($this->spirometrie_DEP<1 or $this->spirometrie_DEP>100)){
		if( !empty($this->spirometrie_DEP) && !is_numeric($this->spirometrie_DEP) ){
			$errors[$i++]="La valeur Spirométrie DEP doit être num&eacute;rique";
		}		
		


		return $errors;
	}
	
	
	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->date = dateToMysqlDate($clone->date);
		$clone->dChol = dateToMysqlDate($clone->dChol);
		$clone->dHDL = dateToMysqlDate($clone->dHDL);
		$clone->dLDL = dateToMysqlDate($clone->dLDL);
		$clone->dtriglycerides = dateToMysqlDate($clone->dtriglycerides);
		$clone->dTA = dateToMysqlDate($clone->dTA);
		$clone->dsokolov = dateToMysqlDate($clone->dsokolov);
		$clone->dCreat = dateToMysqlDate($clone->dCreat);
		$clone->dkaliemie = dateToMysqlDate($clone->dkaliemie);
		$clone->dproteinurie = dateToMysqlDate($clone->dproteinurie);
		$clone->dhematurie = dateToMysqlDate($clone->dhematurie);
		$clone->dFond = dateToMysqlDate($clone->dFond);
		$clone->dECG = dateToMysqlDate($clone->dECG);
		$clone->darret = dateToMysqlDate($clone->darret);
		$clone->spirometrie_date = dateToMysqlDate($clone->spirometrie_date);
		$clone->dpoids = dateToMysqlDate($clone->dpoids);
		$clone->dpouls = dateToMysqlDate($clone->dpouls);
		$clone->dgly = dateToMysqlDate($clone->dgly);
		$clone->exam_cardio = dateToMysqlDate($clone->exam_cardio);
		
		return $clone;
	}

	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->date = mysqlDateTodate($clone->date);
		$clone->dChol = mysqlDateTodate($clone->dChol);
		$clone->dHDL = mysqlDateTodate($clone->dHDL);
		$clone->dLDL = mysqlDateTodate($clone->dLDL);
		$clone->dtriglycerides = mysqlDateTodate($clone->dtriglycerides);
		$clone->dTA = mysqlDateTodate($clone->dTA);
		$clone->dsokolov = mysqlDateTodate($clone->dsokolov);
		$clone->dCreat = mysqlDateTodate($clone->dCreat);
		$clone->dkaliemie = mysqlDateTodate($clone->dkaliemie);
		$clone->dproteinurie = mysqlDateTodate($clone->dproteinurie);
		$clone->dhematurie = mysqlDateTodate($clone->dhematurie);
		$clone->dFond = mysqlDateTodate($clone->dFond);
		$clone->dECG = mysqlDateTodate($clone->dECG);
		$clone->darret = mysqlDateTodate($clone->darret);
		$clone->spirometrie_date = mysqlDateTodate($clone->spirometrie_date);
		$clone->dpoids = mysqlDateTodate($clone->dpoids);
		$clone->dpouls = mysqlDateTodate($clone->dpouls);
		$clone->dgly = mysqlDateTodate($clone->dgly);
		$clone->exam_cardio = mysqlDateTodate($clone->exam_cardio);

		return $clone;
	}
	
	function isOutdatedExamCardio($month =0){
	
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->exam_cardio;
	//		echo "<br>";
	//		print_r($this);
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return "ND";
			$refDate = increaseDateBy($elderDate,0,12,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;

	//		echo "refdate : ".$refDate." elderdate : ".$elderDate."<br>";
			return $refDate;
	    }
	    else{
	        return false;
	    }
	}

	function isOutdatedECG($month =0){
	
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->dECG;
	//		echo "<br>";
	//		print_r($this);
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return "ND";
			$refDate = increaseDateBy($elderDate,0,36,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;

	//		echo "refdate : ".$refDate." elderdate : ".$elderDate."<br>";
			return $refDate;
	    }
	    else{
	        return false;
	    }
	}

	function isOutdateddFond($month =0){
	
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->dFond;
	//		echo "<br>";
			// print_r($this);
			if($this->HTA=="oui"){
				if(is_null($elderDate)) return "ND";
				if($elderDate == "") return "ND";
			
				$refDate = increaseDateBy($elderDate,0,36,0);
				$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
				if(compare($refDate,$actualDate)>0) return false;
			}
			else{
				return false;
			}

	//		echo "refdate : ".$refDate." elderdate : ".$elderDate."<br>";
			return $refDate;
	    }
	    else{
	        return false;
	    }
	}

	function isOutdatedProteinurie($month =0){
	
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->dproteinurie;
	//		echo "<br>";
	//		print_r($this);
			if($this->HTA=="oui"){
				if(is_null($elderDate)) return "ND";
				if($elderDate == "") return "ND";

				$refDate = increaseDateBy($elderDate,0,12,0);
				$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
				if(compare($refDate,$actualDate)>0) return false;
			}
			else{
				return false;
			}

	//		echo "refdate : ".$refDate." elderdate : ".$elderDate."<br>";
			return $refDate;
	    }
	    else{
	        return false;
	    }
	}

	function isOutdatedKaliemie($month =0){
	
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->dkaliemie;
	//		echo "<br>";
	//		print_r($this);
			if($this->HTA=="oui"){
				if(is_null($elderDate)) return "ND";
				if($elderDate == "") return "ND";

				$refDate = increaseDateBy($elderDate,0,12,0);
				$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
				if(compare($refDate,$actualDate)>0) return false;
			}
			else{
				return false;
			}

	//		echo "refdate : ".$refDate." elderdate : ".$elderDate."<br>";
			return $refDate;
	    }
	    else{
	        return false;
	    }
	}

	function isOutdatedCreat($month =0){
	
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->dCreat;
	//		echo "<br>";
	//		print_r($this);
			if($this->HTA=="oui"){
				if(is_null($elderDate)) return "ND";
				if($elderDate == "") return "ND";

				$refDate = increaseDateBy($elderDate,0,12,0);
				$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
				if(compare($refDate,$actualDate)>0) return false;
			}
			else{
				return false;
			}

	//		echo "refdate : ".$refDate." elderdate : ".$elderDate."<br>";
			return $refDate;
	    }
	    else{
	        return false;
	    }
	}


	function isOutdatedChol($month =0){
	
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->dChol;
	//		echo "<br>";
	//		print_r($this);
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return "ND";
			// print_r($this);
			// echo count($this->traitement);
			if(((count($this->traitement)==1)&&($this->traitement[0]=='Aucun'))||
				($this->LDL<=1.6)){
				$refDate = increaseDateBy($elderDate,0,36,0);
			}
			else{
				$refDate = increaseDateBy($elderDate,0,12,0);
			}
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;

	//		echo "refdate : ".$refDate." elderdate : ".$elderDate."<br>";
			return $refDate;
	    }
	    else{
	        return false;
	    }
	}

	function isOutdatedHDL($month =0){
	
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->dHDL;
	//		echo "<br>";
	//		print_r($this);
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return "ND";
			
			if(((count($this->traitement)==1)&&($this->traitement[0]=='Aucun'))||
				($this->LDL<=1.6)){
				$refDate = increaseDateBy($elderDate,0,36,0);
			}
			else{
				$refDate = increaseDateBy($elderDate,0,12,0);
			}
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;

	//		echo "refdate : ".$refDate." elderdate : ".$elderDate."<br>";
			return $refDate;
	    }
	    else{
	        return false;
	    }
	}
	function isOutdatedLDL($month =0){
	
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->dLDL;
	//		echo "<br>";
	//		print_r($this);
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return "ND";

			if(((count($this->traitement)==1)&&($this->traitement[0]=='Aucun'))||
				($this->LDL<=1.6)){
				$refDate = increaseDateBy($elderDate,0,36,0);
			}
			else{
				$refDate = increaseDateBy($elderDate,0,12,0);
			}
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;

	//		echo "refdate : ".$refDate." elderdate : ".$elderDate."<br>";
			return $refDate;
	    }
	    else{
	        return false;
	    }
	}
	function isOutdatedtriglycerides($month =0){
	
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->dtriglycerides;
	//		echo "<br>";
	//		print_r($this);
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return "ND";
			
			if(((count($this->traitement)==1)&&($this->traitement[0]=='Aucun'))||
				($this->LDL<=1.6)){
				$refDate = increaseDateBy($elderDate,0,36,0);
			}
			else{
				$refDate = increaseDateBy($elderDate,0,12,0);
			}
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;

	//		echo "refdate : ".$refDate." elderdate : ".$elderDate."<br>";
			return $refDate;
	    }
	    else{
	        return false;
	    }
	}

	function isOutdatedGlycemie($month =0){
	
	    if($this->sortir_rappel!='1'){
			$elderDate = $this->dgly;
	//		echo "<br>";
	//		print_r($this);
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return "ND";
			
			if(($this->glycemie>1.1)&&($this->glycemie<=1.26)){
				$refDate = increaseDateBy($elderDate,0,12,0);
			}
			else{
				$refDate = increaseDateBy($elderDate,0,36,0);
			}
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;

	//		echo "refdate : ".$refDate." elderdate : ".$elderDate."<br>";
			return $refDate;
	    }
	    else{
	        return false;
	    }
	}
	
	function isOutdated($month =0 ,$type){
		switch($type){
			case "Examen Cardio-Vasculaire": return $this->isOutdatedExamCardio($month);
			case "ECG": return $this->isOutdatedECG($month);
			case "Fond d'oeil": return $this->isOutdateddFond($month);
			case "Protéinurie": return $this->isOutdatedProteinurie($month);
			case "Kaliémie": return $this->isOutdatedKaliemie($month);
			case "Créatinine": return $this->isOutdatedCreat($month);
			case "Cholestérol": return $this->isOutdatedChol($month);
			case "HDL": return $this->isOutdatedHDL($month);
			case "LDL": return $this->isOutdatedLDL($month);
			case "Triglycérides": return $this->isOutdatedtriglycerides($month);
			case "Glycémie": return $this->isOutdatedGlycemie($month);
		}
	}

	function get_rcva($sexe, $age, $diab){
		$tab = $this->tabac;
		$tension=$this->TaSys;
		$choltot=$this->Chol;
		$hdl=$this->HDL;
		$ventricule=$this->HVG;
		$surcharge_ventricule=$this->surcharge_ventricule;
	

		if(($tab!="oui")&&($tab!="non")){
		    return ("Calcul du RCV impossible : toutes les données nécessaires ne sont pas renseignées");
		}
		if($tension==''){
		    return ("Calcul du RCV impossible : toutes les données nécessaires ne sont pas renseignées");
		}
		if($choltot==''){
		    return ("Calcul du RCV impossible : toutes les données nécessaires ne sont pas renseignées");
		}
		if($hdl==''){
		    return ("Calcul du RCV impossible : toutes les données nécessaires ne sont pas renseignées");
		}
		if(($ventricule!="oui")&&($ventricule!="non")&&($surcharge_ventricule!="oui")&&($surcharge_ventricule!="non")){
		    return ("Calcul du RCV impossible : toutes les données nécessaires ne sont pas renseignées");
		}
		
		$e1 = -0.9119;
		$e2 = -0.2767;
		$e3 = -0.7181;
		$e4 = -0.5865;
		$l = 11.1122;
		$m0 = 4.4181 ;
		$s0 = -0.3155 ;
		$s1 = -0.2784;
		$c1 = -1.4792 ;
		$c2 = -0.1759 ;
		$d1 = -5.8549;
		$d2 = 1.8515 ;
		$d3 = -0.3758 ;
		$horizon=10;
		
		$pas=$tension;
		$tabac=0;
		$hvg=0;
		$chol=$choltot;
		$HDL=$hdl;
		
		if($tab=="oui"){
			$tabac=1;
		}
		if(($ventricule!="oui")&&($ventricule!="non")&&($surcharge_ventricule=="oui")){
			$hvg=1;
		}
		if($ventricule=="oui"){
			$hvg=1;
		}

		$a = $l + $e1*log($pas) + $e2*$tabac + $e3*log($chol/$HDL) + $e4*$hvg;
		
		if($sexe=="M"){
			$m = $a + $c1*log($age) + $c2*$diab*1; //;=> on considère que le patient n'est pas diabétique
		}
		if($sexe=='F'){
			$m = $a + $d1 + $d2*(log($age/74)*log($age/74)) + $d3*$diab; //on considère que la patient n'est pas diabétique
		}
		

		$m_calc = $m0 + $m;
		$s = exp($s0 + $s1*$m);

		$u = (log($horizon) - $m_calc ) / $s ;

		$pt = 1- exp(-exp($u));

		$rcva=round($pt*100, 2);
		$rcva=$rcva."%";
		
		return $rcva;

	}

}
 ?>
