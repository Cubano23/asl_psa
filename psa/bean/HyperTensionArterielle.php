<?php

require_once("tools/date.php");


class HyperTensionArterielle{
	var $id;
	var $date;
	var $poids;
	var $dpoids;
	var $TaSys;
	var $TaDia;
	var $TA_mode;
	var $obj_tension;
	var $dtension;
	var $dcoeur;
	var $dartere;
	var $dpouls;
	var $dsouffle;
	var $Creat;
	var $dcreat;
	var $glycemie;
	var $dglycemie;
	var $kaliemie;
	var $dkaliemie;
	var $HDL;
	var $dChol;
	var $LDL;
	var $dLDL;
	var $proteinurie;
	var $dproteinurie;
	var $hematurie;
	var $dhematurie;
	var $dfond;
	var $dECG;
	var $hta_instable;
	var $hta_tritherapie;
	var $hta_complique;
	var $tabac;
	var $hyperlipidemie;
	var $alcool;
	var $dconsult;
	var $degre_satisfaction;
	var $qualite_vie;
	var $iatrogenie;
	var $deliv_trait;
	var $regul_prises;
	var $cpt_rendu;

	function HyperTensionArterielle(
				$id = NULL,
				$date = NULL,
				$poids = NULL,
				$dpoids = NULL,
				$TaSys = NULL,
				$TaDia = NULL,
				$TA_mode = NULL,
				$obj_tension = NULL,
				$dtension = NULL,
				$dcoeur = NULL,
				$dartere = NULL,
				$dpouls = NULL,
				$dsouffle = NULL,
				$Creat = NULL,
				$dcreat = NULL,
				$glycemie = NULL,
				$dglycemie = NULL,
				$kaliemie = NULL,
				$dkaliemie = NULL,
				$HDL = NULL,
				$dChol = NULL,
				$LDL = NULL,
				$dLDL = NULL,
				$proteinurie = NULL,
				$dproteinurie = NULL,
				$hematurie = NULL,
				$dhematurie = NULL,
				$dfond = NULL,
				$dECG = NULL,
				$hta_instable = NULL,
				$hta_tritherapie = NULL,
				$hta_complique = NULL,
				$tabac = NULL,
				$hyperlipidemie = NULL,
				$alcool = NULL,
				$dconsult = NULL,
				$degre_satisfaction = NULL,
				$qualite_vie = NULL,
				$iatrogenie = NULL,
				$deliv_trait = NULL,
				$regul_prises = NULL,
				$cpt_rendu = NULL
		){
		$this->id = $id;
		$this->date = $date;
		$this->poids = $poids;
		$this->dpoids = $dpoids;
		$this->TaSys = $TaSys;
		$this->TaDia = $TaDia;
		$this->TA_mode = $TA_mode;
		$this->obj_tension = $obj_tension;
		$this->dtension = $dtension;
		$this->dcoeur = $dcoeur;
		$this->dartere = $dartere;
		$this->dpouls = $dpouls;
		$this->dsouffle = $dsouffle;
		$this->Creat = $Creat;
		$this->dcreat = $dcreat;
		$this->glycemie = $glycemie;
		$this->dglycemie = $dglycemie;
		$this->kaliemie = $kaliemie;
		$this->dkaliemie = $dkaliemie;
		$this->HDL = $HDL;
		$this->dChol = $dChol;
		$this->LDL = $LDL;
		$this->dLDL = $dLDL;
		$this->proteinurie = $proteinurie;
		$this->dproteinurie = $dproteinurie;
		$this->hematurie = $hematurie;
		$this->dhematurie = $dhematurie;
		$this->dfond = $dfond;
		$this->dECG = $dECG;
		$this->hta_instable = $hta_instable;
		$this->hta_tritherapie = $hta_tritherapie;
		$this->hta_complique = $hta_complique;
		$this->tabac = $tabac;
		$this->hyperlipidemie = $hyperlipidemie;
		$this->alcool = $alcool;
		$this->dconsult = $dconsult;
		$this->degre_satisfaction = $degre_satisfaction;
		$this->qualite_vie = $qualite_vie;
		$this->iatrogenie = $iatrogenie;
		$this->deliv_trait = $deliv_trait;
		$this->regul_prises = $regul_prises;
		$this->cpt_rendu = $cpt_rendu;

	}

