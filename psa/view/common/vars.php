<?php
require_once ("Config.php");
$config = new Config();

$cabinets = array("Argenton"=>"Argenton", "Bessines"=>"Bessines", "Bouille"=>"Bouille", "Brioux"=>"Brioux","Chatillon"=>"Chatillon",
    "Chef-boutonne1"=>"Chef-boutonne1", "Chef-boutonne2"=>"Chef-boutonne2", "Chizé"=>"Chizé",
    "Couture"=>"Couture", "Dominault"=>"Dominault", "Frontenay"=>"Frontenay", "La-Mothe"=>"La-Mothe", "Lezay"=>"Lezay",
    "Lezay2"=>"Lezay2", "Lucquin"=>"Lucquin", "Mauzé"=>"Mauzé", "Niort"=>"Niort","Paquereau"=>"Paquereau",
    "thouars"=>"Thouars", "zTest"=>"zTest", "admin"=>"Admin");

$sexe = array("M"=>"Masculin","F"=>"Féminin");

$actif = array("oui"=>"Actif","non"=>"Inactif");

$antFam = array("none"=>"aucun","polypes"=>"polypes","cancer"=>"cancer");

$dysplasie = array(""=>"","none"=>"pas de dysplasie","low"=>"dysplasie de bas grade","high"=>"dysplasie de haut grade",
    "cr_colon"=>"cancer du colon");

$satisfaction = array(""=>"","a+"=>"très bon","a"=>"bon","b"=>"moyen","c"=>"mauvais","d"=>"très mauvais");

$spirometrie_status = array(""=>"","n"=>"normale","a"=>"anormale");

$monthsArray=array("01"=>"Janvier","02"=>"Février","03"=>"Mars","04"=>"Avril","05"=>"Mai","06"=>"Juin","07"=>"Juillet",
    "08"=>"Août","09"=>"Septembre","10"=>"Octobre","11"=>"Novembre","12"=>"Décembre");

$type_consult=array(""=>"",
    "dep_diab"=>"Dépistage du diabète de type 2",
    "suivi_diab"=>"Suivi du diabète de type 2",
    "rcva"=>"Suivi du patient RCVA",
    "bpco"=>"Repérage BPCO tabagique",
    "cognitif"=>"Repérage des troubles cognitifs",
    "sevrage_tabac" => "Sevrage Tabagique",
    "automesure"=>"Automesure tensionnelle",
    "hemocult"=>"Hémocult",
    "sein"=>"Dépistage cancer du sein",
    "colon"=>"Dépistage cancer du colon",
    "uterus"=>"Dépistage cancer col de l'utérus",
    "surpoids"=>"Surpoids chez l'enfant",
    "autres"=>"Autres");


// Fragilité
$aidants = array(
    "aidantAct_medecin_spe"           => "Médecin spécialiste (hors médecins généralistes)",
    "aidantAct_intervenant_ac_phy_ad" => "Intervenant en activité physique adapté",
    "aidantAct_aux_vie"               => "Auxiliaire de vie",
    "aidantAct_prevention_mat_inf"    => "Prévention maternelle et infantile",
    "aidantAct_maia"                  => "Maia (Maisons pour l?autonomie et l?intégration des malades Alzheimer)",
    "aidantAct_clic"                  => "CLIC (Centres Locaux d'Information et de Coordination)",
    "aidantAct_centre_apa"            => "Centre APA (Allocation Personnalisée D'autonomie)",
    "aidantAct_arespa"                => "ARESPA (Association du Réseau de Santé de Proximité et d?Appui)",
    "aidantAct_autre_aidant"          => "Autre Gestionnaire de cas",
    "aidantAct_acteur_dom_soc"        => "Acteur du domaine social (Assistante sociale, ...)",
    "aidantAct_infirmiere_lib"        => "Infirmière libérale"
);
$_SESSION['aidants'] = $aidants;

