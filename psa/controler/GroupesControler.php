<?php 
require_once("bean/GroupesDossiers.php");
require_once("bean/ControlerParams.php");
require_once("GenericControler.php");
	
class GroupesControler{
	

	var $mappingTable;
		
	function GroupesControlerControler() {
		$this->mappingTable = array(
		 	"URL_MANAGE"=>"view/dossier/liste_groupes.php",
			"URL_AFTER_DELETE"=>new ControlerParams("GroupesControler",ACTION_MANAGE,true)
		);
	}


	function start() {

		// variables inherited from ActionControler
		global $account;
		global $objects;
		global $param;
		global $dossier;


		$ledgerFactory = new LedgerFactory();
		$ledger = $ledgerFactory->getLedger("Controler","GroupesControler");

		//Create connection factory
		$cf = new ConnectionFactory();
		$cf->getConnection();

		//create mappers
		//$SuiviHebdomadaireMapper = new SuiviHebdomadaireMapper($cf->getConnection());

		$ledger->writeArray(I,"Start","Control Parameters = ",$param);

		switch($param->action){
			case ACTION_MANAGE:
				//forward($this->mappingTable["URL_MANAGE"]);	// TODO
				forward("view/dossier/liste_groupes.php");
				break;
			case ACTION_DELETE:
				GroupesDossiers::disableGroupeById($param->id, $account->cabinet);
				forward("view/dossier/liste_groupes.php");
				//forward($this->mappingTable["URL_AFTER_DELETE"]);	// TODO
				break;
		}
	}


}

?> 
