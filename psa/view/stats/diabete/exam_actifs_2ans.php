<?php
/*	session_start();
if(!isset($_SESSION['nom'])) {
	# pas passé par l'identification
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
        <title>Patients actifs depuis 2 ans</title>
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


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("../global/entete.php");
//echo $loc;

entete_asalee("Patients actifs depuis 2 ans");
require_once "../writeexcel/class.writeexcel_workbookbig.inc.php";
require_once "../writeexcel/class.writeexcel_worksheet.inc.php";



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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;

    set_time_limit(120);

    $req="SELECT nom_cab, infirmiere, id, numero, min(dsuivi) ".
        "FROM account, suivi_diabete, dossier ".
        "WHERE actif='oui' and account.cabinet=dossier.cabinet and infirmiere!='' ".
        "AND suivi_diabete.dossier_id=dossier.id and dossier.dcreat<='2009-03-01' ".
        "and dsuivi<='2009-03-01' and region='Poitou-Charentes - 79' ".
        "and account.cabinet!='Frontenay' and account.cabinet!='Mauzé'".
        "group by dossier_id ORDER BY nom_cab, dossier_id ";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $cabinet_prec="";
    $fichiers=array();
    $nb_dossier=0;
    $fichier="../export/diabetiques avant 01-03-09.xls";
    $workbook =& new writeexcel_workbookbig($fichier); // on lui passe en paramètre le chemin de notre fichier
    $worksheet =& $workbook->addworksheet("Liste diabétiques");
    $worksheet->write("A1", "date 1er suivi");
    $worksheet->write("B1", "Id");
    $worksheet->write("C1", "Cabinet");
    $worksheet->write("D1", "numéro dossier");
    $worksheet->write("E1", "Nb consultations");
    $worksheet->write("F1", "Nom");
    $worksheet->write("G1", "Prénom");
    $worksheet->write("H1", "Caisse");
    $worksheet->write("I1", "N° SS");
    $l=1;
    $fichiers[]=$fichier;

    while(list($cabinet, $infirmiere, $id, $numero, $dsuivi)=mysql_fetch_row($res)){
        /*	if($cabinet_prec!=$cabinet){
                if($workbook){
                    $workbook->close();
                }
                $cabinet_prec=$cabinet;
            }
        */
        $l++;
        $worksheet->write("A$l", $dsuivi);
        $worksheet->write("B$l", $id);
        $worksheet->write("C$l", $cabinet);
        $worksheet->write_string("D$l", $numero);

        $req2="SELECT id ".
            "FROM evaluation_infirmier ".
            "WHERE id='$id' ";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

        $nb_consult=mysql_num_rows($res2);
        $worksheet->write("E$l", $nb_consult);

        $req2="SELECT caisse, noss, nom, prenom ".
            "FROM equivalence_no ".
            "WHERE id='$id' ";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

        list($caisse, $noss, $nom, $prenom)=mysql_fetch_row($res2);
        $worksheet->write("F$l", $nom);
        $worksheet->write("G$l", $prenom);
        $worksheet->write("H$l", $caisse);
        $worksheet->write_string("I$l", $noss);

        $nb_dossier++;
    }

    if($workbook){
        $workbook->close();
    }

    echo "$nb_dossier dossiers diabétiques";
    foreach($fichiers as $fichier){
        echo "<a href='$fichier' target='_blank'>$fichier</a><br>";
    }
}