<?php

require_once("tools/date.php");

class AutreConsultCardio{
	  var $id;
	  var $date;
	  var $progres_poids;
	  var $obj_poids;
	  var $progres_alcool;
	  var $obj_alcool;
	  var $progres_tabac;
	  var $obj_tabac;
	  var $progres_tension;
	  var $obj_tension;
	  var $brochure_sel1;
	  var $brochure_sel2;
	  var $commentaire_sel;
	  var $brochure_alcool1;
	  var $brochure_alcool2;
	  var $commentaire_alcool;
	  var $brochure_activite1;
	  var $brochure_activite2;
	  var $commentaire_activite;
	  var $brochure_tabac1;
	  var $brochure_tabac2;
	  var $commentaire_tabac;
	  var $brochure_poids1;
	  var $brochure_poids2;
	  var $commentaire_poids;
	  var $brochure_alim1;
	  var $brochure_alim2;
	  var $commentaire_alim;
	  var $brochure_cafe1;
	  var $brochure_cafe2;
	  var $commentaire_cafe;
	  var $probleme_qualite_vie;
	  var $detail_qualite_vie;
	  var $probleme_secondaire;
	  var $detail_secondaire;
	  var $pb_delivrance;
	  var $detail_delivrance;
	  var $regularite_prise;
	  var $detail_regularite;
	  var $degre_satisfaction;
	  var $duree;
	  var $consult_domicile;
	  var $consult_tel;
	  var $consult_collective;
	  var $points_positifs;
	  var $points_ameliorations;
	  var $type_consultation;
	  var $ecg_seul;
	  var $ecg;
	  var $monofil;
	  var $exapied;
	  var $hba;
	  var $tension;
	  var $spirometre;
	  var $spirometre_seul;
	  var $t_cognitif;
	  var $autre;
	  var $prec_autre;
	  var $aspects_limitant;
	  var $aspects_facilitant;
	  var $objectifs_patient;


