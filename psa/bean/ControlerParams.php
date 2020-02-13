<?php 
	//Common actions 
	define("ACTION_CREATE","AC");
	define("ACTION_DELETE","AD");
	define("ACTION_FIND","AF");
	define("ACTION_LIST","AL");
	define("ACTION_MANAGE","AM");
	define("ACTION_NEW","AN");
	define("ACTION_SAVE","AS");
	define("ACTION_UPDATE","AU");
	define("ACTION_MAIN","AMEN");
	define("ACTION_MANAGE_LIST_EXAM","AMLE");
	define("ACTION_CONSULT_EVT","ACE");
	define("ACTION_HARD","HD"); // chargement de page en dur
	
	// Action for UtilityControler
	define("ACTION_CONSULT","ACONSULT");	
	
	// common parameters
	define("PARAM_EDIT","PE");
	define("PARAM_VIEW","PV");
	define("PARAM_FORCE_UPDATE","FUP");
	define("PARAM_STAND_ALONE","STDALONE");
	define("PARAM_LIST_BY_DOSSIER","PLISDOSS");
	define("PARAM_LIST_BY_DOSSIER_TOOLTIP","PLISDOSSTIP");
	define("PARAM_LIST_BY_DOSSIER_TOOLTIP_FOR_BILAN","PLISDOSSTIPFORBILAN");
	define("PARAM_LIST_BY_DOSSIER_TOOLTIP2","PLISDOSSTIP2");
	
	//RCVA
	define("PARAM_DEPART","PD");

	// parameters for SuiviDiabeteControler
	define("PARAM_SYSTEMATIQUE","PSYS");
	define("PARAM_4MOIS","P4MOIS");
	define("PARAM_SEMESTRIEL","PSEM");
	define("PARAM_ANNUEL","PANNUEL");
	define("PARAM_ANY","PANY");
	define("PARAM_INCOMPLETE","PINC");
	define("PARAM_OUTDATED","POUTD");
	define("PARAM_CREATE","PCREATE");
	define("PARAM_PRE_CREATE","PPCREAT");
	define("PARAM_LIST_BY_CABINET","PLISBCAB");
	define("PARAM_INCOMPLET", "PINCOMP");
	define("PARAM_COMPLET", "PCOMP");
	define("PARAM_TOUS", "PTOUS");
	
	// HBA GRAPH Params
	define("ACTION_GRAPH","AGRAPH");
	define("PARAM_GRAPH1","PGRAPH1");
	define("PARAM_GRAPH2","PGRAPH2");
	
	class ControlerParams{
		var $controler;
		var $action;
		var $param1;
		var $param2;
		var $param3;
		var $message;
		var $resetRequest;
		
		function ControlerParams($controler="",$action="",$resetRequest=false,$param1=NULL,$param2=NULL,$param3=NULL,$message=NULL){
			$this->controler = $controler;
			$this->action = $action;
			$this->resetRequest = $resetRequest;
			$this->param1 = $param1;
			$this->param2 = $param2;
			$this->param3 = $param3;
			$this->message = $message;
		}		
		
		function isParam1Valid(){
			if(is_null($this->param1)) return false;
			if($this->param1 == "") return false;
			if(!is_string($this->param1)) return false;
			return true;
		}
		
		function isParamNValid($n){
			$paramN = "param".$n;
			if(is_null($this->$paramN)) return false;
			if($this->$paramN == "") return false;
			if(!is_string($this->$paramN)) return false;
			return true;
		}
	}
?>