	 function toString(){
		 return
		$this->id." ".
		$this->date." ".
		$this->poids." ".
		$this->dpoids." ".
		$this->TaSys." ".
		$this->TaDia." ".
		$this->TA_mode." ".
		$this->obj_tension." ".
		$this->dtension." ".
		$this->dcoeur." ".
		$this->dartere." ".
		$this->dpouls." ".
		$this->dsouffle." ".
		$this->Creat." ".
		$this->dcreat." ".
		$this->glycemie." ".
		$this->dglycemie." ".
		$this->kaliemie." ".
		$this->dkaliemie." ".
		$this->HDL." ".
		$this->dChol." ".
		$this->LDL." ".
		$this->dLDL." ".
		$this->proteinurie." ".
		$this->dproteinurie." ".
		$this->hematurie." ".
		$this->dhematurie." ".
		$this->dfond." ".
		$this->dECG." ".
		$this->hta_instable." ".
		$this->hta_tritherapie." ".
		$this->hta_complique." ".
		$this->tabac." ".
		$this->hyperlipidemie." ".
		$this->alcool." ".
		$this->dconsult." ".
		$this->degre_satisfaction." ".
		$this->qualite_vie." ".
		$this->iatrogenie." ".
		$this->deliv_trait." ".
		$this->regul_prises." ".
		$this->cpt_rendu;

	}


	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->date = dateToMysqlDate($clone->date);
		$clone->dpoids = dateToMysqlDate($clone->dpoids);
		$clone->dtension = dateToMysqlDate($clone->dtension);
		$clone->dcoeur = dateToMysqlDate($clone->dcoeur);
		$clone->dartere = dateToMysqlDate($clone->dartere);
		$clone->dpouls = dateToMysqlDate($clone->dpouls);
		$clone->dsouffle = dateToMysqlDate($clone->dsouffle);
		$clone->dcreat = dateToMysqlDate($clone->dcreat);
		$clone->dglycemie = dateToMysqlDate($clone->dglycemie);
		$clone->dkaliemie = dateToMysqlDate($clone->dkaliemie);
		$clone->dChol = dateToMysqlDate($clone->dChol);
		$clone->dLDL = dateToMysqlDate($clone->dLDL);
		$clone->dproteinurie = dateToMysqlDate($clone->dproteinurie);
		$clone->dhematurie = dateToMysqlDate($clone->dhematurie);
		$clone->dfond = dateToMysqlDate($clone->dfond);
		$clone->dECG = dateToMysqlDate($clone->dECG);
		$clone->dconsult = dateToMysqlDate($clone->dconsult);

