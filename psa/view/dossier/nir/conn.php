<?php


//Function to sanitize values received from the form. Prevents SQL injection EA 08-01-2015
function clean($str, $op) {
    if ($op==1)
        $str = @trim($str);
    $str = stripslashes($str);
    return mysql_real_escape_string($str);
}

require_once "Config.php";
$config = new Config();
require($config->inclus_path . "/accesbase.inc.php");

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");

$table = "dossier";

?>