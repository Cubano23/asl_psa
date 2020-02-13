<?php

/**
 * Created by SublimeText
 * User: Gisgo
 * Date: 21-11-2018
 * Time: 14:40
 */

function clean($str, $op) {
    if ($op==1)
        $str = trim($str);
    $str = stripslashes($str);

    if ($str == '')
        return null;
    else
    {
        //return mysql_real_escape_string($str);
        //return $con->quote($str);
        return $str;
    }
}

require_once "Config.php";
$config = new Config();
require($config->inclus_path . "/accesbase.inc.php");

?>
