<?php
class SuiviDiabeteA{
	  var $id;
	  var $suivi_diabete_id;
	  var $dChol;
	  var $iChol;
	  var $HDL;
	  var $dLDL;
	  var $iLDL;
	  var $LDL;
	  var $dCreat;
	  var $Creat;
	  var $CreatC;
	  var $iCreat;
	  var $dAlbu;
	  var $iAlbu;
	  var $dFond;
	  var $iFond;
	  var $dECG;
	  var $iECG;

	  var $triglycerides;
	  var $dTriglycerides;
	  var $kaliemie;
	  var $dKaliemie;



	function SuiviDiabeteA(
					 $id = NULL,
					 $suivi_diabete_id = NULL,
					 $dChol = NULL,
					 $iChol = NULL,
					 $HDL = NULL,
					 $dLDL = NULL,
					 $iLDL = NULL,
					 $LDL = NULL,
					 $dCreat = NULL,
					 $Creat = NULL,
					 $CreatC = NULL,
					 $iCreat = NULL,
					 $dAlbu = NULL,
					 $iAlbu = NULL,
					 $dFond = NULL,
					 $iFond = NULL,
					 $dECG = NULL,
					 $iECG = NULL,
					$triglycerides = NULL,
				  	$dTriglycerides =NULL,
				  	$kaliemie = NULL,
				  	$dKaliemie = NULL){
		 $this->id = $id;
		 $this->suivi_diabete_id = $suivi_diabete_id;
		 $this->dChol = $dChol;
		 $this->iChol = $iChol;
		 $this->HDL = $HDL;
		 $this->dLDL = $dLDL;
		 $this->iLDL = $iLDL;
		 $this->LDL = $LDL;
		 $this->dCreat = $dCreat;
		 $this->Creat = $Creat;
		 $this->CreatC = $CreatC;
		 $this->iCreat = $iCreat;
		 $this->dAlbu = $dAlbu;
		 $this->iAlbu = $iAlbu;
		 $this->dFond = $dFond;
		 $this->iFond = $iFond;
		 $this->dECG = $dECG;
		 $this->iECG = $iECG;
		 $this->$triglycerides = $triglycerides;
	 	 $this->$dTriglycerides = $dTriglycerides;
	  	 $this->$kaliemie = $kaliemie;
	  	 $this->$dKaliemie = $dKaliemie;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->suivi_diabete_id." ".
			$this->dChol." ".
			$this->iChol." ".
			$this->HDL." ".
			$this->dLDL." ".
			$this->iLDL." ".
			$this->LDL." ".
			$this->dCreat." ".
			$this->Creat." ".
			$this->CreatC." ".
			$this->iCreat." ".
			$this->dAlbu." ".
			$this->iAlbu." ".
			$this->dFond." ".
			$this->iFond." ".
			$this->dECG." ".
			$this->iECG." ".
			$this->$triglycerides." ".
	 	 	$this->$dTriglycerides." ".
	  	 	$this->$kaliemie." ".
	  	 	$this->$dKaliemie;
	}

	function check(){}
	
	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->dChol = dateToMysqlDate($clone->dChol);
		$clone->dLDL = dateToMysqlDate($clone->dLDL);
		$clone->dCreat = dateToMysqlDate($clone->dCreat);
		$clone->dAlbu = dateToMysqlDate($clone->dAlbu);
		$clone->dECG = dateToMysqlDate($clone->dECG);
		$clone->dTriglycerides = dateToMysqlDate($clone->dTriglycerides);
		$clone->dKaliemie = dateToMysqlDate($clone->dKaliemie);
		return $clone;
	}
		
	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->dChol = mysqlDateTodate($clone->dChol);		
		$clone->dLDL = mysqlDateTodate($clone->dLDL);	
		$clone->dCreat = mysqlDateTodate($clone->dCreat);	
		$clone->dAlbu = mysqlDateTodate($clone->dAlbu);		
		$clone->dECG = mysqlDateTodate($clone->dECG);	
		$clone->dTriglycerides = mysqlDateTodate($clone->dTriglycerides);	
		$clone->dKaliemie = mysqlDateTodate($clone->dKaliemie);		
		return $clone;
	}
}
 ?>
