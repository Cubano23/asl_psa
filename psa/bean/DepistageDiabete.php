<?php

require_once("tools/date.php");

class DepistageDiabete{
	  var $id;
	  var $date;
	  var $dpoids;
	  var $poids;
	  var $parent_diabetique_type2;
	  var $ant_intolerance_glucose;
	  var $hypertension_arterielle;
	  var $dyslipidemie_en_charge;
	  var $hdl;
	  var $bebe_sup_4kg;
	  var $ant_diabete_gestationnel;
	  var $corticotherapie;
	  var $infection;
	  var $intervention_chirugicale;
	  var $autre;
	  var $derniere_gly_date;
	  var $derniere_gly_resultat;
	  var $prescription_gly;
//	  var $nouvelle_gly_date;
//	  var $nouvelle_gly_resultat;
//	  var $note_gly;
	  var $mesure_suivi_diabete;
	  var $mesure_suivi_hygieno_dietetique;
	  var $mesure_suivi_controle_annuel;
	  var $sortir_rappel;
	  var $raison_sortie;


	function DepistageDiabete(
					 $id = NULL,
					 $date = NULL,
					 $dpoids = NULL,
					 $poids = NULL,
					 $parent_diabetique_type2 = NULL,
					 $ant_intolerance_glucose = NULL,
					 $hypertension_arterielle = NULL,
					 $dyslipidemie_en_charge = NULL,
					 $hdl = NULL,
					 $bebe_sup_4kg = NULL,
					 $ant_diabete_gestationnel = NULL,
					 $corticotherapie = NULL,
					 $infection = NULL,
					 $intervention_chirugicale = NULL,
					 $autre = NULL,
					 $derniere_gly_date = NULL,
					 $derniere_gly_resultat = NULL,
					 $prescription_gly = NULL,
//					 $nouvelle_gly_date = NULL,
//					 $nouvelle_gly_resultat = NULL,
//					 $note_gly = NULL,
					 $mesure_suivi_diabete = NULL,
					 $mesure_suivi_hygieno_dietetique = NULL,
					 $mesure_suivi_controle_annuel = NULL,
					 $sortir_rappel = NULL,
					 $raison_sortie = NULL){
		 $this->id = $id;
		 $this->date = $date;
		 $this->dpoids = $dpoids;
		 $this->poids = $poids;
		 $this->parent_diabetique_type2 = $parent_diabetique_type2;
		 $this->ant_intolerance_glucose = $ant_intolerance_glucose;
		 $this->hypertension_arterielle = $hypertension_arterielle;
		 $this->dyslipidemie_en_charge = $dyslipidemie_en_charge;
		 $this->hdl = $hdl;
		 $this->bebe_sup_4kg = $bebe_sup_4kg;
		 $this->ant_diabete_gestationnel = $ant_diabete_gestationnel;
		 $this->corticotherapie = $corticotherapie;
		 $this->infection = $infection;
		 $this->intervention_chirugicale = $intervention_chirugicale;
		 $this->autre = $autre;
		 $this->derniere_gly_date = $derniere_gly_date;
		 $this->derniere_gly_resultat = $derniere_gly_resultat;
		 $this->prescription_gly = $prescription_gly;
//		 $this->nouvelle_gly_date = $nouvelle_gly_date;
//		 $this->nouvelle_gly_resultat = $nouvelle_gly_resultat;
//		 $this->note_gly = $note_gly;
		 $this->mesure_suivi_diabete = $mesure_suivi_diabete;
		 $this->mesure_suivi_hygieno_dietetique = $mesure_suivi_hygieno_dietetique;
		 $this->mesure_suivi_controle_annuel = $mesure_suivi_controle_annuel;
		 $this->sortir_rappel=$sortir_rappel;
		 $this->raison_sortie=$raison_sortie;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->date." ".
			$this->dpoids." ".
			$this->poids." ".
			$this->parent_diabetique_type2." ".
			$this->ant_intolerance_glucose." ".
			$this->hypertension_arterielle." ".
			$this->dyslipidemie_en_charge." ".
			$this->hdl." ".
			$this->bebe_sup_4kg." ".
			$this->ant_diabete_gestationnel." ".
			$this->corticotherapie." ".
			$this->infection." ".
			$this->intervention_chirugicale." ".
			$this->autre." ".
			$this->derniere_gly_date." ".
			$this->derniere_gly_resultat." ".
			$this->prescription_gly." ".
//			$this->nouvelle_gly_date." ".
//			$this->nouvelle_gly_resultat." ".
//			$this->note_gly." ".
			$this->mesure_suivi_diabete." ".
			$this->mesure_suivi_hygieno_dietetique." ".
			$this->mesure_suivi_controle_annuel." ".
			$this->sortir_rappel." ".
			$this->raison_sortie;
	}

	function getIMC($taille){		
		require_once("tools/formulas.php");
		return getIMC($this->poids,$taille);
	}
	
	function getDateDiff(){
		$currentDate = date("d/m/Y");
/*		if(!is_null($this->nouvelle_gly_date) and $this->nouvelle_gly_date !=""){
			return getDiffInMonth($currentDate,$this->nouvelle_gly_date);
		}
		else */
		if(!is_null($this->derniere_gly_date) and $this->derniere_gly_date !=""){
			return getDiffInMonth($currentDate,$this->derniere_gly_date);
		}
		else return "";
	}
	
