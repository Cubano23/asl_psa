<?php
/*	session_start();
if(!isset($_SESSION['nom'])) {
	# pas pass� par l'identification
	$debut=dirname($_SERVER['PHP_SELF']);
	$self=basename($_SERVER['PHP_SELF']);
    header("Location: $debut/ident_util.php?url=$self");
    echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
	exit;
}
*/
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
        <meta http-equiv="content-type"
              content="text/html; charset=ISO-8859-15">
        <title>Remplissage zone mesures � prendre par le m�decin</title>
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


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("../global/entete.php");
//echo $loc;

entete_asalee("Remplissage zone mesures � prendre par le m�decin");
require_once "../writeexcel/class.writeexcel_workbookbig.inc.php";
require_once "../writeexcel/class.writeexcel_worksheet.inc.php";



# boucle principale
do {
    $repete=false;

    # fen�tre glissante:
    if (isset($_GET['mois']) && isset($_GET['annee']))
    {
        etape_2($repete);
        exit;
    }

    # �tape 1 : identification du patient et de la date
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 1:
                etape_1($repete);
                break;

            # �tape 2  : saisie des d�tails
            case 2:
                etape_2($repete);
                break;

            # �tape 3  : validation des donn�es et m�j base
            case 3:
                etape_3($repete);
                break;
        }
    }
} while($repete);

# fin de traitement principal


function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;
    $fichier="../export/exam medecin.xls";
    set_time_limit(120);
    $workbook =& new writeexcel_workbookbig($fichier); // on lui passe en param�tre le chemin de notre fichier
    $worksheet =& $workbook->addworksheet("exam m�decin");
    $worksheet->write("A1", "Id");
    $worksheet->write("B1", "Cabinet");
    $worksheet->write("C1", "num�ro dossier");
    $worksheet->write("D1", "date suivi");
    $worksheet->write("E1", "Modification traitement antidiab�tiques oraux");
    $worksheet->write("F1", "Modification ou mise � l'insuline ");
    $worksheet->write("G1", "Correction HTA ");
    $worksheet->write("H1", "Prise en charge hyperlipid�mie ");

    $req="SELECT cabinet, id, numero, dsuivi, mesure_ADO, insuline, mesure_hta, hypl ".
        "FROM suivi_diabete, dossier ".
        "WHERE actif='oui' ".
        "AND suivi_diabete.dossier_id=dossier.id ".
        "ORDER BY cabinet, dossier_id ";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $l=1;

    while(list($cabinet, $id, $numero, $dsuivi, $mesure_ADO, $insuline, $mesure_hta, $hypl)=mysql_fetch_row($res)){
        $l++;
        $worksheet->write("A$l", $id);
        $worksheet->write("B$l", $cabinet);
        $worksheet->write("C$l", $numero);
        $worksheet->write("D$l", $dsuivi);
        $worksheet->write("E$l", $mesure_ADO);
        $worksheet->write("F$l", $insuline);
        $worksheet->write("G$l", $mesure_hta);
        $worksheet->write("H$l", $hypl);
    }

    $workbook->close();

    echo "<a href='$fichier' target='_blank'>$fichier</a>";
}