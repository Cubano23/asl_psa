<?php
session_start();
if(!isset($_SESSION['nom'])) {
    # pas passé par l'identification
    $debut=dirname($_SERVER['PHP_SELF']);
    $self=basename($_SERVER['PHP_SELF']);
    header("Location: $debut/ident_util.php?url=$self");
    echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
    exit;
}

?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
        <meta http-equiv="content-type"
              content="text/html; charset=ISO-8859-15">
        <title>nb patients Ruelle</title>
    </head>
    <body bgcolor=#FFE887>
<?php

require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
    die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
    die("Impossible de se connecter à la base");

require("./global/entete.php");
require_once "./writeexcel/class.writeexcel_workbookbig.inc.php";
require_once "./writeexcel/class.writeexcel_worksheet.inc.php";

$titre="Nb patients Ruelle";
entete_asalee($titre);

# initialisations
$nom = "";
$message=array();

# boucle principale
do {
    $repete=false;

    # étape 1 : identification de l'établissement
    if (!isset($_POST['etape'])) {
        etape_1($repete);
    }
    elseif($_POST['etape']==2) {
        # étape 2  : vérification du mot de passe et continuation vers l'url
        etape_2($repete);
    }
} while($repete);

exit;

# étape 1 : identification de l'établissement
function etape_1(&$repete) {
    global $message, $nom;


    extract($_POST);

    set_time_limit(0);
    $fich="./export/Patients fichier hors asalee.xls";
    $workbook =& new writeexcel_workbookbig($fich); // on lui passe en paramètre le chemin de notre fichier

    $worksheet =& $workbook->addworksheet("Patients fichier hors asalée");

    $colonnes=array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K");
    $col=1;

    $req="SELECT nom, prenom, numero from medecin_ruelle  ";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");


    $worksheet->write("A1", "N° dossier");
    $worksheet->write("B1", "Nom médecin");
    $worksheet->write("C1", "Prénom médecin");
    $l=1;
    // print_r($dossier);print_r($colonne);die;
    while(list($nom, $prenom, $numero)=mysql_fetch_row($res)){
        $req2="SELECT id FROM dossier ".
            "WHERE numero='$numero' and cabinet='ruelle'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $l++;
            $worksheet->write("A$l", "$numero");
            $worksheet->write("B$l", "$nom");
            $worksheet->write("C$l", "$prenom");
        }
    }

    $worksheet =& $workbook->addworksheet("Patients asalée hors fichier");

    $req="SELECT dossier.numero, sexe, dnaiss from dossier ".
        "LEFT JOIN medecin_ruelle ON dossier.numero=medecin_ruelle.numero ".
        "WHERE cabinet='Ruelle' and actif='oui' and nom is NULL";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");


    $worksheet->write("A1", "N° dossier");
    $worksheet->write("B1", "Sexe");
    $worksheet->write("C1", "Date de naissance");
    $l=1;
    // print_r($dossier);print_r($colonne);die;
    while(list($numero, $sexe, $dnaiss)=mysql_fetch_row($res)){
        $l++;
        $worksheet->write("A$l", "$numero");
        $worksheet->write("B$l", "$sexe");
        $worksheet->write("C$l", "$dnaiss");
    }


    $workbook->close();

    echo "<a href='$fich' target='_blank'>$fich</a>";
    exit;
}
?>