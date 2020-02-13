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
    <title>Nombre de dossiers pour lesquels le RCVA peut être calculé</title>
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

entete_asalee("Nombre de dossiers pour lesquels le Framingham peut être calculé");
//echo $loc;
?>
<!--<table cellpadding="2" cellspacing="2" border="0"
 style="text-align: left; width: 100%;">
  <tbody>
    <tr>
      <td style="width: 20%; vertical-align: top;">
      <br>
      <img src="<?php echo $loc; ?>/images/inf79.gif" alt="logo informed79"><br>
        <a href="mailto:contact@asalee.fr"><font size="-1">contact</font></a>
      </td>
      <td style="text-align: center; vertical-align: top;">
	       <span style="font-family: arial; font-weight: bold;">
 <?php
echo "
<i><font face='times new roman' size='32'>Asalée</font><br>
<font face='times new roman'>Indicateurs d'évaluation Asalée : taux de dépistage des troubles cognitifs</font></i>";
?>
           </span><br>
 <?php if(isset($_SESSION['nom'])) echo '<font size="-1"><i>'.$_SESSION['nom'].'</i></font>'; ?>
      </td>
      <td style="width: 15%; text-align: right; vertical-align: middle;">
      <img src="<?php echo $loc; ?>/images/urml.jpg" alt="logo urml"><br>
      </td>
    </tr>
  </tbody>