		return $clone;
	}

	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->date = mysqlDateTodate($clone->date);
		$clone->dpoids = mysqlDateTodate($clone->dpoids);
		$clone->dtension = mysqlDateTodate($clone->dtension);
		$clone->dcoeur = mysqlDateTodate($clone->dcoeur);
		$clone->dartere = mysqlDateTodate($clone->dartere);
		$clone->dpouls = mysqlDateTodate($clone->dpouls);
		$clone->dsouffle = mysqlDateTodate($clone->dsouffle);
		$clone->dcreat = mysqlDateTodate($clone->dcreat);
		$clone->dglycemie = mysqlDateTodate($clone->dglycemie);
		$clone->dkaliemie = mysqlDateTodate($clone->dkaliemie);
		$clone->dChol = mysqlDateTodate($clone->dChol);
		$clone->dLDL = mysqlDateTodate($clone->dLDL);
		$clone->dproteinurie = mysqlDateTodate($clone->dproteinurie);
		$clone->dhematurie = mysqlDateTodate($clone->dhematurie);
		$clone->dfond = mysqlDateTodate($clone->dfond);
		$clone->dECG = mysqlDateTodate($clone->dECG);
		$clone->dconsult = mysqlDateTodate($clone->dconsult);

		return $clone;
	}

	function getIMC($taille){
		require_once("tools/formulas.php");
		return getIMC($this->poids,$taille);
	}

	function getClearance($dossier){
		require_once("tools/formulas.php");
		return getClearance($dossier->sexe,$this->poids,$dossier->getAge(),$this->Creat);
	}

	function getHDLc($dossier){
		if($dossier->sexe == "F") return "0.40";
		if($dossier->sexe == "M") return "0.35";
		else return "0";
	}

	function isHDLPathologic($dossier){
		if($this->HDL == NULL) return false;
		if($this->HDL == "") return false;
		if($this->HDL < $this->getHDLc($dossier)) return true;
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


	function IsOutDatedPoids($month){
		$elderDate = $this->dpoids;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
		$refDate = increaseDateBy($elderDate,0,6,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate;
	}


	function IsOutDatedtension($month){
//	    return "ND";
		$elderDate = $this->dtension;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
		$refDate = increaseDateBy($elderDate,0,3,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate;
	}

	function IsOutDatedcoeur($month){
		$elderDate = $this->dcoeur;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
		$refDate = increaseDateBy($elderDate,0,12,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
//		echo "oik";
		return $refDate;
	}

	function IsOutDatedartere($month){
		$elderDate = $this->dartere;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
		$refDate = increaseDateBy($elderDate,0,12,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate;
	}

	function IsOutDatedpouls($month){
		$elderDate = $this->dpouls;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
		$refDate = increaseDateBy($elderDate,0,12,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate;
	}

	function IsOutDatedsouffle($month){
		$elderDate = $this->dsouffle;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
		$refDate = increaseDateBy($elderDate,0,12,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate;
	}


	function IsOutDatedcreat($month){
		$elderDate = $this->dcreat;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
		$refDate = increaseDateBy($elderDate,0,12,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate;
	}

	function IsOutDatedglycemie($month){
		$elderDate = $this->dglycemie;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
		$refDate = increaseDateBy($elderDate,0,12,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate;
	}

	function IsOutDatedkaliemie($month){
		$elderDate = $this->dkaliemie;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
		$refDate = increaseDateBy($elderDate,0,12,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate;
	}

	function IsOutDatedHDL($month){
		$elderDate = $this->dChol;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
		$refDate = increaseDateBy($elderDate,0,36,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate;
	}

	function IsOutDatedLDL($month){
		$elderDate = $this->dLDL;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
		$refDate = increaseDateBy($elderDate,0,36,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate;
	}

	function IsOutDatedproteinurie($month){
		$elderDate = $this->dproteinurie;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
		$refDate = increaseDateBy($elderDate,0,12,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate;
	}

	function IsOutDatedhematurie($month){
		$elderDate = $this->dhematurie;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
//		$refDate = increaseDateBy($elderDate,0,12,0);
//		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
//		if(compare($refDate,$actualDate)>0) return false;
		return false;
	}

	function IsOutDatedfond($month){
		$elderDate = $this->dfond;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
		$refDate = increaseDateBy($elderDate,0,12,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate;
	}

	function IsOutDatedECG($month){
		$elderDate = $this->dECG;
		if(is_null($elderDate)) return "ND";
		if($elderDate == "") return "ND";
		$refDate = increaseDateBy($elderDate,0,36,0);
		$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
		if(compare($refDate,$actualDate)>0) return false;
		return $refDate;
	}

						   
	function isOutdated($month =0 ,$type){
		switch($type){
			case "Poids": return $this->isOutdatedPoids($month);
			case "Tension": return $this->isOutdatedTension($month);
			case "Examen Cardio-vasculaire": return $this->isOutdatedcoeur($month);
			case "Auscultation des artères": return $this->isOutdatedartere($month);
			case "Palpation des pouls périphériques": return $this->isOutdatedpouls($month);
			case "Recherche d'un souffle abdominal": return $this->isOutdatedsouffle($month);
			case "Créatinine": return $this->isOutdatedcreat($month);
			case "Glycémie": return $this->isOutdatedglycemie($month);
			case "Kaliémie": return $this->isOutdatedkaliemie($month);
			case "Cholestérol HDL": return $this->isOutdatedHDL($month);
			case "Cholestérol LDL": return $this->isOutdatedLDL($month);
			case "Protéinurie": return $this->isOutdatedproteinurie($month);
			case "Hématurie": return $this->isOutdatedhematurie($month);
			case "Fond d'oeil": return $this->isOutdatedfond($month);
			case "ECG": return $this->isOutdatedECG($month);
		}
	}


	function check(){
		$errors = array();

		$i=0;

	if($this->dpoids == ""){
		if(($this->poids != "")and ($this->poids != 0)){
			$errors[$i++] ="Date du poids n'est pas saisie";
		}
	}

	if($this->poids == ""){
		if($this->dpoids != ""){
			$errors[$i++] ="La valeur poids n'est pas saisie";
		}
	}


	if($this->dpoids != "" and $this->poids != "" and $this->poids != 0){
		if(!is_numeric($this->poids) or $this->poids<0 or $this->poids > 200)
			$errors[$i++] ="Valeur du poids invalide";
		if(!isValidDate($this->dpoids))
			$errors[$i++] ="Date du poids";
	}
	

	if(($this->TaDia < 35) or ($this->TaDia >150) or ($this->TaSys <70) or ($this->TaSys > 300))
		$errors[$i++] ="Tension artérelle invalide";

	if($this->TA_mode != "manuel")
		if($this->TA_mode !="automatique")
			if($this->TA_mode != "automesure")
					$errors[$i++] ="Selectionnez le mode de mesure de la tension artérelle";

	if($this->obj_tension!='oui')
	    if($this->obj_tension!='non')
	        $errors[$i++] = "Indiquez si l'objetif tensionnel est atteint";
	
	if($this->dtension == ""){
			$errors[$i++] ="Date de la tension n'est pas saisie";
	}

	if(!isValidDate($this->dtension))
		$errors[$i++] ="Date de la tension invalide";


	if($this->dcoeur != ""){
		if(!isValidDate($this->dcoeur))
			$errors[$i++] ="Date d'auscultation du coeur invalide";
	}


	if($this->dartere != ""){
		if(!isValidDate($this->dartere))
			$errors[$i++] ="Date d'auscultation des arteres invalide";
	}

	if($this->dpouls != ""){
		if(!isValidDate($this->dpouls))
			$errors[$i++] ="Date de palpation des pouls périphériques invalide";
	}
	
	if($this->dsouffle != ""){
		if(!isValidDate($this->dsouffle))
			$errors[$i++] ="Date de recherche du souffle abdominal invalide";
	}


	if($this->dcreat == ""){
		if(($this->Creat != "") && ($this->Creat != 0)){
			$errors[$i++] ="Date Créatinine n'est pas saisie";
		}
	}
	else if($this->Creat == ""){
		$errors[$i++] ="Créatinine n'est pas saisie";
	}

	if($this->dcreat != "" and $this->Creat != ""){
		if(!is_numeric($this->Creat) or $this->Creat<=0)
			$errors[$i++] ="Créatinine invalide";
		if(!isValidDate($this->dcreat))
			$errors[$i++] ="Date de la mesure Créatinine invalide";
	}


	if($this->dglycemie == ""){
		if(($this->glycemie != "") && ($this->glycemie != 0)){
			$errors[$i++] ="Date de la glycémie n'est pas saisie";
		}
	}
	else if($this->glycemie == ""){
		$errors[$i++] ="La glycémie n'est pas saisie";
	}

	if($this->dglycemie != "" and $this->glycemie != ""){
		if(!is_numeric($this->glycemie) or $this->glycemie<=0)
			$errors[$i++] ="Glycémie invalide";
		if(!isValidDate($this->dglycemie))
			$errors[$i++] ="Date de la mesure glycémie invalide";
	}

	if($this->dkaliemie == ""){
		if(($this->kaliemie != "") && ($this->kaliemie != 0)){
			$errors[$i++] ="Date kaliémie n'est pas saisie";
		}
	}
	else if($this->kaliemie == ""){
		$errors[$i++] ="Kaliémie n'est pas saisie";
	}

	if($this->dkaliemie != "" and $this->kaliemie != ""){
		if(!is_numeric($this->kaliemie) or $this->kaliemie<=0)
			$errors[$i++] ="Kaliémie invalide";
		if(!isValidDate($this->dkaliemie))
			$errors[$i++] ="Date de la kaliémie invalide";
	}

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
			$errors[$i++] ="HDL invalide";
		if(!isValidDate($this->dChol))
			$errors[$i++] ="Date du HDL invalide";
	}

	if($this->dLDL == ""){
		if(($this->LDL != "") && ($this->LDL != 0)){
			$errors[$i++] ="Date LDL n'est pas saisie";
		}
	}
	else if(($this->LDL == "") && ($this->LDL != 0)){
		$errors[$i++] ="LDL n'est pas saisie";
	}

	if($this->dLDL != "" and $this->LDL != ""){
		if(!is_numeric($this->LDL) or $this->LDL<=0)
			$errors[$i++] ="LDL invalide";
		if(!isValidDate($this->dLDL))
			$errors[$i++] ="Date du LDL invalide";
	}

	if($this->dproteinurie == ""){
		if($this->proteinurie != ""){
			$errors[$i++] ="Date proteinurie n'est pas saisie";
		}
	}


	if($this->dhematurie == ""){
		if($this->hematurie != ""){
			$errors[$i++] ="Date hématurie n'est pas saisie";
		}
	}

	if($this->dfond != ""){
		if(!isValidDate($this->dfond))
			$errors[$i++] ="Date fond d'oeil";
	}

	if($this->dECG != ""){
		if(!isValidDate($this->dECG))
			$errors[$i++] ="Date d'ECG";
	}

	if($this->dconsult != ""){
		if(!isValidDate($this->dconsult))
			$errors[$i++] ="Date consultation";
	}

		return $errors;
						
	}
}
 ?>
