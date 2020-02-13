<?php
class EvaluationPatient{
	  var $id;
	  var $date;
	  var $degre_satisfaction;
	  var $question_pat;
	  var $evol_recours_med;


	function EvaluationPatient(
					 $id = "",
					 $date = "",
					 $degre_satisfaction = "",
					 $question_pat = "",
					 $evol_recours_med = ""){
		 $this->id = $id;
		 $this->date = $date;
		 $this->degre_satisfaction = $degre_satisfaction;
		 $this->question_pat = $question_pat;
		 $this->evol_recours_med = $evol_recours_med;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->date." ".
			$this->degre_satisfaction." ".
			$this->question_pat." ".
			$this->evol_recours_med;
	}

	function check(){}
				
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
}
 ?>
