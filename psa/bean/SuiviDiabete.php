<?php
require_once("global/config.php");
require_once("tools/date.php");


class SuiviDiabete{
	  var $dsuivi;
	  var $dossier_id;
	  var $dHBA;
	  var $ResHBA;
	  var $dExaFil;
	  var $ExaFil;
	  var $dExaPieds;
	  var $ExaPieds;
	  var $dChol;
	  var $iChol;
	  var $HDL;
	  var $HDLc;
	  var $dLDL;
	  var $iLDL;
	  var $LDLc;
	  var $LDL;
	  var $dCreat;
	  var $Creat;
	  var $iCreat;
	  var $dAlbu;
	  var $iAlbu;
	  var $dFond;
	  var $iFond;
	  var $dECG;
	  var $iECG;
	  var $dentiste;
	  var $suivi_type;
	  var $poids;
	  var $dPoids;
	  var $Regime;
	  var $InsulReq;
	  var $ADO;
	  var $TaSys;
	  var $TaDia;
	  var $TA_mode;
	  var $dtension;
	  var $risques;
	  var $nbrtabac;
	  var $type;
	  var $hta;
	  var $arte;
	  var $neph;
	  var $coro;
	  var $reti;
	  var $neur;
	  var $equilib;
	  var $tension;
	  var $lipide;
	  var $mesure_ADO;
	  var $insuline;
	  var $mesure_hta;
	  var $hypl;
	  var $phys;
	  var $diet;
	  var $taba;
	  var $etp;
	  var $date_debut;
	  var $diab10ans;
	  var $triglycerides;
	  var $dTriglycerides;
	  var $kaliemie;
	  var $dKaliemie;
	  var $sortie;


	function SuiviDiabete(
					 $dsuivi = NULL,
					 $dossier_id = NULL,
					 $dHBA = NULL,
					 $ResHBA = NULL,
					 $dExaFil = NULL,
					 $ExaFil = NULL,
					 $dExaPieds = NULL,
					 $ExaPieds = NULL,
					 $dChol = NULL,
					 $iChol = NULL,
					 $HDL = NULL,
					 $HDLc = NULL,
					 $dLDL = NULL,
					 $iLDL = NULL,
					 $LDLc = NULL,
					 $LDL = NULL,
					 $dCreat = NULL,
					 $Creat = NULL,
					 $iCreat = NULL,
					 $dAlbu = NULL,
					 $iAlbu = NULL,
					 $dFond = NULL,
					 $iFond = NULL,
					 $dECG = NULL,
					 $iECG = NULL,
					 $dentiste = NULL,
					 $suivi_type = array(),
					 $poids = NULL,
					 $dPoids = NULL,
					 $Regime = NULL,
					 $InsulReq = NULL,
					 $ADO = array(),
					 $TaSys = NULL,
					 $TaDia = NULL,
					 $TA_mode = NULL,
					 $dtension = NULL,
					 $risques = NULL,
					 $nbrtabac = NULL,
					 $type = NULL,
					 $hta = NULL,
					 $arte = NULL,
					 $neph = NULL,
					 $coro = NULL,
					 $reti = NULL,
					 $neur = NULL,
					 $equilib = NULL,
					 $tension = NULL,
					 $lipide = NULL,
					 $mesure_ADO = NULL,
					 $insuline = NULL,
					 $mesure_hta = NULL,
					 $hypl = NULL,
					 $phys = NULL,
					 $diet = NULL,
					 $taba = NULL,
					 $etp = NULL,
					 $date_debut = NULL,
					 $diab10ans = NULL,
					 $triglycerides = NULL,
					 $dTriglycerides = NULL,
					 $kaliemie = NULL,
					 $dKaliemie = NULL,
					 $sortie = NULL){
		 $this->dsuivi = $dsuivi;
		 $this->dossier_id = $dossier_id;
		 $this->dHBA = $dHBA;
		 $this->ResHBA = $ResHBA;
		 $this->dExaFil = $dExaFil;
		 $this->ExaFil = $ExaFil;
		 $this->dExaPieds = $dExaPieds;
		 $this->ExaPieds = $ExaPieds;
		 $this->dChol = $dChol;
		 $this->iChol = $iChol;
		 $this->HDL = $HDL;
		 $this->HDLc = $HDLc;
		 $this->dLDL = $dLDL;
		 $this->iLDL = $iLDL;
		 $this->LDLc = $LDLc;
		 $this->LDL = $LDL;
		 $this->dCreat = $dCreat;
		 $this->Creat = $Creat;
		 $this->iCreat = $iCreat;
		 $this->dAlbu = $dAlbu;
		 $this->iAlbu = $iAlbu;
		 $this->dFond = $dFond;
		 $this->iFond = $iFond;
		 $this->dECG = $dECG;
		 $this->iECG = $iECG;
		 $this->dentiste = $dentiste;
		 $this->suivi_type = $suivi_type;
		 $this->poids = $poids;
		 $this->dPoids = $dPoids;
		 $this->Regime = $Regime;
		 $this->InsulReq = $InsulReq;
		 $this->ADO = $ADO;
		 $this->TaSys = $TaSys;
		 $this->TaDia = $TaDia;
		 $this->TA_mode = $TA_mode;
		 $this->dtension = $dtension;
		 $this->risques = $risques;
		 $this->nbrtabac = $nbrtabac;
		 $this->type = $type;
		 $this->hta = $hta;
		 $this->arte = $arte;
		 $this->neph = $neph;
		 $this->coro = $coro;
		 $this->reti = $reti;
		 $this->neur = $neur;
		 $this->equilib = $equilib;
		 $this->tension = $tension;
		 $this->lipide = $lipide;
		 $this->mesure_ADO = $mesure_ADO;
		 $this->insuline = $insuline;
		 $this->mesure_hta = $mesure_hta;
		 $this->hypl = $hypl;
		 $this->phys = $phys;
		 $this->diet = $diet;
		 $this->taba = $taba;

		 $this->etp = $etp;
		 $this->date_debut = $date_debut;
		 $this->diab10ans = $diab10ans;
		 $this->triglycerides = $triglycerides;
		 $this->dTriglycerides = $dTriglycerides;
		 $this->kaliemie = $kaliemie;
		 $this->dKaliemie = $dKaliemie;
		 $this->sortie = $sortie;
	}

