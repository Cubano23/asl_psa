<?php
error_reporting(E_ALL);
	// SERVEUR SQL
$sql_serveur = "localhost";

// LOGIN SQL
$sql_user = "informed";

// MOT DE PASSE SQL
$sql_passwd = "no11iugX";

// NOM DE LA BASE DE DONNEES
$sql_bdd = "informed3";

// CONNEXION SERVEUR
$link = mysql_connect($sql_serveur, $sql_user, $sql_passwd);
if (!$link) {
    die('Connexion impossible : ' . mysql_error());
}

// SELECTION BASE
$db_selected = mysql_select_db($sql_bdd, $link);
if (!$db_selected) {
   die ('Impossible de sélectionner la base de données : ' . mysql_error());
}

	
#$source="les_115_restants_Lucquin.csv";
$source="Changement+de+numpatient+V3.csv";
$contenu = array();
$contenu=file($source);

$tableau = array();

while ( list( $numero_ligne, $ligne) = each( $contenu ) ){

	$tableau = explode(";",$ligne);
	
	$val1 = trim($tableau[0]); //numero existant
	$val2 = trim($tableau[1]); 
	#$val3 = trim($tableau[7]);

	#$req="update dossier set numero='A$val1' where numero='$val1' and cabinet='Lucquin' "; // 1ere etape on met A devant les numeros de la liste de pierre (393)
	
	#$req="update dossier set numero='old$val2' where numero='$val2' and cabinet='Lucquin' "; // 2em etape old devant les restants qui ne sont pas ds la liste (115)

	#$req="update dossier set numero='$val2' where numero='A$val1' and cabinet='Lucquin' "; // 3em etape on change les numeros Axyz vers les nouveaux qui sont ds la colonne 2
	$sql = mysql_query($req);
	echo $req;
	




	// if($val3=='deja'){
	// $req="update dossier set numero='old$val2' where numero='$val2' and cabinet='Lucquin' ";
	// $sql = mysql_query($req);
	// echo $req;
	// }
	
}
?>