	function AutreConsultCardio(
					 $id = NULL,
					 $date = NULL,
					 $progres_poids = NULL,
					 $obj_poids = NULL,
					 $progres_alcool = NULL,
					 $obj_alcool = NULL,
					 $progres_tabac = NULL,
					 $obj_tabac = NULL,
					 $progres_tension = NULL,
					 $obj_tension = NULL,
					 $brochure_sel1 = NULL,
					 $brochure_sel2 = NULL,
					 $commentaire_sel = NULL,
					 $brochure_alcool1 = NULL,
					 $brochure_alcool2 = NULL,
					 $commentaire_alcool = NULL,
					 $brochure_activite1 = NULL,
					 $brochure_activite2 = NULL,
					 $commentaire_activite = NULL,
					 $brochure_tabac1 = NULL,
					 $brochure_tabac2 = NULL,
					 $commentaire_tabac = NULL,
					 $brochure_poids1 = NULL,
					 $brochure_poids2 = NULL,
					 $commentaire_poids = NULL,
					 $brochure_alim1 = NULL,
					 $brochure_alim2 = NULL,
					 $commentaire_alim = NULL,
					 $brochure_cafe1 = NULL,
					 $brochure_cafe2 = NULL,
					 $commentaire_cafe = NULL,
					 $probleme_qualite_vie = NULL,
					 $detail_qualite_vie = NULL,
					 $probleme_secondaire = NULL,
					 $detail_secondaire = NULL,
					 $pb_delivrance = NULL,
					 $detail_delivrance = NULL,
					 $regularite_prise = NULL,
					 $detail_regularite = NULL,
					 $degre_satisfaction = NULL,
					 $duree = NULL,
					 $consult_domicile = NULL,
					 $consult_tel = NULL,
					 $consult_collective = NULL,
					 $points_positifs = NULL,
					 $points_ameliorations = NULL,
					 $type_consultation = array(),
					 $ecg_seul = "",
					 $ecg = "",
					 $monofil = "",
					 $exapied = "",
					 $hba = "",
					 $tension = "",
					 $spirometre = "",
					 $spirometre_seul = "",
					 $t_cognitif = "",
					 $autre = "",
					 $prec_autre = "",
					 $aspects_limitant = "",
					 $aspects_facilitant = "",
					 $objectifs_patient = ""){
		$this->id = $id;
		$this->date = $date;
		$this->progres_poids = $progres_poids;
		$this->obj_poids = $obj_poids;
		$this->progres_alcool = $progres_alcool;
		$this->obj_alcool = $obj_alcool;
		$this->progres_tabac = $progres_tabac;
		$this->obj_tabac = $obj_tabac;
		$this->progres_tension = $progres_tension;
		$this->obj_tension = $obj_tension;
		$this->brochure_sel1 = $brochure_sel1;
		$this->brochure_sel2 = $brochure_sel2;
		$this->commentaire_sel = $commentaire_sel;
		$this->brochure_alcool1 = $brochure_alcool1;
		$this->brochure_alcool2 = $brochure_alcool2;
		$this->commentaire_alcool = $commentaire_alcool;
		$this->brochure_activite1 = $brochure_activite1;
		$this->brochure_activite2 = $brochure_activite2;
		$this->commentaire_activite = $commentaire_activite;
		$this->brochure_tabac1 = $brochure_tabac1;
		$this->brochure_tabac2 = $brochure_tabac2;
		$this->commentaire_tabac = $commentaire_tabac;
		$this->brochure_poids1 = $brochure_poids1;
		$this->brochure_poids2 = $brochure_poids2;
		$this->commentaire_poids = $commentaire_poids;
		$this->brochure_alim1 = $brochure_alim1;
		$this->brochure_alim2 = $brochure_alim2;
		$this->commentaire_alim = $commentaire_alim;
		$this->brochure_cafe1 = $brochure_cafe1;
		$this->brochure_cafe2 = $brochure_cafe2;
		$this->commentaire_cafe = $commentaire_cafe;
		$this->probleme_qualite_vie = $probleme_qualite_vie;
		$this->detail_qualite_vie = $detail_qualite_vie;
		$this->probleme_secondaire = $probleme_secondaire;
		$this->detail_secondaire = $detail_secondaire;
		$this->pb_delivrance = $pb_delivrance;
		$this->detail_delivrance = $detail_delivrance;
		$this->regularite_prise = $regularite_prise;
		$this->detail_regularite = $detail_regularite;
		$this->degre_satisfaction = $degre_satisfaction;
		$this->duree = $duree;
		$this->consult_domicile = $consult_domicile;
		$this->consult_tel = $consult_tel;
		$this->consult_collective = $consult_collective;
		$this->points_positifs = $points_positifs;
		$this->points_ameliorations = $points_ameliorations;
		$this->type_consultation = $type_consultation;
		$this->ecg_seul = $ecg_seul;
		$this->ecg = $ecg;
		$this->monofil = $monofil;
		$this->exapied = $exapied;
		$this->hba = $hba;
		$this->tension = $tension;
		$this->spirometre = $spirometre;
		$this->spirometre_seul = $spirometre_seul;
		$this->t_cognitif = $t_cognitif;
		$this->autre = $autre;
		$this->prec_autre = $prec_autre;
		$this->aspects_limitant = $aspects_limitant;
		$this->aspects_facilitant = $aspects_facilitant;
		$this->objectifs_patient = $objectifs_patient;
	}