</table>
-->
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
    global $message,$Dossier,$Cabinet, $deval, $self,$tcabinet, $tville, $t_cogni;

    set_time_limit(0);
    $req="SELECT dossier.id, cabinet, numero, date_format(dnaiss, '%d/%m/%Y'), sexe, taille, actif, date_format(dossier.dcreat, '%d/%m/%Y'), date_format(date, '%d/%m/%Y'), antecedants, Chol, 
	date_format(dChol, '%d/%m/%Y'), HDL, date_format(dHDL, '%d/%m/%Y'), LDL, date_format(dLDL, '%d/%m/%Y'), triglycerides, date_format(dtriglycerides, '%d/%m/%Y'), traitement, dosage, HTA, TaSys, TaDia, 
	date_format(dTA, '%d/%m/%Y'), TA_mode, hypertenseur3, automesure, diuretique, HVG, surcharge_ventricule, sokolov, date_format(dsokolov, '%d/%m/%Y'), 
	Creat, date_format(cardio_vasculaire_depart.dCreat, '%d/%m/%Y'), kaliemie, date_format(dkaliemie, '%d/%m/%Y'), proteinurie, date_format(dproteinurie, '%d/%m/%Y'), hematurie, date_format(dhematurie, '%d/%m/%Y'), date_format(dFond, '%d/%m/%Y'), date_format(dECG, '%d/%m/%Y'), 
	tabac, date_format(darret, '%d/%m/%Y'), poids, date_format(dpoids, '%d/%m/%Y'), activite, pouls, date_format(dpouls, '%d/%m/%Y'), alcool, glycemie, date_format(dgly, '%d/%m/%Y'), date_format(exam_cardio, '%d/%m/%Y'), sortir_rappel,
	raison_sortie, date_format(cardio_vasculaire_depart.dmaj, '%d/%m/%Y')
	FROM dossier, `cardio_vasculaire_depart`
	WHERE dossier.id = cardio_vasculaire_depart.id order by cabinet, numero";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $fichier="../export/Liste suivis RCVA depuis origine ".date("dmY").".xls";
    $workbook =& new writeexcel_workbookbig($fichier); // on lui passe en paramètre le chemin de notre fichier
    $worksheet =& $workbook->addworksheet("Liste suivis");
    $worksheet->write("A1", "id");
    $worksheet->write("B1", "cabinet");
    $worksheet->write("C1", "numéro");
    $worksheet->write("D1", "date naissance");
    $worksheet->write("E1", "sexe");
    $worksheet->write("F1", "taille");
    $worksheet->write("G1", "actif");
    $worksheet->write("H1", "date création");
    $worksheet->write("I1", "date suivi RCVA");
    $worksheet->write("J1", "antécédents");
    $worksheet->write("K1", "Cholestérol total");
    $worksheet->write("L1", "date cholestérol total");
    $worksheet->write("M1", "HDL");
    $worksheet->write("N1", "date HDL");
    $worksheet->write("O1", "LDL");
    $worksheet->write("P1", "date LDL");
    $worksheet->write("Q1", "triglycerides");
    $worksheet->write("R1", "date triglycerides");
    $worksheet->write("S1", "traitement");
    $worksheet->write("T1", "dosage");
    $worksheet->write("U1", "HTA");
    $worksheet->write("V1", "systole");
    $worksheet->write("W1", "diastole");
    $worksheet->write("X1", "date tension");
    $worksheet->write("Y1", "mode tension");
    $worksheet->write("Z1", "3 traitements anti hypertenseur");
    $worksheet->write("AA1", "présence automesure");
    $worksheet->write("AB1", "présence diurétique");
    $worksheet->write("AC1", "Echocardiogramme Hypertrophie Ventriculaire Gauche");
    $worksheet->write("AD1", "surcharge ventriculaire gauche");
    $worksheet->write("AE1", "sokolov");
    $worksheet->write("AF1", "date sokolov");
    $worksheet->write("AG1", "Creatinine");
    $worksheet->write("AH1", "date Creatinine");
    $worksheet->write("AI1", "kaliemie");
    $worksheet->write("AJ1", "date kaliemie");
    $worksheet->write("AK1", "proteinurie");
    $worksheet->write("AL1", "date proteinurie");
    $worksheet->write("AM1", "hematurie");
    $worksheet->write("AN1", "date hematurie");
    $worksheet->write("AO1", "date fond d'oeil");
    $worksheet->write("AP1", "date ECG");
    $worksheet->write("AQ1", "tabagisme");
    $worksheet->write("AR1", "date arret tabac");
    $worksheet->write("AS1", "poids");
    $worksheet->write("AT1", "date poids");
    $worksheet->write("AU1", "activite physique");
    $worksheet->write("AV1", "fréquence cardiaque");
    $worksheet->write("AW1", "date fréquence cardiaque");
    $worksheet->write("AX1", "alcool");
    $worksheet->write("AY1", "glycemie");
    $worksheet->write("AZ1", "date glycémie");
    $worksheet->write("BA1", "examen cardio-vasculaire");
    $worksheet->write("BB1", "sortir du protocole");
    $worksheet->write("BC1", "raison sortie");
    $worksheet->write("BD1", "date mise à jour de l'enregistrement");

    $l=1;

    echo mysql_num_rows($res)." lignes";
    while(list($id, $cabinet, $numero, $dnaiss, $sexe, $taille, $actif, $dcreat, $date, $antecedants, $Chol,
        $dChol, $HDL, $dHDL, $LDL, $dLDL, $triglycerides, $dtriglycerides, $traitement, $dosage, $HTA, $TaSys, $TaDia,
        $dTA, $TA_mode, $hypertenseur3, $automesure, $diuretique, $HVG, $surcharge_ventricule, $sokolov, $dsokolov,
        $Creat, $dCreat, $kaliemie, $dkaliemie, $proteinurie, $dproteinurie, $hematurie, $dhematurie, $dFond, $dECG,
        $tabac, $darret, $poids, $dpoids, $activite, $pouls, $dpouls, $alcool, $glycemie, $dgly, $exam_cardio, $sortir_rappel,
        $raison_sortie, $dmaj) = mysql_fetch_row($res)) {

        if($dnaiss=="00/00/0000"){
            $dnaiss="";
        }


        if($dcreat=="00/00/0000"){
            $dcreat="";
        }


        if($dChol=="00/00/0000"){
            $dChol="";
        }


        if($dHDL=="00/00/0000"){
            $dHDL="";
        }


        if($dLDL=="00/00/0000"){
            $dLDL="";
        }


        if($dtriglycerides=="00/00/0000"){
            $dtryglycerides="";
        }


        if($dTA=="00/00/0000"){
            $dTA="";
        }


        if($dsokolov=="00/00/0000"){
            $dsokolov="";
        }


        if($dCreat=="00/00/0000"){
            $dCreat="";
        }

        if($dkaliemie=="00/00/0000"){
            $dkaliemie="";
        }


        if($dproteinurie=="00/00/0000"){
            $dproteinurie="";
        }


        if($dhematurie=="00/00/0000"){
            $dhematurie="";
        }


        if($dFond=="00/00/0000"){
            $dFond="";
        }


        if($dECG=="00/00/0000"){
            $dECG="";
        }


        if($darret=="00/00/0000"){
            $darret="";
        }


        if($dpoids=="00/00/0000"){
            $dpoids="";
        }


        if($dpouls=="00/00/0000"){
            $dpouls="";
        }

        if($dgly=="00/00/0000"){
            $dgly="";
        }

        $l++;

        $worksheet->write("A$l", "$id");
        $worksheet->write("B$l", "$cabinet");
        $worksheet->write_string("C$l", "$numero");
        $worksheet->write("D$l", "$dnaiss");
        $worksheet->write("E$l", "$sexe");
        $worksheet->write("F$l", "$taille");
        $worksheet->write("G$l", "$actif");
        $worksheet->write("H$l", "$dcreat");
        $worksheet->write("I$l", "$date");
        $worksheet->write("J$l", "$antecedants");
        $worksheet->write("K$l", "$Chol");
        $worksheet->write("L$l", "$dChol");
        $worksheet->write("M$l", "$HDL");
        $worksheet->write("N$l", "$dHDL");
        $worksheet->write("O$l", "$LDL");
        $worksheet->write("P$l", "$dLDL");
        $worksheet->write("Q$l", "$triglycerides");
        $worksheet->write("R$l", "$dtriglycerides");
        $worksheet->write("S$l", "$traitement");
        $worksheet->write("T$l", "$dosage");
        $worksheet->write("U$l", "$HTA");
        $worksheet->write("V$l", "$TaSys");
        $worksheet->write("W$l", "$TaDia");
        $worksheet->write("X$l", "$dTA");
        $worksheet->write("Y$l", "$TA_mode");
        $worksheet->write("Z$l", "$hypertenseur3");
        $worksheet->write("AA$l", "$automesure");
        $worksheet->write("AB$l", "$diuretique");
        $worksheet->write("AC$l", "$HVG");
        $worksheet->write("AD$l", "$surcharge_ventricule");
        $worksheet->write("AE$l", "$sokolov");
        $worksheet->write("AF$l", "$dsokolov");
        $worksheet->write("AG$l", "$Creat");
        $worksheet->write("AH$l", "$dCreat");
        $worksheet->write("AI$l", "$kaliemie");
        $worksheet->write("AJ$l", "$dkaliemie");
        $worksheet->write("AK$l", "$proteinurie");
        $worksheet->write("AL$l", "$dproteinurie");
        $worksheet->write("AM$l", "$hematurie");
        $worksheet->write("AN$l", "$dhematurie");
        $worksheet->write("AO$l", "$dFond");
        $worksheet->write("AP$l", "$dECG");
        $worksheet->write("AQ$l", "$tabac");
        $worksheet->write("AR$l", "$darret");
        $worksheet->write("AS$l", "$poids");
        $worksheet->write("AT$l", "$dpoids");
        $worksheet->write("AU$l", "$activite");
        $worksheet->write("AV$l", "$pouls");
        $worksheet->write("AW$l", "$dpouls");
        $worksheet->write("AX$l", "$alcool");
        $worksheet->write("AY$l", "$glycemie");
        $worksheet->write("AZ$l", "$dgly");
        $worksheet->write("BA$l", "$exam_cardio");
        $worksheet->write("BB$l", "$sortir_rappel");
        $worksheet->write("BC$l", "$raison_sortie");
        $worksheet->write_string("BD$l", "$dmaj");
    }

    $workbook->close();
    echo "<a href='$fichier' target='_blank'>$fichier</a><br>";

    ?>
    </table>
    <br>
    <br>
    <?php

}

?>
</body>
</html>
