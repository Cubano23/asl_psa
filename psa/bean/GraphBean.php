<?php
	class GraphBean{
	
		var $cabinets;
		var $stepsNum;
		var $zoom;
		var $startYear;
		var $startMonth;
		var $endYear;
		var $endMonth;
		
		function GraphBean(){
			$this->stepsNum = 25;
			$this->zoom = 5;
			
			$this->startYear = date("Y");
			$this->startMonth = "01";
			$this->endYear = date("Y");
			$this->endMonth = date("m");									
		}
		
	}
?>