<?php
session_start();
if(!isset($_SESSION['nom'])) {
    # pas pass� par l'identification
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
        <title>Liste des patients � Chatillon avec ancien num�ro</title>
    </head>
    <body bgcolor=#FFE887>
<?php

require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux donn�es
mysql_connect($serveur,$idDB,$mdpDB) or
    die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
    die("Impossible de se connecter � la base");

require("./global/entete.php");

require_once "./writeexcel/class.writeexcel_workbookbig.inc.php";
require_once "./writeexcel/class.writeexcel_worksheet.inc.php";

$titre="Liste des patients � Chatillon avec ancien num�ro";


entete_asalee($titre);

# initialisations
$nom = "";
$message=array();

# boucle principale
do {
    $repete=false;

    # �tape 1 : identification de l'�tablissement
    if (!isset($_POST['etape'])) {
        etape_1($repete);
    }
    elseif($_POST['etape']==2) {
        # �tape 2  : v�rification du mot de passe et continuation vers l'url
        etape_2($repete);
    }
} while($repete);

exit;

# �tape 1 : identification de l'�tablissement
function etape_1(&$repete) {
    global $message, $nom;


    extract($_POST);

    set_time_limit(0);
    $fich="./export/Liste patients avec ancien numero.xls";
    $workbook =& new writeexcel_workbookbig($fich); // on lui passe en param�tre le chemin de notre fichier

    $worksheet_asalee =& $workbook->addworksheet("liste patients");
    $worksheet_asalee->write("A1", "Id");
    $worksheet_asalee->write("B1", "n� dossier ancien num�ro");
    $worksheet_asalee->write("C1", "date naissance dans asal�e");
    $worksheet_asalee->write("D1", "date naissance dans fichier excel");
    $worksheet_asalee->write("E1", "Nouveau num�ro dossier fichier excel");
    $worksheet_asalee->write("F1", "sexe");
    $worksheet_asalee->write("G1", "actif");
    $worksheet_asalee->write("H1", "Raison");


    //Recherche des examens uniquement dans asal�e
    $req="SELECT numero, id, sexe, dnaiss, actif FROM dossier WHERE ".
        "cabinet='Chatillon' and numero not like 'N%'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    $l=1;
    while(list($numero, $id, $sexe, $dnaiss, $actif)=mysql_fetch_row($res)){
        $req2="SELECT dnaiss, nouveau from no_chatillon where ancien='$numero'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==1){
            list($dnaiss2, $nouveau)=mysql_fetch_row($res2);
            $raison="Date de naissance diff�rente";
        }
        else{
            $dnaiss2=$nouveau="";
            $raison="Dossier non trouv�";
        }

        $l++;
        $worksheet_asalee->write("A$l", $id);
        $worksheet_asalee->write("B$l", $numero);
        $worksheet_asalee->write("C$l", $dnaiss);
        $worksheet_asalee->write("D$l", $dnaiss2);
        $worksheet_asalee->write("E$l", $nouveau);
        $worksheet_asalee->write("F$l", $sexe);
        $worksheet_asalee->write("G$l", $actif);
        $worksheet_asalee->write("H$l", $raison);
    }

    $workbook->close();

    echo "<a href='$fich' target='_blank'>$fich</a>";
    exit;
}
?>