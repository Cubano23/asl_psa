<?php

/*
 * Pierre
 * 23/09/13
 */

error_reporting(E_ALL);
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
require_once "../writeexcel/class.writeexcel_workbookbig.inc.php";
require_once "../writeexcel/class.writeexcel_worksheet.inc.php";

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("../global/entete.php");

require_once("../../../bean/SuiviHebdomadaireTempsPasse.php");
require_once("../../../bean/ControlerParams.php");
require_once("../../../persistence/SuiviHebdomadaireTempsPasseMapper.php");
require_once("../../../controler/GenericControler.php");
require_once("../../../tools/date.php");

require_once("../../../bean/EvaluationInfirmier.php");
require_once("../../../persistence/EvaluationInfirmierMapper.php");
require_once("../../../bean/SuiviReunionMedecin.php");
require_once("../../../persistence/SuiviReunionMedecinMapper.php");

entete_asalee("Liste suivis temps hebdo");

//echo $loc;
?>

<br><br>
<?php


$sqlSuivi = "SELECT * FROM suivi_hebdo_temps_passe ORDER BY date";
$resSuivi = mysql_query($sqlSuivi) or die("erreur SQL:".mysql_error()."<br>$req");


$cf = new ConnectionFactory();
//create mappers
$dossierMapper = new DossierMapper($cf->getConnection());
$SuiviHebdomadaireTempsPasseMapper = new SuiviHebdomadaireTempsPasseMapper($cf->getConnection());
$evaluationInfirmierMapper = new EvaluationInfirmierMapper($cf->getConnection());
$SuiviReunionMedecinMapper = new SuiviReunionMedecinMapper($cf->getConnection());

$SuiviHebdomadaireTempsPasse = new SuiviHebdomadaireTempsPasse();



$fichier="../export/Liste_suivi_temps_hebdo_".date("dmY").".xls";
$i=1;
while(file_exists($fichier)){
    $fichier="../export/Liste_suivi_temps_hebdo_".date("dmY").".$i.xls";
    $i++;
}
$workbook =& new writeexcel_workbookbig($fichier); // on lui passe en paramètre le chemin de notre fichier

$worksheet =& $workbook->addworksheet("Liste suivi temps hebdo");
$worksheet->write("A1", "Cabinet");
$worksheet->write("B1", "date");
$worksheet->write("C1", "nb consultations");
$worksheet->write("D1", "temps consultations");
$worksheet->write("E1", "dont nb à domicile");
$worksheet->write("F1", "dont temps à domicile");
$worksheet->write("G1", "dont nb au télèphone");
$worksheet->write("H1", "dont temps au télèphone");
$worksheet->write("I1", "dont nb au télèphone");
$worksheet->write("J1", "dont temps au télèphone");
$worksheet->write("K1", "Préparation/bilan des consultations");
$worksheet->write("L1", "Contribution aux actions de développement");
$worksheet->write("M1", "Développement de nouveaux protocoles, communication");
$worksheet->write("N1", "Gestion sur dossier patient");
$worksheet->write("O1", "Auto-formation");
$worksheet->write("P1", "Formation suivie");
$worksheet->write("Q1", "Encadrement de stagiaires");
$worksheet->write("R1", "Concertation avec les médecins (nb)");
$worksheet->write("S1", "Concertation avec les médecins (min)");
$worksheet->write("T1", "Echanges avec d\'autres infirmières (nb)");
$worksheet->write("U1", "Echanges avec d\'autres infirmières (min)");
$worksheet->write("V1", "Autres et/ou Non attribué (min)");
$worksheet->write("W1", "Total (min)");
$l=1;

