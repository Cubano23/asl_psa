<?php

require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");
$table = "account";
$rs = mysql_query("select infirmiere from $table");

$items = array();
$row =   array();
while(list($cab ) = mysql_fetch_row($rs))
{
    $value = $cab;
    $value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
    $row["inf"] = $value;
    $row["inf_t"] = $value;
    array_push($items, $row);
}

echo json_encode($items);

?>
