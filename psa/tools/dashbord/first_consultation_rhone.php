<?php


$serveur = 'localhost';

$idDB = 'root';
$mdpDB = 'root';
$DB = 'isas';

$idDB = 'informed';
$mdpDB = 'no11iugX';
$DB = 'informed3';
mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter &agrave; la base");

$q = "SELECT * FROM temp_dashboard WHERE is_ok=0 ORDER BY cabinet";
$r = mysql_query($q);
while($tab = mysql_fetch_array($r))
{
	$q = "SELECT date FROM evaluation_infirmier as e INNER JOIN dossier as d ON e.id=d.id WHERE cabinet='".$tab['cabinet']."' ORDER BY date ASC LIMIT 1";
	$r2 = mysql_query($q);
	$tab2 = mysql_fetch_assoc($r2);
	//if((date('Y-m-d H:i:s', strtotime($tab2['date'])) > '2013-02-01 00:00:01') && (date('Y-m-d H:i:s', strtotime($tab2['date'])) < '2015-02-01 00:00:01'))
	//{
		echo '<br>'.$tab['cabinet'].': '.date('d/m/Y', strtotime($tab2['date']));
	//}
}

mysql_close();

?>