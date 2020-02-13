<?php

require_once("tools/date.php");

class PremiereConsultCardio{
	  var $id;
	  var $date;
	  var $objectif_poids;
	  var $commentaire_obj_poids;
	  var $objectif_alcool;
	  var $commentaire_obj_alcool;
	  var $objectif_tabac;
	  var $commentaire_obj_tabac;
	  var $objectif_tension;
	  var $commentaire_obj_tension;
	  var $conseil_sel;
	  var $brochure_sel1;
	  var $brochure_sel2;
	  var $commentaire_sel;
	  var $conseil_alcool;
	  var $brochure_alcool1;
	  var $brochure_alcool2;
	  var $commentaire_alcool;
	  var $conseil_activite;
	  var $brochure_activite1;
	  var $brochure_activite2;
	  var $commentaire_activite;
	  var $conseil_tabac;
	  var $brochure_tabac1;
	  var $brochure_tabac2;
	  var $commentaire_tabac;
	  var $conseil_poids;
	  var $brochure_poids1;
	  var $brochure_poids2;
	  var $commentaire_poids;
	  var $conseil_alim;
	  var $brochure_alim1;
	  var $brochure_alim2;
	  var $commentaire_alim;
	  var $conseil_cafe;
	  var $brochure_cafe1;
	  var $brochure_cafe2;
	  var $commentaire_cafe;
	  var $degre_satisfaction;
	  var $duree;
	  var $consult_domicile;
	  var $points_positifs;
	  var $points_ameliorations;


	function PremiereConsultCardio(
					 $id = NULL,
					 $date = NULL,
					 $objectif_poids = NULL,
					 $commentaire_obj_poids = NULL,
					 $objectif_alcool = NULL,
					 $commentaire_obj_alcool = NULL,
					 $objectif_tabac = NULL,
					 $commentaire_obj_tabac = NULL,
					 $objectif_tension = NULL,
					 $commentaire_obj_tension = NULL,
					 $conseil_sel = NULL,
					 $brochure_sel1 = NULL,
					 $brochure_sel2 = NULL,
					 $commentaire_sel = NULL,
					 $conseil_alcool = NULL,
					 $brochure_alcool1 = NULL,
					 $brochure_alcool2 = NULL,
					 $commentaire_alcool = NULL,
					 $conseil_activite = NULL,
					 $brochure_activite1 = NULL,
					 $brochure_activite2 = NULL,
					 $commentaire_activite = NULL,
					 $conseil_tabac = NULL,
					 $brochure_tabac1 = NULL,
					 $brochure_tabac2 = NULL,
					 $commentaire_tabac = NULL,
					 $conseil_poids = NULL,
					 $brochure_poids1 = NULL,
					 $brochure_poids2 = NULL,
					 $commentaire_poids = NULL,
					 $conseil_alim = NULL,
					 $brochure_alim1 = NULL,
					 $brochure_alim2 = NULL,
					 $commentaire_alim = NULL,
					 $conseil_cafe = NULL,
					 $brochure_cafe1 = NULL,
					 $brochure_cafe2 = NULL,
					 $commentaire_cafe = NULL,
					 $degre_satisfaction = NULL,
					 $duree = NULL,
					 $consult_domicile = NULL,
					 $points_positifs = NULL,
					 $points_ameliorations = NULL){
		 $this->id = $id;
		 $this->date = $date;
		 $this->objectif_poids = $objectif_poids;
		 $this->commentaire_obj_poids = $commentaire_obj_poids;
		 $this->objectif_alcool = $objectif_alcool;
		 $this->commentaire_obj_alcool = $commentaire_obj_alcool;
		 $this->objectif_tabac = $objectif_tabac;
		 $this->commentaire_obj_tabac = $commentaire_obj_tabac;
		 $this->objectif_tension = $objectif_tension;
		 $this->commentaire_obj_tension = $commentaire_obj_tension;
		 $this->conseil_sel = $conseil_sel;
		 $this->brochure_sel1 = $brochure_sel1;
		 $this->brochure_sel2 = $brochure_sel2;
		 $this->commentaire_sel = $commentaire_sel;
		 $this->conseil_alcool = $conseil_alcool;
		 $this->brochure_alcool1 = $brochure_alcool1;
		 $this->brochure_alcool2 = $brochure_alcool2;
		 $this->commentaire_alcool = $commentaire_alcool;
		 $this->conseil_activite = $conseil_activite;
		 $this->brochure_activite1 = $brochure_activite1;
		 $this->brochure_activite2 = $brochure_activite2;
		 $this->commentaire_activite = $commentaire_activite;
		 $this->conseil_tabac = $conseil_tabac;
		 $this->brochure_tabac1 = $brochure_tabac1;
		 $this->brochure_tabac2 = $brochure_tabac2;
		 $this->commentaire_tabac = $commentaire_tabac;
		 $this->conseil_poids = $conseil_poids;
		 $this->brochure_poids1 = $brochure_poids1;
		 $this->brochure_poids2 = $brochure_poids2;
		 $this->commentaire_poids = $commentaire_poids;
		 $this->conseil_alim = $conseil_alim;
		 $this->brochure_alim1 = $brochure_alim1;
		 $this->brochure_alim2 = $brochure_alim2;
		 $this->commentaire_alim = $commentaire_alim;
		 $this->conseil_cafe = $conseil_cafe;
		 $this->brochure_cafe1 = $brochure_cafe1;
		 $this->brochure_cafe2 = $brochure_cafe2;
		 $this->commentaire_cafe = $commentaire_cafe;
		 $this->degre_satisfaction = $degre_satisfaction;
		 $this->duree = $duree;
		 $this->consult_domicile = $consult_domicile;
		 $this->points_positifs = $points_positifs;
		 $this->points_ameliorations = $points_ameliorations;
					 
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->date." ".
			$this->objectif_poids." ".
			$this->commentaire_obj_poids." ".
			$this->objectif_alcool." ".
			$this->commentaire_obj_alcool." ".
			$this->objectif_tabac." ".
			$this->commentaire_obj_tabac." ".
			$this->objectif_tension." ".
			$this->commentaire_obj_tension." ".
			$this->conseil_sel." ".
			$this->brochure_sel1." ".
			$this->brochure_sel2." ".
			$this->commentaire_sel." ".
			$this->conseil_alcool." ".
			$this->brochure_alcool1." ".
			$this->brochure_alcool2." ".
			$this->commentaire_alcool." ".
			$this->conseil_activite." ".
			$this->brochure_activite1." ".
			$this->brochure_activite2." ".
			$this->commentaire_activite." ".
			$this->conseil_tabac." ".
			$this->brochure_tabac1." ".
			$this->brochure_tabac2." ".
			$this->commentaire_tabac." ".
			$this->conseil_poids." ".
			$this->brochure_poids1." ".
			$this->brochure_poids2." ".
			$this->commentaire_poids." ".
			$this->conseil_alim." ".
			$this->brochure_alim1." ".
			$this->brochure_alim2." ".
			$this->commentaire_alim." ".
			$this->conseil_cafe." ".
			$this->brochure_cafe1." ".
			$this->brochure_cafe2." ".
			$this->commentaire_cafe." ".
			$this->degre_satisfaction." ".
			$this->duree." ".
			$this->consult_domicile." ".
			$this->points_positifs." ".
			$this->points_ameliorations;
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