while($tabSuivi = mysql_fetch_assoc($resSuivi))
{
    $l++;


    $SuiviHebdomadaireTempsPasse->cabinet = $tabSuivi['cabinet'];
    $SuiviHebdomadaireTempsPasse->date = $tabSuivi['date'];

    $saisieInfirmiere = $evaluationInfirmierMapper->getObjectsByCabinetAndDate($tabSuivi['cabinet'], $tabSuivi['date']);
    //var_dump($saisieInfirmiere);
    //
    $tpsConsultTotal = 0;

    $tpsConsultDom = 0;
    $nbConsultDom = 0;

    $tpsConsultTel = 0;
    $nbConsultTel = 0;

    $tpsConsultCol = 0;
    $nbConsultCol = 0;

    $tpsPreparation = 0;

    //if(sizeof($saisieInfirmiere) != 0)
    //{

    foreach ($saisieInfirmiere as $key => $value)
    {
        foreach ($value as $k => $v)
        {
            //echo "<br>- ".$k.' => '.$v;
            if(($k == 'duree') && ($v != NULL))
            {
                $tpsConsultTotal += intval($v);
                $currentRowDuree = intval($v);
            }

            if(($k == 'consult_domicile') && ($v == '1'))
            {
                $tpsConsultDom += intval($currentRowDuree);
                $nbConsultDom += 1;
            }else if(($k == 'consult_tel') && ($v == '1')) {
                $tpsConsultTel += intval($currentRowDuree);
                $nbConsultTel += 1;
            }
            else if(($k == 'consult_collective') && ($v == '1')) {
                $tpsConsultCol += intval($currentRowDuree);
                $nbConsultColl += 1;
            }

            if($k == 'type_consultation')
            {
                switch($v)
                {
                    case 'suivi_diab': $tpsPreparation += intval($currentRowDuree) * 0.25; break;
                    case 'dep_diab': $tpsPreparation += intval($currentRowDuree) * 0.25; break;
                    case 'rcva': $tpsPreparation += intval($currentRowDuree) * 0.25; break;
                    case 'bpco': $tpsPreparation += intval($currentRowDuree) * 0.2; break;
                    case 'cognitif': $tpsPreparation += intval($currentRowDuree) * 0.1; break;
                    case 'autres': $tpsPreparation += intval($currentRowDuree) * 0.2; break;
                    case 'cognitif': $tpsPreparation += intval($currentRowDuree) * 0.1; break;
                }
            }
        }
        //echo '<hr>';
    }

    $tpsNonAttrib = intval($tabSuivi['tps_passe_cabinet']) - ($tpsConsultTotal + $tpsPreparation + $tabSuivi['tps_contact_tel_patient'] + $tabSuivi['info_asalee'] + $tabSuivi['autoformation'] + $tabSuivi['formation'] + $tabSuivi['stagiaires'] + $tabSuivi['tps_reunion_medecin'] + $tabSuivi['tps_reunion_infirmiere']);

    //$SuiviReunionMedecin = $SuiviReunionMedecinMapper->getObjectsByCabinetAndDate($tabSuivi['cabinet'], $SuiviHebdomadaireTempsPasse->date);
    //$result = $SuiviHebdomadaireTempsPasseMapper->findObject($SuiviHebdomadaireTempsPasse->beforeSerialisation($account));

    $worksheet->write("A$l", $tabSuivi['cabinet']);
    $worksheet->write("B$l", $tabSuivi['date']);
    $worksheet->write("C$l", sizeof($saisieInfirmiere));
    $worksheet->write("D$l", "$tpsConsultTotal");
    $worksheet->write("E$l", "$nbConsultDom");
    $worksheet->write("F$l", "$tpsConsultDom");
    $worksheet->write("G$l", "$nbConsultTel");
    $worksheet->write("H$l", "$tpsConsultTel");
    $worksheet->write("I$l", "$nbConsultCol");
    $worksheet->write("J$l", "$tpsConsultCol");
    $worksheet->write("K$l", "$tpsPreparation");
    $worksheet->write("L$l", $tabSuivi['tps_contact_tel_patient']);
    $worksheet->write("M$l", "-");
    $worksheet->write("N$l", $tabSuivi['info_asalee']);
    $worksheet->write("O$l", $tabSuivi['autoformation']);
    $worksheet->write("P$l", $tabSuivi['formation']);
    $worksheet->write("Q$l", $tabSuivi['stagiaires']);
    $worksheet->write("R$l", $tabSuivi['nb_reunion_medecin']);
    $worksheet->write("S$l", $tabSuivi['tps_reunion_medecin']);
    $worksheet->write("T$l", $tabSuivi['nb_reunion_infirmiere']);
    $worksheet->write("U$l", $tabSuivi['tps_reunion_infirmiere']);
    $worksheet->write("V$l", "$tpsNonAttrib");
    $worksheet->write("W$l", $tabSuivi['tps_passe_cabinet']);

    //exit();
    //}
}


$workbook->close();
echo "Télécharger le fichier : <a href='$fichier' target='_blank'>".str_replace("../export/", "", $fichier)."</a><br>";


?>
</body>
</html>
