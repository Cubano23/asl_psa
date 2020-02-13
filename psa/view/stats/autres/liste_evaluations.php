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







    $req="SELECT dossier.id, numero, account.nom_cab, date_format(`date`, '%d/%m/%Y'), degre_satisfaction, points_positifs, points_ameliorations, type_consultation, ".
        "ecg, monofil, exapied, hba, tension, autre, prec_autre FROM `evaluation_infirmier` , dossier, account ".
        "WHERE dossier.id = evaluation_infirmier.id AND account.cabinet=dossier.cabinet and region != '' ";//and  `date`>='2009-01-01' ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
    echo mysql_num_rows($res);
    echo "<table border='1'><tr><td>id</td><td>numero</td><td>cabinet</td><td>date evaluation</td><td>degre satisfaction</td>
	 <td>points positifs</td><td>points amélioration</td><td>type consultation</td><td>ECG</Td><td>Monofilament</td><td>examen des pieds</Td>
	 <td>Prescription HBA1c</td><td>Tension</Td><td>Autre</Td><td>Précision autre examen</Td><td>date maj</td></tr>";

    while(list($id, $numero, $cabinet, $date, $degre_satisfaction, $points_positifs, $points_ameliorations, $type_consultation,
        $ecg, $monofil, $exapied, $hba, $tension, $autre, $prec_autre, $dmaj)=mysql_fetch_row($res)){
        echo "<tr><td>$id</td><td>$numero</td><td>$cabinet</td><td>$date</td><td>$degre_satisfaction</td>
	 <td>$points_positifs</td><td>$points_ameliorations</td><td>$type_consultation</td><td>$ecg</Td>
	 <td>$monofil</Td><td>$exapied</td><td>$hba</td><td>$tension</Td><td>$autre</td><td>$prec_autre</td></tr>";
    }

    echo "</table>";

}


?>
</body>
</html>
