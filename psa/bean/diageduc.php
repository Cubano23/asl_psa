<?php

require_once("tools/date.php");

class diageduc{
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


	function diageduc(
					 $id = NULL,
					 $date = NULL,
					 $objectif_poids = NULL,
					 $commentaire_obj_poids = NULL,
					 $objectif_alcool = NULL,
					 $commentaire_obj_alcool = NULL,
					 $objectif_tabac = NULL,
					 $commentaire_obj_tabac = NULL,
					 $objectif_tension = NULL,
					 $commentaire_obj_tension = NULL){
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
			$this->commentaire_obj_tension;
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