	 function toString(){
		 return
			$this->id." ".
			$this->date." ".
			$this->progres_poids." ".
			$this->obj_poids." ".
			$this->progres_alcool." ".
			$this->obj_alcool." ".
			$this->progres_tabac." ".
			$this->obj_tabac." ".
			$this->progres_tension." ".
			$this->obj_tension." ".
			$this->brochure_sel1." ".
			$this->brochure_sel2." ".
			$this->commentaire_sel." ".
			$this->brochure_alcool1." ".
			$this->brochure_alcool2." ".
			$this->commentaire_alcool." ".
			$this->brochure_activite1." ".
			$this->brochure_activite2." ".
			$this->commentaire_activite." ".
			$this->brochure_tabac1." ".
			$this->brochure_tabac2." ".
			$this->commentaire_tabac." ".
			$this->brochure_poids1." ".
			$this->brochure_poids2." ".
			$this->commentaire_poids." ".
			$this->brochure_alim1." ".
			$this->brochure_alim2." ".
			$this->commentaire_alim." ".
			$this->brochure_cafe1." ".
			$this->brochure_cafe2." ".
			$this->commentaire_cafe." ".
			$this->probleme_qualite_vie." ".
			$this->detail_qualite_vie." ".
			$this->probleme_secondaire." ".
			$this->detail_secondaire." ".
			$this->pb_delivrance." ".
			$this->detail_delivrance." ".
			$this->regularite_prise." ".
			$this->detail_regularite." ".
			$this->degre_satisfaction." ".
			$this->duree." ".
			$this->consult_domicile." ".
			$this->consult_tel." ".
			$this->consult_collective." ".
			$this->points_positifs." ".
			$this->points_ameliorations." ";
			$this->type_consultation." ".
			$this->ecg_seul." ".
			$this->ecg." ".
			$this->monofil." ".
			$this->exapied." ".
			$this->hba." ".
			$this->tension." ".
			$this->spirometre." ".
			$this->spirometre_seul." ".
			$this->t_cognitif." ".
			$this->autre." ".
			$this->prec_autre." ".
			$this->aspects_limitant." ".
			$this->aspects_facilitant." ".
			$this->objectifs_patient;
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
		if(!isValidDate($this->date)) $errors[$i++] = "La date de la consultation est invalide";


		if(empty($this->type_consultation[0])) $errors[$i++] = "Veuillez indiquer un type de consultation";

		if(empty($this->duree)||$this->duree =='0') $errors[$i++] = "Veuillez indiquer une dur&eacute;e de consultation";

		//if( empty($this->consult_domicile) && empty($this->consult_tel) && empty($this->consult_collective) ) $errors[$i++] = "Veuillez indiquer la consultation &agrave; domicile, t&eacute;l&eacute;phonique ou collective";

		if( !empty($this->consult_domicile) && !empty($this->consult_tel) ) $errors[$i++] = "Vous devez choisir une seule consultation parmi les trois suivantes : &agrave; domicile, t&eacute;l&eacute;phonique ou collective";
		if( !empty($this->consult_domicile) && !empty($this->consult_collective) ) $errors[$i++] = "Vous devez choisir une seule consultation parmi les trois suivantes : &agrave; domicile, t&eacute;l&eacute;phonique ou collective";
		if( !empty($this->consult_tel) && !empty($this->consult_collective) ) $errors[$i++] = "Vous devez choisir une seule consultation parmi les trois suivantes : &agrave; domicile, t&eacute;l&eacute;phonique ou collective";


		return $errors;
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

	function isOutdatedProteinurie($month =0){

	    if($this->sortir_rappel!='1'){
			$elderDate = $this->dproteinurie;
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

	function isOutdatedCreat($month =0){

	    if($this->sortir_rappel!='1'){
			$elderDate = $this->dCreat;
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


	function isOutdatedChol($month =0){

	    if($this->sortir_rappel!='1'){
			$elderDate = $this->dChol;
	//		echo "<br>";
	//		print_r($this);
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return "ND";

			if((count($this->traitement)==1)&&($this->traitement[0]=='')){
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

			if((count($this->traitement)==1)&&($this->traitement[0]=='')){
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

			if((count($this->traitement)==1)&&($this->traitement[0]=='')){
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

			if((count($this->traitement)==1)&&($this->traitement[0]=='')){
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
}
 ?>
