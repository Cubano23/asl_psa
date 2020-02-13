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

require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");
mysql_connect($serveur,$idDB,$mdpDB) or die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or die("Impossible de se connecter à la base");

$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";




$tables = array('account', 'medecin', 'suivi_reunion_medecin', 'suivi_hebdo_temps_passe', 'suivi_hebdo_temps_passe_infirmiere', 'cardio_vasculaire_depart', 'sevrage_tabac');
$header = array();
$header['account'] = array("cabinet", "nom_complet", "code_postal", "ville", "contact", "telephone", "courriel", "total_pat", "total_sein", "total_cogni", "total_colon", "total_uterus", "total_diab2", "total_HTA", "infirmiere", "nom_cab", "portable", "region", "logiciel", "log_ope", "Date de mise a jour");
$header['medecin'] = array('id', 'cabinet', 'prenom', 'nom');
$header['suivi_reunion_medecin'] = array("id_reu", "cabinet", "date enregistrement", "date reunion", "duree", "medecin", "infirmiere", "motif","id_mg","id_inf");
$header['suivi_hebdo_temps_passe'] = array("cabinet", "date semaine", "Gestion sur dossier patient", "info_dossiermed (plus utilise)", "nb_contact_tel_patient (plus utilise)", "Contribution aux actions de developpement d'Asalee", "autoformation", "formation", "stagiaires", "nb reunion medecin", "temps reunion medecin", "nb reunion infirmiere", "temps reunion infirmiere", "Nombre de jours travailles dans la semaine dans ce cabinet", "Autres et/ou Non attribue", "Date de mise a jour");
$header['evaluation_infirmier'] = array("id", "date evaluation", "degre satisfaction", "duree", "consultation domicile", "consultation telelphone", "consultation collective", "points positifs", "points ameliorations", "type consultation", "ecg seul", "ecg", "monofil", "exapied", "hba", "tension", "spirometre seul", "spirometre", "trouble cognitif", "autre", "prec_autre", "aspects limitant", "aspects facilitant", "objectifs patient", "Date de mise a jour");
#$header['evaluation_infirmier_light'] = array("id", "date evaluation", "duree", "consultation domicile", "consultation telelphone", "consultation collective", "points positifs", "points ameliorations", "type consultation", "ecg seul", "ecg", "monofil", "exapied", "hba", "tension", "spirometre seul", "spirometre", "trouble cognitif", "autre", "prec_autre", "aspects limitant", "aspects facilitant", "objectifs patient", "Date de mise a jour");
$header['cardio_vasculaire_depart'] = array("id", "date suivi", "antecedants", "cholesterol", "date cholesterol", "HDL", "date HDL", "LDL", "date LDL", "triglycerides", "dtriglycerides", "traitement", "dosage", "HTA", "TaSys", "TaDia", "date TA", "TA mode", "hypertenseur3", "automesure", "diuretique", "HVG", "surcharge ventricule", "sokolov", "date sokolov", "creatinine", "date creatinine", "kaliemie", "date kaliemie", "proteinurie", "date proteinurie", "hematurie", "date hematurie", "date Fond", "date ECG", "tabagisme", "nb paquets-annees", "date arret", "spirometrie status", "spirometrie date", "spirometrie CVF", "spirometrie VEMS", "spirometrie DEP", "spirometrie rapport VEMS-CVF", "spirometrie", "poids", "date poids", "activite", "pouls", "date pouls", "alcool", "glycemie", "date glycemie", "exam cardio", "sortir rappel", "raison sortie", "date mise a jour");


$header['sevrage_tabac'] = array('id', 'numero', 'date', 'tabac', 'nbrtabac', 'type_tabac', 'ddebut', 'darret_old', 'darret', 'spirometrie_date', 'spirometrie_CVF', 'RESULTAT1', 'spirometrie_VEMS', 'spirometrie_DEP', 'spirometrie_status', 'spirometrie_rapport_VEMS_CVF', 'dco_test', 'co_ppm', 'fagerstrom', 'horn_stimulation', 'horn_plaisir', 'horn_relaxation', 'horn_anxiete', 'horn_besoin', 'horn_habitude', 'had_anxiete', 'had_depression', 'echelle_analogique', 'echelle_confiance', 'stade_motivationnel', 'poids', 'dpoids', 'activite', 'alcool', 'aspects_limitants', 'aspects_facilitants', 'objectifs_patient', 'dmaj');


if(isset($_GET['exported']) && in_array($_GET['exported'], $tables)){
    $table_selected = $_GET['exported'];

    $filename = "export_".$table_selected."_".date('Y-m-d_H-i');

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=".$filename.".csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    $outstream = fopen("php://output", "w");

    switch($table_selected){

        case 'account' :
            $q = "SELECT cabinet, nom_complet, code_postal, ville, contact, telephone, courriel, total_pat, total_sein, total_cogni, total_colon, total_uterus, total_diab2, total_HTA, infirmiere, nom_cab, portable, region, logiciel, log_ope, dmaj FROM account";
            break;
        case 'evaluation_infirmier_light' :
            $q = "SELECT id, date, duree, consult_domicile, consult_tel, consult_collective";
            break;
        default :
            $q = "SELECT * FROM ".$table_selected;
    }


    $r = mysql_query($q);

    fputcsv($outstream, $header[$table_selected], ';');
    while($tab = mysql_fetch_assoc($r)){
        fputcsv($outstream, $tab, ';');
    }
    fclose($outstream);
}
else{
    die('Erreur');
}

?>
