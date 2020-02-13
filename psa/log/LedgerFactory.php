<?php 
	require_once("Ledger.php");
	
	define("DEFAULT_LEVEL",E);
	
	class LedgerFactory{
			
		function LedgerFactory(){
		}
		
		function getLedger($layerName,$moduleName,$logLevel = DEFAULT_LEVEL){
			return new Ledger($layerName,$moduleName,$logLevel);
		}
		
	}
	
	function getLedger($layerName,$moduleName,$logLevel = DEFAULT_LEVEL){
		$ledgerFactory = new LedgerFactory();
		return $ledgerFactory->getLedger($layerName,$moduleName,$logLevel);
	}
?>