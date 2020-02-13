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
set_time_limit(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Liste des évaluations infirmières</title>
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

entete_asalee("Liste des évaluations infirmières");

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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;


    $liste_exam=array(""=>"", "automesure"=>"automesure", "autres"=>"autres", "cognitif"=>"troubles cognitifs",
        "rcva"=>"rcva", "colon"=>"cancer colon", "sein"=>"cancer sein",
        "hemocult"=>"hémoccult", "uterus"=>"cancer utérus",
        "dep_diab"=>"dépistage diabète", "suivi_diab"=>"suivi diabète");

    echo "début extraction : ".date("H:i:s")."<br>";

    $req="SELECT dossier.id, numero, account.nom_cab, date_format(`date`, '%d/%m/%Y'), degre_satisfaction, points_positifs, points_ameliorations, type_consultation, ".
        "ecg, ecg_seul, monofil, exapied, hba, spirometre, spirometre_seul, t_cognitif, autre, prec_autre, evaluation_infirmier.dmaj FROM `evaluation_infirmier` , dossier, account ".
        "WHERE dossier.id = evaluation_infirmier.id AND account.cabinet=dossier.cabinet and region != '' ";//and  `date`>='2009-01-01' ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    require_once './PEAR/Spreadsheet/Excel/Writer.php';


    $fichier="../export/Liste evaluation infirmieres depuis origine ".date("dmY").".xls";
    $i=1;
    while(file_exists($fichier)){
        $fichier="../export/Liste evaluation infirmieres depuis origine ".date("dmY").".$i.xls";
        $i++;
    }
    $workbook = new Spreadsheet_Excel_Writer($fichier);
    $workbook->setVersion(8);

// $workbook->send($fichier);
    $worksheet = &$workbook->addWorksheet("Evaluations");

    $texte =& $workbook->addFormat();
    $texte->setNumFormat('text');

// echo mysql_num_rows($res);

    $worksheet->write(0, 0, "id");
    $worksheet->write(0, 1, "numéro");
    $worksheet->write(0, 2, "cabinet");
    $worksheet->write(0, 3, "date évaluation");
    $worksheet->write(0, 4, "degré satisfaction");
    $worksheet->write(0, 5, "points positifs");
    $worksheet->write(0, 6, "points amélioration");
    $worksheet->write(0, 7, "type consultation");
    $worksheet->write(0, 8, "ECG");
    $worksheet->write(0, 9, "ECG seul non dérogatoire");
    $worksheet->write(0, 10, "examen des pieds et monofilamentilament");
    $worksheet->write(0, 11, "examen des pieds");
    $worksheet->write(0, 12, "Spirométrie");
    $worksheet->write(0, 13, "Spirométrie seule");
    $worksheet->write(0, 14, "Repérage troubles cognitifs");
    $worksheet->write(0, 15, "Patient diabétique type 2");//Prescription HBA1c
// $worksheet->write(0, 15, "Tension");
    $worksheet->write(0, 16, "Autre");
    $worksheet->write(0, 17, "Précision autre examen");
// $worksheet->write("P1", "date maj");
    $l=0;
    /*echo "<table border='1'><tr><td>id</td><td>numero</td><td>cabinet</td><td>date evaluation</td><td>degre satisfaction</td>
         <td>points positifs</td><td>points amélioration</td><td>type consultation</td><td>ECG</Td><td>Monofilament</td><td>examen des pieds</Td>
         <td>Prescription HBA1c</td><td>Tension</Td><td>Autre</Td><td>Précision autre examen</Td><td>date maj</td></tr>";
        */
    while(list($id, $numero, $cabinet, $date, $degre_satisfaction, $points_positifs, $points_ameliorations, $type_consultation,
        $ecg, $ecg_seul, $monofil, $exapied, $spirometre, $spirometre_seul, $hba, $t_cognitif, $autre, $prec_autre, $dmaj)=mysql_fetch_row($res)){
        $l++;
        $worksheet->write($l, 0, "$id");
        $worksheet->writeString($l, 1, "$numero");
        $worksheet->write($l, 2, "$cabinet");
        $worksheet->write($l, 3, "$date");
        $worksheet->write($l, 4, "$degre_satisfaction");
        $worksheet->write($l, 5, "$points_positifs");
        $worksheet->write($l, 6, "$points_ameliorations");
// echo $l;
// if($l>25000){
// echo "erreur ligne";
// die;
// }
// $worksheet->write("G$l", "$points_ameliorations");
        $type_consultation=explode(",", $type_consultation);
        $type_consult="";
        $virgule="";
        foreach($type_consultation as $consult){
            $type_consult=$type_consult.$virgule.$liste_exam[$consult];
            $virgule=", ";
        }
        $worksheet->write($l, 7, "$type_consult");
        $worksheet->write($l, 8, "$ecg");
        $worksheet->write($l, 9, "$ecg_seul");
        $worksheet->write($l, 10, "$monofil");
        $worksheet->write($l, 11, "$exapied");
        $worksheet->write($l, 12, "$spirometre");
        $worksheet->write($l, 13, "$spirometre_seul");
        $worksheet->write($l, 14, "$t_cognitif");
        $worksheet->write($l, 15, "$hba");

        $worksheet->write($l, 16, "$autre");
        $worksheet->write($l, 17, "$prec_autre");
// $worksheet->write("P1", "date maj");
        /*	    echo "<tr><td>$id</td><td>$numero</td><td>$cabinet</td><td>$date</td><td>$degre_satisfaction</td>
             <td>$points_positifs</td><td>$points_ameliorations</td><td>$type_consultation</td><td>$ecg</Td>
             <td>$monofil</Td><td>$exapied</td><td>$hba</td><td>$tension</Td><td>$autre</td><td>$prec_autre</td></tr>";*/
    }

// echo "</table>";
    $workbook->close();
    echo "fin extraction : ".date("H:i:s")."<br>";
    echo "Télécharger le fichier : <a href='$fichier' target='_blank'>".str_replace("../export/", "", $fichier)."</a><br>";

}


?>
</body>
</html>