	function isDiabetic(){
		$resultat = 0;

/*		if(!is_null($this->nouvelle_gly_resultat) and $this->nouvelle_gly_resultat !=""){
			if($this->nouvelle_gly_resultat > 1.26) return true;
			$resultat =  $this->nouvelle_gly_resultat;
		}*/
		if(!is_null($this->derniere_gly_resultat) and $this->derniere_gly_resultat !=""){		
			if($this->derniere_gly_resultat > 1.26) return true;
			$resultat =  $this->derniere_gly_resultat;
		}
		// False => pas de diabete 
		if($resultat != 0) return false;
		
		// NULL => le test de glycemie n'est pas encore saisi
		return NULL;
	}
		
	function check($account=NULL){											
	
		$errors = array();
		$i = 0;
		
		if(!isValidDate($this->date))
			$errors[$i++] ="La date du dépistage est invalide";
		
		$l1 = 0;
		$l2 = 0;

/*		if(!is_null($this->nouvelle_gly_date) and strlen($this->nouvelle_gly_date)!=0) $l1 = strlen($this->nouvelle_gly_date);
		if(!is_null($this->nouvelle_gly_resultat) and strlen($this->nouvelle_gly_resultat)!=0) $l2 = strlen($this->nouvelle_gly_resultat);
		if($l1  != 0 or $l2 !=0 ){
			if(!isValidDate($this->nouvelle_gly_date)) $errors[$i++] ="La date de la nouvelle glycemie est invalide";
			if(!is_numeric($this->nouvelle_gly_resultat) or $this->nouvelle_gly_resultat <0) $errors[$i++] ="Le resultat de la nouvelle glycemie est invalide";				
		}
*/	
		$l1 = 0;
		$l2 = 0;
		if(!is_null($this->derniere_gly_date) and strlen($this->derniere_gly_date)!=0) $l1 = strlen($this->derniere_gly_date);
		if(!is_null($this->derniere_gly_resultat) and strlen($this->derniere_gly_resultat)!=0) $l2 = strlen($this->derniere_gly_resultat);
		if($l1  != 0 or $l2 !=0 ){
			if(!isValidDate($this->derniere_gly_date)) $errors[$i++] ="La date de la dernière glycemie est invalide";
			if(!is_numeric($this->derniere_gly_resultat) or $this->derniere_gly_resultat <0) $errors[$i++] ="Le resultat de la dernière glycemie est invalide";				
		}

		if(!is_null($this->dpoids) and strlen($this->dpoids)!=0){
			if(!isValidDate($this->dpoids)) $errors[$i++] ="La date du poids est invalide";
		}
		
		if(($this->poids) and (!is_numeric($this->poids) or $this->poids <0 or $this->poids > 200)) $errors[$i++] ="Le poids a une valeur invalide";

		return $errors;
	}
		
	function beforeSerialisation($account){
		$clone = clone $this;
		// print_r($this);
		$clone->date = dateToMysqlDate($clone->date);
		// print_r($this);
		$clone->derniere_gly_date = dateToMysqlDate($clone->derniere_gly_date);
//		$clone->nouvelle_gly_date = dateToMysqlDate($clone->nouvelle_gly_date);
		return $clone;
	}
		
	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->date = mysqlDateTodate($clone->date);
		$clone->derniere_gly_date = mysqlDateTodate($clone->derniere_gly_date);
//		$clone->nouvelle_gly_date = mysqlDateTodate($clone->nouvelle_gly_date);
		return $clone;
	} 
	
	function haveRisks($dossier){
	
		if( $this->getIMC($dossier->taille) > 28 or
			$this->parent_diabetique_type2 == 1 or
			$this->ant_intolerance_glucose == 1 or
			$this->hypertension_arterielle == 1 or
			$this->dyslipidemie_en_charge == 1 or
			$this->hdl == 1 or
			$this->bebe_sup_4kg == 1 or
			$this->ant_diabete_gestationnel == 1 or
			$this->corticotherapie == 1 or
			$this->infection == 1 or
			$this->intervention_chirugicale == 1 or
			$this->autre == 1) return true;
			return false;
	}
	
	function isOutdated($month =0){

/*	    if(($this->nouvelle_gly_date=='0000/00/00') || ($this->nouvelle_gly_date=='NULL') || (!isset($this->nouvelle_gly_date)) ||
			(isset($this->nouvelle_gly_date) && (compare($this->nouvelle_gly_date, $this->derniere_gly_date)<0)))
	    {
			$elderDate = $this->derniere_gly_date;
			//print_r($this);
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return "ND";
			$refDate = increaseDateBy($elderDate,0,12,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
	    }
	    else
*/	    
		if($this->sortir_rappel!='1')
		{
			$elderDate = $this->derniere_gly_date;
			//print_r($this);
			if(is_null($elderDate)) return "ND";
			if($elderDate == "") return "ND";
			$refDate = increaseDateBy($elderDate,0,12,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
	    }
	    else{
	    	return false;
	    }
		return $refDate;
	}

}
 ?>
