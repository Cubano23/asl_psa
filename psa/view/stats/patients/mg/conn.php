<?php

function getValue( $name)
{
    /*
        $dest = $_REQUEST[$name];
        if(is_null($dest)
    */
}

require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux donn�es
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter � la base");

$table = "medecin";
$table_historique_medecin = "historique_medecin";

?>