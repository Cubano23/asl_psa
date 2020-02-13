<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type"
          content="text/html; charset=iso-8859-1">
    <title>importation des données</title>

    <script language="JavaScript" type="text/javascript">
        <!--
        function verif_date(date) { // vérifie le format de date d'un champ
            if (!date.value.match(/[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{4}/)) {
                alert("la date doit être au format jj/mm/aaaa !");
                date.focus();
                return false;
            }
            return true;
        }
        -->
    </script>
</head>
<body>

<?php
# paramétrage
require_once "Config.php";
$config = new Config();
require($config->inclus_path . "/accesbase.inc.php");

error_reporting(E_ALL);

?>
<table cellpadding="2" cellspacing="2" border="0"
       style="text-align: left; width: 100%;">
    <tbody>
    <tr>
        <td style="width: 20%; vertical-align: top;">
            <a href="https://www.asalee.fr/psa/bienvenue.php">
                <img src="http://www.asalee.fr/informed79/images/maison.gif" alt="retour à l'accueil" border="0"></a>
            <a href="javascript:history.back()">
                <img src="http://www.asalee.fr/informed79/images/back.gif" alt="page précédente" border="0"></a>
            <br>
            <img src="http://www.asalee.fr/informed79/images/inf79.gif" alt="logo informed79"><br>
            &nbsp;&nbsp;<a href="mailto:informed79@cc-parthenay.fr"><font size="-1">contact</font></a>
        </td>
        <td style="text-align: center; vertical-align: top;">
	       <span style="font-family: arial; font-weight: bold;">
                Importation des données
           </span><br>
            <?php if(isset($_SESSION['nom'])) echo '<font size="-1"><i>'.$_SESSION['nom'].'</i></font>'; ?>
        </td>
        <td style="width: 15%; text-align: right; vertical-align: middle;">
            <img src="http://www.asalee.fr/informed79/images/urml.jpg" alt="logo urml"><br>
        </td>
    </tr>
    </tbody>
</table>
<?php


if(isset($_REQUEST['raz'])) $_POST = array();
echo '<!--';
print_r($_POST);
echo '-->';


# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db('informed3') or
die("Impossible de se connecter à la base");


# initialisations
$message=array();
$Dossier=0;
$Cabinet='';
$deval=date('d/m/Y');
$doc=substr(basename($_SERVER['PHP_SELF']),0,-4); # nom du script sans suffixe



# boucle principale
do {
    $repete=false;

    # étape 1 : identification du patient et de la date
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

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


# premiere étape du formulaire : saisie des identifiants
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $sql_file;

    ?>

    <form enctype="multipart/form-data" action="<?php echo $self; ?>" method="post" name="form">
        <input type="hidden" name="etape" value="2">
        <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
        <br>
        <table border="0">
            <tbody>
            <tr>
                <td>Cabinet</td>
                <td><?php echo $_SESSION['nom']; ?>
                </td>
            </tr>
            <tr>
                <td colspan = "2"> <input type="file" name="sql_file">
                </td>
            </tr>
            <tr>
                <td></td>
                <td><br/> <input type="submit" name="submit" value="exécuter">
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <br>
    <?php
    echo "<font color='red'><b>";
    foreach($message as $mes)
        echo "$mes<br>";
    echo "</b></font>";

} # fin de l'étape 1


# deuxième étape du formulaire : contrôle des identifiant et saisie des données
function etape_2(&$repete) {
    global $message, $Dossier, $Cabinet, $deval, $self, $doc, $sql_file;

# récupération des données de l'étape précédente 
//foreach(array('Cabinet') as $val)
//  $$val=$_POST[$val];


#recherche du fichier sql

    $sql_file = $_FILES['sql_file']['tmp_name'];
    $sql_file_name = $_FILES['sql_file']['name'];
    $sql_file_size = $_FILES['sql_file']['size'];
    $sql_file_type = $_FILES['sql_file']['type'];
    $sql_file_error = $_FILES['sql_file']['error'];

    if ($sql_file_error>0)
    {
        echo 'Problème : ';

        switch ($sql_file_error)
        {
            case 3: echo 'fichier partiellement téléchargé, recommencez plus tard'; break;
            case 4: echo "le fichier n'a pas été téléchargé, recommencez ultérieurement"; break;
            default: echo "problème lors du téléchargement"; break;
        }
        exit;
    }
    $Cabinet=$_SESSION['nom'];

    if ($Cabinet=='Lucquin')
    {

        $req="DROP TABLE IF EXISTS depistage_colon_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

        $req="DROP TABLE IF EXISTS depistage_diabete_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

        $req="DROP TABLE IF EXISTS depistage_uterus_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

        $req="DROP TABLE IF EXISTS tension_arterielle_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

        $req="DROP TABLE IF EXISTS tension_arterielle_moyenne_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

        $req="DROP TABLE IF EXISTS suivi_hebdomadaire_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

        $req="DROP TABLE IF EXISTS suivi_diabete_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

        $req="DROP TABLE IF EXISTS evaluation_infirmier_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

        $req="DROP TABLE IF EXISTS dossier_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

        $req="DROP TABLE IF EXISTS depistage_sein_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

        $req="DROP TABLE IF EXISTS trouble_cognitif_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");


        $req="CREATE TABLE `depistage_colon_tmp` (
	  `id` int(15) NOT NULL default '0',
	  `date` date NOT NULL default '0000-00-00',
	  `ant_pere_type` set('aucun','polypes','cancer') default NULL,
	  `ant_pere_age` int(11) default NULL,
	  `ant_mere_type` set('aucun','polypes','cancer') default NULL,
	  `ant_mere_age` int(11) default NULL,
	  `ant_fratrie_type` set('aucun','polypes','cancer') default NULL,
	  `ant_fratrie_age` int(11) default NULL,
	  `ant_collat_type` set('aucun','polypes','cancer') default NULL,
	  `ant_collat_age` int(11) default NULL,
	  `ant_enfants_type` set('aucun','polypes','cancer') default NULL,
	  `ant_enfants_age` int(11) default NULL,
	  `just_ant_fam` tinyint(1) default NULL,
	  `just_ant_polype` tinyint(1) default NULL,
	  `just_ant_cr_colique` tinyint(1) default NULL,
	  `just_ant_sg_selles` tinyint(1) default NULL,
	  `colos_date` date default NULL,
	  `colos_polypes` tinyint(1) default NULL,
	  `colos_dysplasie` enum('aucun','bas','haut','cancer') default NULL,
	  `rappel_colos_period` int(11) default '0',
	  `dmaj` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	  PRIMARY KEY  (`id`,`date`)
	) ENGINE=MyISAM;";

        $res = mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        $req="CREATE TABLE `depistage_diabete_tmp` (
	  `id` int(15) NOT NULL default '0',
	  `date` date NOT NULL default '0000-00-00',
	  `poids` float NOT NULL default '0',
	  `surpoids` tinyint(1) default NULL,
	  `parent_diabetique_type2` tinyint(1) default NULL,
	  `ant_intolerance_glucose` tinyint(1) default NULL,
	  `hypertension_arterielle` tinyint(1) default NULL,
	  `dyslipidemie_en_charge` tinyint(1) default NULL,
	  `hdl` tinyint(1) default NULL,
	  `bebe_sup_4kg` tinyint(1) default NULL,
	  `ant_diabete_gestationnel` tinyint(1) default NULL,
	  `corticotherapie` tinyint(1) default NULL,
	  `infection` tinyint(1) default NULL,
	  `intervention_chirugicale` tinyint(1) default NULL,
	  `autre` tinyint(1) default NULL,
	  `derniere_gly_date` date default NULL,
	  `derniere_gly_resultat` float default NULL,
	  `prescription_gly` tinyint(1) default NULL,
	  `nouvelle_gly_date` date default NULL,
	  `nouvelle_gly_resultat` float default NULL,
	  `note_gly` varchar(100) default NULL,
	  `mesure_suivi_diabete` tinyint(1) default NULL,
	  `mesure_suivi_hygieno_dietetique` tinyint(1) default NULL,
	  `mesure_suivi_controle_annuel` tinyint(1) default NULL,
	  `dmaj` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	  PRIMARY KEY  (`id`,`date`)
	) ENGINE=MyISAM;";

        $res = mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");



        $req="CREATE TABLE `depistage_sein_tmp` (
	  `id` int(15) NOT NULL default '0',
	  `date` date NOT NULL default '0000-00-00',
	  `ant_fam_mere` tinyint(1) default NULL,
	  `ant_fam_soeur` tinyint(1) default NULL,
	  `ant_fam_tante` tinyint(1) default NULL,
	  `ant_fam_grandmere` tinyint(1) default NULL,
	  `dep_type` enum('indiv','coll','rappel') default NULL,
	  `mamograph_date` date default NULL,
	  `rappel_mammographie` date default NULL,
	  `dmaj` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	  PRIMARY KEY  (`id`,`date`)
	) ENGINE=MyISAM;";
        $res = mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        $req="CREATE TABLE `dossier_tmp` (
	  `id` int(15) NOT NULL auto_increment,
	  `cabinet` varchar(15) NOT NULL default '',
	  `numero` varchar(10) NOT NULL default '',
	  `dnaiss` date default NULL,
	  `sexe` enum('M','F') default NULL,
	  `taille` smallint(4) unsigned default NULL,
	  `actif` enum('oui', 'non') default NULL,
	  `dcreat` date default NULL,
	  `dmaj` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	  PRIMARY KEY  (`id`),
	  UNIQUE KEY `u_key` (`numero`,`cabinet`)
	) ENGINE=MyISAM;";

        $res = mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        $req="CREATE TABLE `evaluation_infirmier_tmp` (
	  `id` int(15) NOT NULL default '0',
	  `date` date NOT NULL default '0000-00-00',
	  `degre_satisfaction` enum('a+','a','b','c','d') default NULL,
	  `points_positifs` text,
	  `points_ameliorations` text,
	  `dmaj` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	  PRIMARY KEY  (`id`,`date`)
	) ENGINE=MyISAM;";

        $res = mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        $req="CREATE TABLE `suivi_diabete_tmp` (
	  `dossier_id` int(15) NOT NULL default '0',
	  `dsuivi` date NOT NULL default '0000-00-00',
	  `dHBA` date default NULL,
	  `ResHBA` float default NULL,
	  `dExaFil` date default NULL,
	  `ExaFil` enum('oui','non','nsp') default NULL,
	  `dExaPieds` date default NULL,
	  `ExaPieds` enum('oui','non','nsp') default NULL,
	  `dChol` date default NULL,
	  `iChol` tinyint(1) default NULL,
	  `HDL` float default NULL,
	  `HDLc` float default NULL,
	  `dLDL` date default NULL,
	  `iLDL` tinyint(1) default NULL,
	  `LDLc` float default NULL,
	  `LDL` float default NULL,
	  `dCreat` date default NULL,
	  `Creat` float default NULL,
	  `CreatC` float default NULL,
	  `iCreat` tinyint(1) default NULL,
	  `dAlbu` date default NULL,
	  `iAlbu` tinyint(1) default NULL,
	  `Albu` float default NULL,
	  `AlbuC` float default NULL,
	  `dFond` date default NULL,
	  `iFond` tinyint(1) default NULL,
	  `dECG` date default NULL,
	  `iECG` tinyint(1) default NULL,
	  `suivi_type` set('4','s','a') default NULL,
	  `poids` float default '0',
	  `Regime` tinyint(1) default NULL,
	  `InsulReq` tinyint(1) default NULL,
	  `ADO` set('aucun','Pioglitazone chlorhydrate','Metformine','Gliclazide','Miglitol','Repaglinide','Carbutamide','Acarbose','Glimepiride','Rosiglitazone maleate','Glibenclamide','MetformineVildagliptine','MetformineSitagliptine') default 'aucun',
	  `TaSys` varchar(15) default NULL,
	  `TaDia` varchar(15) default NULL,
	  `TA_mode` enum('manuel','automatique','automesure') default NULL,
	  `risques` tinyint(1) default NULL,
	  `hta` tinyint(1) default NULL,
	  `arte` tinyint(1) default NULL,
	  `neph` tinyint(1) default NULL,
	  `coro` tinyint(1) default NULL,
	  `reti` tinyint(1) default NULL,
	  `neur` tinyint(1) default NULL,
	  `equilib` tinyint(1) default NULL,
	  `tension` tinyint(1) default NULL,
	  `lipide` tinyint(1) default NULL,
	  `mesure_ADO` tinyint(1) default NULL,
	  `insuline` tinyint(1) default NULL,
	  `mesure_hta` tinyint(1) default NULL,
	  `hypl` tinyint(1) default NULL,
	  `phys` tinyint(1) default NULL,
	  `diet` tinyint(1) default NULL,
	  `taba` tinyint(1) default NULL,
	  `dmaj` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	  PRIMARY KEY  (`dossier_id`,`dsuivi`)
	) ENGINE=MyISAM;";

        $res = mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        $req="CREATE TABLE `suivi_hebdomadaire_tmp` (
	  `cabinet` varchar(15) NOT NULL default '0',
	  `date` date NOT NULL default '0000-00-00',
	  `travail_base_h` float default NULL,
	  `consult_indiv_h` float default NULL,
	  `consult_indiv_n` int(11) default NULL,
	  `prevention_diabete_h` float default NULL,
	  `prevention_autre_h` float default NULL,
	  `prevention_autre_note` varchar(100) default NULL,
	  `seance_diabete_h` float default NULL,
	  `seance_autre_h` float default NULL,
	  `seance_autre_note` varchar(100) default NULL,
	  `suivi_armoire_h` float default NULL,
	  `suivi_armoire_n` int(11) default NULL,
	  `aide_telephone` float default NULL,
	  `aide_prep_matos` float default NULL,
	  `aide_examen_compl` float default NULL,
	  `aide_soins` float default NULL,
	  `aide_formation` float default NULL,
	  `aide_autre` float default NULL,
	  `dmaj` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	  PRIMARY KEY  (`date`,`cabinet`)
	) ENGINE=MyISAM;";

        $res = mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        $req="CREATE TABLE `tension_arterielle_tmp` (
	  `id` varchar(100) NOT NULL default '',
	  `date` date NOT NULL default '0000-00-00',
	  `momment_journee` enum('matin','soir') NOT NULL default 'matin',
	  `indice` tinyint(4) NOT NULL default '0',
	  `systole` smallint(6) NOT NULL default '0',
	  `dmaj` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	  `group_id` int(11) NOT NULL default '0',
	  `diastole` smallint(6) NOT NULL default '0',
	  PRIMARY KEY  (`id`,`date`,`momment_journee`,`indice`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

        $res = mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        $req="CREATE TABLE `tension_arterielle_moyenne_tmp` (
	  `id` int(11) NOT NULL default '0',
	  `group_id` int(11) NOT NULL default '0',
	  `date_debut` date NOT NULL default '0000-00-00',
	  `nombre_jours` int(11) NOT NULL default '0',
	  `moyenne_sys_matin` smallint(6) NOT NULL default '0',
	  `moyenne_sys_soir` smallint(6) NOT NULL default '0',
	  `moyenne_sys` smallint(6) NOT NULL default '0',
	  `moyenne_dia_matin` smallint(6) NOT NULL default '0',
	  `moyenne_dia_soir` smallint(6) NOT NULL default '0',
	  `moyenne_dia` smallint(6) NOT NULL default '0',
	  `dmaj` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	  PRIMARY KEY  (`id`,`group_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

        $res = mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
//die('stop');

        $req="CREATE TABLE `depistage_uterus_tmp` (
	`id` int(15) NOT NULL default '0',
	`date` date NOT NULL default '0000-00-00',
	`date_frottis` date NULL default NULL,
	`frottis_normal` char(3) NULL default NULL,
	`date_rappel` date NULL default NULL,
	`avis_medecin` text NULL default NULL,
	`dmaj` timestamp NULL default NULL,
	PRIMARY KEY  (`id`, `date`)
	) ENGINE=MyISAM;";

        $res = mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $req="CREATE TABLE `trouble_cognitif_tmp` (
	`id` int(11) NOT NULL default '0',
	`date` date NOT NULL default '0000-00-00',
	`suivi_type` set('mmse','gds','iadl','horl') NULL default NULL,
	`date_rappel` date NULL default NULL,
	`dep_type` enum('coll','indiv') NULL default NULL,
	`raison_dep` text NULL default NULL,
	`mmse_annee` tinyint(1) NULL default NULL,
	`mmse_saison` tinyint(1) NULL default NULL,
	`mmse_mois` tinyint(1) NULL default NULL,
	`mmse_jour_mois` tinyint(1) NULL default NULL,
	`mmse_jour_semaine` tinyint(1) NULL default NULL,
	`mmse_nom_hop` tinyint(1) NULL default NULL,
	`mmse_nom_ville` tinyint(1) NULL default NULL,
	`mmse_nom_dep` tinyint(1) NULL default NULL,
	`mmse_region` tinyint(1) NULL default NULL,
	`mmse_etage` tinyint(1) NULL default NULL,
	`mmse_cigare1` tinyint(1) NULL default NULL,
	`mmse_fleur1` tinyint(1) NULL default NULL,
	`mmse_porte1` tinyint(1) NULL default NULL,
	`mmse_93` tinyint(1) NULL default NULL,
	`mmse_86` tinyint(1) NULL default NULL,
	`mmse_79` tinyint(1) NULL default NULL,
	`mmse_72` tinyint(1) NULL default NULL,
	`mmse_65` tinyint(1) NULL default NULL,
	`mmse_monde` varchar(10) NULL default NULL,
	`mmse_cigare2` tinyint(1) NULL default NULL,
	`mmse_fleur2` tinyint(1) NULL default NULL,
	`mmse_porte2` tinyint(1) NULL default NULL,
	`mmse_crayon` tinyint(1) NULL default NULL,
	`mmse_montre` tinyint(1) NULL default NULL,
	`mmse_repete_phrase` tinyint(1) NULL default NULL,
	`mmse_feuille_prise` tinyint(1) NULL default NULL,
	`mmse_feuille_pliee` tinyint(1) NULL default NULL,
	`mmse_feuille_jetee` tinyint(1) NULL default NULL,
	`mmse_fermer_yeux` tinyint(1) NULL default NULL,
	`mmse_ecrit_phrase` tinyint(1) NULL default NULL,
	`mmse_copie_dessin` tinyint(1) NULL default NULL,
	`gds_satisf` char(3) NULL default NULL,
	`gds_renonce_act` char(3) NULL default NULL,
	`gds_vie_vide` char(3) NULL default NULL,
	`gds_ennui` char(3) NULL default NULL,
	`gds_avenir_opt` char(3) NULL default NULL,
	`gds_cata` char(3) NULL default NULL,
	`gds_bonne_humeur` char(3) NULL default NULL,
	`gds_besoin_aide` char(3) NULL default NULL,
	`gds_prefere_seul` char(3) NULL default NULL,
	`gds_mauvaise_mem` char(3) NULL default NULL,
	`gds_heureux_vivre` char(3) NULL default NULL,
	`gds_bon_rien` char(3) NULL default NULL,
	`gds_energie` char(3) NULL default NULL,
	`gds_desespere_sit` char(3) NULL default NULL,
	`gds_sit_autres_best` char(3) NULL default NULL,
	`iadl_telephone` varchar(15) NULL default NULL,
	`iadl_transport` varchar(15) NULL default NULL,
	`iadl_med` varchar(15) NULL default NULL,
	`iadl_budget` varchar(15) NULL default NULL,
	`horloge` int(11) NULL default NULL,
	`dmaj` timestamp NULL default NULL,
	PRIMARY KEY  (`id`, `date`)
	) ENGINE=MyISAM;";

        $res = mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        $test_OK=TRUE;

        $file = @fopen($sql_file, 'rb');


        if (!$file)
        {
            echo "Erreur lors de l'ouverture du fichier";
            die;
            //$test_OK=FALSE;
        }

        set_time_limit(600);

//	$query = fread($file, filesize($sql_file));

        $chaine_suiv='non';

        fseek ($file, 0, SEEK_SET);

        while (!feof ($file))
        {
            if ($chaine_suiv=='non')
                $test = fgets ($file, 2000);
            else
                $test=$test_suiv;
//echo $test."<br>";

            $chaine_suiv='non';

            if ($test == "")
                break;


            $chaine0="REPLACE INTO dossier_tmp (`id`, `cabinet`, `numero`, `dnaiss`, `sexe`, `taille`, actif, `dcreat`, `dmaj`) VALUES (";
            $chaine1="REPLACE INTO  depistage_colon_tmp (`id`, `date`, `ant_pere_type`, `ant_pere_age`, `ant_mere_type`, `ant_mere_age`, `ant_fratrie_type`, `ant_fratrie_age`, ant_collat_type, `ant_collat_age`, `ant_enfants_type`, `ant_enfants_age`,just_ant_fam, just_ant_polype, just_ant_cr_colique, just_ant_sg_selles, colos_date, colos_polypes, colos_dysplasie, rappel_colos_period, dmaj) VALUES (";
            $chaine2="REPLACE INTO depistage_sein_tmp (id, `date`, ant_fam_mere, ant_fam_soeur, ant_fam_tante, ant_fam_grandmere,dep_type, mamograph_date, rappel_mammographie, dmaj)VALUES ('";
            $chaine3="REPLACE INTO depistage_diabete_tmp (id, `date`, poids, surpoids, parent_diabetique_type2, ant_intolerance_glucose, hypertension_arterielle, dyslipidemie_en_charge, hdl, bebe_sup_4kg, ant_diabete_gestationnel, corticotherapie, infection, intervention_chirugicale, autre, derniere_gly_date, derniere_gly_resultat, prescription_gly, nouvelle_gly_date, nouvelle_gly_resultat, note_gly, mesure_suivi_diabete, mesure_suivi_hygieno_dietetique, mesure_suivi_controle_annuel, dmaj)VALUES ('";
            $chaine4="REPLACE INTO evaluation_infirmier_tmp (id, date, degre_satisfaction, points_positifs, points_ameliorations, dmaj)VALUES (";
            $chaine5="REPLACE INTO suivi_diabete_tmp (dossier_id, dsuivi, dHBA, ResHBA, dExaFil, ExaFil, dExaPieds, ExaPieds".
                ", dChol, iChol, HDL, HDLc, dLDL, iLDL, LDLc, LDL, dCreat, Creat, CreatC".
                ", iCreat, dAlbu, iAlbu, Albu, AlbuC, dFond, iFond, dECG, iECG, suivi_type".
                ", poids, Regime, InsulReq, ADO, TaSys, TaDia, TA_mode, risques, hta, arte".
                ", neph, coro, reti, neur, equilib, tension, lipide, mesure_ADO, insuline".
                ", mesure_hta, hypl, phys, diet, taba, dmaj)".
                "VALUES (";
            $chaine6="REPLACE INTO suivi_hebdomadaire_tmp (cabinet, date, travail_base_h, consult_indiv_h, consult_indiv_n, prevention_diabete_h".
                ", prevention_autre_h, prevention_autre_note, seance_diabete_h, seance_autre_h, seance_autre_note".
                ", suivi_armoire_h, suivi_armoire_n, aide_telephone, aide_prep_matos, aide_examen_compl, aide_soins".
                ", aide_formation, aide_autre, dmaj)".
                " VALUES ('";
            $chaine7="REPLACE INTO tension_arterielle_tmp (id, date, momment_journee, indice, systole, dmaj, group_id, diastole)".
                "VALUES ('";
            $chaine8="REPLACE INTO tension_arterielle_moyenne_tmp (id, group_id, date_debut, nombre_jours, moyenne_sys_matin, moyenne_sys_soir".
                ", moyenne_sys, moyenne_dia_matin, moyenne_dia_soir, moyenne_dia, dmaj)".
                " VALUES ('";

            $chaine9="REPLACE INTO depistage_uterus_tmp (id, date, date_frottis, frottis_normal, date_rappel, avis_medecin, dmaj)".
                " VALUES ('";

            $chaine10= "REPLACE INTO trouble_cognitif_tmp (id, date, suivi_type, date_rappel, dep_type, raison_dep, ".
                "mmse_annee, mmse_saison, mmse_mois, mmse_jour_mois, mmse_jour_semaine, mmse_nom_hop, mmse_nom_ville, ".
                "mmse_nom_dep, mmse_region, mmse_etage, mmse_cigare1, mmse_fleur1, mmse_porte1, mmse_93, ".
                "mmse_86, mmse_79, mmse_72, mmse_65, mmse_monde, mmse_cigare2, mmse_fleur2, mmse_porte2, ".
                "mmse_crayon, mmse_montre, mmse_repete_phrase, mmse_feuille_prise, mmse_feuille_pliee, ".
                "mmse_feuille_jetee, mmse_fermer_yeux, mmse_ecrit_phrase, mmse_copie_dessin, gds_satisf, ".
                "gds_renonce_act, gds_vie_vide, gds_ennui, gds_avenir_opt, gds_cata, gds_bonne_humeur, ".
                "gds_besoin_aide, gds_prefere_seul, gds_mauvaise_mem, gds_heureux_vivre, gds_bon_rien, ".
                "gds_energie, gds_desespere_sit, gds_sit_autres_best, iadl_telephone, iadl_transport, ".
                "iadl_med, iadl_budget, horloge, dmaj)".
                " VALUES ('";

            $long0 = strlen ($chaine0);
            $long1 = strlen ($chaine1);
            $long2 = strlen ($chaine2);
            $long3 = strlen ($chaine3);
            $long4 = strlen ($chaine4);
            $long5 = strlen ($chaine5);
            $long6 = strlen ($chaine6);
            $long7 = strlen ($chaine7);
            $long8 = strlen ($chaine8);
            $long9 = strlen ($chaine9);
            $long10 = strlen ($chaine10);


//	echo "compare : ".strncmp (trim($test), "REPLACE INTO `inf79_patient` (`cabinet`, `dossier`, `dnaiss`, `sexe`, `taille`) VALUES ('Lucquin'", $long1);

            if (strncmp (trim($test), $chaine0, $long0)!=0 &&
                strncmp (trim($test), $chaine1, $long1)!=0 &&
                strncmp (trim($test), $chaine2, $long2)!=0 &&
                strncmp (trim($test), $chaine3, $long3)!=0 &&
                strncmp (trim($test), $chaine4, $long4)!=0 &&
                strncmp (trim($test), $chaine5, $long5)!=0 &&
                strncmp (trim($test), $chaine6, $long6)!=0 &&
                strncmp (trim($test), $chaine7, $long7)!=0 &&
                strncmp (trim($test), $chaine8, $long8)!=0 &&
                strncmp (trim($test), $chaine9, $long9)!=0 &&
                strncmp (trim($test), $chaine10, $long10)!=0
            )
            {
                $test_OK = FALSE;
            }



            if ($test_OK == TRUE)
            {

//			echo "test : ".$test."<br/>";
                if (strncmp (trim($test), $chaine4, $long4)==0)
                {
                    //	    echo "<b>ok</b>";
                    $continue='oui';
                    $chaine_suiv="oui";
                    while($continue=='oui')
                    {
                        $test_suiv = fgets ($file, 2000);
                        if (strncmp (trim($test_suiv), $chaine0, $long0)!=0 &&
                            strncmp (trim($test_suiv), $chaine1, $long1)!=0 &&
                            strncmp (trim($test_suiv), $chaine2, $long2)!=0 &&
                            strncmp (trim($test_suiv), $chaine3, $long3)!=0 &&
                            strncmp (trim($test_suiv), $chaine4, $long4)!=0 &&
                            strncmp (trim($test_suiv), $chaine5, $long5)!=0 &&
                            strncmp (trim($test_suiv), $chaine6, $long6)!=0 &&
                            strncmp (trim($test_suiv), $chaine7, $long7)!=0 &&
                            strncmp (trim($test_suiv), $chaine8, $long8)!=0 &&
                            strncmp (trim($test_suiv), $chaine9, $long9)!=0 &&
                            strncmp (trim($test_suiv), $chaine10, $long10)!=0
                        )
                            $test=$test."\n".$test_suiv;
                        else
                            $continue='non';
                    }

                }


//echo $test."<br>";
                $test = chop($test);
                $res = mysql_query($test) or die("erreur SQL:".mysql_error()."<br>$test");
                //		echo $test."<br>";

            }
            else
            {
                echo "le fichier choisi a été modifié, la mise à jour n'a pas été validée";
                echo $test;
                break;
            }
        }

        fclose($file);


        $req2="SELECT id FROM dossier WHERE cabinet='Lucquin'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

        while(list($id)=mysql_fetch_row($res2))
        {
            $req="DELETE FROM depistage_colon WHERE id='$id'";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            $req="DELETE FROM depistage_sein WHERE id='$id'";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            $req="DELETE FROM depistage_uterus WHERE id='$id'";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            $req="DELETE FROM depistage_diabete WHERE id='$id'";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            $req="DELETE FROM evaluation_infirmier WHERE id='$id'";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            $req="DELETE FROM suivi_diabete WHERE dossier_id='$id'";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            $req="DELETE FROM suivi_hebdomadaire WHERE cabinet='Lucquin'";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            $req="DELETE FROM tension_arterielle_moyenne WHERE id='$id'";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            $req="DELETE FROM tension_arterielle WHERE id='$id'";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            $req="DELETE FROM trouble_cognitif WHERE id='$id'";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        }



        $req="SELECT * FROM dossier_tmp WHERE cabinet='Lucquin'";
        $res = mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $tab_corres_id=array();

        while(list($id, $cabinet, $numero, $dnaiss, $sexe, $taille, $actif, $dcreat, $dmaj)=mysql_fetch_row($res))
        {
            $req2="SELECT id FROM dossier WHERE cabinet='$cabinet' AND numero='$numero'";
            $res2 = mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            if (mysql_num_rows($res2)==0)
            {
                $req2="SELECT max(id) FROM dossier";
                $res2 = mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
                list($id_nouveau)=mysql_fetch_row($res2);
                $id_nouveau++;
                $tab_corres_id[$id]=$id_nouveau;
                $req3="INSERT INTO dossier SET id='$id_nouveau', cabinet='$cabinet', numero='$numero', dnaiss='$dnaiss', ".
                    "sexe='$sexe', taille='$taille', actif='$actif', dcreat='$dcreat', dmaj='$dmaj'";
                $res3 = mysql_query($req3) or die("erreur SQL:".mysql_error()."<br>$req3");
            }
            else
            {
                list($id_nouveau)=mysql_fetch_row($res2);
                $tab_corres_id[$id]=$id_nouveau;
                $req3="REPLACE INTO dossier SET id='$id_nouveau', cabinet='$cabinet', numero='$numero', dnaiss='$dnaiss', ".
                    "sexe='$sexe', taille='$taille', actif='$actif', dcreat='$dcreat', dmaj='$dmaj'";
                $res3 = mysql_query($req3) or die("erreur SQL:".mysql_error()."<br>$req3");
            }



        }


        $req="select id, date, ant_pere_type, ant_pere_age, ant_mere_type, ant_mere_age, ant_fratrie_type, ant_fratrie_age, ".
            "ant_collat_type, ant_collat_age, ant_enfants_type, ant_enfants_age, just_ant_fam, just_ant_polype, just_ant_cr_colique, ".
            "just_ant_sg_selles, colos_date, colos_polypes, colos_dysplasie, rappel_colos_period, dmaj FROM depistage_colon_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($id, $date, $ant_pere_type, $ant_pere_age, $ant_mere_type, $ant_mere_age, $ant_fratrie_type, $ant_fratrie_age,
            $ant_collat_type, $ant_collat_age, $ant_enfants_type, $ant_enfants_age, $just_ant_fam, $just_ant_polype, $just_ant_cr_colique,
            $just_ant_sg_selles, $colos_date, $colos_polypes, $colos_dysplasie, $rappel_colos_period,$dmaj)=mysql_fetch_row($res))
        {
            $req2="REPLACE INTO  depistage_colon (`id`, `date`, `ant_pere_type`, `ant_pere_age`, `ant_mere_type`, `ant_mere_age`,".
                " `ant_fratrie_type`, `ant_fratrie_age`, ant_collat_type, `ant_collat_age`, `ant_enfants_type`, `ant_enfants_age`,".
                "just_ant_fam, just_ant_polype, just_ant_cr_colique, just_ant_sg_selles, colos_date, colos_polypes, colos_dysplasie, ".
                "rappel_colos_period, dmaj) VALUES ('".$tab_corres_id[$id]."', '$date', '$ant_pere_type', '$ant_pere_age', ".
                "'$ant_mere_type', ".
                "'$ant_mere_age', '$ant_fratrie_type', '$ant_fratrie_age', '$ant_collat_type', '$ant_collat_age', ".
                "'$ant_enfants_type', '$ant_enfants_age', '$just_ant_fam', '$just_ant_polype', '$just_ant_cr_colique',".
                "'$just_ant_sg_selles', '$colos_date', '$colos_polypes', '$colos_dysplasie', '$rappel_colos_period',".
                "  '$dmaj');";

            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
        }




        $req = "SELECT  id, `date`, ant_fam_mere, ant_fam_soeur, ant_fam_tante, ant_fam_grandmere,".
            "dep_type, mamograph_date, rappel_mammographie, dmaj FROM depistage_sein_tmp";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");



        while(list($id, $date, $ant_fam_mere, $ant_fam_soeur, $ant_fam_tante, $ant_fam_grandmere, $dep_type, $mamograph_date,
            $rappel_mammographie, $dmaj)=mysql_fetch_row($res))
        {
            $req2= "REPLACE INTO depistage_sein (id, `date`, ant_fam_mere, ant_fam_soeur, ant_fam_tante, ant_fam_grandmere,".
                "dep_type, mamograph_date, rappel_mammographie, dmaj)".
                "VALUES ('".$tab_corres_id[$id]."', '$date', '$ant_fam_mere', '$ant_fam_soeur', '$ant_fam_tante', '$ant_fam_grandmere',".
                "'$dep_type', '$mamograph_date', '$rappel_mammographie', '$dmaj');\n";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");


        }



        $req = "SELECT id, `date`, poids, surpoids, parent_diabetique_type2, ant_intolerance_glucose".
            ", hypertension_arterielle, dyslipidemie_en_charge, hdl, bebe_sup_4kg, ant_diabete_gestationnel".
            ", corticotherapie, infection, intervention_chirugicale, autre, derniere_gly_date, ".
            "derniere_gly_resultat, prescription_gly, nouvelle_gly_date, nouvelle_gly_resultat, note_gly".
            ", mesure_suivi_diabete, mesure_suivi_hygieno_dietetique, mesure_suivi_controle_annuel, dmaj".
            " FROM depistage_diabete_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($id, $date, $poids, $surpoids, $parent_diabetique_type2, $ant_intolerance_glucose,
            $hypertension_arterielle, $dyslipidemie_en_charge, $hdl, $bebe_sup_4kg, $ant_diabete_gestationnel,
            $corticotherapie, $infection, $intervention_chirugicale, $autre, $derniere_gly_date,
            $derniere_gly_resultat, $prescription_gly, $nouvelle_gly_date, $nouvelle_gly_resultat, $note_gly,
            $mesure_suivi_diabete, $mesure_suivi_hygieno_dietetique, $mesure_suivi_controle_annuel, $dmaj)
            =mysql_fetch_row($res))
        {
            $note_gly=stripslashes($note_gly);
            $note_gly=Addslashes($note_gly);

            $req2 = "REPLACE INTO depistage_diabete (id, `date`, poids, surpoids, parent_diabetique_type2, ant_intolerance_glucose".
                ", hypertension_arterielle, dyslipidemie_en_charge, hdl, bebe_sup_4kg, ant_diabete_gestationnel".
                ", corticotherapie, infection, intervention_chirugicale, autre, derniere_gly_date, ".
                "derniere_gly_resultat, prescription_gly, nouvelle_gly_date, nouvelle_gly_resultat, note_gly".
                ", mesure_suivi_diabete, mesure_suivi_hygieno_dietetique, mesure_suivi_controle_annuel, dmaj)".
                "VALUES ('".$tab_corres_id[$id]."', '$date', '$poids', '$surpoids', '$parent_diabetique_type2', '$ant_intolerance_glucose'".
                ", '$hypertension_arterielle', '$dyslipidemie_en_charge', '$hdl', '$bebe_sup_4kg', '$ant_diabete_gestationnel'".
                ", '$corticotherapie', '$infection', '$intervention_chirugicale', '$autre', '$derniere_gly_date', ".
                "'$derniere_gly_resultat', '$prescription_gly', '$nouvelle_gly_date', '$nouvelle_gly_resultat', ".
                "'$note_gly', '$mesure_suivi_diabete', '$mesure_suivi_hygieno_dietetique', ".
                "'$mesure_suivi_controle_annuel', '$dmaj');\n";

            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
        }


        $req = "SELECT id, date, degre_satisfaction, points_positifs, points_ameliorations, dmaj FROM evaluation_infirmier_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($id, $date, $degre_satisfaction, $points_positifs, $points_ameliorations, $dmaj)
            =mysql_fetch_row($res))
        {
            $points_positifs=stripslashes($points_positifs);
            $points_ameliorations=stripslashes($points_ameliorations);

            $req2= "REPLACE INTO evaluation_infirmier (id, date, degre_satisfaction, points_positifs, points_ameliorations, dmaj)".
                "VALUES ('".$tab_corres_id[$id]."', '$date', '$degre_satisfaction', '".addslashes($points_positifs)."', '".
                addslashes($points_ameliorations)."', '$dmaj');\n";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
        }


        $req = "SELECT dossier_id, dsuivi, dHBA, ResHBA, dExaFil, ExaFil, dExaPieds, ExaPieds".
            ", dChol, iChol, HDL, HDLc, dLDL, iLDL, LDLc, LDL, dCreat, Creat, CreatC".
            ", iCreat, dAlbu, iAlbu, Albu, AlbuC, dFond, iFond, dECG, iECG, suivi_type".
            ", poids, Regime, InsulReq, ADO, TaSys, TaDia, TA_mode, risques, hta, arte".
            ", neph, coro, reti, neur, equilib, tension, lipide, mesure_ADO, insuline".
            ", mesure_hta, hypl, phys, diet, taba, dmaj FROM suivi_diabete_tmp";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while(list($dossier_id, $dsuivi, $dHBA, $ResHBA, $dExaFil, $ExaFil, $dExaPieds, $ExaPieds,
            $dChol, $iChol, $HDL, $HDLc, $dLDL, $iLDL, $LDLc, $LDL, $dCreat, $Creat, $CreatC, $iCreat,
            $dAlbu, $iAlbu, $Albu, $AlbuC, $dFond, $iFond, $dECG, $iECG, $suivi_type,
            $poids, $Regime, $InsulReq, $ADO, $TaSys, $TaDia, $TA_mode, $risques, $hta, $arte,
            $neph, $coro, $reti, $neur, $equilib, $tension, $lipide, $mesure_ADO, $insuline,
            $mesure_hta, $hypl, $phys, $diet, $taba, $dmaj)
            =mysql_fetch_row($res))
        {
            $req2 = "REPLACE INTO suivi_diabete (dossier_id, dsuivi, dHBA, ResHBA, dExaFil, ExaFil, dExaPieds, ExaPieds".
                ", dChol, iChol, HDL, HDLc, dLDL, iLDL, LDLc, LDL, dCreat, Creat, CreatC".
                ", iCreat, dAlbu, iAlbu, Albu, AlbuC, dFond, iFond, dECG, iECG, suivi_type".
                ", poids, Regime, InsulReq, ADO, TaSys, TaDia, TA_mode, risques, hta, arte".
                ", neph, coro, reti, neur, equilib, tension, lipide, mesure_ADO, insuline".
                ", mesure_hta, hypl, phys, diet, taba, dmaj)".
                "VALUES ('".$tab_corres_id[$dossier_id]."', '$dsuivi', '$dHBA', '$ResHBA', '$dExaFil', '$ExaFil', '$dExaPieds', '$ExaPieds'".
                ", '$dChol', '$iChol', '$HDL', '$HDLc', '$dLDL', '$iLDL', '$LDLc', '$LDL', '$dCreat', '$Creat', '$CreatC'".
                ", '$iCreat', '$dAlbu', '$iAlbu', '$Albu', '$AlbuC', '$dFond', '$iFond', '$dECG', '$iECG', '$suivi_type'".
                ", '$poids', '$Regime', '$InsulReq', '$ADO', '$TaSys', '$TaDia', '$TA_mode', '$risques', '$hta', '$arte'".
                ", '$neph', '$coro', '$reti', '$neur', '$equilib', '$tension', '$lipide', '$mesure_ADO', '$insuline'".
                ", '$mesure_hta', '$hypl', '$phys', '$diet', '$taba', '$dmaj');\n";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

        }



        $req = "SELECT cabinet, date, travail_base_h, consult_indiv_h, consult_indiv_n, prevention_diabete_h".
            ", prevention_autre_h, prevention_autre_note, seance_diabete_h, seance_autre_h, seance_autre_note".
            ", suivi_armoire_h, suivi_armoire_n, aide_telephone, aide_prep_matos, aide_examen_compl, aide_soins".
            ", aide_formation, aide_autre, dmaj FROM suivi_hebdomadaire_tmp";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while(list($cabinet, $date, $travail_base_h, $consult_indiv_h, $consult_indiv_n, $prevention_diabete_h,
            $prevention_autre_h, $prevention_autre_note, $seance_diabete_h, $seance_autre_h, $seance_autre_note,
            $suivi_armoire_h, $suivi_armoire_n, $aide_telephone, $aide_prep_matos, $aide_examen_compl, $aide_soins,
            $aide_formation, $aide_autre, $dmaj)
            =mysql_fetch_row($res))
        {
            $req2 = "REPLACE INTO suivi_hebdomadaire (cabinet, date, travail_base_h, consult_indiv_h, consult_indiv_n, prevention_diabete_h".
                ", prevention_autre_h, prevention_autre_note, seance_diabete_h, seance_autre_h, seance_autre_note".
                ", suivi_armoire_h, suivi_armoire_n, aide_telephone, aide_prep_matos, aide_examen_compl, aide_soins".
                ", aide_formation, aide_autre, dmaj)".
                " VALUES ('$cabinet', '$date', '$travail_base_h', '$consult_indiv_h', '$consult_indiv_n', '$prevention_diabete_h'".
                ", '$prevention_autre_h', '$prevention_autre_note', '$seance_diabete_h', '$seance_autre_h, '$eance_autre_note'".
                ", '$suivi_armoire_h', '$suivi_armoire_n', '$aide_telephone', '$aide_prep_matos', '$aide_examen_compl', '$aide_soins'".
                ", '$aide_formation', '$aide_autre', '$dmaj');\n";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

        }



        $req = "SELECT id, date, momment_journee, indice, systole, dmaj, group_id, diastole FROM tension_arterielle_tmp";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while(list($id, $date, $momment_journee, $indice, $systole, $dmaj, $group_id, $diastole )
            =mysql_fetch_row($res))
        {
            $req2 = "REPLACE INTO tension_arterielle (id, date, momment_journee, indice, systole, dmaj, group_id, diastole)".
                "VALUES ('".$tab_corres_id[$id]."', '$date', '$momment_journee', '$indice', '$systole', '$dmaj', '$group_id', '$diastole');\n";

            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
        }



        $req = "SELECT id, group_id, date_debut, nombre_jours, moyenne_sys_matin, moyenne_sys_soir".
            ", moyenne_sys, moyenne_dia_matin, moyenne_dia_soir, moyenne_dia, dmaj".
            "  FROM tension_arterielle_moyenne_tmp";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while(list($id, $group_id, $date_debut, $nombre_jours, $moyenne_sys_matin, $moyenne_sys_soir,
            $moyenne_sys, $moyenne_dia_matin, $moyenne_dia_soir, $moyenne_dia, $dmaj)
            =mysql_fetch_row($res))
        {
            $req2 = "REPLACE INTO tension_arterielle_moyenne (id, group_id, date_debut, nombre_jours, moyenne_sys_matin, moyenne_sys_soir".
                ", moyenne_sys, moyenne_dia_matin, moyenne_dia_soir, moyenne_dia, dmaj)".
                " VALUES ('".$tab_corres_id[$id]."', '$group_id', '$date_debut', '$nombre_jours', '$moyenne_sys_matin', '$moyenne_sys_soir'".
                ", '$moyenne_sys', '$moyenne_dia_matin', '$moyenne_dia_soir', '$moyenne_dia', '$dmaj');\n";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");


        }


        $req = "SELECT id, date, date_frottis, frottis_normal, date_rappel, avis_medecin, dmaj".
            "  FROM depistage_uterus_tmp";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while(list($id, $date, $date_frottis, $frottis_normal, $date_rappel, $avis_medecin, $dmaj)
            =mysql_fetch_row($res))
        {
            $req2 = "REPLACE INTO depistage_uterus (id, date, date_frottis, frottis_normal, date_rappel, avis_medecin, dmaj)".
                " VALUES ('".$tab_corres_id[$id]."', '$date', '$date_frottis', '$frottis_normal', '$date_rappel', ".
                "'$avis_medecin', '$dmaj');\n";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");


        }



        $req = "SELECT id, date, suivi_type, date_rappel, dep_type, raison_dep, ".
            "mmse_annee, mmse_saison, mmse_mois, mmse_jour_mois, mmse_jour_semaine, mmse_nom_hop, mmse_nom_ville, ".
            "mmse_nom_dep, mmse_region, mmse_etage, mmse_cigare1, mmse_fleur1, mmse_porte1, mmse_93, ".
            "mmse_86, mmse_79, mmse_72, mmse_65, mmse_monde, mmse_cigare2, mmse_fleur2, mmse_porte2, ".
            "mmse_crayon, mmse_montre, mmse_repete_phrase, mmse_feuille_prise, mmse_feuille_pliee, ".
            "mmse_feuille_jetee, mmse_fermer_yeux, mmse_ecrit_phrase, mmse_copie_dessin, gds_satisf, ".
            "gds_renonce_act, gds_vie_vide, gds_ennui, gds_avenir_opt, gds_cata, gds_bonne_humeur, ".
            "gds_besoin_aide, gds_prefere_seul, gds_mauvaise_mem, gds_heureux_vivre, gds_bon_rien, ".
            "gds_energie, gds_desespere_sit, gds_sit_autres_best, iadl_telephone, iadl_transport, ".
            "iadl_med, iadl_budget, horloge, dmaj".
            "  FROM trouble_cognitif_tmp";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while(list($id, $date, $suivi_type, $date_rappel, $dep_type, $raison_dep,
            $mmse_annee, $mmse_saison, $mmse_mois, $mmse_jour_mois, $mmse_jour_semaine, $mmse_nom_hop, $mmse_nom_ville,
            $mmse_nom_dep, $mmse_region, $mmse_etage, $mmse_cigare1, $mmse_fleur1, $mmse_porte1, $mmse_93,
            $mmse_86, $mmse_79, $mmse_72, $mmse_65, $mmse_monde, $mmse_cigare2, $mmse_fleur2, $mmse_porte2,
            $mmse_crayon, $mmse_montre, $mmse_repete_phrase, $mmse_feuille_prise, $mmse_feuille_pliee,
            $mmse_feuille_jetee, $mmse_fermer_yeux, $mmse_ecrit_phrase, $mmse_copie_dessin, $gds_satisf,
            $gds_renonce_act, $gds_vie_vide, $gds_ennui, $gds_avenir_opt, $gds_cata, $gds_bonne_humeur,
            $gds_besoin_aide, $gds_prefere_seul, $gds_mauvaise_mem, $gds_heureux_vivre, $gds_bon_rien,
            $gds_energie, $gds_desespere_sit, $gds_sit_autres_best, $iadl_telephone, $iadl_transport,
            $iadl_med, $iadl_budget, $horloge, $dmaj)
            =mysql_fetch_row($res))
        {
            $req2 = "REPLACE INTO trouble_cognitif (id, date, suivi_type, date_rappel, dep_type, raison_dep, ".
                "mmse_annee, mmse_saison, mmse_mois, mmse_jour_mois, mmse_jour_semaine, mmse_nom_hop, mmse_nom_ville, ".
                "mmse_nom_dep, mmse_region, mmse_etage, mmse_cigare1, mmse_fleur1, mmse_porte1, mmse_93, ".
                "mmse_86, mmse_79, mmse_72, mmse_65, mmse_monde, mmse_cigare2, mmse_fleur2, mmse_porte2, ".
                "mmse_crayon, mmse_montre, mmse_repete_phrase, mmse_feuille_prise, mmse_feuille_pliee, ".
                "mmse_feuille_jetee, mmse_fermer_yeux, mmse_ecrit_phrase, mmse_copie_dessin, gds_satisf, ".
                "gds_renonce_act, gds_vie_vide, gds_ennui, gds_avenir_opt, gds_cata, gds_bonne_humeur, ".
                "gds_besoin_aide, gds_prefere_seul, gds_mauvaise_mem, gds_heureux_vivre, gds_bon_rien, ".
                "gds_energie, gds_desespere_sit, gds_sit_autres_best, iadl_telephone, iadl_transport, ".
                "iadl_med, iadl_budget, horloge, dmaj)".
                " VALUES ('".$tab_corres_id[$id]."', '$date', '$suivi_type', '$date_rappel', '$dep_type', '$raison_dep',
		'$mmse_annee', '$mmse_saison', '$mmse_mois', '$mmse_jour_mois', '$mmse_jour_semaine', '$mmse_nom_hop', '$mmse_nom_ville',
	    '$mmse_nom_dep', '$mmse_region', '$mmse_etage', '$mmse_cigare1', '$mmse_fleur1', '$mmse_porte1', '$mmse_93',
	    '$mmse_86', '$mmse_79', '$mmse_72', '$mmse_65', '$mmse_monde', '$mmse_cigare2', '$mmse_fleur2', '$mmse_porte2',
	    '$mmse_crayon', '$mmse_montre', '$mmse_repete_phrase', '$mmse_feuille_prise', '$mmse_feuille_pliee',
	    '$mmse_feuille_jetee', '$mmse_fermer_yeux', '$mmse_ecrit_phrase', '$mmse_copie_dessin', '$gds_satisf',
	    '$gds_renonce_act', '$gds_vie_vide', '$gds_ennui', '$gds_avenir_opt', '$gds_cata', '$gds_bonne_humeur',
	    '$gds_besoin_aide', '$gds_prefere_seul', '$gds_mauvaise_mem', '$gds_heureux_vivre', '$gds_bon_rien',
	    '$gds_energie', '$gds_desespere_sit', '$gds_sit_autres_best', '$iadl_telephone', '$iadl_transport',
	    '$iadl_med', '$iadl_budget', '$horloge', '$dmaj');\n";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");


        }


        $req="DROP TABLE `depistage_colon_tmp`, `depistage_diabete_tmp`, `depistage_sein_tmp`, `dossier_tmp`, ".
            "`evaluation_infirmier_tmp`, `suivi_diabete_tmp`, `suivi_hebdomadaire_tmp`, `tension_arterielle_moyenne_tmp`, `tension_arterielle_tmp`, ".
            "`depistage_uterus_tmp`, `trouble_cognitif_tmp` ;";
        $res = mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        if ($test_OK == TRUE)
        {
            echo "la mise à jour a été effectuée avec succès <br/>";
        }


//if ($test_OK == TRUE)
//{
//	$res = mysql_query($query) or die("erreur SQL:".mysql_error()."<br>$query"); 
//}
//else 
//	echo "le fichier choisi a été modifié, la mise à jour n'a pas été validée";


//echo "query : ".$query;

    }
    else
        echo "seule les données du Docteur Lucquin peuvent être enregistrées. Pour cela, se connecter sur son compte";

} # fin de l'étape 2 

?>
</body>
</html>
