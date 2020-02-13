<?php

# connexion aux données
mysql_connect("localhost","informed","no11iugX") or 
   die("Impossible de se connecter au SGBD");
mysql_select_db("informed3") or 
   die("Impossible de se connecter à la base");


require_once "../writeexcel/class.writeexcel_workbookbig.inc.php";
require_once "../writeexcel/class.writeexcel_worksheet.inc.php";


$fichier="Liste patients Smarves.xls";

$workbook =& new writeexcel_workbookbig($fichier); // on lui passe en paramètre le chemin de notre fichier
$worksheet =& $workbook->addworksheet("dossiers non modifiés");

$worksheet->write("A1","Id (identifiant interne au système asalée)");
$worksheet->write("B1","Ancien numéro dans le cabinet");
$worksheet->write("C1","Date de naissance");
$worksheet->write("D1","Sexe");
$worksheet->write("E1","Taille");

$l=1;
$req="SELECT id, numero, date_format(dnaiss, '%d/%m/%Y'), sexe, taille ".
	 "from dossier where cabinet='Smarves' and numero not like 'N%'";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

while(list($id, $numero, $dnaiss, $sexe, $taille)=mysql_fetch_row($res)){

	$l++;
	$worksheet->write("A$l","$id");
	$worksheet->write("B$l","$numero");
	$worksheet->write("C$l","$dnaiss");
	$worksheet->write("D$l","$sexe");
	$worksheet->write("E$l","$taille");
	
}

$worksheet =& $workbook->addworksheet("dossiers modifiés");

$worksheet->write("A1","Id (identifiant interne au système asalée)");
$worksheet->write("B1","Nouveau numéro dans le cabinet");
$worksheet->write("C1","Date de naissance");
$worksheet->write("D1","Sexe");
$worksheet->write("E1","Taille");

$l=1;
$req="SELECT id, numero, date_format(dnaiss, '%d/%m/%Y'), sexe, taille ".
	 "from dossier where cabinet='Smarves' and numero like 'N%'";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

while(list($id, $numero, $dnaiss, $sexe, $taille)=mysql_fetch_row($res)){

	$l++;
	$worksheet->write("A$l","$id");
	$worksheet->write("B$l","$numero");
	$worksheet->write("C$l","$dnaiss");
	$worksheet->write("D$l","$sexe");
	$worksheet->write("E$l","$taille");
	
}

	$workbook->close();	
	
echo "<a href='$fichier' target='_blank'>$fichier</a>";