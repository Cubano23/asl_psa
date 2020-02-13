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
        <title>nb hémoccult Gérardmer</title>
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

$titre="Nb hémoccult Gérardmer";


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
    echo "<table>";
    echo "<tr><td colspan='2'>Du 01/12/2009 au 30/11/2010</td>".
        "<td colspan='2'>du 01/12/2010 au 30/11/2011</td></tr>".
        "<tr><td>H</td><td>F</td><td>H</td><td>F</td></td>";

    $req="SELECT dossier.id, sexe, count(*) from hemocult, dossier WHERE cabinet='Gerardmer' ".
        "and dossier.id=hemocult.id and date_resultat>='2009-12-01' and date_resultat<='2010-11-30' ".
        "group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    $H=0;
    $F=0;
    while(list($id, $sexe)=mysql_fetch_row($res)){
        if($sexe=="M"){
            $H++;
        }
        else{
            $F++;
        }
    }

    echo "<tr><td>$H</td><td>$F</td>";

    $req="SELECT dossier.id, sexe, count(*) from hemocult, dossier WHERE cabinet='Gerardmer' ".
        "and dossier.id=hemocult.id and date_resultat>='2010-12-01' and date_resultat<='2011-11-30' ".
        "group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    $H=0;
    $F=0;
    while(list($id, $sexe)=mysql_fetch_row($res)){
        if($sexe=="M"){
            $H++;
        }
        else{
            $F++;
        }
    }

    echo "<td>$H</td><td>$F</td></tr>";


    echo "</table>";
    exit;
}
?>