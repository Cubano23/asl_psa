
<?php

$file = $_GET["file"];
$rep = $_GET["rep"];

header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="'.$file.'"');
readfile($rep.$file);
?>


