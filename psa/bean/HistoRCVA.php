<?php
	class HistoRCVA{
		var $type_exam;
		var $date;
		var $valeur;

		function HistoRCVA($type_exam=NULL, $date=NULL, $valeur=NULL){
			$this->type_exam=$type_exam;
			$this->date=$date;
			$this->valeur=$valeur;
		}
		
		function toString(){
			return $this->exam_type;
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
	}
?>