	 function toString(){
		 return 
			$this->dsuivi." ".
			$this->dossier_id." ".
			$this->dHBA." ".
			$this->ResHBA." ".
			$this->dExaFil." ".
			$this->ExaFil." ".
			$this->dExaPieds." ".
			$this->ExaPieds." ".
			$this->dChol." ".
			$this->iChol." ".
			$this->HDL." ".
			$this->HDLc." ".
			$this->dLDL." ".
			$this->iLDL." ".
			$this->LDLc." ".
			$this->LDL." ".
			$this->dCreat." ".
			$this->Creat." ".
			$this->iCreat." ".
			$this->dAlbu." ".
			$this->iAlbu." ".
			$this->dFond." ".
			$this->iFond." ".
			$this->dECG." ".
			$this->iECG." ".
			$this->dentiste." ".
			$this->suivi_type." ".
			$this->poids." ".
			$this->dPoids." ".
			$this->Regime." ".
			$this->InsulReq." ".
			$this->ADO." ".
			$this->TaSys." ".
			$this->TaDia." ".
			$this->TA_mode." ".
			$this->dtension." ".
			$this->risques." ".
			$this->nbrtabac." ".
			$this->type." ".
			$this->hta." ".
			$this->arte." ".
			$this->neph." ".
			$this->coro." ".
			$this->reti." ".
			$this->neur." ".
			$this->equilib." ".
			$this->tension." ".
			$this->lipide." ".
			$this->mesure_ADO." ".
			$this->insuline." ".
			$this->mesure_hta." ".
			$this->hypl." ".
			$this->phys." ".
			$this->diet." ".
			$this->taba." ".
			
			$this->etp." ".
			$this->date_debut." ".
			$this->diab10ans." ".
			$this->triglycerides." ".
		    $this->dTriglycerides." ".
		    $this->kaliemie." ".
		    $this->dKaliemie." ".
			$this->sortie;
	}

	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->dsuivi = dateToMysqlDate($clone->dsuivi);
		$clone->dPoids = dateToMysqlDate($clone->dPoids);
		$clone->dtension = dateToMysqlDate($clone->dtension);
		$clone->dHBA = dateToMysqlDate($clone->dHBA);
		$clone->dExaFil = dateToMysqlDate($clone->dExaFil);
		$clone->dExaPieds = dateToMysqlDate($clone->dExaPieds);
		$clone->dChol = dateToMysqlDate($clone->dChol);
		$clone->dLDL = dateToMysqlDate($clone->dLDL);
		$clone->dCreat = dateToMysqlDate($clone->dCreat);
		$clone->dAlbu = dateToMysqlDate($clone->dAlbu);
		$clone->dFond = dateToMysqlDate($clone->dFond);
		$clone->dECG = dateToMysqlDate($clone->dECG);
		$clone->dentiste = dateToMysqlDate($clone->dentiste);
		$clone->dTriglycerides = dateToMysqlDate($clone->dTriglycerides);
		$clone->dKaliemie = dateToMysqlDate($clone->dKaliemie);
		$clone->date_debut = dateToMysqlDate($clone->date_debut);
		if (($clone->ResHBA==NULL) && empty($clone->dHBA))
		    $clone->ResHBA=NULL;
		if (($clone->HDL==NULL) && empty($clone->dChol))
		    $clone->HDL=NULL;
		if (($clone->LDL==NULL) && empty($clone->dLDL))
		    $clone->LDL=NULL;
		if (($clone->Creat==NULL) && empty($clone->dCreat))
		    $clone->Creat=NULL;
		return $clone;
	}

	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->dsuivi = mysqlDateTodate($clone->dsuivi);
		$clone->dHBA = mysqlDateTodate($clone->dHBA);
		$clone->dPoids = mysqlDateTodate($clone->dPoids);
		$clone->dtension = mysqlDateTodate($clone->dtension);
		$clone->dExaFil = mysqlDateTodate($clone->dExaFil);
		$clone->dExaPieds = mysqlDateTodate($clone->dExaPieds);
		$clone->dChol = mysqlDateTodate($clone->dChol);
		$clone->dLDL = mysqlDateTodate($clone->dLDL);
		$clone->dCreat = mysqlDateTodate($clone->dCreat);
		$clone->dAlbu = mysqlDateTodate($clone->dAlbu);
		$clone->dFond = mysqlDateTodate($clone->dFond);
		$clone->dECG = mysqlDateTodate($clone->dECG);
		$clone->dentiste = mysqlDateTodate($clone->dentiste);
		$clone->dTriglycerides = mysqlDateTodate($clone->dTriglycerides);
		$clone->dKaliemie = mysqlDateTodate($clone->dKaliemie);
		$clone->date_debut = mysqlDateTodate($clone->date_debut);
		return $clone;
	}


	function isOutdated4($month){
		if($this->sortie!='1'){
			$elderDate = $this->dHBA;
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return false;
			$refDate = increaseDateBy($elderDate,0,4,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
			return $refDate; 
		}
		else{
			return false;
		}
	}
	
/*	function isOutdatedS($month){
		$elderDate = getLowerDate(array($this->dExaFil,$this->dExaPieds));
		if(is_null($elderDate)) return false;
		if($elderDate == "") return false;
		$refDate = increaseDateBy($elderDate,0,12,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate;  
	}
	
	function isOutdatedA($month){					
		$elderDate = getLowerDate(array($this->dChol,$this->dLDL,$this->dCreat,$this->dAlbu,$this->dECG ));
		if(is_null($elderDate)) return false;
		if($elderDate == "") return false;
		$refDate = increaseDateBy($elderDate,0,12,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate; 
	}
	
*/							


	function isOutdatedMono($month){
		if($this->sortie!='1'){
			$elderDate = $this->dExaFil;
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return false;
			$refDate = increaseDateBy($elderDate,0,12,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
			return $refDate;
		}
		else{
			return false;
		}
	}

	function isOutdatedPied($month){
		if($this->sortie!='1'){
			$elderDate = $this->dExaPieds;
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return false;
			$refDate = increaseDateBy($elderDate,0,12,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
			return $refDate;
		}
		else{
			return false;
		}
	}
	function isOutdatedMonofil($month){
		if($this->sortie!='1'){
			$elderDate = $this->dExaFil;
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return false;
			$refDate = increaseDateBy($elderDate,0,12,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
			return $refDate;
		}
		else{
			return false;
		}
	}

	function isOutdatedHDL($month){
		if($this->sortie!='1'){
			$elderDate = $this->dChol;
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return false;
			$refDate = increaseDateBy($elderDate,0,12,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
			return $refDate;
		}
		else{
			return false;
		}
	}

	function isOutdatedCreat($month){
		if($this->sortie!='1'){
			$elderDate = $this->dCreat;
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return false;
			$refDate = increaseDateBy($elderDate,0,12,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
			return $refDate;
		}
		else{
			return false;
		}
	}

	function isOutdatedAlbu($month){
		if($this->sortie!='1'){
			$elderDate = $this->dAlbu;
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return false;
			$refDate = increaseDateBy($elderDate,0,12,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
			return $refDate;
		}
		else{
			return false;
		}
	}

	function isOutdatedFoeil($month){
		if($this->sortie!='1'){
			$elderDate = $this->dFond;
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return false;
			$refDate = increaseDateBy($elderDate,0,12,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
			return $refDate;
		}
		else{
			return false;
		}
	}

	function isOutdatedECG($month){
		if($this->sortie!='1'){
			$elderDate = $this->dECG;
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return false;
			$refDate = increaseDateBy($elderDate,0,12,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
			return $refDate;
		}
		else{
			return false;
		}
	}

	function isOutdatedDentiste($month){
		if($this->sortie!='1'){
			$elderDate = $this->dentiste;
			if(is_null($elderDate)) return false;
			if($elderDate == "") return false;
			$refDate = increaseDateBy($elderDate,0,12,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
			return $refDate;
		}
		else{
			return false;
		}
	}
	
	function isOutdatedTriglycerides($month){
		if($this->sortie!='1'){
			$elderDate = $this->dTriglycerides;
			if(is_null($elderDate)) return false;
			if($elderDate == "") return false;
			$refDate = increaseDateBy($elderDate,0,12,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
			return $refDate;
		}
		else{
			return false;
		}
	}
	
	function isOutdatedKaliemie($month){
		if($this->sortie!='1'){
			$elderDate = $this->dKaliemie;
			if(is_null($elderDate)) return false;
			if($elderDate == "") return false;
			$refDate = increaseDateBy($elderDate,0,12,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
			return $refDate;
		}
		else{
			return false;
		}
	}

	function isOutdated($month =0 ,$type){
		switch($type){
			case "4": return $this->isOutdated4($month);
			case "Monofilament": return $this->isOutdatedMono($month);
			case "Examen des pieds": return $this->isOutdatedPied($month);
			case "HDL/LDL": return $this->isOutdatedHDL($month);
			case "Créatininémie": return $this->isOutdatedCreat($month);
			case "Albuminurie": return $this->isOutdatedAlbu($month);
			case "Fond oeil": return $this->isOutdatedFoeil($month);
			case "ECG": return $this->isOutdatedECG($month);
			case "Dentiste": return $this->isOutdatedDentiste($month);
			case "Triglycerides": return $this->isOutdatedTriglycerides($month);
			case "Kaliemie": return $this->isOutdatedKaliemie($month);
		}
	}


/*	function isOutdated($month =0 ,$type){
		switch($type){
			case "4": return $this->isOutdated4($month);
			case "s": return $this->isOutdatedS($month);
			case "a": return $this->isOutdatedA($month);
		}
	}
*/
	function getIMC($taille){		
		require_once("tools/formulas.php");
		return getIMC($this->poids,$taille);
	}
	
	function getClearance($dossier){
		require_once("tools/formulas.php");
		return getClearance($dossier->sexe,$this->poids,$dossier->getAge(),$this->Creat);
	}

	function getHDLc($dossier){
/*		if($dossier->sexe == "F") */return "0.40";
//		if($dossier->sexe == "M") return "0.35";
//		else return "0";
	}
	
	function isHDLPathologic($dossier){
		if($this->HDL == NULL) return false;
		if($this->HDL == "") return false;
		if($this->HDL > $this->getHDLc($dossier)) return true;
		return false;
	}
	
	function getLDLc(){
		if($this->coro == 1) return "1";
		else return "1.30";					
	}
	
	function isLDLPathologic(){
		if($this->LDL == NULL) return false;
		if($this->LDL == "") return false;
		if($this->LDL > $this->getLDLc()) return true;
		return false;
	}
	
	function check($dossier){
		$errors = array();
		$i = 0;
		// les verfications du suivi systematique
		if(($this->type!="1") and ($this->type!="2")){
			$errors[$i++] ="Veuillez indiquer le type de diabète";
		}

		if(($this->poids!="" )||($this->dPoids!='')){//Si des données sont saisies pour le poids => on fait les contrôles
			if(!is_numeric($this->poids) or $this->poids<0 or $this->poids > 200) 
				$errors[$i++] ="Le poids à une valeur invalide";
			if(!isValidDate($this->dPoids))
				$errors[$i++] ="Date poids invalide";
		}

		if($this->Regime == 1){
			if($this->InsulReq == 1) $errors[$i++] ="Les traitements sont incompatibles";
			if(count($this->ADO)== 1 and !in_array("aucun",$this->ADO)) $errors[$i++] ="Les traitements sont incompatibles";
			if(count($this->ADO) > 1) $errors[$i++] ="Les traitements sont incompatibles";
		}

		// if($this->risques == 1){
		// 	if($this->nbrtabac < 1)||($this->nbrtabac > 100){
		// 		$errors[$i++] = "Le nbr de paquets est invalide (doit être compris entre 1 et 100)";
		// 	}
		// }
		//else{
		//	if($this->InsulReq == 0){
		//		if(count($this->ADO)== 0) $errors[$i++] ="Choisisez un type de traitement";
		//		if(count($this->ADO)== 1 and in_array("aucun",$this->ADO)) $errors[$i++] ="Choisisez un type de traitement";				
		//	}
		//}
		//Si une valeur est saisie pour la tension, on fait les contrôles
		
		if(($this->TaDia!="")||($this->TaSys!="")||($this->TA_mode!="")||($this->dtension!="")){
			if(($this->TaDia < 35) or ($this->TaDia >150) or ($this->TaSys <50) or ($this->TaSys > 300))		
				$errors[$i++] ="Tension artérelle invalide";
				
			if($this->TA_mode != "manuel")
				if($this->TA_mode !="automatique")
					if($this->TA_mode != "automesure")
							$errors[$i++] ="Selectionnez le mode de mesure de la tension artérelle";
							
			if(!isValidDate($this->dtension))
				$errors[$i++] ="Date tension invalide";
		}
		
		//verfication du suivi 4 mois
		if(in_array("4",$this->suivi_type)){
		
			if($this->dHBA == ""){
				if(($this->ResHBA != "") && ($this->ResHBA != 0)){
					$errors[$i++] ="Date HBA n'est pas saisie";
				}				
			}
			else if($this->ResHBA == ""){
				$errors[$i++] ="HBA n'est pas saisie";
			}
			
			if($this->dHBA != "" and $this->ResHBA != ""){
				if(!is_numeric($this->ResHBA) or $this->ResHBA<=0)
					$errors[$i++] ="Mesure HBA invalide";
				if(!isValidDate($this->dHBA))
					$errors[$i++] ="Date HBA invalide";
			}
						
		}
		
		//verification du suivi semestriel
		if(in_array("a",$this->suivi_type)){
/*			if(($this->ExaPieds !="") and ($this->ExaPieds !="oui") and ($this->ExaPieds !="non")
				and ($this->ExaPieds !="nsp"))
			{
				$errors[$i++] ="Valeur pour l'examen des pieds invalide";
			}
			else	
			{
				if($this->dExaPieds == "" and $this->ExaPieds =="oui"){
					$errors[$i++] ="Entrez une date pour l'examen des pieds";
				}
				elseif($this->ExaPieds=="non" or $this->ExaPieds=="nsp")
				{
				    if ($this->dExaPieds!="")
				    	$errors[$i++]="N'entrez pas de date pour l'examen des pieds s'il n'y en a pas eu ou si vous ne savez pas";
				}
				elseif ($this->ExaPieds=="")
				    $errors[$i++]="Précisez s'il y a eu un examen des pieds";
				else*/
					if(($this->dExaPieds!="")&&(!isValidDate($this->dExaPieds)))
						$errors[$i++] ="Date d'examen des pieds invalide";
			// }
					

/*			if(($this->ExaFil !="") and ($this->ExaFil !="oui") and ($this->ExaFil !="non")
				and ($this->ExaFil !="nsp"))
			{
				$errors[$i++] ="Valeur pour l'examen au monofilament invalide";
			}
			else
			{
				if($this->dExaFil == "" and $this->ExaFil =="oui"){
					$errors[$i++] ="Entrez une date pour l'examen au monofilament";
				}
				elseif($this->ExaFil=="non" or $this->ExaFil=="nsp")
				{
				    if ($this->dExaFil!="")
				    	$errors[$i++]="N'entrez pas de date pour l'examen au monofilament s'il n'y en a pas eu ou si vous ne savez pas";
				}
				elseif ($this->ExaFil=="")
				    $errors[$i++]="Précisez s'il y a eu un examen au monofilament";
				else
*/					if(($this->dExaFil!="")&&(!isValidDate($this->dExaFil)))
						$errors[$i++] ="Date d'examen au monofilament invalide";
			// }

		}
		//verification du suivi annuel
		
		if(in_array("a",$this->suivi_type)){
			if($this->dChol == ""){
				if(($this->HDL != "") && ($this->HDL != 0)){
					$errors[$i++] ="Date HDL n'est pas saisie";
				}				
			}
			else if($this->HDL == ""){
				$errors[$i++] ="HDL n'est pas saisie";
			}
			
			if($this->dChol != "" and $this->HDL != ""){
				if(!is_numeric($this->HDL) or $this->HDL<=0)
					$errors[$i++] ="Mesure HDL invalide";
				if(!isValidDate($this->dChol))
					$errors[$i++] ="Date du Cholestérol HDL invalide";
			}
			
			
			
			
			if($this->dLDL == ""){
				if(($this->LDL != "") && ($this->LDL != 0)){
					$errors[$i++] ="Date LDL n'est pas saisie";
				}				
			}
			else if($this->LDL == ""){
				$errors[$i++] ="LDL n'est pas saisie";
			}
			
			if($this->dLDL != "" and $this->LDL != ""){							
				if(!is_numeric($this->LDL) or $this->LDL<=0)
					$errors[$i++] ="Mesure LDL invalide";			
				if(!isValidDate($this->dLDL))
					$errors[$i++] ="Date de la mesure LDL invalide";	
			}
			
			
			if($this->dCreat == ""){
				if(($this->Creat != "") && ($this->Creat != 0)){
					$errors[$i++] ="Date Créatininémie n'est pas saisie";
				}				
			}
			else if($this->Creat == ""){
				$errors[$i++] ="Créatininémie n'est pas saisie";
			}
			
			if($this->dCreat != "" and $this->Creat != ""){							
				if(!is_numeric($this->Creat) or $this->Creat<=0)
					$errors[$i++] ="Créatininémie invalide";			
				if(!isValidDate($this->dCreat))
					$errors[$i++] ="Date de la mesure Créatininémie invalide";	
			}
			
			
			if($this->iAlbu == 1){
				if($this->dAlbu == ""){
					$errors[$i++] ="Date de la mesure de micro albuminurie n'est pas saisie";
				}				
			}
			if($this->dAlbu != "" and !isValidDate($this->dAlbu))
				$errors[$i++] ="Date de la mesure de micro albuminurie invalide";	
			
			
			if($this->iFond == 1){
				if($this->dFond == ""){
					$errors[$i++] ="Date du fond d'oeil n'est pas saisie";
				}				
			}
			if($this->dFond != "" and !isValidDate($this->dFond))
				$errors[$i++] ="Date du fond d'oeil invalide";	
			
			if($this->iECG == 1){
				if($this->dECG == ""){
					$errors[$i++] ="Date ECG n'est pas saisie";
				}				
			}
			if($this->dECG != "" and !isValidDate($this->dECG))
				$errors[$i++] ="Date ECG de repos invalide";											

			if($this->dentiste != "" and !isValidDate($this->dentiste))
				$errors[$i++] ="Date dentiste invalide";											
				
			if($this->date_debut != "" and !isValidDate($this->date_debut))
				$errors[$i++] ="Date de début de diabète invalide";											
		

		}

		return $errors;
						
	}
}
 ?>
