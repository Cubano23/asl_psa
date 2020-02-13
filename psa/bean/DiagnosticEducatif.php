<?php


class DiagnosticEducatif {
	

	var $id_dossier;
	var $type;
	var $created_at;
	var $clotured_at;
	var $statut;
	var $aspects_limitants;
	var $aspects_facilitants;
	var $objectifs_patient;
		

	function DiagnosticEducatif(
							$id_dossier = "",
							$type = "",
							$created_at = "",
							$clotured_at =  "",
							$statut =  "",
							$aspects_limitants =  "",
							$aspects_facilitants =  "",
							$objectifs_patient = ""){

		#$id_dossier = $type = $created_at = $clotured_at = $statut = $aspect_limitant = $aspect_facilitant = $objectif_patient = NULL;
		
		$this->id_dossier = $id_dossier;
		$this->type = $type;
		$this->created_at = $created_at;
		$this->clotured_at = $clotured_at;
		$this->statut = $statut;
		$this->aspects_limitants = $aspects_limitants;
		$this->aspects_facilitants = $aspects_facilitants;
		$this->objectifs_patient = $objectifs_patient;
					 
					 

	}

}