<?php
/**
 * Created by SublimeText
 * User: Gisgo
 * Date: 21-11-2018
 * Time: 14:40
 */
$file = $_GET["file"];
$filename = explode('/', $file);
$filemame = $filename[6];

if(is_file($file))
{
    header("Content-type: application/force-download");
    header("Content-Transfer-Encoding: Binary");
    header("Content-length: ".filesize($file));
    header("Content-disposition: attachment; filename=".$filemame);
    readfile("$file");
}
else
{
    echo "Le fichier $file n'existe pas";
}
