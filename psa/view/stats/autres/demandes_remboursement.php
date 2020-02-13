<?php

require_once '../../../controler/UtilityControler.php';
require_once ("Config.php");

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
    <title>Demandes de remboursement de frais</title>
</head>
<body bgcolor=#FFE887>
<?php
//$base=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
//require("$base/inclus/accesbase.inc.php");
$config = new Config();
require($config->inclus_path ."/accesbase.inc.php");

#echo $serveur.','.$idDB.','.$mdpDB;

if($_SERVER['APPLICATION_ENV']=='dev-herve'){
    $DB = 'isas';
}

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter � la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("../global/entete.php");
//echo $loc;

entete_asalee("Demandes de remboursement de frais");

//echo $loc;
?>

<br><br>
<?php

# boucle principale
do {
    $repete=false;

    # fen�tre glissante:
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


    echo "<table border='1' width='100%'><tr><th width='8%'>Date de la demande</th><th>Infirmière</th>".
        "<th>Nature des frais</th><th width='8%'>Date des frais</th><th>Motif</th>".
	 "<th>Montant en euros</th><th>Autre unité de calcul</th><th>Pièces jointe</th></tr>";

//    $date_min = (date("Y")-1).'-01-01';
    $date_min = (date("Y")-3).'-01-01';
    $date_max = (date("Y")+2).'-31-12';
    #echo $date_min;
    $req="SELECT id,infirmiere, date_format(date_demande, '%d/%m/%Y'), nature, ".
        "motif, montant, autre_calcul, pj, date_frais from frais where date_demande > '$date_min' and date_demande < '$date_max'  order by date_demande DESC ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($id_file, $infirmiere, $date_demande, $nature, $motif, $montant, $autre_calcul, $pj, $date_frais)=mysql_fetch_row($res)){

        echo '<tr>
			<td>'.$date_demande.'</td>
			<td>'.$infirmiere.'</td>
			<td>'.$nature.'</td>
			<td>'.date('d/m/Y', strtotime($date_frais)).'</td>
			<td>'.UtilityControler::cropVar($motif,100).'</td>
			<td>'.$montant.'</td>
			<td>'.$autre_calcul.'</td>
			<td>';
        if($pj != '') {
            $config = new Config();
            $pj=str_replace($config->files_path, "", $pj);
            echo '> <a target="_blank" href="'. $config->psa_path .'/view/frais/load_file.php?id_file='.$id_file.'">voir</a>';
        }
        echo '</td></tr>';
        #"</td><td><a href='../../../_files/notes_de_frais/$pj' target='_blank'>$pj</A></td></Tr>"

    }

    echo "</table>";

}


?>
</body>
</html>
