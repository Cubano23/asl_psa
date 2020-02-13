<?php

require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/connectannuaire.php") ;

$con = DoConnect(true);

$table = "identifications";


$sql = "select login,nom,prenom from $table where recordstatus=0 ";

$rs = mysql_query($sql, $con);

$items = array();
while($row = mysql_fetch_object($rs))
{
    $rows = (array)$row;
    $cbrow=array();
    $login="";
    $nom="";
    $prenom="";

    foreach( $rows as $key => $value)
    {
        if(!is_null($value))
        {

            $value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
            if($key=="login")
                $login = $value;
            if($key=="nom")
                $nom = $value;
            if($key=="prenom")
                $prenom = $value;
        }
    }
    $cbrow["cblogin"]=$login;
    $cbrow["cblogin_t"]=$prenom." ".$nom;
    array_push($items, $cbrow);
}


echo json_encode($items);

?>
