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
    <title>Nombre de dossiers pour lesquels le RCVA peut être calculé</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once ("Config.php");
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

    echo "<table><tr><td>id</td><td>cabinet</td><td>numero</td><td>date naissance</td><td>sexe</td><td>taille</td><td>actif</td><td>date creation</td>".
        "<td>date suivi RCVA</td><td>antecedants</td><td>Cholestérol total</td><td>date Cholestérol total</td><td>HDL</td><td>date HDL</td>".
        "<td>LDL</td><td>date LDL</td><td>triglycerides</td><td>date triglycerides</td><td>traitement</td><td>dosage</td><td>HTA</td>".
        "<td>systole</td><td>diastole</td><td>date tension</td><td>mode tension</td><td>3 traitements anti hypertenseur</td>".
        "<td>présence automesure</td><td>présence diurétique</td><td>Echocardiogramme Hypertrophie Ventriculaire Gauche</td>".
        "<td>surcharge ventriculaire gauche</td><td>sokolov</td><td>date sokolov</td><td>Creatinine</td><td>date Creatinine</td>".
        "<td>kaliemie</td><td>date kaliemie</td><td>proteinurie</td><td>date proteinurie</td><td>hematurie</td><td>date hematurie</td>".
        "<td>date fond d'oeil</td><td>date ECG</td><td>tabagisme</td><td>date arret tabac</td><td>poids</td><td>date poids</td>".
        "<td>activite physique</td><td>fréquence cardiaque</td><td>date fréquence cardiaque</td>".
        "<td>alcool</td><td>glycemie</td><td>date glycémie</td><td>examen cardio-vasculaire</td>".
        "<td>sortir du protocole</Td><td>raison sortie</td><td>date mise à jour de l'enregistrement</td></tr>";

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


        echo "<tr><td>$id</td><td>$cabinet</td><td>$numero</td><td>$dnaiss</td><td>$sexe</td><td>$taille</td><td>$actif</td><td>$dcreat</td>".
            "<td>$date</td><td>$antecedants</td><td>$Chol</td><td>$dChol</td><td>$HDL</td><td>$dHDL</td><td>$LDL</td><td>$dLDL</td>".
            "<td>$triglycerides</td><td>$dtriglycerides</td><td>$traitement</td><td>$dosage</td><td>$HTA</td><td>$TaSys</td><td>$TaDia</td>".
            "<td>$dTA</td><td>$TA_mode</td><td>$hypertenseur3</td><td>$automesure</td><td>$diuretique</td><td>$HVG</td>".
            "<td>$surcharge_ventricule</td><td>$sokolov</td><td>$dsokolov</td><td>$Creat</td><td>$dCreat</td><td>$kaliemie</td>".
            "<td>$dkaliemie</td><td>$proteinurie</td><td>$dproteinurie</td><td>$hematurie</td><td>$dhematurie</td><td>$dFond</td>".
            "<td>$dECG</td><td>$tabac</td><td>$darret</td><td>$poids</td><td>$dpoids</td><td>$activite</td><td>$pouls</td><td>$dpouls</td>".
            "<td>$alcool</td><td>$glycemie</td><td>$dgly</td><td>$exam_cardio</td><td>$sortir_rappel</Td><td>$raison_sortie</td><td>$dmaj</td></tr>";
    }
    ?>
    </table>
    <br>
    <br>
    <?php

}

?>
</body>
</html>
