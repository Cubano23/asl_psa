<?php

/**
 * Created by Codelobster
 * User: Gisgo
 * Date: 22-11-2018
 * Time: 14:40
 */

require_once("tools/date.php");

class Rib{
	  var $id;
	  var $date_demande;
	  var $inf_login;
	  var $pj;


	function Rib(
					 $id = NULL,
					 $date_demande = NULL,
					 $inf_login = NULL,									 
					 $pj = NULL){
		 $this->id = $id;
		 $this->date_demande = $date_demande;
		 $this->inf_login = $inf_login;
		 $this->pj = $pj;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->date_demande." ".
			$this->inf_login." ".
			$this->pj;
	}

	
	
	


}
 ?>

