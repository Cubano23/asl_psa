<?php

require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;

#$infirmieres = GetLoginsByCab('casseneuil', &$status);

$cabinets = GetCabsByLogin('slamy', &$status);

var_dump( $cabinets );

?>