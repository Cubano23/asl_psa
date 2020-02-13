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
    <title>Nombre de tensions par mois</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");
require_once "../writeexcel/class.writeexcel_workbookbig.inc.php";
require_once "../writeexcel/class.writeexcel_worksheet.inc.php";

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
require("../global/entete.php");
//echo $loc;

$titre="Nombre de tensions mois par mois";


entete_asalee($titre);
//echo $loc;
?>
<br><br>
<?php

# boucle principale
do {
    $repete=false;

    # fenêtre glissante:
    if (isset($_GET['mois']) && isset($_GET['annee']))
    {
        etape_2($repete);
        exit;
    }

    # étape 1 : identification du patient et de la date
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 1:
                etape_1($repete);
                break;

            # étape 2  : saisie des détails
            case 2:
                etape_2($repete);
                break;

            # étape 3  : validation des données et màj base
            case 3:
                etape_3($repete);
                break;
        }
    }
} while($repete);

# fin de traitement principal


function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet;


    $fichier="../export/nb tensions mois par mois ".date("dmY").".xls";
    $workbook =& new writeexcel_workbookbig($fichier); // on lui passe en paramètre le chemin de notre fichier
    $worksheet =& $workbook->addworksheet("Nb tensions");
    $worksheet->write("A2", "Nb tensions");

    $colonnes=array("", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
        "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
        "V", "W", "X", "Y", "Z",
        "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ",
        "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU",
        "AV", "AW", "AX", "AY", "AZ",
        "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ",
        "BK", "BL", "BM", "BN", "BO", "BP", "BQ", "BR", "BS", "BT", "BU",
        "BV", "BW", "BX", "BY", "BZ",
        "CA", "CB", "CC", "CD", "CE", "CF", "CG", "CH", "CI", "CJ",
        "CK", "CL", "CM", "CN", "CO", "CP", "CQ", "CR", "CS", "CT", "CU",
        "CV", "CW", "CX", "CY", "CZ",
        "DA", "DB", "DC", "DD", "DE", "DF", "DG", "DH", "DI", "DJ",
        "DK", "DL", "DM", "DN", "DO", "DP", "DQ", "DR", "DS", "DT", "DU",
        "DV", "DW", "DX", "DY", "DZ");

    $req="SELECT dossier.id from cardio_vasculaire_depart, ".
        "dossier where cardio_vasculaire_depart.id=dossier.id ".
        "and dTA>'1990-01-01' and dTA>='2010-12-01' and dTA<='2011-05-30' ".
        "";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($id)=mysql_fetch_row($res)){
        echo $id."<br>";
    }
    $req="SELECT dossier.id from cardio_vasculaire_depart, ".
        "dossier where cardio_vasculaire_depart.id=dossier.id ".
        "and dTA>'1990-01-01' and dTA>='2010-06-01' and dTA<='2010-11-30' ".
        "";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    echo "<br><br>";
    while(list($id)=mysql_fetch_row($res)){
        echo $id."<br>";
    }

}


function get_imc($poids, $taille){
    if(($taille==0)||($taille=='')||($taille=="NULL")){
        return 'ND';
    }

    return $poids/($taille*$taille/10000);
}


?>
</body>
</html>
