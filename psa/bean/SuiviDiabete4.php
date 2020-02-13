<?php
class SuiviDiabete4{
	  var $id;
	  var $suivi_diabete_id;
	  var $dHBA;
	  var $ResHBA;


	function SuiviDiabete4(
					 $id = NULL,
					 $suivi_diabete_id = NULL,
					 $dHBA = NULL,
					 $ResHBA = NULL){
		 $this->id = $id;
		 $this->suivi_diabete_id = $suivi_diabete_id;
		 $this->dHBA = $dHBA;
		 $this->ResHBA = $ResHBA;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->suivi_diabete_id." ".
			$this->dHBA." ".
			$this->ResHBA;
	}

	function check(){}
	
	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->dHBA = dateToMysqlDate($clone->dHBA);
		return $clone;
	}
		
	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->dHBA = mysqlDateTodate($clone->dHBA);		
		return $clone;
	}
}
 ?>
