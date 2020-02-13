<?php

require_once ("Config.php");
$config = new Config();

$base = $config->app_path;

$serveur="localhost";
$iDB="informed";

$mdpDB="no11iugX";
$DB="informed3";
// require("$base/informed79/inclus/accesbase.inc.php");


# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
    die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
    die("Impossible de se connecter à la base");


$table = "integration";


$dirname = $base . $config->psa_path . "/view/integration/log";
$cpt= "Compte-rendu integration ";

//$dir = opendir($dirname);
$ext=array(".xls", ".zip");

$pattern =      $dirname."/".$cpt."*";
$files = glob($pattern,GLOB_NOSORT );

// echo ($files);                           <?php
foreach ($files as $filename) {

    //while($file = readdir($dir)) {

    $file =  basename($filename);
    $sstr = substr($file, strlen($cpt),strlen($file) - strlen($cpt) );
    $sstr= str_replace($ext, "", $sstr);


    $tokens= explode(    " ", $sstr );

    $cabinet= $tokens[0];
    $dintegration=$tokens[1];
    $reportfile= $file;


    echo ($file."\n");
    // logiciel
    $sql = "select logiciel from account where cabinet='$cabinet'";
    $rs = mysql_query( $sql);
    list($row) = mysql_fetch_row($rs);
    $logiciel=$row;


    $tokens  = explode(    "-", $dintegration );
    $dintegration = $tokens[2]."-".$tokens[1]."-".$tokens[0];
    $reportfile = '<a href="integration_logs/'.$reportfile.'" target="_blank">'.$reportfile.'</a>';
    $sql2="INSERT  INTO integration(cabinet, logiciel, dintegration, entryfile, reportfile, cr, tintegration)".
        " VALUES ('$cabinet','$logiciel','$dintegration','Importedrow','$reportfile',0,0)";
    //      echo ($sql2."\n");
    //       $rs = mysql_query($sql2);
}




//closedir($dir);


?>
