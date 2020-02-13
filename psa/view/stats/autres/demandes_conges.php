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
    <title>Demandes de congés</title>
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

require("../global/entete.php");

entete_asalee("Demandes de congés");
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


    echo "<table border='1'><tr><th>Date de la demande</th><th>Nom</th><th>Prénom</th>".
        "<th>Date 1er jour d'absence</th><th>Date dernier jour d'absence</th>".
        "<th>Nature du congé</th><th>Précision sur la nature du congé</th></tr>";

    $natures=array(""=>"",
        "paye"=>"Congés payés",
        "sanssolde"=>"Congés sans solde",
        "autres"=>"autres");

    $req="SELECT date_format(date_demande, '%d/%m/%Y'), nom, prenom, ".
        "date_format(date_debut, '%d/%m/%Y'), date_format(date_fin, '%d/%m/%Y'), ".
        "nature, prec from conges order by date_demande";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($date_demande, $nom, $prenom, $date_debut, $date_fin, $nature, $prec)=mysql_fetch_row($res)){
        echo "<tr><td>$date_demande</td><td>$nom</td><td>$prenom</td><td>$date_debut</td>".
            "<td>$date_fin</td><td>".$natures[$nature]."</td><td>$prec&nbsp;</td></Tr>";
    }

    echo "</table>";

}


?>
</body>
</html>
