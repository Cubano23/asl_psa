<?php 
 require("LedgerFactory.php");
 
$lf = new LedgerFactory();
$ledger = $lf->getLedger("TestLayer","TestModule",W);

$ledger->write(2,"TestOp","This is a Warning");

?>