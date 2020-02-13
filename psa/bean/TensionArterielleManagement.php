<?php
	class TensionArterielleManagement {
		var $dateDebut;
		var $nombreJours;
		var $group_id;
		
		function TensionArterielleManagement($dateDebut = NULL,$nombreJours = 3, $group_id=NULL){
			$this->dateDebut = $dateDebut;
			$this->nombreJours = $nombreJours;
			$this->group_id = $group_id;
		}
	}
?>