$autre_aidants = array(
    "aidant_informel" => "Aidant informel",
    "aidant_familial" => "Aidant familial"
);
$_SESSION['autre_aidants'] = $autre_aidants;

$res_externes = array(
    "resExt_medecin_spe"           => "Médecin spécialiste (hors médecins généralistes)",
    "resExt_intervenant_ac_phy_ad" => "Intervenant en activité physique adapté",
    "resExt_aux_vie"               => "Auxiliaire de vie",
    "resExt_prevention_mat_inf"    => "Prévention maternelle et infantile",
    "resExt_maia"                  => "Maia (Maisons pour l?autonomie et l?intégration des malades Alzheimer)",
    "resExt_clic"                  => "CLIC (Centres Locaux d'Information et de Coordination)",
    "resExt_centre_apa"            => "Centre APA (Allocation Personnalisée D'autonomie)",
    "resExt_arespa"                => "ARESPA (Association du Réseau de Santé de Proximité et d?Appui)",
    "resExt_autre_aidant"          => "Autre Gestionnaire de cas",
    "resExt_acteur_dom_soc"        => "Acteur du domaine social (Assistante sociale, ...)",
    "resExt_infirmiere_lib"        => "Infirmière libérale"
);
$_SESSION['res_externes'] = $res_externes;


//AOMI
$cabinets_IPS = array("zTest"=>"zTest");

//Liste des cabinets autorisés à remplir un formulaire de dépistage aomi
$liste_cabs_aut = array(
    "zTest","Espagnac1","Espagnac2","Brioux","Chatillon", "moncoutant","thouars" ,"Argenton","stcesaire","chervesrichemont","brizambourg","burie","montreuil","tonnaycharentepoilus","mauze-thouarsais","montaiguesculape"
);


// Liste des infirmières authorizées d'accéder au formulaire de l'activité physique
$liste_inf_activite_physique = array(
    "crepond", "krobert", "irevel", "sbardin", "vtoussaint", "clebellec", "cgroulard", "sbobineau", "fsipraseuth", "vtibbal", "mpagnier", "cpacaud"
);


// REMBOURSEMENT DE FRAIS --> Liste des natures de dépenses
$nature_depenses = array(
    "Choisir la nature du frais",
    "Hôtel",
    "Avion",
    "Train",
    "Métro/bus/tram",
    "Bateau",
    "Taxi",
    "Péage",
    "Parking",
    "Frais kilométriques",
    "Repas",
    "Alimentation hors repas",
    "Téléphone",
    "Fournitures de bureau",
    "Matériel informatique",
    "Logiciel informatique",
    "Cartes de visite",
    "Livres",
    "Photocopies",
    "Location salle",
    "Autre"
);


$stade_motivationnel = array(" "=>" ",
    "nsp" => "Ne se prononce pas",
    "pre_intention" => "Pré-intention (ça ne me concerne pas)",
    "intention" => "Intention (je sais, je dois)",
    "decision" => "Décision (je veux, je peux)",
    "action" => "Action (je fais)",
    "maintien" => "Maintien (je poursuis)"
);

$type_tabac = array(" "=>" ",
    "cigarette" => "Cigarette",
    "roule" => "Tabac roulé",
    "pipe" => "Pipe",
    "cigare" => "Cigare"
);


$yearsArray = array();
$startYear = 1990;
for($i=0;$i<30;$i++){
    $yearsArray["$startYear"] = $startYear;
    $startYear++;
}

$path = $config->psa_path;
//$path = "/psa";

//error_log("--------------------------------- ",0);
//error_log($config->psa_path,0);
//error_log("--------------------------------- ",0);
//error_log($path,0);
//error_log("--------------------------------- ",0);

//die;
$ageMax = 150;
$dtCh = "\\";
$minYear = 1900;
$maxYear = 2100;
$poidsMin = 30;
$poidsMax = 200;
//$htdoc='/home/informed/www/';
$htdoc= $config->app_path. '/';
//$htdoc= $config->app_path;
?>
