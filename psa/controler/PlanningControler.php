<?php

// tout ce qui concerne les planning infirmières


class PlanningControler{
	

	static function monPlanning(){



	} 


	static function postModifPlanning(){

			$userCurrent = $_SESSION['id.login'];

		
			if($userCurrent=='arizk'){
				$userCurrent = 'asravier';
			}

			$cab = $_POST['cab'];
			$lundi = addslashes($_POST['lundi']);
			$mardi = addslashes($_POST['mardi']);
			$mercredi = addslashes($_POST['mercredi']);
			$jeudi = addslashes($_POST['jeudi']);
			$vendredi = addslashes($_POST['vendredi']);
			$samedi = addslashes($_POST['samedi']);

			$response = PlanningInfirmieres::recordPlanning($userCurrent,$cab,$lundi,$mardi,$mercredi,$jeudi,$vendredi,$samedi);
		
	return $response;

	}



}