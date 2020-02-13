<?php
class SuiviDiabeteS{
	  var $id;
	  var $suivi_diabete_id;
	  var $dExaFil;
	  var $ExaFil;
	  var $dExaPieds;
	  var $ExaPieds;


	function SuiviDiabeteS(
					 $id = NULL,
					 $suivi_diabete_id = NULL,
					 $dExaFil = NULL,
					 $ExaFil = NULL,
					 $dExaPieds = NULL,
					 $ExaPieds = NULL){
		 $this->id = $id;
		 $this->suivi_diabete_id = $suivi_diabete_id;
		 $this->dExaFil = $dExaFil;
		 $this->ExaFil = $ExaFil;
		 $this->dExaPieds = $dExaPieds;
		 $this->ExaPieds = $ExaPieds;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->suivi_diabete_id." ".
			$this->dExaFil." ".
			$this->ExaFil." ".
			$this->dExaPieds." ".
			$this->ExaPieds;
	}

	function check(){}
	
	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->dExaFil = dateToMysqlDate($clone->dExaFil);
		$clone->dExaPieds = dateToMysqlDate($clone->dExaPieds);
		return $clone;
	}
		
	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->dExaFil = mysqlDateTodate($clone->dExaFil);		
		$clone->dExaPieds = mysqlDateTodate($clone->dExaPieds);	
		return $clone;
	}
}
 ?>
