<?php
require_once ("Config.php");
$config = new Config();

$cabinets = array("Argenton"=>"Argenton", "Bessines"=>"Bessines", "Bouille"=>"Bouille", "Brioux"=>"Brioux","Chatillon"=>"Chatillon",
    "Chef-boutonne1"=>"Chef-boutonne1", "Chef-boutonne2"=>"Chef-boutonne2", "Chiz�"=>"Chiz�",
    "Couture"=>"Couture", "Dominault"=>"Dominault", "Frontenay"=>"Frontenay", "La-Mothe"=>"La-Mothe", "Lezay"=>"Lezay",
    "Lezay2"=>"Lezay2", "Lucquin"=>"Lucquin", "Mauz�"=>"Mauz�", "Niort"=>"Niort","Paquereau"=>"Paquereau",
    "thouars"=>"Thouars", "zTest"=>"zTest", "admin"=>"Admin");

$sexe = array("M"=>"Masculin","F"=>"F�minin");

$actif = array("oui"=>"Actif","non"=>"Inactif");

$antFam = array("none"=>"aucun","polypes"=>"polypes","cancer"=>"cancer");

$dysplasie = array(""=>"","none"=>"pas de dysplasie","low"=>"dysplasie de bas grade","high"=>"dysplasie de haut grade",
    "cr_colon"=>"cancer du colon");

$satisfaction = array(""=>"","a+"=>"tr�s bon","a"=>"bon","b"=>"moyen","c"=>"mauvais","d"=>"tr�s mauvais");

$spirometrie_status = array(""=>"","n"=>"normale","a"=>"anormale");

$monthsArray=array("01"=>"Janvier","02"=>"F�vrier","03"=>"Mars","04"=>"Avril","05"=>"Mai","06"=>"Juin","07"=>"Juillet",
    "08"=>"Ao�t","09"=>"Septembre","10"=>"Octobre","11"=>"Novembre","12"=>"D�cembre");

$type_consult=array(""=>"",
    "dep_diab"=>"D�pistage du diab�te de type 2",
    "suivi_diab"=>"Suivi du diab�te de type 2",
    "rcva"=>"Suivi du patient RCVA",
    "bpco"=>"Rep�rage BPCO tabagique",
    "cognitif"=>"Rep�rage des troubles cognitifs",
    "sevrage_tabac" => "Sevrage Tabagique",
    "automesure"=>"Automesure tensionnelle",
    "hemocult"=>"H�mocult",
    "sein"=>"D�pistage cancer du sein",
    "colon"=>"D�pistage cancer du colon",
    "uterus"=>"D�pistage cancer col de l'ut�rus",
    "surpoids"=>"Surpoids chez l'enfant",
    "autres"=>"Autres");


// Fragilit�
$aidants = array(
    "aidantAct_medecin_spe"           => "M�decin sp�cialiste (hors m�decins g�n�ralistes)",
    "aidantAct_intervenant_ac_phy_ad" => "Intervenant en activit� physique adapt�",
    "aidantAct_aux_vie"               => "Auxiliaire de vie",
    "aidantAct_prevention_mat_inf"    => "Pr�vention maternelle et infantile",
    "aidantAct_maia"                  => "Maia (Maisons pour l?autonomie et l?int�gration des malades Alzheimer)",
    "aidantAct_clic"                  => "CLIC (Centres Locaux d'Information et de Coordination)",
    "aidantAct_centre_apa"            => "Centre APA (Allocation Personnalis�e D'autonomie)",
    "aidantAct_arespa"                => "ARESPA (Association du R�seau de Sant� de Proximit� et d?Appui)",
    "aidantAct_autre_aidant"          => "Autre Gestionnaire de cas",
    "aidantAct_acteur_dom_soc"        => "Acteur du domaine social (Assistante sociale, ...)",
    "aidantAct_infirmiere_lib"        => "Infirmi�re lib�rale"
);
$_SESSION['aidants'] = $aidants;

$autre_aidants = array(
    "aidant_informel" => "Aidant informel",
    "aidant_familial" => "Aidant familial"
);
$_SESSION['autre_aidants'] = $autre_aidants;

$res_externes = array(
    "resExt_medecin_spe"           => "M�decin sp�cialiste (hors m�decins g�n�ralistes)",
    "resExt_intervenant_ac_phy_ad" => "Intervenant en activit� physique adapt�",
    "resExt_aux_vie"               => "Auxiliaire de vie",
    "resExt_prevention_mat_inf"    => "Pr�vention maternelle et infantile",
    "resExt_maia"                  => "Maia (Maisons pour l?autonomie et l?int�gration des malades Alzheimer)",
    "resExt_clic"                  => "CLIC (Centres Locaux d'Information et de Coordination)",
    "resExt_centre_apa"            => "Centre APA (Allocation Personnalis�e D'autonomie)",
    "resExt_arespa"                => "ARESPA (Association du R�seau de Sant� de Proximit� et d?Appui)",
    "resExt_autre_aidant"          => "Autre Gestionnaire de cas",
    "resExt_acteur_dom_soc"        => "Acteur du domaine social (Assistante sociale, ...)",
    "resExt_infirmiere_lib"        => "Infirmi�re lib�rale"
);
$_SESSION['res_externes'] = $res_externes;


//AOMI
$cabinets_IPS = array("zTest"=>"zTest");

//Liste des cabinets autoris�s � remplir un formulaire de d�pistage aomi
$liste_cabs_aut = array(
    "zTest","Espagnac1","Espagnac2","Brioux","Chatillon", "moncoutant","thouars" ,"Argenton","stcesaire","chervesrichemont","brizambourg","burie","montreuil","tonnaycharentepoilus","mauze-thouarsais","montaiguesculape"
);


// Liste des infirmi�res authoriz�es d'acc�der au formulaire de l'activit� physique
$liste_inf_activite_physique = array(
    "crepond", "krobert", "irevel", "sbardin", "vtoussaint", "clebellec", "cgroulard", "sbobineau", "fsipraseuth", "vtibbal", "mpagnier", "cpacaud"
);


// REMBOURSEMENT DE FRAIS --> Liste des natures de d�penses
$nature_depenses = array(
    "Choisir la nature du frais",
    "H�tel",
    "Avion",
    "Train",
    "M�tro/bus/tram",
    "Bateau",
    "Taxi",
    "P�age",
    "Parking",
    "Frais kilom�triques",
    "Repas",
    "Alimentation hors repas",
    "T�l�phone",
    "Fournitures de bureau",
    "Mat�riel informatique",
    "Logiciel informatique",
    "Cartes de visite",
    "Livres",
    "Photocopies",
    "Location salle",
    "Autre"
);


$stade_motivationnel = array(" "=>" ",
    "nsp" => "Ne se prononce pas",
    "pre_intention" => "Pr�-intention (�a ne me concerne pas)",
    "intention" => "Intention (je sais, je dois)",
    "decision" => "D�cision (je veux, je peux)",
    "action" => "Action (je fais)",
    "maintien" => "Maintien (je poursuis)"
);

$type_tabac = array(" "=>" ",
    "cigarette" => "Cigarette",
    "roule" => "Tabac roul�",
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
