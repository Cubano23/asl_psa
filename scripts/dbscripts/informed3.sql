-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Lun 19 Novembre 2018 à 13:52
-- Version du serveur :  5.7.24-0ubuntu0.18.04.1
-- Version de PHP :  5.6.38-1+ubuntu18.04.1+deb.sury.org+2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `informed3`
--
CREATE DATABASE IF NOT EXISTS `informed3` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `informed3`;

-- --------------------------------------------------------

--
-- Structure de la table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `cabinet` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(30) NOT NULL DEFAULT '',
  `nom_complet` text,
  `ville` varchar(30) DEFAULT NULL,
  `contact` text,
  `telephone` varchar(15) DEFAULT NULL,
  `courriel` varchar(50) DEFAULT NULL,
  `total_pat` int(11) DEFAULT NULL,
  `total_sein` int(11) DEFAULT NULL,
  `total_cogni` int(11) DEFAULT NULL,
  `total_colon` int(11) DEFAULT NULL,
  `total_uterus` int(11) DEFAULT NULL,
  `total_diab2` int(11) DEFAULT NULL,
  `total_HTA` int(11) DEFAULT NULL,
  `infirmiere` varchar(50) NOT NULL DEFAULT '',
  `nom_cab` text NOT NULL,
  `portable` varchar(15) NOT NULL DEFAULT '',
  `code_postal` varchar(5) NOT NULL,
  `adresseCabinet` varchar(45) DEFAULT NULL,
  `region` varchar(255) NOT NULL DEFAULT '',
  `logiciel` varchar(50) NOT NULL DEFAULT '',
  `log_ope` tinyint(4) DEFAULT NULL,
  `recordstatus` int(11) NOT NULL DEFAULT '0',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tdb_export` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `account_psaet`
--

CREATE TABLE `account_psaet` (
  `user` varchar(15) NOT NULL DEFAULT '',
  `password` varchar(30) NOT NULL DEFAULT '',
  `nom_complet` varchar(100) DEFAULT NULL,
  `courriel` varchar(60) DEFAULT NULL,
  `region` tinyint(1) NOT NULL DEFAULT '0',
  `national` tinyint(1) NOT NULL DEFAULT '0',
  `nom_region` varchar(100) NOT NULL DEFAULT '',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `account_psam`
--

CREATE TABLE `account_psam` (
  `medecin` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(30) NOT NULL DEFAULT '',
  `nom_complet` varchar(100) DEFAULT NULL,
  `prenom` varchar(30) DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `courriel` varchar(50) DEFAULT NULL,
  `cabinet` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(15) NOT NULL DEFAULT '',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `activite`
--

CREATE TABLE `activite` (
  `PK_activite` int(11) NOT NULL,
  `FK_professionnel` int(11) NOT NULL DEFAULT '0',
  `type_activite` varchar(40) NOT NULL DEFAULT '',
  `FK_structure` int(11) DEFAULT NULL,
  `adresse` varchar(50) DEFAULT NULL,
  `cp` varchar(5) DEFAULT NULL,
  `ville` varchar(20) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `siteweb` varchar(50) DEFAULT NULL,
  `population` varchar(250) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `them_id` varchar(250) DEFAULT NULL,
  `date_maj` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `amberieu`
--

CREATE TABLE `amberieu` (
  `numero` varchar(20) NOT NULL DEFAULT '',
  `medecin` varchar(150) NOT NULL DEFAULT '',
  `libelle` varchar(50) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `redacteur` varchar(128) NOT NULL,
  `dcreat` date NOT NULL,
  `sujet` varchar(256) NOT NULL,
  `lien` longtext NOT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `cardio_autre_consult`
--

CREATE TABLE `cardio_autre_consult` (
  `id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `progres_poids` text,
  `obj_poids` varchar(255) DEFAULT NULL,
  `progres_alcool` text,
  `obj_alcool` varchar(255) DEFAULT NULL,
  `progres_tabac` text,
  `obj_tabac` varchar(255) DEFAULT NULL,
  `progres_tension` text,
  `obj_tension` varchar(255) DEFAULT NULL,
  `brochure_sel1` tinyint(1) DEFAULT NULL,
  `brochure_sel2` tinyint(1) DEFAULT NULL,
  `commentaire_sel` varchar(255) DEFAULT NULL,
  `brochure_alcool1` tinyint(1) DEFAULT NULL,
  `brochure_alcool2` tinyint(1) DEFAULT NULL,
  `commentaire_alcool` varchar(255) DEFAULT NULL,
  `brochure_activite1` tinyint(1) DEFAULT NULL,
  `brochure_activite2` tinyint(1) DEFAULT NULL,
  `commentaire_activite` varchar(255) DEFAULT NULL,
  `brochure_tabac1` tinyint(1) DEFAULT NULL,
  `brochure_tabac2` tinyint(1) DEFAULT NULL,
  `commentaire_tabac` varchar(255) DEFAULT NULL,
  `brochure_poids1` tinyint(1) DEFAULT NULL,
  `brochure_poids2` tinyint(1) DEFAULT NULL,
  `commentaire_poids` varchar(255) DEFAULT NULL,
  `brochure_alim1` tinyint(1) DEFAULT NULL,
  `brochure_alim2` tinyint(1) DEFAULT NULL,
  `commentaire_alim` varchar(255) DEFAULT NULL,
  `brochure_cafe1` tinyint(1) DEFAULT NULL,
  `brochure_cafe2` tinyint(1) DEFAULT NULL,
  `commentaire_cafe` varchar(255) DEFAULT NULL,
  `probleme_qualite_vie` tinyint(1) DEFAULT NULL,
  `detail_qualite_vie` varchar(255) DEFAULT NULL,
  `probleme_secondaire` tinyint(1) DEFAULT NULL,
  `detail_secondaire` varchar(255) DEFAULT NULL,
  `pb_delivrance` tinyint(1) DEFAULT NULL,
  `detail_delivrance` varchar(255) DEFAULT NULL,
  `regularite_prise` tinyint(1) DEFAULT NULL,
  `detail_regularite` varchar(255) DEFAULT NULL,
  `degre_satisfaction` enum('a+','a','b','c','d') DEFAULT NULL,
  `duree` int(11) DEFAULT NULL,
  `points_positifs` text,
  `points_ameliorations` text,
  `type_consultation` set('','dep_diab','suivi_diab','automesure','sein','colon','uterus','cognitif','hemocult','rcva','autres') DEFAULT NULL,
  `ecg_seul` tinyint(1) DEFAULT NULL,
  `ecg` tinyint(1) DEFAULT NULL,
  `monofil` tinyint(1) DEFAULT NULL,
  `exapied` tinyint(1) DEFAULT NULL,
  `hba` tinyint(1) DEFAULT NULL,
  `tension` tinyint(1) DEFAULT NULL,
  `spirometre_seul` tinyint(1) DEFAULT NULL,
  `spirometre` tinyint(1) DEFAULT NULL,
  `t_cognitif` tinyint(1) DEFAULT NULL,
  `autre` tinyint(1) DEFAULT NULL,
  `prec_autre` varchar(100) DEFAULT NULL,
  `aspects_limitant` text NOT NULL,
  `aspects_facilitant` text NOT NULL,
  `objectifs_patient` text NOT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `consult_domicile` tinyint(4) DEFAULT NULL,
  `consult_tel` tinyint(4) DEFAULT NULL,
  `consult_collective` tinyint(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `cardio_diag_educ`
--

CREATE TABLE `cardio_diag_educ` (
  `id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `objectif_poids` tinyint(1) DEFAULT NULL,
  `commentaire_obj_poids` varchar(255) DEFAULT NULL,
  `objectif_alcool` tinyint(1) DEFAULT NULL,
  `commentaire_obj_alcool` varchar(255) DEFAULT NULL,
  `objectif_tabac` tinyint(1) DEFAULT NULL,
  `commentaire_obj_tabac` varchar(255) DEFAULT NULL,
  `objectif_tension` tinyint(1) DEFAULT NULL,
  `commentaire_obj_tension` varchar(255) DEFAULT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `cardio_premiere_consult`
--

CREATE TABLE `cardio_premiere_consult` (
  `id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `objectif_poids` tinyint(1) DEFAULT NULL,
  `commentaire_obj_poids` varchar(255) DEFAULT NULL,
  `objectif_alcool` tinyint(1) DEFAULT NULL,
  `commentaire_obj_alcool` varchar(255) DEFAULT NULL,
  `objectif_tabac` tinyint(1) DEFAULT NULL,
  `commentaire_obj_tabac` varchar(255) DEFAULT NULL,
  `objectif_tension` tinyint(1) DEFAULT NULL,
  `commentaire_obj_tension` varchar(255) DEFAULT NULL,
  `conseil_sel` tinyint(1) DEFAULT NULL,
  `brochure_sel1` tinyint(1) DEFAULT NULL,
  `brochure_sel2` tinyint(1) DEFAULT NULL,
  `commentaire_sel` varchar(255) DEFAULT NULL,
  `conseil_alcool` tinyint(1) DEFAULT NULL,
  `brochure_alcool1` tinyint(1) DEFAULT NULL,
  `brochure_alcool2` tinyint(1) DEFAULT NULL,
  `commentaire_alcool` varchar(255) DEFAULT NULL,
  `conseil_activite` tinyint(1) DEFAULT NULL,
  `brochure_activite1` tinyint(1) DEFAULT NULL,
  `brochure_activite2` tinyint(1) DEFAULT NULL,
  `commentaire_activite` varchar(255) DEFAULT NULL,
  `conseil_tabac` tinyint(1) DEFAULT NULL,
  `brochure_tabac1` tinyint(1) DEFAULT NULL,
  `brochure_tabac2` tinyint(1) DEFAULT NULL,
  `commentaire_tabac` varchar(255) DEFAULT NULL,
  `conseil_poids` tinyint(1) DEFAULT NULL,
  `brochure_poids1` tinyint(1) DEFAULT NULL,
  `brochure_poids2` tinyint(1) DEFAULT NULL,
  `commentaire_poids` varchar(255) DEFAULT NULL,
  `conseil_alim` tinyint(1) DEFAULT NULL,
  `brochure_alim1` tinyint(1) DEFAULT NULL,
  `brochure_alim2` tinyint(1) DEFAULT NULL,
  `commentaire_alim` varchar(255) DEFAULT NULL,
  `conseil_cafe` tinyint(1) DEFAULT NULL,
  `brochure_cafe1` tinyint(1) DEFAULT NULL,
  `brochure_cafe2` tinyint(1) DEFAULT NULL,
  `commentaire_cafe` varchar(255) DEFAULT NULL,
  `degre_satisfaction` enum('a+','a','b','c','d') DEFAULT NULL,
  `duree` int(11) DEFAULT NULL,
  `points_positifs` text,
  `points_ameliorations` text,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `cardio_vasculaire_depart`
--

CREATE TABLE `cardio_vasculaire_depart` (
  `id` int(15) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `antecedants` enum('oui','non','nsp') DEFAULT NULL,
  `Chol` float DEFAULT NULL,
  `dChol` date DEFAULT NULL,
  `HDL` float DEFAULT NULL,
  `dHDL` date DEFAULT NULL,
  `LDL` float DEFAULT NULL,
  `dLDL` date DEFAULT NULL,
  `triglycerides` float DEFAULT NULL,
  `dtriglycerides` date DEFAULT NULL,
  `traitement` set('Aucun','Atorvastatine','Fluvastatine','Pravastatine','Rosuvastatine','Simvastatine','Simvastatine_ezetimibe','Ezetimibe','Bezafibrate','Ciprofibrate','Fenofibrate','Gemfibrozil','Cholestyramine','Colestipol','Tiadenol','Benfluorex','atorvastatine_ezetimibe') DEFAULT NULL,
  `dosage` varchar(50) DEFAULT NULL,
  `HTA` enum('oui','non') DEFAULT NULL,
  `TaSys` int(4) DEFAULT NULL,
  `TaDia` int(4) DEFAULT NULL,
  `dTA` date DEFAULT NULL,
  `TA_mode` enum('manuel','automatique','automesure') DEFAULT NULL,
  `hypertenseur3` enum('oui','non','nsp') DEFAULT NULL,
  `automesure` enum('oui','non','nsp') DEFAULT NULL,
  `diuretique` enum('oui','non','nsp') DEFAULT NULL,
  `HVG` enum('oui','non','nsp') DEFAULT NULL,
  `surcharge_ventricule` enum('oui','non','nsp') DEFAULT NULL,
  `sokolov` float DEFAULT NULL,
  `dsokolov` date DEFAULT NULL,
  `Creat` float DEFAULT NULL,
  `dCreat` date DEFAULT NULL,
  `kaliemie` float DEFAULT NULL,
  `dkaliemie` date DEFAULT NULL,
  `proteinurie` tinyint(1) DEFAULT NULL,
  `dproteinurie` date DEFAULT NULL,
  `hematurie` tinyint(1) DEFAULT NULL,
  `dhematurie` date DEFAULT NULL,
  `dFond` date DEFAULT NULL,
  `dECG` date DEFAULT NULL,
  `tabac` enum('oui','non','nsp') DEFAULT NULL,
  `nbrtabac` varchar(5) DEFAULT NULL,
  `darret` date DEFAULT NULL,
  `spirometrie_status` enum('n','a') DEFAULT NULL,
  `spirometrie_date` date DEFAULT NULL,
  `spirometrie_CVF` float DEFAULT NULL,
  `spirometrie_VEMS` float DEFAULT NULL,
  `spirometrie_DEP` float DEFAULT NULL,
  `spirometrie_rapport_VEMS_CVF` float DEFAULT NULL,
  `spirometrie` float DEFAULT NULL,
  `poids` float DEFAULT NULL,
  `dpoids` date DEFAULT NULL,
  `activite` float DEFAULT NULL,
  `pouls` float DEFAULT NULL,
  `dpouls` date DEFAULT NULL,
  `alcool` enum('oui','non','nsp') DEFAULT NULL,
  `glycemie` float DEFAULT NULL,
  `dgly` date DEFAULT NULL,
  `exam_cardio` date DEFAULT NULL,
  `sortir_rappel` tinyint(1) DEFAULT NULL,
  `raison_sortie` text,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `carte_grise_grille_taux_actuelle`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `carte_grise_grille_taux_actuelle` (
`id_demandeur` int(11)
,`login_demandeur` varchar(50)
,`puissance` float(15,2)
,`taux` float(15,2)
);

-- --------------------------------------------------------

--
-- Structure de la table `certificats`
--

CREATE TABLE `certificats` (
  `owner` varchar(50) NOT NULL,
  `ownermail` varchar(50) NOT NULL,
  `organisation` varchar(50) NOT NULL DEFAULT 'asalee',
  `token` varchar(50) NOT NULL,
  `lot` varchar(50) NOT NULL DEFAULT '0',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `competences_infirmier`
--

CREATE TABLE `competences_infirmier` (
  `login` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `diabete` smallint(6) NOT NULL,
  `rcva` smallint(6) NOT NULL,
  `bpco_spiro` smallint(6) NOT NULL,
  `cognitif` smallint(6) NOT NULL,
  `cancer` smallint(6) NOT NULL,
  `pied_diabetique` smallint(6) NOT NULL,
  `mms` smallint(6) NOT NULL,
  `rea_spiro` smallint(6) NOT NULL,
  `nutrition` smallint(6) NOT NULL,
  `act_physique` smallint(6) NOT NULL,
  `vigilance2` smallint(6) NOT NULL,
  `obesite` smallint(6) NOT NULL,
  `apnee_sommeil` smallint(6) NOT NULL,
  `tabac_addict` smallint(6) NOT NULL,
  `coord_geronto` smallint(6) NOT NULL,
  `retinographie` smallint(6) NOT NULL,
  `autre_domaine` varchar(250) NOT NULL,
  `evaluer_pps` smallint(6) NOT NULL,
  `anim_etp_collec` smallint(6) NOT NULL,
  `programme_etp_collec` smallint(6) NOT NULL,
  `formation_etp` smallint(6) NOT NULL,
  `amelioration_formation_etp` smallint(6) NOT NULL,
  `entretien_etp` smallint(6) NOT NULL,
  `coord_reu_secteur` smallint(6) NOT NULL,
  `orga_reu_secteur` smallint(6) NOT NULL,
  `coord_compagnonnage` smallint(6) NOT NULL,
  `rea_compagnonnage` smallint(6) NOT NULL,
  `recrutement` smallint(6) NOT NULL,
  `elaboration_analyse_pratiques` smallint(6) NOT NULL,
  `animation_analyse_pratiques` smallint(6) NOT NULL,
  `aide_installation` smallint(6) NOT NULL,
  `suport_exercice_mixte` smallint(6) NOT NULL,
  `utilisation_portail_psa` smallint(6) NOT NULL,
  `integration_donnees` smallint(6) NOT NULL,
  `informatique` smallint(6) NOT NULL,
  `bureautique` smallint(6) NOT NULL,
  `communication` smallint(6) NOT NULL,
  `almapro` smallint(6) NOT NULL,
  `axisante4` smallint(6) NOT NULL,
  `axisante5` smallint(6) NOT NULL,
  `clinidoc` smallint(6) NOT NULL,
  `crossway` smallint(6) NOT NULL,
  `dbmed` smallint(6) NOT NULL,
  `docware` smallint(6) NOT NULL,
  `easyprat` smallint(6) NOT NULL,
  `eomed` smallint(6) NOT NULL,
  `hellodoc` smallint(6) NOT NULL,
  `hellodoc5_55` smallint(6) NOT NULL,
  `hellodoc5_6` smallint(6) NOT NULL,
  `hypermed` smallint(6) NOT NULL,
  `ict` smallint(6) NOT NULL,
  `maldis` smallint(6) NOT NULL,
  `medi` smallint(6) NOT NULL,
  `medicawin` smallint(6) NOT NULL,
  `mediclick` smallint(6) NOT NULL,
  `mediclick5` smallint(6) NOT NULL,
  `medimust` smallint(6) NOT NULL,
  `medistory` smallint(6) NOT NULL,
  `mediwin` smallint(6) NOT NULL,
  `shaman` smallint(6) NOT NULL,
  `weda` smallint(6) NOT NULL,
  `xmed` smallint(6) NOT NULL,
  `mlm` smallint(6) NOT NULL COMMENT 'MLM en minuscule',
  `autre_logiciel` varchar(250) NOT NULL,
  `transverses_compagnonnage` smallint(6) NOT NULL,
  `transverses_reunion` smallint(6) NOT NULL,
  `transverses_contact` smallint(6) NOT NULL,
  `transverses_sevrage_tabac` smallint(6) NOT NULL,
  `transverses_apa` smallint(6) NOT NULL,
  `datemaj` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `competences_infirmier_v1`
--

CREATE TABLE `competences_infirmier_v1` (
  `login` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `diabete` smallint(6) NOT NULL,
  `rcva` smallint(6) NOT NULL,
  `bpco_spiro` smallint(6) NOT NULL,
  `cognitif` smallint(6) NOT NULL,
  `cancer` smallint(6) NOT NULL,
  `pied_diabetique` smallint(6) NOT NULL,
  `mms` smallint(6) NOT NULL,
  `rea_spiro` smallint(6) NOT NULL,
  `nutrition` smallint(6) NOT NULL,
  `act_physique` smallint(6) NOT NULL,
  `vigilance2` smallint(6) NOT NULL,
  `obesite` smallint(6) NOT NULL,
  `apnee_sommeil` smallint(6) NOT NULL,
  `tabac_addict` smallint(6) NOT NULL,
  `coord_geronto` smallint(6) NOT NULL,
  `autre_domaine` varchar(250) NOT NULL,
  `evaluer_pps` smallint(6) NOT NULL,
  `anim_etp_collec` smallint(6) NOT NULL,
  `programme_etp_collec` smallint(6) NOT NULL,
  `formation_etp` smallint(6) NOT NULL,
  `amelioration_formation_etp` smallint(6) NOT NULL,
  `entretien_etp` smallint(6) NOT NULL,
  `coord_reu_secteur` smallint(6) NOT NULL,
  `orga_reu_secteur` smallint(6) NOT NULL,
  `coord_compagnonnage` smallint(6) NOT NULL,
  `rea_compagnonnage` smallint(6) NOT NULL,
  `recrutement` smallint(6) NOT NULL,
  `elaboration_analyse_pratiques` smallint(6) NOT NULL,
  `animation_analyse_pratiques` smallint(6) NOT NULL,
  `aide_installation` smallint(6) NOT NULL,
  `suport_exercice_mixte` smallint(6) NOT NULL,
  `utilisation_portail_psa` smallint(6) NOT NULL,
  `integration_donnees` smallint(6) NOT NULL,
  `informatique` smallint(6) NOT NULL,
  `bureautique` smallint(6) NOT NULL,
  `communication` smallint(6) NOT NULL,
  `almapro` smallint(6) NOT NULL,
  `axisante4` smallint(6) NOT NULL,
  `axisante5` smallint(6) NOT NULL,
  `clinidoc` smallint(6) NOT NULL,
  `crossway` smallint(6) NOT NULL,
  `dbmed` smallint(6) NOT NULL,
  `docware` smallint(6) NOT NULL,
  `easyprat` smallint(6) NOT NULL,
  `eomed` smallint(6) NOT NULL,
  `hellodoc` smallint(6) NOT NULL,
  `hellodoc5_55` smallint(6) NOT NULL,
  `hellodoc5_6` smallint(6) NOT NULL,
  `hypermed` smallint(6) NOT NULL,
  `ict` smallint(6) NOT NULL,
  `maldis` smallint(6) NOT NULL,
  `medi` smallint(6) NOT NULL,
  `medicawin` smallint(6) NOT NULL,
  `mediclick` smallint(6) NOT NULL,
  `mediclick5` smallint(6) NOT NULL,
  `medimust` smallint(6) NOT NULL,
  `medistory` smallint(6) NOT NULL,
  `mediwin` smallint(6) NOT NULL,
  `shaman` smallint(6) NOT NULL,
  `weda` smallint(6) NOT NULL,
  `xmed` smallint(6) NOT NULL,
  `autre_logiciel` varchar(250) NOT NULL,
  `datemaj` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `conges`
--

CREATE TABLE `conges` (
  `id` int(11) NOT NULL,
  `date_demande` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `nom` varchar(100) NOT NULL DEFAULT '',
  `prenom` varchar(100) NOT NULL DEFAULT '',
  `inf_login` varchar(50) NOT NULL,
  `date_debut` date NOT NULL DEFAULT '0000-00-00',
  `date_fin` date DEFAULT '0000-00-00',
  `nature` varchar(100) NOT NULL DEFAULT '',
  `prec` varchar(255) NOT NULL DEFAULT '',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `connexion_portail`
--

CREATE TABLE `connexion_portail` (
  `portail` text NOT NULL,
  `login` text NOT NULL,
  `date_tentative` datetime NOT NULL,
  `retour` text NOT NULL,
  `IP` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `PK_contact` int(11) NOT NULL,
  `type_message` varchar(30) DEFAULT NULL,
  `objet` varchar(30) DEFAULT NULL,
  `objetautre` varchar(30) DEFAULT NULL,
  `detail` varchar(250) DEFAULT NULL,
  `nom` varchar(30) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `contacter` tinyint(1) DEFAULT '0',
  `date_CRE` datetime DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `cormeau`
--

CREATE TABLE `cormeau` (
  `numero` varchar(10) NOT NULL DEFAULT '',
  `nom` varchar(50) NOT NULL DEFAULT '',
  `prenom` varchar(50) NOT NULL DEFAULT '',
  `dnaiss` date NOT NULL DEFAULT '0000-00-00',
  `sexe` char(2) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `courriers_bilans`
--

CREATE TABLE `courriers_bilans` (
  `id` int(11) NOT NULL,
  `date_doc` date DEFAULT NULL,
  `nom_doc` varchar(255) DEFAULT NULL,
  `text_doc` text,
  `ordre` varchar(255) DEFAULT NULL,
  `indic` varchar(255) DEFAULT NULL,
  `initiales` varchar(255) DEFAULT NULL,
  `date_resultat` date DEFAULT NULL,
  `labo_angio` varchar(255) DEFAULT NULL,
  `couleur` varchar(255) DEFAULT NULL,
  `id_unique` varchar(255) DEFAULT NULL,
  `correspondants` varchar(255) DEFAULT NULL,
  `rid_patient` varchar(255) DEFAULT NULL,
  `time_stamp` datetime DEFAULT NULL,
  `type_doc` varchar(255) DEFAULT NULL,
  `zone_plug` varchar(255) DEFAULT NULL,
  `suivi_etat` varchar(255) DEFAULT NULL,
  `rid_praticien` varchar(255) DEFAULT NULL,
  `chemin` varchar(255) DEFAULT NULL,
  `sous_dossier` varchar(255) DEFAULT NULL,
  `rid_imagerie` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `dashboard_results`
--

CREATE TABLE `dashboard_results` (
  `cabinet` varchar(50) NOT NULL,
  `nom_cabinet` varchar(100) NOT NULL,
  `infirmieres` varchar(100) NOT NULL,
  `localisation` varchar(100) NOT NULL,
  `periode` varchar(50) NOT NULL,
  `consultation` float(10,2) NOT NULL,
  `consultation_percent` varchar(20) NOT NULL,
  `gestion_dossier` float(10,2) NOT NULL,
  `gestion_dossier_percent` varchar(10) NOT NULL,
  `concertation` varchar(10) NOT NULL,
  `concertation_percent` varchar(10) NOT NULL,
  `formation` float(10,2) NOT NULL,
  `formation_percent` varchar(10) NOT NULL,
  `contrib_asalee` float(10,2) NOT NULL,
  `contrib_asalee_percent` varchar(10) NOT NULL,
  `non_attribue` float(10,2) NOT NULL,
  `non_attribue_percent` varchar(10) NOT NULL,
  `total` float(10,2) NOT NULL,
  `total_percent` varchar(10) NOT NULL,
  `jours_retenus` float(10,2) NOT NULL,
  `nbre_consultations` int(11) NOT NULL,
  `consultations_jours` float(10,2) NOT NULL,
  `objectifs_percent` varchar(10) NOT NULL,
  `total_actes_derog` int(11) NOT NULL,
  `spirometrie` int(11) NOT NULL,
  `troubles_cogn` int(11) NOT NULL,
  `ecg` int(11) NOT NULL,
  `examen_du_pied` int(11) NOT NULL,
  `monofilament` int(11) NOT NULL,
  `autre_suivi_diabete` int(11) NOT NULL,
  `nb_examens_saisis` int(11) NOT NULL,
  `nb_examens_mois` int(11) NOT NULL,
  `patient_protocole` int(11) NOT NULL,
  `patient_dep_diabete` int(11) NOT NULL,
  `patient_suivi_diabete` int(11) NOT NULL,
  `patient_rcva` int(11) NOT NULL,
  `patient_trouble_cogn` int(11) NOT NULL,
  `patient_bpco` int(11) NOT NULL,
  `patient_cancer` int(11) NOT NULL,
  `patient_sevrage_tabac` int(11) NOT NULL,
  `patient_autre_type` int(11) NOT NULL,
  `patient_multiprotocole` int(11) NOT NULL,
  `nb_patient_periode` int(11) NOT NULL,
  `nb_patient_periode_percent` varchar(10) NOT NULL,
  `hba1c_1_avant` float(10,2) NOT NULL,
  `hba1c_1_apres` float(10,2) NOT NULL,
  `hba1c_1_percent` varchar(10) NOT NULL,
  `hba1c_2_avant` float(10,2) NOT NULL,
  `hba1c_2_apres` float(10,2) NOT NULL,
  `hba1c_2_percent` varchar(10) NOT NULL,
  `hba1c_3_avant` float(10,2) NOT NULL,
  `hba1c_3_apres` float(10,2) NOT NULL,
  `hba1c_3_percent` varchar(10) NOT NULL,
  `hba1c_4_avant` float(10,2) NOT NULL,
  `hba1c_4_apres` float(10,2) NOT NULL,
  `hba1c_4_percent` varchar(10) NOT NULL,
  `hba1c_5_avant` float(10,2) NOT NULL,
  `hba1c_5_apres` float(10,2) NOT NULL,
  `hba1c_5_percent` varchar(10) NOT NULL,
  `hba1c_6_avant` float(10,2) NOT NULL,
  `hba1c_6_apres` float(10,2) NOT NULL,
  `hba1c_6_percent` varchar(10) NOT NULL,
  `ldl_1_avant` float(10,2) NOT NULL,
  `ldl_1_apres` float(10,2) NOT NULL,
  `ldl_1_percent` varchar(10) NOT NULL,
  `ldl_2_avant` float(10,2) NOT NULL,
  `ldl_2_apres` float(10,2) NOT NULL,
  `ldl_2_percent` varchar(10) NOT NULL,
  `ldl_3_avant` float(10,2) NOT NULL,
  `ldl_3_apres` float(10,2) NOT NULL,
  `ldl_3_percent` varchar(10) NOT NULL,
  `tension_1` varchar(10) NOT NULL,
  `tension_2` varchar(10) NOT NULL,
  `tension_3` varchar(10) NOT NULL,
  `tension_4` varchar(10) NOT NULL,
  `nb_spiro_unique` int(11) NOT NULL,
  `efr_percent` varchar(10) NOT NULL,
  `nb_depistage_cognitif` int(11) NOT NULL,
  `test_cognitif_percent` varchar(10) NOT NULL,
  `nb_patient_total` int(11) NOT NULL,
  `nb_patient_diab_type2` int(11) NOT NULL,
  `nb_patient_risque_cardio` int(11) NOT NULL,
  `nb_patient_bpco` int(11) NOT NULL,
  `nb_patient_cogn` int(11) NOT NULL,
  `publipostage` varchar(100) NOT NULL,
  `date_edition` datetime NOT NULL,
  `date_periode` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `dashboard_results_sauv`
--

CREATE TABLE `dashboard_results_sauv` (
  `cabinet` varchar(50) NOT NULL,
  `nom_cabinet` varchar(100) NOT NULL,
  `infirmieres` varchar(100) NOT NULL,
  `localisation` varchar(100) NOT NULL,
  `periode` varchar(50) NOT NULL,
  `consultation` float(10,2) NOT NULL,
  `consultation_percent` varchar(20) NOT NULL,
  `gestion_dossier` float(10,2) NOT NULL,
  `gestion_dossier_percent` varchar(10) NOT NULL,
  `concertation` varchar(10) NOT NULL,
  `concertation_percent` varchar(10) NOT NULL,
  `formation` float(10,2) NOT NULL,
  `formation_percent` varchar(10) NOT NULL,
  `contrib_asalee` float(10,2) NOT NULL,
  `contrib_asalee_percent` varchar(10) NOT NULL,
  `non_attribue` float(10,2) NOT NULL,
  `non_attribue_percent` varchar(10) NOT NULL,
  `total` float(10,2) NOT NULL,
  `total_percent` varchar(10) NOT NULL,
  `jours_retenus` float(10,2) NOT NULL,
  `nbre_consultations` int(11) NOT NULL,
  `consultations_jours` float(10,2) NOT NULL,
  `objectifs_percent` varchar(10) NOT NULL,
  `total_actes_derog` int(11) NOT NULL,
  `spirometrie` int(11) NOT NULL,
  `troubles_cogn` int(11) NOT NULL,
  `ecg` int(11) NOT NULL,
  `examen_du_pied` int(11) NOT NULL,
  `monofilament` int(11) NOT NULL,
  `autre_suivi_diabete` int(11) NOT NULL,
  `nb_examens_saisis` int(11) NOT NULL,
  `nb_examens_mois` int(11) NOT NULL,
  `patient_protocole` int(11) NOT NULL,
  `patient_dep_diabete` int(11) NOT NULL,
  `patient_suivi_diabete` int(11) NOT NULL,
  `patient_rcva` int(11) NOT NULL,
  `patient_trouble_cogn` int(11) NOT NULL,
  `patient_bpco` int(11) NOT NULL,
  `patient_cancer` int(11) NOT NULL,
  `patient_autre_type` int(11) NOT NULL,
  `patient_multiprotocole` int(11) NOT NULL,
  `nb_patient_periode` int(11) NOT NULL,
  `nb_patient_periode_percent` varchar(10) NOT NULL,
  `hba1c_1_avant` float(10,2) NOT NULL,
  `hba1c_1_apres` float(10,2) NOT NULL,
  `hba1c_1_percent` varchar(10) NOT NULL,
  `hba1c_2_avant` float(10,2) NOT NULL,
  `hba1c_2_apres` float(10,2) NOT NULL,
  `hba1c_2_percent` varchar(10) NOT NULL,
  `hba1c_3_avant` float(10,2) NOT NULL,
  `hba1c_3_apres` float(10,2) NOT NULL,
  `hba1c_3_percent` varchar(10) NOT NULL,
  `hba1c_4_avant` float(10,2) NOT NULL,
  `hba1c_4_apres` float(10,2) NOT NULL,
  `hba1c_4_percent` varchar(10) NOT NULL,
  `hba1c_5_avant` float(10,2) NOT NULL,
  `hba1c_5_apres` float(10,2) NOT NULL,
  `hba1c_5_percent` varchar(10) NOT NULL,
  `hba1c_6_avant` float(10,2) NOT NULL,
  `hba1c_6_apres` float(10,2) NOT NULL,
  `hba1c_6_percent` varchar(10) NOT NULL,
  `ldl_1_avant` float(10,2) NOT NULL,
  `ldl_1_apres` float(10,2) NOT NULL,
  `ldl_1_percent` varchar(10) NOT NULL,
  `ldl_2_avant` float(10,2) NOT NULL,
  `ldl_2_apres` float(10,2) NOT NULL,
  `ldl_2_percent` varchar(10) NOT NULL,
  `ldl_3_avant` float(10,2) NOT NULL,
  `ldl_3_apres` float(10,2) NOT NULL,
  `ldl_3_percent` varchar(10) NOT NULL,
  `tension_1` varchar(10) NOT NULL,
  `tension_2` varchar(10) NOT NULL,
  `tension_3` varchar(10) NOT NULL,
  `tension_4` varchar(10) NOT NULL,
  `nb_spiro_unique` int(11) NOT NULL,
  `efr_percent` varchar(10) NOT NULL,
  `nb_depistage_cognitif` int(11) NOT NULL,
  `test_cognitif_percent` varchar(10) NOT NULL,
  `nb_patient_total` int(11) NOT NULL,
  `nb_patient_diab_type2` int(11) NOT NULL,
  `nb_patient_risque_cardio` int(11) NOT NULL,
  `nb_patient_bpco` int(11) NOT NULL,
  `nb_patient_cogn` int(11) NOT NULL,
  `publipostage` varchar(100) NOT NULL,
  `date_edition` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `demande_carte_grise_grille`
--

CREATE TABLE `demande_carte_grise_grille` (
  `id` int(11) NOT NULL,
  `puissance` float(15,2) NOT NULL,
  `taux` float(15,2) NOT NULL,
  `dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `demande_carte_grise_historique`
--

CREATE TABLE `demande_carte_grise_historique` (
  `id` int(11) NOT NULL,
  `id_demandeur` int(11) NOT NULL,
  `login_demandeur` varchar(50) NOT NULL,
  `date_obtention` date NOT NULL DEFAULT '1970-01-01',
  `puissance` float(15,2) NOT NULL,
  `precisions` varchar(255) NOT NULL DEFAULT '',
  `justificatif` varchar(255) DEFAULT NULL,
  `dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `demande_carte_grise_identification`
--

CREATE TABLE `demande_carte_grise_identification` (
  `id` int(11) NOT NULL,
  `intitule` varchar(255) NOT NULL DEFAULT '',
  `dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `demande_carte_grise_status`
--

CREATE TABLE `demande_carte_grise_status` (
  `id` int(11) NOT NULL,
  `intitule` varchar(255) NOT NULL DEFAULT '',
  `dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `demande_carte_grise_suivi`
--

CREATE TABLE `demande_carte_grise_suivi` (
  `id` int(11) NOT NULL,
  `id_demande_carte_grise` int(11) NOT NULL,
  `id_status` int(11) NOT NULL,
  `id_historique` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `login_utilisateur` varchar(50) NOT NULL,
  `notes` varchar(250) NOT NULL DEFAULT '',
  `dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `demande_carte_grise_vue_detaillee`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `demande_carte_grise_vue_detaillee` (
`id` int(11)
,`identifiant_suivi` int(11)
,`identifiant_demande` int(11)
,`date_demande` date
,`titre` varchar(255)
,`id_demandeur` int(11)
,`login_demandeur` varchar(50)
,`nom_demandeur` varchar(131)
,`date_obtention` date
,`puissance` float(15,2)
,`precisions` varchar(255)
,`justificatif` varchar(255)
,`dernier_intervenant_id` int(11)
,`dernierIntervenant` varchar(50)
,`nom_intervenant` varchar(131)
,`dernierStatus` varchar(255)
,`id_status` int(11)
,`date_dernierStatut` date
,`notes` varchar(250)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `demande_carte_grise_vue_resumee`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `demande_carte_grise_vue_resumee` (
`id` int(11)
,`identifiant_suivi` int(11)
,`identifiant_demande` int(11)
,`date_demande` date
,`titre` varchar(255)
,`id_demandeur` int(11)
,`login_demandeur` varchar(50)
,`nom_demandeur` varchar(131)
,`date_obtention` date
,`puissance` float(15,2)
,`precisions` varchar(255)
,`justificatif` varchar(255)
,`dernier_intervenant_id` int(11)
,`dernierIntervenant` varchar(50)
,`nom_intervenant` varchar(131)
,`dernierStatus` varchar(255)
,`id_status` int(11)
,`date_dernierStatut` date
,`notes` varchar(250)
);

-- --------------------------------------------------------

--
-- Structure de la table `demande_frais_historique`
--

CREATE TABLE `demande_frais_historique` (
  `id` int(11) NOT NULL,
  `id_demandeur` int(11) NOT NULL,
  `login_demandeur` varchar(50) NOT NULL,
  `date_frais` date NOT NULL DEFAULT '1970-01-01',
  `nature` varchar(255) NOT NULL DEFAULT '',
  `motif` varchar(255) NOT NULL DEFAULT '',
  `distance` float(15,2) NOT NULL DEFAULT '0.00',
  `taux_applique` float(5,2) NOT NULL DEFAULT '0.00',
  `montant` float(15,2) NOT NULL,
  `justificatif` varchar(255) DEFAULT NULL,
  `dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `demande_frais_identification`
--

CREATE TABLE `demande_frais_identification` (
  `id` int(11) NOT NULL,
  `intitule` varchar(255) NOT NULL DEFAULT '',
  `dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `demande_frais_status`
--

CREATE TABLE `demande_frais_status` (
  `id` int(11) NOT NULL,
  `intitule` varchar(255) NOT NULL DEFAULT '',
  `dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `demande_frais_suivi`
--

CREATE TABLE `demande_frais_suivi` (
  `id` int(11) NOT NULL,
  `id_frais` int(11) NOT NULL,
  `id_status` int(11) NOT NULL,
  `id_historique` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `login_utilisateur` varchar(50) NOT NULL,
  `notes` varchar(250) NOT NULL DEFAULT '',
  `dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `demande_frais_vue_detaillee`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `demande_frais_vue_detaillee` (
`id` int(11)
,`identifiant_suivi` int(11)
,`identifiant_demande` int(11)
,`date_demande` date
,`titre` varchar(255)
,`id_demandeur` int(11)
,`login_demandeur` varchar(50)
,`nom_demandeur` varchar(131)
,`date_frais` date
,`nature` varchar(255)
,`motif` varchar(255)
,`distance` float(15,2)
,`taux_applique` float(5,2)
,`montant` float(15,2)
,`justificatif` varchar(255)
,`dernier_intervenant_id` int(11)
,`dernierIntervenant` varchar(50)
,`nom_intervenant` varchar(131)
,`dernierStatus` varchar(255)
,`id_status` int(11)
,`date_dernierStatut` date
,`notes` varchar(250)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `demande_frais_vue_resumee`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `demande_frais_vue_resumee` (
`id` int(11)
,`identifiant_suivi` int(11)
,`date_demande` date
,`titre` varchar(255)
,`id_demandeur` int(11)
,`login_demandeur` varchar(50)
,`nom_demandeur` varchar(131)
,`date_frais` date
,`nature` varchar(255)
,`motif` varchar(255)
,`distance` float(15,2)
,`taux_applique` float(5,2)
,`montant` float(15,2)
,`justificatif` varchar(255)
,`dernier_intervenant_id` int(11)
,`dernierIntervenant` varchar(50)
,`nom_intervenant` varchar(131)
,`dernierStatus` varchar(255)
,`id_status` int(11)
,`date_dernierStatut` date
,`notes` varchar(250)
);

-- --------------------------------------------------------

--
-- Structure de la table `depistage_aomi`
--

CREATE TABLE `depistage_aomi` (
  `id` int(11) NOT NULL,
  `dossier_id` int(11) DEFAULT NULL,
  `dossier_numero` varchar(45) DEFAULT NULL,
  `dt1plus20` varchar(45) DEFAULT NULL,
  `dt2` varchar(45) DEFAULT NULL,
  `tabacActifOuCorrige` varchar(45) DEFAULT NULL,
  `htaPermanente` varchar(45) DEFAULT NULL,
  `dyslipidemies` varchar(45) DEFAULT NULL,
  `pathoCVASansAOMIAvere` varchar(45) DEFAULT NULL,
  `antecedantsFamiliaux` varchar(45) DEFAULT NULL,
  `SOASAveree` varchar(45) DEFAULT NULL,
  `ipsd` varchar(45) DEFAULT NULL,
  `ipsg` varchar(45) DEFAULT NULL,
  `eda` varchar(45) DEFAULT NULL,
  `initiateurIPS` varchar(45) DEFAULT NULL,
  `realisateurIPS` varchar(45) DEFAULT NULL,
  `commentaires` mediumtext,
  `provenance` varchar(45) DEFAULT NULL,
  `cabinet` varchar(45) DEFAULT NULL,
  `dateSaisie` date DEFAULT NULL,
  `infAjout` varchar(45) DEFAULT NULL,
  `dateAjout` timestamp NULL DEFAULT NULL,
  `estActif` tinyint(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `depistage_colon`
--

CREATE TABLE `depistage_colon` (
  `id` int(15) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `ant_pere_type` set('aucun','polypes','cancer') DEFAULT NULL,
  `ant_pere_age` int(11) DEFAULT NULL,
  `ant_mere_type` set('aucun','polypes','cancer') DEFAULT NULL,
  `ant_mere_age` int(11) DEFAULT NULL,
  `ant_fratrie_type` set('aucun','polypes','cancer') DEFAULT NULL,
  `ant_fratrie_age` int(11) DEFAULT NULL,
  `ant_collat_type` set('aucun','polypes','cancer') DEFAULT NULL,
  `ant_collat_age` int(11) DEFAULT NULL,
  `ant_enfants_type` set('aucun','polypes','cancer') DEFAULT NULL,
  `ant_enfants_age` int(11) DEFAULT NULL,
  `just_ant_fam` tinyint(1) DEFAULT NULL,
  `just_ant_polype` tinyint(1) DEFAULT NULL,
  `just_ant_cr_colique` tinyint(1) DEFAULT NULL,
  `just_ant_sg_selles` tinyint(1) DEFAULT NULL,
  `colos_date` date DEFAULT NULL,
  `colos_polypes` tinyint(1) DEFAULT NULL,
  `colos_dysplasie` enum('aucun','bas','haut','cancer') DEFAULT NULL,
  `rappel_colos_period` float DEFAULT NULL,
  `sortir_rappel` tinyint(1) DEFAULT NULL,
  `raison_sortie` text,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `depistage_colon_old`
--

CREATE TABLE `depistage_colon_old` (
  `id` int(15) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `ant_pere_type` enum('aucun','polypes','cancer') DEFAULT NULL,
  `ant_pere_age` int(11) DEFAULT NULL,
  `ant_mere_type` enum('aucun','polypes','cancer') DEFAULT NULL,
  `ant_mere_age` int(11) DEFAULT NULL,
  `ant_fratrie_type` enum('aucun','polypes','cancer') DEFAULT NULL,
  `ant_fratrie_age` int(11) DEFAULT NULL,
  `ant_collat_type` enum('aucun','polypes','cancer') DEFAULT NULL,
  `ant_collat_age` int(11) DEFAULT NULL,
  `ant_enfants_type` enum('aucun','polypes','cancer') DEFAULT NULL,
  `ant_enfants_age` int(11) DEFAULT NULL,
  `just_ant_fam` tinyint(1) DEFAULT NULL,
  `just_ant_polype` tinyint(1) DEFAULT NULL,
  `just_ant_cr_colique` tinyint(1) DEFAULT NULL,
  `just_ant_sg_selles` tinyint(1) DEFAULT NULL,
  `colos_date` date DEFAULT NULL,
  `colos_polypes` tinyint(1) DEFAULT NULL,
  `colos_dysplasie` enum('aucun','bas','haut','cancer') DEFAULT NULL,
  `rappel_colos_period` int(11) DEFAULT '0',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `depistage_diabete`
--

CREATE TABLE `depistage_diabete` (
  `id` int(15) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `dpoids` date NOT NULL DEFAULT '0000-00-00',
  `poids` float NOT NULL DEFAULT '0',
  `surpoids` tinyint(1) DEFAULT NULL,
  `parent_diabetique_type2` tinyint(1) DEFAULT NULL,
  `ant_intolerance_glucose` tinyint(1) DEFAULT NULL,
  `hypertension_arterielle` tinyint(1) DEFAULT NULL,
  `dyslipidemie_en_charge` tinyint(1) DEFAULT NULL,
  `hdl` tinyint(1) DEFAULT NULL,
  `bebe_sup_4kg` tinyint(1) DEFAULT NULL,
  `ant_diabete_gestationnel` tinyint(1) DEFAULT NULL,
  `corticotherapie` tinyint(1) DEFAULT NULL,
  `infection` tinyint(1) DEFAULT NULL,
  `intervention_chirugicale` tinyint(1) DEFAULT NULL,
  `autre` tinyint(1) DEFAULT NULL,
  `derniere_gly_date` date DEFAULT NULL,
  `derniere_gly_resultat` float DEFAULT NULL,
  `prescription_gly` tinyint(1) DEFAULT NULL,
  `nouvelle_gly_date` date DEFAULT NULL,
  `nouvelle_gly_resultat` float DEFAULT NULL,
  `note_gly` varchar(100) DEFAULT NULL,
  `mesure_suivi_diabete` tinyint(1) DEFAULT NULL,
  `mesure_suivi_hygieno_dietetique` tinyint(1) DEFAULT NULL,
  `mesure_suivi_controle_annuel` tinyint(1) DEFAULT NULL,
  `sortir_rappel` tinyint(1) DEFAULT NULL,
  `raison_sortie` text,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `depistage_sein`
--

CREATE TABLE `depistage_sein` (
  `id` int(15) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `ant_fam_mere` tinyint(1) DEFAULT NULL,
  `ant_fam_soeur` tinyint(1) DEFAULT NULL,
  `ant_fam_fille` tinyint(1) DEFAULT NULL,
  `ant_fam_tante` tinyint(1) DEFAULT NULL,
  `ant_fam_grandmere` tinyint(1) DEFAULT NULL,
  `dep_type` enum('indiv','coll') DEFAULT NULL,
  `mamograph_date` date DEFAULT NULL,
  `rappel_mammographie` date DEFAULT NULL,
  `sortir_rappel` tinyint(1) DEFAULT NULL,
  `raison_sortie` text,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `depistage_uterus`
--

CREATE TABLE `depistage_uterus` (
  `id` int(15) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `date_frottis` date DEFAULT NULL,
  `frottis_normal` char(3) DEFAULT NULL,
  `date_rappel` date DEFAULT NULL,
  `avis_medecin` text,
  `sortir_rappel` tinyint(1) DEFAULT NULL,
  `raison_sortie` text,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `diagnostic_educatif`
--

CREATE TABLE `diagnostic_educatif` (
  `id_dossier` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `clotured_at` datetime NOT NULL,
  `statut` smallint(6) NOT NULL,
  `aspects_limitants` text NOT NULL,
  `aspects_facilitants` text NOT NULL,
  `objectifs_patient` text NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `diagnostic_educatif_farid`
--

CREATE TABLE `diagnostic_educatif_farid` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `maladie_ressources` varchar(255) DEFAULT NULL,
  `maladie_freins` varchar(255) DEFAULT NULL,
  `maladie_prob_resoudre` varchar(255) DEFAULT NULL,
  `maladie_zone_ombre` varchar(255) DEFAULT NULL,
  `traitement_ressources` varchar(255) DEFAULT NULL,
  `traitement_freins` varchar(255) DEFAULT NULL,
  `traitement_prob_resoudre` varchar(255) DEFAULT NULL,
  `traitement_zone_ombre` varchar(255) DEFAULT NULL,
  `alimentation_ressources` varchar(255) DEFAULT NULL,
  `alimentation_freins` varchar(255) DEFAULT NULL,
  `alimentation_prob_resoudre` varchar(255) DEFAULT NULL,
  `alimentation_zone_ombre` varchar(255) DEFAULT NULL,
  `physique_ressources` varchar(255) DEFAULT NULL,
  `physique_freins` varchar(255) DEFAULT NULL,
  `physique_prob_resoudre` varchar(255) DEFAULT NULL,
  `physique_zone_ombre` varchar(255) DEFAULT NULL,
  `repos_ressources` varchar(255) DEFAULT NULL,
  `repos_freins` varchar(255) DEFAULT NULL,
  `repos_prob_resoudre` varchar(255) DEFAULT NULL,
  `repos_zone_ombre` varchar(255) DEFAULT NULL,
  `alcool_tabac_ressources` varchar(255) DEFAULT NULL,
  `alcool_tabac_freins` varchar(255) DEFAULT NULL,
  `alcool_tabac_prob_resoudre` varchar(255) DEFAULT NULL,
  `alcool_tabac_zone_ombre` varchar(255) DEFAULT NULL,
  `vie_sociale_ressources` varchar(255) DEFAULT NULL,
  `vie_sociale_freins` varchar(255) DEFAULT NULL,
  `vie_sociale_prob_resoudre` varchar(255) DEFAULT NULL,
  `vie_sociale_zone_ombre` varchar(255) DEFAULT NULL,
  `vie_famille_ressources` varchar(255) DEFAULT NULL,
  `vie_famille_freins` varchar(255) DEFAULT NULL,
  `vie_famille_prob_resoudre` varchar(255) DEFAULT NULL,
  `vie_famille_zone_ombre` varchar(255) DEFAULT NULL,
  `autres_ressources` varchar(255) DEFAULT NULL,
  `autres_freins` varchar(255) DEFAULT NULL,
  `autres_prob_resoudre` varchar(255) DEFAULT NULL,
  `autres_zone_ombre` varchar(255) DEFAULT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `donnees_cardio`
--

CREATE TABLE `donnees_cardio` (
  `cabinet` varchar(50) NOT NULL DEFAULT '',
  `antecedants` enum('','vert','orange','rouge') DEFAULT NULL,
  `Chol` enum('','vert','orange','rouge') DEFAULT NULL,
  `dChol` enum('','vert','orange','rouge') DEFAULT NULL,
  `HDL` enum('','vert','orange','rouge') DEFAULT NULL,
  `dHDL` enum('','vert','orange','rouge') DEFAULT NULL,
  `LDL` enum('','vert','orange','rouge') DEFAULT NULL,
  `dLDL` enum('','vert','orange','rouge') DEFAULT NULL,
  `triglycerides` enum('','vert','orange','rouge') DEFAULT NULL,
  `dtriglycerides` enum('','vert','orange','rouge') DEFAULT NULL,
  `traitement` enum('','vert','orange','rouge') DEFAULT NULL,
  `dosage` enum('','vert','orange','rouge') DEFAULT NULL,
  `HTA` enum('','vert','orange','rouge') DEFAULT NULL,
  `TaSys` enum('','vert','orange','rouge') DEFAULT NULL,
  `TaDia` enum('','vert','orange','rouge') DEFAULT NULL,
  `dTA` enum('','vert','orange','rouge') DEFAULT NULL,
  `hypertenseur3` enum('','vert','orange','rouge') DEFAULT NULL,
  `automesure` enum('','vert','orange','rouge') DEFAULT NULL,
  `diuretique` enum('','vert','orange','rouge') DEFAULT NULL,
  `HVG` enum('','vert','orange','rouge') DEFAULT NULL,
  `surcharge_ventricule` enum('','vert','orange','rouge') DEFAULT NULL,
  `sokolov` enum('','vert','orange','rouge') DEFAULT NULL,
  `dsokolov` enum('','vert','orange','rouge') DEFAULT NULL,
  `Creat` enum('','vert','orange','rouge') DEFAULT NULL,
  `dCreat` enum('','vert','orange','rouge') DEFAULT NULL,
  `kaliemie` enum('','vert','orange','rouge') DEFAULT NULL,
  `dkaliemie` enum('','vert','orange','rouge') DEFAULT NULL,
  `proteinurie` enum('','vert','orange','rouge') DEFAULT NULL,
  `dproteinurie` enum('','vert','orange','rouge') DEFAULT NULL,
  `hematurie` enum('','vert','orange','rouge') DEFAULT NULL,
  `dhematurie` enum('','vert','orange','rouge') DEFAULT NULL,
  `dFond` enum('','vert','orange','rouge') DEFAULT NULL,
  `dECG` enum('','vert','orange','rouge') DEFAULT NULL,
  `tabac` enum('','vert','orange','rouge') DEFAULT NULL,
  `darret` enum('','vert','orange','rouge') DEFAULT NULL,
  `poids` enum('','vert','orange','rouge') DEFAULT NULL,
  `dpoids` enum('','vert','orange','rouge') DEFAULT NULL,
  `activite` enum('','vert','orange','rouge') DEFAULT NULL,
  `pouls` enum('','vert','orange','rouge') DEFAULT NULL,
  `dpouls` enum('','vert','orange','rouge') DEFAULT NULL,
  `alcool` enum('','vert','orange','rouge') DEFAULT NULL,
  `glycemie` enum('','vert','orange','rouge') DEFAULT NULL,
  `dgly` enum('','vert','orange','rouge') DEFAULT NULL,
  `exam_cardio` enum('','vert','orange','rouge') DEFAULT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `dossier`
--

CREATE TABLE `dossier` (
  `id` int(15) NOT NULL,
  `cabinet` varchar(255) NOT NULL DEFAULT '',
  `numero` varchar(16) NOT NULL DEFAULT '',
  `dnaiss` date DEFAULT NULL,
  `sexe` enum('M','F') DEFAULT NULL,
  `taille` smallint(4) UNSIGNED DEFAULT NULL,
  `actif` enum('oui','non') DEFAULT NULL,
  `dconsentement` date NOT NULL DEFAULT '0000-00-00',
  `dcreat` date DEFAULT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `encnir` longtext NOT NULL,
  `enckey` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `dossier_kc`
--

CREATE TABLE `dossier_kc` (
  `id` int(15) NOT NULL,
  `cabinet` varchar(25) NOT NULL DEFAULT '',
  `numero` varchar(16) NOT NULL DEFAULT '',
  `dnaiss` date DEFAULT NULL,
  `sexe` enum('M','F') DEFAULT NULL,
  `taille` smallint(4) UNSIGNED DEFAULT NULL,
  `actif` enum('oui','non') DEFAULT NULL,
  `dconsentement` date NOT NULL DEFAULT '0000-00-00',
  `dcreat` date DEFAULT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `encnir` longtext NOT NULL,
  `enckey` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `entretienAnnuel`
--

CREATE TABLE `entretienAnnuel` (
  `id` int(11) NOT NULL,
  `difficultesRencontrees` longtext,
  `ressourcesIdentifiees` longtext,
  `formationsSuivies` longtext,
  `realisationsMarquantes` longtext,
  `perspectivesProEnvisagees` longtext,
  `besoinsASatisfaire` longtext,
  `projetAcademique` tinyint(4) DEFAULT NULL,
  `realiseAvecPrenom` varchar(45) DEFAULT NULL,
  `realiseAvecNom` varchar(45) DEFAULT NULL,
  `realiseAvecLoginAsalee` varchar(45) DEFAULT NULL,
  `infAjout` varchar(45) DEFAULT NULL,
  `cabinet` varchar(45) DEFAULT NULL,
  `dateAjout` datetime DEFAULT NULL,
  `estActif` tinyint(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `epices`
--

CREATE TABLE `epices` (
  `id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `travailleur_social` varchar(10) NOT NULL DEFAULT '',
  `complementaire` varchar(10) NOT NULL DEFAULT '',
  `couple` varchar(10) NOT NULL DEFAULT '',
  `proprietaire` varchar(10) NOT NULL DEFAULT '',
  `difficulte` varchar(10) NOT NULL DEFAULT '',
  `sport` varchar(10) NOT NULL DEFAULT '',
  `spectacle` varchar(10) NOT NULL DEFAULT '',
  `vacances` varchar(10) NOT NULL DEFAULT '',
  `famille` varchar(10) NOT NULL DEFAULT '',
  `hebergement` varchar(10) NOT NULL DEFAULT '',
  `materiel` varchar(10) NOT NULL DEFAULT '',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `equivalence_no`
--

CREATE TABLE `equivalence_no` (
  `noss` varchar(50) NOT NULL DEFAULT '',
  `id` varchar(50) NOT NULL DEFAULT '',
  `cabinet` varchar(50) NOT NULL DEFAULT '',
  `numero` varchar(50) NOT NULL DEFAULT '',
  `nom` varchar(150) NOT NULL DEFAULT '',
  `prenom` varchar(150) NOT NULL DEFAULT '',
  `caisse` varchar(20) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `evaluation_infirmier`
--

CREATE TABLE `evaluation_infirmier` (
  `id` int(15) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `degre_satisfaction` enum('a+','a','b','c','d') DEFAULT NULL,
  `duree` int(11) NOT NULL DEFAULT '0',
  `consult_domicile` tinyint(4) DEFAULT NULL,
  `consult_tel` tinyint(4) DEFAULT NULL,
  `consult_collective` tinyint(4) DEFAULT NULL,
  `points_positifs` text,
  `points_ameliorations` text,
  `type_consultation` set('','bpco','dep_diab','suivi_diab','automesure','sein','colon','uterus','cognitif','hemocult','rcva','autres','sevrage_tabac','patient_absent','surpoids') DEFAULT NULL,
  `ecg_seul` tinyint(1) DEFAULT NULL,
  `ecg` tinyint(1) DEFAULT '0',
  `monofil` tinyint(1) DEFAULT NULL,
  `exapied` tinyint(1) DEFAULT NULL,
  `hba` tinyint(1) DEFAULT NULL,
  `tension` tinyint(1) DEFAULT NULL,
  `spirometre_seul` tinyint(1) DEFAULT NULL,
  `spirometre` tinyint(1) DEFAULT NULL,
  `t_cognitif` tinyint(1) DEFAULT NULL,
  `autre` tinyint(1) DEFAULT NULL,
  `prec_autre` varchar(100) DEFAULT NULL,
  `aspects_limitant` text NOT NULL,
  `aspects_facilitant` text NOT NULL,
  `objectifs_patient` text NOT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uuid_collectif` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `evaluation_infirmier_avec_cab`
--

CREATE TABLE `evaluation_infirmier_avec_cab` (
  `id` int(15) NOT NULL DEFAULT '0',
  `cabinet` varchar(15) NOT NULL DEFAULT '',
  `numero` varchar(30) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `degre_satisfaction` enum('a+','a','b','c','d') DEFAULT NULL,
  `points_positifs` text,
  `points_ameliorations` text,
  `type_consult` set('','dep_diab','suivi_diab','automesure','sein','colon','uterus','cognitif','autre') DEFAULT NULL,
  `dmaj` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `evaluation_medecin`
--

CREATE TABLE `evaluation_medecin` (
  `id` varchar(100) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `degre_satisfaction` enum('a+','a','b','c','d') DEFAULT NULL,
  `duree_freq_consult` varchar(100) DEFAULT '',
  `satisfaction_pat` varchar(100) DEFAULT '',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `evaluation_patient`
--

CREATE TABLE `evaluation_patient` (
  `id` varchar(100) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `degre_satisfaction` enum('a+','a','b','c','d') DEFAULT NULL,
  `question_pat` varchar(100) DEFAULT '',
  `evol_recours_med` varchar(100) DEFAULT '',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `eval_continue`
--

CREATE TABLE `eval_continue` (
  `id` int(11) NOT NULL DEFAULT '0',
  `numero_eval` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `suivi` varchar(150) NOT NULL DEFAULT '',
  `causes` varchar(30) NOT NULL DEFAULT '',
  `terminologie` varchar(30) NOT NULL DEFAULT '',
  `comprendre_traitement` varchar(30) NOT NULL DEFAULT '',
  `appliquer_traitement` varchar(30) NOT NULL DEFAULT '',
  `risques` varchar(30) NOT NULL DEFAULT '',
  `gravite` varchar(30) NOT NULL DEFAULT '',
  `mesures` varchar(30) NOT NULL DEFAULT '',
  `appliquer` varchar(30) NOT NULL DEFAULT '',
  `connaitre_equilibre` varchar(30) NOT NULL DEFAULT '',
  `appliquer_equilibre` varchar(30) NOT NULL DEFAULT '',
  `activite` varchar(30) NOT NULL DEFAULT '',
  `autre` varchar(30) NOT NULL DEFAULT '',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `exam_chatillon`
--

CREATE TABLE `exam_chatillon` (
  `exam` varchar(20) NOT NULL DEFAULT '',
  `numero` varchar(20) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `valeur` float NOT NULL DEFAULT '0',
  `id` varchar(20) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `fond_oeil`
--

CREATE TABLE `fond_oeil` (
  `id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `oeil` enum('D','G') NOT NULL DEFAULT 'D',
  `fichier` text NOT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `fragilite`
--

CREATE TABLE `fragilite` (
  `id` int(11) NOT NULL,
  `dossier_id` int(11) DEFAULT NULL,
  `dossier_numero` varchar(45) DEFAULT NULL,
  `lieu_visite` varchar(45) DEFAULT NULL,
  `estSeul` tinyint(4) DEFAULT NULL,
  `animaldeCompagnie` tinyint(4) DEFAULT NULL,
  `aidantAct_medecin_spe` tinyint(4) DEFAULT NULL,
  `aidantAct_intervenant_ac_phy_ad` tinyint(4) DEFAULT NULL,
  `aidantAct_aux_vie` tinyint(4) DEFAULT NULL,
  `aidantAct_prevention_mat_inf` tinyint(4) DEFAULT NULL,
  `aidantAct_maia` tinyint(4) DEFAULT NULL,
  `aidantAct_clic` tinyint(4) DEFAULT NULL,
  `aidantAct_centre_apa` tinyint(4) DEFAULT NULL,
  `aidantAct_arespa` tinyint(4) DEFAULT NULL,
  `aidantAct_autre_aidant` tinyint(4) DEFAULT NULL,
  `aidantAct_acteur_dom_soc` tinyint(4) DEFAULT NULL,
  `aidantAct_infirmiere_lib` tinyint(4) DEFAULT NULL,
  `aidant_informel` tinyint(4) DEFAULT NULL,
  `aidant_familial` tinyint(4) DEFAULT NULL,
  `ressourcesFamSuff` tinyint(4) DEFAULT NULL,
  `ressourcesAmSuff` tinyint(4) DEFAULT NULL,
  `logementAdapte` tinyint(4) DEFAULT NULL,
  `insecFinanciere` tinyint(4) DEFAULT NULL,
  `niveauScolaire` varchar(45) DEFAULT NULL,
  `frEcrit` tinyint(4) DEFAULT NULL,
  `frParle` tinyint(4) DEFAULT NULL,
  `couvSocActive` tinyint(4) DEFAULT NULL,
  `pathChronique` int(11) DEFAULT NULL,
  `medPrescSupCinq` tinyint(4) DEFAULT NULL,
  `niveauObservance` varchar(45) DEFAULT NULL,
  `hospitalisationRecenteProg` tinyint(4) DEFAULT NULL,
  `hospitalisationRecenteNonProg` tinyint(4) DEFAULT NULL,
  `nombreTotalHospit` int(11) DEFAULT NULL,
  `dateSortieDerniereHospit` date DEFAULT NULL,
  `fragPsych` tinyint(4) DEFAULT NULL,
  `fragEco` tinyint(4) DEFAULT NULL,
  `fragSoc` tinyint(4) DEFAULT NULL,
  `fragSom` tinyint(4) DEFAULT NULL,
  `trblCogn` tinyint(4) DEFAULT NULL,
  `iadl` tinyint(4) DEFAULT NULL,
  `gds` tinyint(4) DEFAULT NULL,
  `evalGS` tinyint(4) DEFAULT NULL,
  `epices` tinyint(4) DEFAULT NULL,
  `activitePhy` tinyint(4) DEFAULT NULL,
  `perimetreMarche` tinyint(4) DEFAULT NULL,
  `vitesseMarche` tinyint(4) DEFAULT NULL,
  `vitesseMarche4m4s` tinyint(4) DEFAULT NULL,
  `arretConduite` tinyint(4) DEFAULT NULL,
  `diffVieQuot` tinyint(4) DEFAULT NULL,
  `diffIntell` tinyint(4) DEFAULT NULL,
  `protectionJud` tinyint(4) DEFAULT NULL,
  `diminutionCapSensInterne` tinyint(4) DEFAULT NULL,
  `dureedepuisdiminutionCapSensInterne` longtext NOT NULL,
  `diminutionCapSensExterne` tinyint(4) DEFAULT NULL,
  `dureedepuisdiminutionCapSensExterne` longtext NOT NULL,
  `perturbationSommeil` tinyint(4) DEFAULT NULL,
  `dureedepuisperturbationsommeil` longtext NOT NULL,
  `variationPoids` tinyint(4) DEFAULT NULL,
  `imc` varchar(45) DEFAULT NULL,
  `douleur` tinyint(4) DEFAULT NULL,
  `dureedepuisdouleur` longtext NOT NULL,
  `addictAlcool` tinyint(4) DEFAULT NULL,
  `addictTabac` tinyint(4) DEFAULT NULL,
  `addictMed` tinyint(4) DEFAULT NULL,
  `addictCanabis` tinyint(4) DEFAULT NULL,
  `autreAddiction` varchar(45) DEFAULT NULL,
  `emotionLimitante` tinyint(4) DEFAULT NULL,
  `incapExpression` tinyint(4) DEFAULT NULL,
  `isolementPhy` tinyint(4) DEFAULT NULL,
  `abandon` tinyint(4) DEFAULT NULL,
  `submerge` tinyint(4) DEFAULT NULL,
  `epuisement` tinyint(4) DEFAULT NULL,
  `maintenanceFM` tinyint(4) DEFAULT NULL,
  `resExt_medecin_spe` tinyint(4) DEFAULT NULL,
  `resExt_intervenant_ac_phy_ad` tinyint(4) DEFAULT NULL,
  `resExt_aux_vie` tinyint(4) DEFAULT NULL,
  `resExt_prevention_mat_inf` tinyint(4) DEFAULT NULL,
  `resExt_maia` tinyint(4) DEFAULT NULL,
  `resExt_clic` tinyint(4) DEFAULT NULL,
  `resExt_centre_apa` tinyint(4) DEFAULT NULL,
  `resExt_arespa` tinyint(4) DEFAULT NULL,
  `resExt_autre_aidant` tinyint(4) DEFAULT NULL,
  `resExt_acteur_dom_soc` tinyint(4) DEFAULT NULL,
  `resExt_infirmiere_lib` tinyint(4) DEFAULT NULL,
  `subjectiviteInf` longtext,
  `autresOutils` longtext NOT NULL,
  `autresStrategies` longtext NOT NULL,
  `infAjout` varchar(45) DEFAULT NULL,
  `cabinet` varchar(45) DEFAULT NULL,
  `dateAjout` datetime DEFAULT NULL,
  `estActif` tinyint(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `frais`
--

CREATE TABLE `frais` (
  `id` int(11) NOT NULL,
  `date_demande` date NOT NULL DEFAULT '1970-01-01',
  `infirmiere` varchar(255) NOT NULL DEFAULT '',
  `inf_login` varchar(50) NOT NULL,
  `date_frais` date NOT NULL DEFAULT '1970-01-01',
  `nature` varchar(255) NOT NULL DEFAULT '',
  `motif` varchar(255) NOT NULL DEFAULT '',
  `montant` varchar(255) NOT NULL DEFAULT '',
  `autre_calcul` varchar(255) NOT NULL DEFAULT '',
  `pj` varchar(255) NOT NULL DEFAULT '',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `groupes`
--

CREATE TABLE `groupes` (
  `id_groupe` int(11) NOT NULL,
  `cabinet` varchar(100) NOT NULL,
  `libelle` varchar(150) NOT NULL,
  `commentaire` text NOT NULL,
  `dossiers` text NOT NULL,
  `id_dossiers` text NOT NULL,
  `is_actif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `hemocult`
--

CREATE TABLE `hemocult` (
  `id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `date_convoc` date DEFAULT NULL,
  `date_plaquette` date DEFAULT NULL,
  `date_resultat` date DEFAULT NULL,
  `resultat` tinyint(1) DEFAULT NULL,
  `date_rappel` date DEFAULT NULL,
  `rappel` int(11) DEFAULT NULL,
  `sortir_rappel` tinyint(1) DEFAULT NULL,
  `raison_sortie` text,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `historique_account`
--

CREATE TABLE `historique_account` (
  `id` int(11) NOT NULL,
  `cabinet` varchar(150) NOT NULL,
  `actualstatus` int(11) NOT NULL DEFAULT '0',
  `dstatus` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `historique_medecin`
--

CREATE TABLE `historique_medecin` (
  `id` int(11) NOT NULL,
  `medid` int(11) NOT NULL,
  `cabinet` varchar(150) DEFAULT NULL,
  `nom` varchar(150) NOT NULL,
  `prenom` varchar(150) NOT NULL,
  `actualstatus` int(11) NOT NULL DEFAULT '0',
  `dstatus` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `histo_account`
--

CREATE TABLE `histo_account` (
  `cabinet` varchar(30) NOT NULL DEFAULT '',
  `d_modif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `total_pat` int(11) DEFAULT NULL,
  `total_sein` int(11) DEFAULT NULL,
  `total_cogni` int(11) DEFAULT NULL,
  `total_colon` int(11) DEFAULT NULL,
  `total_uterus` int(11) DEFAULT NULL,
  `total_diab2` int(11) DEFAULT NULL,
  `total_HTA` int(11) DEFAULT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `hyper_tension`
--

CREATE TABLE `hyper_tension` (
  `id` int(15) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `poids` float DEFAULT NULL,
  `dpoids` date DEFAULT NULL,
  `TaSys` varchar(15) DEFAULT NULL,
  `TaDia` varchar(15) DEFAULT NULL,
  `TA_mode` enum('manuel','automesure','automatique') DEFAULT NULL,
  `obj_tension` enum('oui','non') DEFAULT NULL,
  `dtension` date DEFAULT NULL,
  `dcoeur` date DEFAULT NULL,
  `dartere` date DEFAULT NULL,
  `dpouls` date DEFAULT NULL,
  `dsouffle` date DEFAULT NULL,
  `Creat` float DEFAULT NULL,
  `dcreat` date DEFAULT NULL,
  `proteinurie` tinyint(1) DEFAULT NULL,
  `dproteinurie` date DEFAULT NULL,
  `hematurie` tinyint(1) DEFAULT NULL,
  `dhematurie` date DEFAULT NULL,
  `glycemie` float DEFAULT NULL,
  `dglycemie` date DEFAULT NULL,
  `kaliemie` float DEFAULT NULL,
  `dkaliemie` date DEFAULT NULL,
  `dChol` date DEFAULT NULL,
  `HDL` float DEFAULT NULL,
  `dLDL` date DEFAULT NULL,
  `LDL` float DEFAULT NULL,
  `dfond` date DEFAULT NULL,
  `dECG` date DEFAULT NULL,
  `hta_instable` tinyint(1) DEFAULT NULL,
  `hta_tritherapie` tinyint(1) DEFAULT NULL,
  `hta_complique` tinyint(1) DEFAULT NULL,
  `tabac` tinyint(1) DEFAULT NULL,
  `hyperlipidemie` tinyint(1) DEFAULT NULL,
  `alcool` tinyint(1) DEFAULT NULL,
  `dconsult` date DEFAULT NULL,
  `degre_satisfaction` enum('a+','a','b','c','d') DEFAULT NULL,
  `qualite_vie` enum('oui','non') DEFAULT NULL,
  `iatrogenie` enum('oui','non') DEFAULT NULL,
  `deliv_trait` enum('oui','non') DEFAULT NULL,
  `regul_prises` enum('oui','non') DEFAULT NULL,
  `cpt_rendu` text,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

CREATE TABLE `inscription` (
  `PK_inscription` int(11) NOT NULL,
  `civilite` varchar(11) DEFAULT NULL,
  `nom` varchar(20) NOT NULL DEFAULT '',
  `prenom` varchar(20) DEFAULT NULL,
  `ville` varchar(20) DEFAULT NULL,
  `cp` varchar(5) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `categ_structure` varchar(11) DEFAULT NULL,
  `type_structure` varchar(11) DEFAULT NULL,
  `mission` text,
  `date_CRE` datetime DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `integration`
--

CREATE TABLE `integration` (
  `cabinet` varchar(30) NOT NULL,
  `logiciel` varchar(50) NOT NULL,
  `dintegration` date NOT NULL,
  `hintegration` text NOT NULL,
  `entryfile` text CHARACTER SET utf8,
  `reportfile` text CHARACTER SET ucs2,
  `cr` int(11) NOT NULL DEFAULT '0',
  `tintegration` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='compte rendu intégration';

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam`
--

CREATE TABLE `liste_exam` (
  `id` int(11) NOT NULL DEFAULT '0',
  `numero` int(11) NOT NULL,
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL,
  `resultat2` varchar(30) DEFAULT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_amberieu_u`
--

CREATE TABLE `liste_exam_amberieu_u` (
  `dossier` varchar(16) NOT NULL DEFAULT 'X',
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_Argenton_u`
--

CREATE TABLE `liste_exam_Argenton_u` (
  `dossier` varchar(16) NOT NULL DEFAULT 'X',
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_avallon_u`
--

CREATE TABLE `liste_exam_avallon_u` (
  `dossier` varchar(16) NOT NULL DEFAULT 'X',
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_Blanzac_u`
--

CREATE TABLE `liste_exam_Blanzac_u` (
  `dossier` varchar(16) NOT NULL DEFAULT 'X',
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_Bouille_u`
--

CREATE TABLE `liste_exam_Bouille_u` (
  `dossier` varchar(16) NOT NULL DEFAULT 'X',
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_brieuc1_u`
--

CREATE TABLE `liste_exam_brieuc1_u` (
  `dossier` varchar(16) NOT NULL DEFAULT 'X',
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_chalais2_u`
--

CREATE TABLE `liste_exam_chalais2_u` (
  `dossier` varchar(16) NOT NULL DEFAULT 'X',
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_chazelles_u`
--

CREATE TABLE `liste_exam_chazelles_u` (
  `dossier` varchar(16) NOT NULL DEFAULT 'X',
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_coullons_u`
--

CREATE TABLE `liste_exam_coullons_u` (
  `dossier` varchar(16) NOT NULL DEFAULT 'X',
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_marsan_u`
--

CREATE TABLE `liste_exam_marsan_u` (
  `dossier` varchar(16) NOT NULL DEFAULT 'X',
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_montbron_u`
--

CREATE TABLE `liste_exam_montbron_u` (
  `dossier` varchar(16) NOT NULL DEFAULT 'X',
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_no_asalee`
--

CREATE TABLE `liste_exam_no_asalee` (
  `cabinet` varchar(25) NOT NULL DEFAULT '',
  `numero` int(11) NOT NULL,
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL,
  `resultat2` varchar(30) DEFAULT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_Ruelle_u`
--

CREATE TABLE `liste_exam_Ruelle_u` (
  `dossier` varchar(16) NOT NULL DEFAULT 'X',
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_saulieu_u`
--

CREATE TABLE `liste_exam_saulieu_u` (
  `dossier` varchar(16) NOT NULL DEFAULT 'X',
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `liste_exam_venissieuxcroizat_u`
--

CREATE TABLE `liste_exam_venissieuxcroizat_u` (
  `dossier` varchar(16) NOT NULL DEFAULT 'X',
  `type_exam` varchar(30) NOT NULL DEFAULT '',
  `date_exam` date NOT NULL DEFAULT '0000-00-00',
  `resultat1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `maillochaud`
--

CREATE TABLE `maillochaud` (
  `numero` varchar(10) NOT NULL DEFAULT '',
  `nom` varchar(50) NOT NULL DEFAULT '',
  `prenom` varchar(50) NOT NULL DEFAULT '',
  `dnaiss` date NOT NULL DEFAULT '0000-00-00',
  `sexe` char(2) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `medecin`
--

CREATE TABLE `medecin` (
  `id` int(11) NOT NULL,
  `cabinet` varchar(150) DEFAULT NULL,
  `prenom` varchar(150) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `courriel` varchar(50) DEFAULT NULL,
  `adeli` varchar(16) DEFAULT NULL,
  `adresse` varchar(50) DEFAULT NULL,
  `codepostal` varchar(5) DEFAULT NULL,
  `ville` varchar(30) DEFAULT NULL,
  `departement` varchar(30) DEFAULT NULL,
  `region` varchar(30) DEFAULT NULL,
  `rpps` varchar(16) DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `portable` varchar(15) DEFAULT NULL,
  `recordstatus` int(11) NOT NULL DEFAULT '0',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `medecins_generalistes`
--

CREATE TABLE `medecins_generalistes` (
  `id` int(11) NOT NULL,
  `cabinet` varchar(30) DEFAULT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `courriel` varchar(50) DEFAULT NULL,
  `adeli` varchar(16) DEFAULT NULL,
  `adresse` varchar(50) DEFAULT NULL,
  `codepostal` varchar(5) DEFAULT NULL,
  `ville` varchar(30) DEFAULT NULL,
  `departement` varchar(30) DEFAULT NULL,
  `region` varchar(30) DEFAULT NULL,
  `rpps` varchar(16) DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `portable` varchar(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `medecin_ruelle`
--

CREATE TABLE `medecin_ruelle` (
  `numero` varchar(10) NOT NULL DEFAULT '',
  `nom` varchar(50) NOT NULL DEFAULT '',
  `prenom` varchar(50) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `no_chatillon`
--

CREATE TABLE `no_chatillon` (
  `ancien` varchar(10) NOT NULL DEFAULT '',
  `nouveau` varchar(10) NOT NULL DEFAULT '',
  `dnaiss` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `p6`
--

CREATE TABLE `p6` (
  `id` int(11) NOT NULL,
  `actif` text NOT NULL,
  `cabinet` text NOT NULL,
  `deval` date NOT NULL,
  `dnaiss` date NOT NULL,
  `sexe` text NOT NULL,
  `consult` text NOT NULL,
  `diabete` int(11) NOT NULL DEFAULT '0',
  `rcva` int(11) NOT NULL DEFAULT '0',
  `cognitif` int(11) NOT NULL DEFAULT '0',
  `bpco` int(11) NOT NULL DEFAULT '0',
  `cancer` int(11) NOT NULL DEFAULT '0',
  `autre` int(11) NOT NULL DEFAULT '0',
  `depistage` int(11) NOT NULL DEFAULT '0',
  `suivi` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `p630062016`
--

CREATE TABLE `p630062016` (
  `id` int(11) NOT NULL,
  `actif` text NOT NULL,
  `cabinet` text NOT NULL,
  `deval` date NOT NULL,
  `dnaiss` date NOT NULL,
  `sexe` text NOT NULL,
  `consult` text NOT NULL,
  `diabete` int(11) NOT NULL DEFAULT '0',
  `rcva` int(11) NOT NULL DEFAULT '0',
  `cognitif` int(11) NOT NULL DEFAULT '0',
  `bpco` int(11) NOT NULL DEFAULT '0',
  `cancer` int(11) NOT NULL DEFAULT '0',
  `autre` int(11) NOT NULL DEFAULT '0',
  `depistage` int(11) NOT NULL DEFAULT '0',
  `suivi` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `patients`
--

CREATE TABLE `patients` (
  `nom_dossier` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `intitule` varchar(255) DEFAULT NULL,
  `date_naissance` varchar(255) DEFAULT NULL,
  `rue` varchar(255) DEFAULT NULL,
  `adr2` varchar(255) DEFAULT NULL,
  `code_postal` varchar(255) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `profession` varchar(255) DEFAULT NULL,
  `antecedents` varchar(255) DEFAULT NULL,
  `pense_bete` varchar(255) DEFAULT NULL,
  `nom_patient` varchar(255) DEFAULT NULL,
  `poids` varchar(255) DEFAULT NULL,
  `id_praticien` varchar(255) DEFAULT NULL,
  `divers` varchar(255) DEFAULT NULL,
  `hospitalise` varchar(255) DEFAULT NULL,
  `sexe` varchar(255) DEFAULT NULL,
  `num_secu` varchar(255) DEFAULT NULL,
  `mutuelle` varchar(255) DEFAULT NULL,
  `dem_alerte` varchar(255) DEFAULT NULL,
  `nom_jeune_fille` varchar(255) DEFAULT NULL,
  `lieu_naissance` varchar(255) DEFAULT NULL,
  `revoir_le` varchar(255) DEFAULT NULL,
  `taille` varchar(255) DEFAULT NULL,
  `convoc_auto` varchar(255) DEFAULT NULL,
  `initiales_convo` varchar(255) DEFAULT NULL,
  `num_dossier` varchar(255) DEFAULT NULL,
  `telephone_bur` varchar(255) DEFAULT NULL,
  `type_dern_recet` varchar(255) DEFAULT NULL,
  `privee` varchar(255) DEFAULT NULL,
  `derniere_regle` varchar(255) DEFAULT NULL,
  `debut_grossesse` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `derniererecette_e` varchar(255) DEFAULT NULL,
  `code_suivi_bilan` varchar(255) DEFAULT NULL,
  `banque` varchar(255) DEFAULT NULL,
  `x_id_palm` varchar(255) DEFAULT NULL,
  `adresse_habituelle` varchar(255) DEFAULT NULL,
  `mere_nom` varchar(255) DEFAULT NULL,
  `mere_profession` varchar(255) DEFAULT NULL,
  `mere_adresse_1` varchar(255) DEFAULT NULL,
  `mere_adresse_2` varchar(255) DEFAULT NULL,
  `mere_cp` varchar(255) DEFAULT NULL,
  `mere_ville` varchar(255) DEFAULT NULL,
  `mere_tel_dom` varchar(255) DEFAULT NULL,
  `mere_tel_prof` varchar(255) DEFAULT NULL,
  `pere_nom` varchar(255) DEFAULT NULL,
  `pere_profession` varchar(255) DEFAULT NULL,
  `pere_adresse_1` varchar(255) DEFAULT NULL,
  `pere_adresse_2` varchar(255) DEFAULT NULL,
  `pere_cp` varchar(255) DEFAULT NULL,
  `pere_ville` varchar(255) DEFAULT NULL,
  `pere_tel_dom` varchar(255) DEFAULT NULL,
  `pere_tel_prof` varchar(255) DEFAULT NULL,
  `fratrie` varchar(255) DEFAULT NULL,
  `nombre_enfants` varchar(255) DEFAULT NULL,
  `jumeaux_triplet` varchar(255) DEFAULT NULL,
  `date_modifcation` varchar(255) DEFAULT NULL,
  `telephone_3` varchar(255) DEFAULT NULL,
  `alerte_agenda` varchar(255) DEFAULT NULL,
  `medecin_traitant` varchar(255) DEFAULT NULL,
  `id_unique` varchar(255) DEFAULT NULL,
  `time_stamp` varchar(255) DEFAULT NULL,
  `motcle_medoc_txt` varchar(255) DEFAULT NULL,
  `antecedent_wr` varchar(255) DEFAULT NULL,
  `id_clinique` varchar(255) DEFAULT NULL,
  `id_dossier_clinique` varchar(255) DEFAULT NULL,
  `naissance_approximative` varchar(255) DEFAULT NULL,
  `allaitement` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `pat_mediclic`
--

CREATE TABLE `pat_mediclic` (
  `numero` varchar(20) NOT NULL DEFAULT '',
  `cabinet` varchar(50) NOT NULL DEFAULT '',
  `nom` varchar(50) NOT NULL DEFAULT '',
  `prenom` varchar(50) NOT NULL DEFAULT '',
  `ville` varchar(50) NOT NULL DEFAULT '',
  `dnaiss` varchar(20) NOT NULL DEFAULT '',
  `tel` varchar(20) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_auth_access`
--

CREATE TABLE `phpbb_auth_access` (
  `group_id` mediumint(8) NOT NULL DEFAULT '0',
  `forum_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `auth_view` tinyint(1) NOT NULL DEFAULT '0',
  `auth_read` tinyint(1) NOT NULL DEFAULT '0',
  `auth_post` tinyint(1) NOT NULL DEFAULT '0',
  `auth_reply` tinyint(1) NOT NULL DEFAULT '0',
  `auth_edit` tinyint(1) NOT NULL DEFAULT '0',
  `auth_delete` tinyint(1) NOT NULL DEFAULT '0',
  `auth_sticky` tinyint(1) NOT NULL DEFAULT '0',
  `auth_announce` tinyint(1) NOT NULL DEFAULT '0',
  `auth_vote` tinyint(1) NOT NULL DEFAULT '0',
  `auth_pollcreate` tinyint(1) NOT NULL DEFAULT '0',
  `auth_attachments` tinyint(1) NOT NULL DEFAULT '0',
  `auth_mod` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_banlist`
--

CREATE TABLE `phpbb_banlist` (
  `ban_id` mediumint(8) UNSIGNED NOT NULL,
  `ban_userid` mediumint(8) NOT NULL DEFAULT '0',
  `ban_ip` varchar(8) NOT NULL DEFAULT '',
  `ban_email` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_categories`
--

CREATE TABLE `phpbb_categories` (
  `cat_id` mediumint(8) UNSIGNED NOT NULL,
  `cat_title` varchar(100) DEFAULT NULL,
  `cat_order` mediumint(8) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_config`
--

CREATE TABLE `phpbb_config` (
  `config_name` varchar(255) NOT NULL DEFAULT '',
  `config_value` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_confirm`
--

CREATE TABLE `phpbb_confirm` (
  `confirm_id` char(32) NOT NULL DEFAULT '',
  `session_id` char(32) NOT NULL DEFAULT '',
  `code` char(6) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_disallow`
--

CREATE TABLE `phpbb_disallow` (
  `disallow_id` mediumint(8) UNSIGNED NOT NULL,
  `disallow_username` varchar(25) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_forums`
--

CREATE TABLE `phpbb_forums` (
  `forum_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `cat_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `forum_name` varchar(150) DEFAULT NULL,
  `forum_desc` text,
  `forum_status` tinyint(4) NOT NULL DEFAULT '0',
  `forum_order` mediumint(8) UNSIGNED NOT NULL DEFAULT '1',
  `forum_posts` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `forum_topics` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `forum_last_post_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `prune_next` int(11) DEFAULT NULL,
  `prune_enable` tinyint(1) NOT NULL DEFAULT '0',
  `auth_view` tinyint(2) NOT NULL DEFAULT '0',
  `auth_read` tinyint(2) NOT NULL DEFAULT '0',
  `auth_post` tinyint(2) NOT NULL DEFAULT '0',
  `auth_reply` tinyint(2) NOT NULL DEFAULT '0',
  `auth_edit` tinyint(2) NOT NULL DEFAULT '0',
  `auth_delete` tinyint(2) NOT NULL DEFAULT '0',
  `auth_sticky` tinyint(2) NOT NULL DEFAULT '0',
  `auth_announce` tinyint(2) NOT NULL DEFAULT '0',
  `auth_vote` tinyint(2) NOT NULL DEFAULT '0',
  `auth_pollcreate` tinyint(2) NOT NULL DEFAULT '0',
  `auth_attachments` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_forum_prune`
--

CREATE TABLE `phpbb_forum_prune` (
  `prune_id` mediumint(8) UNSIGNED NOT NULL,
  `forum_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `prune_days` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `prune_freq` smallint(5) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_groups`
--

CREATE TABLE `phpbb_groups` (
  `group_id` mediumint(8) NOT NULL,
  `group_type` tinyint(4) NOT NULL DEFAULT '1',
  `group_name` varchar(40) NOT NULL DEFAULT '',
  `group_description` varchar(255) NOT NULL DEFAULT '',
  `group_moderator` mediumint(8) NOT NULL DEFAULT '0',
  `group_single_user` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_posts`
--

CREATE TABLE `phpbb_posts` (
  `post_id` mediumint(8) UNSIGNED NOT NULL,
  `topic_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `forum_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `poster_id` mediumint(8) NOT NULL DEFAULT '0',
  `post_time` int(11) NOT NULL DEFAULT '0',
  `poster_ip` varchar(8) NOT NULL DEFAULT '',
  `post_username` varchar(25) DEFAULT NULL,
  `enable_bbcode` tinyint(1) NOT NULL DEFAULT '1',
  `enable_html` tinyint(1) NOT NULL DEFAULT '0',
  `enable_smilies` tinyint(1) NOT NULL DEFAULT '1',
  `enable_sig` tinyint(1) NOT NULL DEFAULT '1',
  `post_edit_time` int(11) DEFAULT NULL,
  `post_edit_count` smallint(5) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_posts_text`
--

CREATE TABLE `phpbb_posts_text` (
  `post_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `bbcode_uid` varchar(10) NOT NULL DEFAULT '',
  `post_subject` varchar(60) DEFAULT NULL,
  `post_text` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_privmsgs`
--

CREATE TABLE `phpbb_privmsgs` (
  `privmsgs_id` mediumint(8) UNSIGNED NOT NULL,
  `privmsgs_type` tinyint(4) NOT NULL DEFAULT '0',
  `privmsgs_subject` varchar(255) NOT NULL DEFAULT '0',
  `privmsgs_from_userid` mediumint(8) NOT NULL DEFAULT '0',
  `privmsgs_to_userid` mediumint(8) NOT NULL DEFAULT '0',
  `privmsgs_date` int(11) NOT NULL DEFAULT '0',
  `privmsgs_ip` varchar(8) NOT NULL DEFAULT '',
  `privmsgs_enable_bbcode` tinyint(1) NOT NULL DEFAULT '1',
  `privmsgs_enable_html` tinyint(1) NOT NULL DEFAULT '0',
  `privmsgs_enable_smilies` tinyint(1) NOT NULL DEFAULT '1',
  `privmsgs_attach_sig` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_privmsgs_text`
--

CREATE TABLE `phpbb_privmsgs_text` (
  `privmsgs_text_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `privmsgs_bbcode_uid` varchar(10) NOT NULL DEFAULT '0',
  `privmsgs_text` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_ranks`
--

CREATE TABLE `phpbb_ranks` (
  `rank_id` smallint(5) UNSIGNED NOT NULL,
  `rank_title` varchar(50) NOT NULL DEFAULT '',
  `rank_min` mediumint(8) NOT NULL DEFAULT '0',
  `rank_special` tinyint(1) DEFAULT '0',
  `rank_image` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_search_results`
--

CREATE TABLE `phpbb_search_results` (
  `search_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `session_id` varchar(32) NOT NULL DEFAULT '',
  `search_time` int(11) NOT NULL DEFAULT '0',
  `search_array` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_search_wordlist`
--

CREATE TABLE `phpbb_search_wordlist` (
  `word_text` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `word_id` mediumint(8) UNSIGNED NOT NULL,
  `word_common` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_search_wordmatch`
--

CREATE TABLE `phpbb_search_wordmatch` (
  `post_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `word_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `title_match` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_sessions`
--

CREATE TABLE `phpbb_sessions` (
  `session_id` char(32) NOT NULL DEFAULT '',
  `session_user_id` mediumint(8) NOT NULL DEFAULT '0',
  `session_start` int(11) NOT NULL DEFAULT '0',
  `session_time` int(11) NOT NULL DEFAULT '0',
  `session_ip` char(8) NOT NULL DEFAULT '0',
  `session_page` int(11) NOT NULL DEFAULT '0',
  `session_logged_in` tinyint(1) NOT NULL DEFAULT '0',
  `session_admin` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_sessions_keys`
--

CREATE TABLE `phpbb_sessions_keys` (
  `key_id` varchar(32) NOT NULL DEFAULT '0',
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `last_ip` varchar(8) NOT NULL DEFAULT '0',
  `last_login` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_smilies`
--

CREATE TABLE `phpbb_smilies` (
  `smilies_id` smallint(5) UNSIGNED NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `smile_url` varchar(100) DEFAULT NULL,
  `emoticon` varchar(75) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_themes`
--

CREATE TABLE `phpbb_themes` (
  `themes_id` mediumint(8) UNSIGNED NOT NULL,
  `template_name` varchar(30) NOT NULL DEFAULT '',
  `style_name` varchar(30) NOT NULL DEFAULT '',
  `head_stylesheet` varchar(100) DEFAULT NULL,
  `body_background` varchar(100) DEFAULT NULL,
  `body_bgcolor` varchar(6) DEFAULT NULL,
  `body_text` varchar(6) DEFAULT NULL,
  `body_link` varchar(6) DEFAULT NULL,
  `body_vlink` varchar(6) DEFAULT NULL,
  `body_alink` varchar(6) DEFAULT NULL,
  `body_hlink` varchar(6) DEFAULT NULL,
  `tr_color1` varchar(6) DEFAULT NULL,
  `tr_color2` varchar(6) DEFAULT NULL,
  `tr_color3` varchar(6) DEFAULT NULL,
  `tr_class1` varchar(25) DEFAULT NULL,
  `tr_class2` varchar(25) DEFAULT NULL,
  `tr_class3` varchar(25) DEFAULT NULL,
  `th_color1` varchar(6) DEFAULT NULL,
  `th_color2` varchar(6) DEFAULT NULL,
  `th_color3` varchar(6) DEFAULT NULL,
  `th_class1` varchar(25) DEFAULT NULL,
  `th_class2` varchar(25) DEFAULT NULL,
  `th_class3` varchar(25) DEFAULT NULL,
  `td_color1` varchar(6) DEFAULT NULL,
  `td_color2` varchar(6) DEFAULT NULL,
  `td_color3` varchar(6) DEFAULT NULL,
  `td_class1` varchar(25) DEFAULT NULL,
  `td_class2` varchar(25) DEFAULT NULL,
  `td_class3` varchar(25) DEFAULT NULL,
  `fontface1` varchar(50) DEFAULT NULL,
  `fontface2` varchar(50) DEFAULT NULL,
  `fontface3` varchar(50) DEFAULT NULL,
  `fontsize1` tinyint(4) DEFAULT NULL,
  `fontsize2` tinyint(4) DEFAULT NULL,
  `fontsize3` tinyint(4) DEFAULT NULL,
  `fontcolor1` varchar(6) DEFAULT NULL,
  `fontcolor2` varchar(6) DEFAULT NULL,
  `fontcolor3` varchar(6) DEFAULT NULL,
  `span_class1` varchar(25) DEFAULT NULL,
  `span_class2` varchar(25) DEFAULT NULL,
  `span_class3` varchar(25) DEFAULT NULL,
  `img_size_poll` smallint(5) UNSIGNED DEFAULT NULL,
  `img_size_privmsg` smallint(5) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_themes_name`
--

CREATE TABLE `phpbb_themes_name` (
  `themes_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `tr_color1_name` char(50) DEFAULT NULL,
  `tr_color2_name` char(50) DEFAULT NULL,
  `tr_color3_name` char(50) DEFAULT NULL,
  `tr_class1_name` char(50) DEFAULT NULL,
  `tr_class2_name` char(50) DEFAULT NULL,
  `tr_class3_name` char(50) DEFAULT NULL,
  `th_color1_name` char(50) DEFAULT NULL,
  `th_color2_name` char(50) DEFAULT NULL,
  `th_color3_name` char(50) DEFAULT NULL,
  `th_class1_name` char(50) DEFAULT NULL,
  `th_class2_name` char(50) DEFAULT NULL,
  `th_class3_name` char(50) DEFAULT NULL,
  `td_color1_name` char(50) DEFAULT NULL,
  `td_color2_name` char(50) DEFAULT NULL,
  `td_color3_name` char(50) DEFAULT NULL,
  `td_class1_name` char(50) DEFAULT NULL,
  `td_class2_name` char(50) DEFAULT NULL,
  `td_class3_name` char(50) DEFAULT NULL,
  `fontface1_name` char(50) DEFAULT NULL,
  `fontface2_name` char(50) DEFAULT NULL,
  `fontface3_name` char(50) DEFAULT NULL,
  `fontsize1_name` char(50) DEFAULT NULL,
  `fontsize2_name` char(50) DEFAULT NULL,
  `fontsize3_name` char(50) DEFAULT NULL,
  `fontcolor1_name` char(50) DEFAULT NULL,
  `fontcolor2_name` char(50) DEFAULT NULL,
  `fontcolor3_name` char(50) DEFAULT NULL,
  `span_class1_name` char(50) DEFAULT NULL,
  `span_class2_name` char(50) DEFAULT NULL,
  `span_class3_name` char(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_topics`
--

CREATE TABLE `phpbb_topics` (
  `topic_id` mediumint(8) UNSIGNED NOT NULL,
  `forum_id` smallint(8) UNSIGNED NOT NULL DEFAULT '0',
  `topic_title` char(60) NOT NULL DEFAULT '',
  `topic_poster` mediumint(8) NOT NULL DEFAULT '0',
  `topic_time` int(11) NOT NULL DEFAULT '0',
  `topic_views` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `topic_replies` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `topic_status` tinyint(3) NOT NULL DEFAULT '0',
  `topic_vote` tinyint(1) NOT NULL DEFAULT '0',
  `topic_type` tinyint(3) NOT NULL DEFAULT '0',
  `topic_first_post_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `topic_last_post_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `topic_moved_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_topics_watch`
--

CREATE TABLE `phpbb_topics_watch` (
  `topic_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `notify_status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_users`
--

CREATE TABLE `phpbb_users` (
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `user_active` tinyint(1) DEFAULT '1',
  `username` varchar(25) NOT NULL DEFAULT '',
  `user_password` varchar(32) NOT NULL DEFAULT '',
  `user_session_time` int(11) NOT NULL DEFAULT '0',
  `user_session_page` smallint(5) NOT NULL DEFAULT '0',
  `user_lastvisit` int(11) NOT NULL DEFAULT '0',
  `user_regdate` int(11) NOT NULL DEFAULT '0',
  `user_level` tinyint(4) DEFAULT '0',
  `user_posts` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `user_timezone` decimal(5,2) NOT NULL DEFAULT '0.00',
  `user_style` tinyint(4) DEFAULT NULL,
  `user_lang` varchar(255) DEFAULT NULL,
  `user_dateformat` varchar(14) NOT NULL DEFAULT 'd M Y H:i',
  `user_new_privmsg` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `user_unread_privmsg` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `user_last_privmsg` int(11) NOT NULL DEFAULT '0',
  `user_login_tries` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `user_last_login_try` int(11) NOT NULL DEFAULT '0',
  `user_emailtime` int(11) DEFAULT NULL,
  `user_viewemail` tinyint(1) DEFAULT NULL,
  `user_attachsig` tinyint(1) DEFAULT NULL,
  `user_allowhtml` tinyint(1) DEFAULT '1',
  `user_allowbbcode` tinyint(1) DEFAULT '1',
  `user_allowsmile` tinyint(1) DEFAULT '1',
  `user_allowavatar` tinyint(1) NOT NULL DEFAULT '1',
  `user_allow_pm` tinyint(1) NOT NULL DEFAULT '1',
  `user_allow_viewonline` tinyint(1) NOT NULL DEFAULT '1',
  `user_notify` tinyint(1) NOT NULL DEFAULT '1',
  `user_notify_pm` tinyint(1) NOT NULL DEFAULT '0',
  `user_popup_pm` tinyint(1) NOT NULL DEFAULT '0',
  `user_rank` int(11) DEFAULT '0',
  `user_avatar` varchar(100) DEFAULT NULL,
  `user_avatar_type` tinyint(4) NOT NULL DEFAULT '0',
  `user_email` varchar(255) DEFAULT NULL,
  `user_icq` varchar(15) DEFAULT NULL,
  `user_website` varchar(100) DEFAULT NULL,
  `user_from` varchar(100) DEFAULT NULL,
  `user_sig` text,
  `user_sig_bbcode_uid` varchar(10) DEFAULT NULL,
  `user_aim` varchar(255) DEFAULT NULL,
  `user_yim` varchar(255) DEFAULT NULL,
  `user_msnm` varchar(255) DEFAULT NULL,
  `user_occ` varchar(100) DEFAULT NULL,
  `user_interests` varchar(255) DEFAULT NULL,
  `user_actkey` varchar(32) DEFAULT NULL,
  `user_newpasswd` varchar(32) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_user_group`
--

CREATE TABLE `phpbb_user_group` (
  `group_id` mediumint(8) NOT NULL DEFAULT '0',
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `user_pending` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_vote_desc`
--

CREATE TABLE `phpbb_vote_desc` (
  `vote_id` mediumint(8) UNSIGNED NOT NULL,
  `topic_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `vote_text` text NOT NULL,
  `vote_start` int(11) NOT NULL DEFAULT '0',
  `vote_length` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_vote_results`
--

CREATE TABLE `phpbb_vote_results` (
  `vote_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `vote_option_id` tinyint(4) UNSIGNED NOT NULL DEFAULT '0',
  `vote_option_text` varchar(255) NOT NULL DEFAULT '',
  `vote_result` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_vote_voters`
--

CREATE TABLE `phpbb_vote_voters` (
  `vote_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `vote_user_id` mediumint(8) NOT NULL DEFAULT '0',
  `vote_user_ip` char(8) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_words`
--

CREATE TABLE `phpbb_words` (
  `word_id` mediumint(8) UNSIGNED NOT NULL,
  `word` char(100) NOT NULL DEFAULT '',
  `replacement` char(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `planning`
--

CREATE TABLE `planning` (
  `infirmiere` varchar(50) NOT NULL DEFAULT '',
  `lundi` text NOT NULL,
  `mardi` text NOT NULL,
  `mercredi` text NOT NULL,
  `jeudi` text NOT NULL,
  `vendredi` text NOT NULL,
  `samedi` text NOT NULL,
  `debutconge1` date NOT NULL DEFAULT '0000-00-00',
  `finconge1` date NOT NULL DEFAULT '0000-00-00',
  `debutconge2` date NOT NULL DEFAULT '0000-00-00',
  `finconge2` date NOT NULL DEFAULT '0000-00-00',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Planning des infirmieres';

-- --------------------------------------------------------

--
-- Structure de la table `planning_infirmieres`
--

CREATE TABLE `planning_infirmieres` (
  `infirmiere` varchar(50) NOT NULL DEFAULT '',
  `cabinet` varchar(30) NOT NULL,
  `lundi` text NOT NULL,
  `mardi` text NOT NULL,
  `mercredi` text NOT NULL,
  `jeudi` text NOT NULL,
  `vendredi` text NOT NULL,
  `samedi` text NOT NULL,
  `cabinet_dpt` int(2) NOT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `professionel`
--

CREATE TABLE `professionel` (
  `PK_professionnel` int(11) NOT NULL,
  `FK_proprietaire` int(11) NOT NULL DEFAULT '0',
  `civilite` varchar(11) NOT NULL DEFAULT '',
  `nom` varchar(20) NOT NULL DEFAULT '',
  `prenom` varchar(20) NOT NULL DEFAULT '',
  `profession` varchar(11) NOT NULL DEFAULT '',
  `specialite` varchar(11) DEFAULT NULL,
  `capacite` varchar(100) DEFAULT NULL,
  `visible` smallint(1) NOT NULL DEFAULT '0',
  `date_MAJ` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `proprietaire`
--

CREATE TABLE `proprietaire` (
  `PK_proprietaire` int(11) NOT NULL,
  `FK_utilisateur` int(11) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `date_MAJ` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `questionnaire_medecin`
--

CREATE TABLE `questionnaire_medecin` (
  `medecin` varchar(20) NOT NULL DEFAULT '',
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `discipline` varchar(100) DEFAULT NULL,
  `adresse_pro` varchar(255) DEFAULT NULL,
  `tel` varchar(14) DEFAULT NULL,
  `fax` varchar(14) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `implic_initiation` tinyint(1) DEFAULT NULL,
  `commentaire_implic_initiation` text,
  `implic_conception` tinyint(1) DEFAULT NULL,
  `commentaire_implic_conception` text,
  `implic_recueil` tinyint(1) DEFAULT NULL,
  `commentaire_implic_recueil` text,
  `implic_analyse` tinyint(1) DEFAULT NULL,
  `commentaire_implic_analyse` text,
  `implic_mise_oeuvre` tinyint(1) DEFAULT NULL,
  `commentaire_implic_mise_oeuvre` text,
  `implic_suivi` tinyint(1) DEFAULT NULL,
  `commentaire_implic_suivi` text,
  `amelioration_pratique` text,
  `note_pratique` enum('null','faible','moyenne','bonne','tb') DEFAULT NULL,
  `organisation_soins` text,
  `note_soin` enum('null','faible','moyenne','bonne','tb') DEFAULT NULL,
  `utilite_patient` text,
  `note_patient` enum('null','faible','moyenne','bonne','tb') DEFAULT NULL,
  `demarche_faisable` text,
  `autres_actions` text,
  `satisfaction` text,
  `difficultes` text,
  `ameliorations` text,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `remuneration_mg_jours_forfaitaires`
--

CREATE TABLE `remuneration_mg_jours_forfaitaires` (
  `id_mg` int(11) NOT NULL,
  `cabinet` varchar(100) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `nbre_jours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `remuneration_mg_jours_forfaitaires_20180123`
--

CREATE TABLE `remuneration_mg_jours_forfaitaires_20180123` (
  `id_mg` int(11) NOT NULL,
  `cabinet` varchar(100) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `nbre_jours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `satisf_patient`
--

CREATE TABLE `satisf_patient` (
  `no_questionnaire` int(11) NOT NULL,
  `demande_consult` enum('medecin','patient') DEFAULT NULL,
  `motif_diabete` tinyint(1) DEFAULT NULL,
  `motif_depistage` tinyint(1) DEFAULT NULL,
  `motif_automesure` tinyint(1) DEFAULT NULL,
  `motif_autre` tinyint(1) DEFAULT NULL,
  `conseils_alimentaires` enum('1','2','3','4','5','6') DEFAULT NULL,
  `adapter_vie_sante` enum('1','2','3','4','5','6') DEFAULT NULL,
  `conseils_realisables` enum('1','2','3','4','5','6') DEFAULT NULL,
  `compris_conseils` enum('1','2','3','4','5','6') DEFAULT NULL,
  `qualite_conseils` enum('1','2','3','4','5','6') DEFAULT NULL,
  `repondu_questions` enum('1','2','3','4','5') DEFAULT NULL,
  `informations_ignorees` enum('1','2','3','4','5') DEFAULT NULL,
  `temps_ecoute` enum('1','2','3','4','5') DEFAULT NULL,
  `aise` enum('1','2','3','4','5') DEFAULT NULL,
  `satisf_consult` enum('1','2','3','4','5') DEFAULT NULL,
  `suivi_conseils` enum('1','2','3','4','5') DEFAULT NULL,
  `concerne_sante` enum('1','2','3','4') DEFAULT NULL,
  `revoir_inf` enum('1','2','3','4','5') DEFAULT NULL,
  `commentaire` text,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `satisf_patient2007`
--

CREATE TABLE `satisf_patient2007` (
  `no_questionnaire` int(11) NOT NULL,
  `demande_consult` enum('medecin','patient') DEFAULT NULL,
  `motif_diabete` tinyint(1) DEFAULT NULL,
  `motif_depistage` tinyint(1) DEFAULT NULL,
  `motif_automesure` tinyint(1) DEFAULT NULL,
  `motif_autre` tinyint(1) DEFAULT NULL,
  `conseils_alimentaires` enum('1','2','3','4','5','6') DEFAULT NULL,
  `adapter_vie_sante` enum('1','2','3','4','5','6') DEFAULT NULL,
  `conseils_realisables` enum('1','2','3','4','5','6') DEFAULT NULL,
  `compris_conseils` enum('1','2','3','4','5','6') DEFAULT NULL,
  `qualite_conseils` enum('1','2','3','4','5','6') DEFAULT NULL,
  `repondu_questions` enum('1','2','3','4','5') DEFAULT NULL,
  `informations_ignorees` enum('1','2','3','4','5') DEFAULT NULL,
  `temps_ecoute` enum('1','2','3','4','5') DEFAULT NULL,
  `aise` enum('1','2','3','4','5') DEFAULT NULL,
  `satisf_consult` enum('1','2','3','4','5') DEFAULT NULL,
  `suivi_conseils` enum('1','2','3','4','5') DEFAULT NULL,
  `concerne_sante` enum('1','2','3','4') DEFAULT NULL,
  `revoir_inf` enum('1','2','3','4','5') DEFAULT NULL,
  `commentaire` text,
  `dmaj` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `satisf_patient2009`
--

CREATE TABLE `satisf_patient2009` (
  `no_questionnaire` int(11) NOT NULL,
  `demande_consult` enum('medecin','patient') DEFAULT NULL,
  `motif_diabete` tinyint(1) DEFAULT NULL,
  `motif_depistage` tinyint(1) DEFAULT NULL,
  `motif_automesure` tinyint(1) DEFAULT NULL,
  `motif_autre` tinyint(1) DEFAULT NULL,
  `motif_rcva` tinyint(1) DEFAULT NULL,
  `motif_hemoccult` tinyint(1) DEFAULT NULL,
  `conseils_alimentaires` enum('1','2','3','4','5','6') DEFAULT NULL,
  `adapter_vie_sante` enum('1','2','3','4','5','6') DEFAULT NULL,
  `conseils_realisables` enum('1','2','3','4','5','6') DEFAULT NULL,
  `compris_conseils` enum('1','2','3','4','5','6') DEFAULT NULL,
  `qualite_conseils` enum('1','2','3','4','5','6') DEFAULT NULL,
  `repondu_questions` enum('1','2','3','4','5') DEFAULT NULL,
  `informations_ignorees` enum('1','2','3','4','5') DEFAULT NULL,
  `temps_ecoute` enum('1','2','3','4','5') DEFAULT NULL,
  `aise` enum('1','2','3','4','5') DEFAULT NULL,
  `satisf_consult` enum('1','2','3','4','5') DEFAULT NULL,
  `suivi_conseils` enum('1','2','3','4','5') DEFAULT NULL,
  `concerne_sante` enum('1','2','3','4') DEFAULT NULL,
  `revoir_inf` enum('1','2','3','4','5') DEFAULT NULL,
  `commentaire` text,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE `session` (
  `userid` int(11) NOT NULL DEFAULT '0',
  `signature` varchar(11) NOT NULL DEFAULT '',
  `date_maj` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `sevrage_tabac`
--

CREATE TABLE `sevrage_tabac` (
  `id` int(11) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `tabac` varchar(5) NOT NULL,
  `nbrtabac` int(11) NOT NULL,
  `type_tabac` varchar(20) NOT NULL,
  `ddebut` varchar(4) NOT NULL,
  `darret_old` varchar(255) NOT NULL,
  `darret` date NOT NULL,
  `spirometrie_date` date NOT NULL,
  `spirometrie_CVF` int(11) NOT NULL,
  `resultat1` decimal(10,2) NOT NULL,
  `spirometrie_VEMS` varchar(20) NOT NULL,
  `spirometrie_DEP` varchar(20) NOT NULL,
  `spirometrie_status` varchar(20) NOT NULL,
  `spirometrie_rapport_VEMS_CVF` varchar(20) NOT NULL,
  `dco_test` date NOT NULL,
  `co_ppm` smallint(6) NOT NULL,
  `fagerstrom` smallint(6) NOT NULL,
  `horn_stimulation` smallint(6) DEFAULT NULL,
  `horn_plaisir` smallint(6) DEFAULT NULL,
  `horn_relaxation` smallint(6) DEFAULT NULL,
  `horn_anxiete` smallint(6) DEFAULT NULL,
  `horn_besoin` smallint(6) DEFAULT NULL,
  `horn_habitude` smallint(6) DEFAULT NULL,
  `had_anxiete` smallint(6) DEFAULT NULL,
  `had_depression` smallint(6) DEFAULT NULL,
  `echelle_analogique` smallint(6) DEFAULT NULL,
  `echelle_confiance` smallint(6) DEFAULT NULL,
  `stade_motivationnel` varchar(10) NOT NULL,
  `poids` smallint(6) NOT NULL,
  `dpoids` date NOT NULL,
  `activite` decimal(10,2) NOT NULL,
  `alcool` varchar(3) NOT NULL,
  `aspects_limitants` text NOT NULL,
  `aspects_facilitants` text NOT NULL,
  `objectifs_patient` text NOT NULL,
  `dmaj` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `sevrage_tabac_old`
--

CREATE TABLE `sevrage_tabac_old` (
  `id` int(11) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `tabac` varchar(5) NOT NULL,
  `nbrtabac` int(11) NOT NULL,
  `type_tabac` varchar(20) NOT NULL,
  `ddebut` varchar(4) NOT NULL,
  `darret_old` varchar(4) NOT NULL,
  `darret` date NOT NULL,
  `spirometrie_date` date NOT NULL,
  `spirometrie_CVF` int(11) NOT NULL,
  `resultat1` decimal(10,2) NOT NULL,
  `spirometrie_VEMS` varchar(20) NOT NULL,
  `spirometrie_DEP` varchar(20) NOT NULL,
  `spirometrie_status` varchar(20) NOT NULL,
  `spirometrie_rapport_VEMS_CVF` varchar(20) NOT NULL,
  `dco_test` date NOT NULL,
  `co_ppm` smallint(6) NOT NULL,
  `fagerstrom` smallint(6) NOT NULL,
  `horn_stimulation` smallint(6) DEFAULT NULL,
  `horn_plaisir` smallint(6) DEFAULT NULL,
  `horn_relaxation` smallint(6) DEFAULT NULL,
  `horn_anxiete` smallint(6) DEFAULT NULL,
  `horn_besoin` smallint(6) DEFAULT NULL,
  `horn_habitude` smallint(6) DEFAULT NULL,
  `had_anxiete` smallint(6) DEFAULT NULL,
  `had_depression` smallint(6) DEFAULT NULL,
  `echelle_analogique` smallint(6) DEFAULT NULL,
  `echelle_confiance` smallint(6) DEFAULT NULL,
  `stade_motivationnel` varchar(10) NOT NULL,
  `poids` smallint(6) NOT NULL,
  `dpoids` date NOT NULL,
  `activite` decimal(10,2) NOT NULL,
  `alcool` varchar(3) NOT NULL,
  `aspects_limitants` text NOT NULL,
  `aspects_facilitants` text NOT NULL,
  `objectifs_patient` text NOT NULL,
  `dmaj` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `sms`
--

CREATE TABLE `sms` (
  `id` int(11) NOT NULL,
  `sms_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sms_to` text NOT NULL,
  `sms_from` text NOT NULL,
  `sms_text` varchar(160) NOT NULL,
  `sms_status` int(11) NOT NULL,
  `recordstatus` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `structure`
--

CREATE TABLE `structure` (
  `PK_structure` int(11) NOT NULL,
  `FK_proprietaire` int(11) NOT NULL DEFAULT '0',
  `niveau` tinyint(1) NOT NULL DEFAULT '0',
  `nom` varchar(100) NOT NULL DEFAULT '',
  `autre_nom` varchar(60) DEFAULT NULL,
  `adresse` varchar(60) DEFAULT NULL,
  `cp` varchar(5) DEFAULT NULL,
  `ville` varchar(20) DEFAULT NULL,
  `mission` text,
  `type_population` varchar(250) DEFAULT NULL,
  `recrutement` varchar(60) DEFAULT NULL,
  `categ_structure` varchar(11) DEFAULT NULL,
  `type_structure` varchar(11) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `siteweb` varchar(50) DEFAULT NULL,
  `acces` text,
  `type_prise_charge` varchar(11) DEFAULT NULL,
  `prise_charge` text,
  `equipe` varchar(60) DEFAULT NULL,
  `status` varchar(11) DEFAULT NULL,
  `domaine` varchar(60) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `them_id` varchar(250) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '0',
  `date_maj` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `structure_relation`
--

CREATE TABLE `structure_relation` (
  `PK_struct_struct` int(11) NOT NULL,
  `FK_racine` int(11) NOT NULL DEFAULT '0',
  `FK_structure` int(11) NOT NULL DEFAULT '0',
  `FK_relation` int(11) NOT NULL DEFAULT '0',
  `type_relation` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `suivi_diabete`
--

CREATE TABLE `suivi_diabete` (
  `dossier_id` int(15) NOT NULL DEFAULT '0',
  `dsuivi` date NOT NULL DEFAULT '0000-00-00',
  `dHBA` date DEFAULT NULL,
  `ResHBA` float DEFAULT NULL,
  `dExaFil` date DEFAULT NULL,
  `ExaFil` enum('oui','non','nsp') DEFAULT NULL,
  `dExaPieds` date DEFAULT NULL,
  `ExaPieds` enum('oui','non','nsp') DEFAULT NULL,
  `dChol` date DEFAULT NULL,
  `iChol` tinyint(1) DEFAULT NULL,
  `HDL` float DEFAULT NULL,
  `HDLc` float DEFAULT NULL,
  `dLDL` date DEFAULT NULL,
  `iLDL` tinyint(1) DEFAULT NULL,
  `LDLc` float DEFAULT NULL,
  `LDL` float DEFAULT NULL,
  `dCreat` date DEFAULT NULL,
  `Creat` float DEFAULT NULL,
  `CreatC` float DEFAULT NULL,
  `iCreat` tinyint(1) DEFAULT NULL,
  `dAlbu` date DEFAULT NULL,
  `iAlbu` tinyint(1) DEFAULT NULL,
  `Albu` float DEFAULT NULL,
  `AlbuC` float DEFAULT NULL,
  `dFond` date DEFAULT NULL,
  `iFond` tinyint(1) DEFAULT NULL,
  `dECG` date DEFAULT NULL,
  `iECG` tinyint(1) DEFAULT NULL,
  `triglycerides` tinyint(4) DEFAULT NULL,
  `dTriglycerides` date DEFAULT NULL,
  `kaliemie` tinyint(4) DEFAULT NULL,
  `dKaliemie` date DEFAULT NULL,
  `dentiste` date DEFAULT NULL,
  `suivi_type` set('4','s','a') DEFAULT NULL,
  `poids` float DEFAULT '0',
  `dPoids` date DEFAULT NULL,
  `Regime` tinyint(1) DEFAULT NULL,
  `InsulReq` tinyint(1) DEFAULT NULL,
  `ADO` set('aucun','Pioglitazone','Pioglitazone chlorhydrate','Metformine','Gliclazide','Glipizide','Miglitol','Repaglinide','Carbutamide','Acarbose','Glimepiride','Rosiglitazone','Rosiglitazone maleate','Glibenclamide','Sitagliptine','Byetta','Exénatide','Benfluorex','Vildagliptine','Liraglutide','Saxagliptine','MetformineVildagliptine','MetformineSitagliptine','Exénatide_injection') DEFAULT 'aucun',
  `TaSys` varchar(15) DEFAULT NULL,
  `TaDia` varchar(15) DEFAULT NULL,
  `TA_mode` enum('manuel','automatique','automesure') DEFAULT NULL,
  `dtension` date DEFAULT NULL,
  `risques` tinyint(1) DEFAULT NULL,
  `nbrtabac` varchar(5) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `hta` tinyint(1) DEFAULT NULL,
  `arte` tinyint(1) DEFAULT NULL,
  `neph` tinyint(1) DEFAULT NULL,
  `coro` tinyint(1) DEFAULT NULL,
  `reti` tinyint(1) DEFAULT NULL,
  `neur` tinyint(1) DEFAULT NULL,
  `equilib` tinyint(1) DEFAULT NULL,
  `tension` tinyint(1) DEFAULT NULL,
  `lipide` tinyint(1) DEFAULT NULL,
  `mesure_ADO` tinyint(1) DEFAULT NULL,
  `insuline` tinyint(1) DEFAULT NULL,
  `mesure_hta` tinyint(1) DEFAULT NULL,
  `hypl` tinyint(1) DEFAULT NULL,
  `phys` tinyint(1) DEFAULT NULL,
  `diet` tinyint(1) DEFAULT NULL,
  `taba` tinyint(1) DEFAULT NULL,
  `etp` tinyint(1) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `diab10ans` tinyint(1) DEFAULT NULL,
  `sortie` tinyint(1) DEFAULT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Suivis périodiques diabète systématique';

-- --------------------------------------------------------

--
-- Structure de la table `suivi_hebdomadaire`
--

CREATE TABLE `suivi_hebdomadaire` (
  `cabinet` varchar(15) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `travail_base_h` float DEFAULT NULL,
  `consult_indiv_h` float DEFAULT NULL,
  `consult_indiv_n` int(11) DEFAULT NULL,
  `prevention_diabete_h` float DEFAULT NULL,
  `prevention_autre_h` float DEFAULT NULL,
  `prevention_autre_note` varchar(100) DEFAULT NULL,
  `seance_diabete_h` float DEFAULT NULL,
  `seance_autre_h` float DEFAULT NULL,
  `seance_autre_note` varchar(100) DEFAULT NULL,
  `suivi_armoire_h` float DEFAULT NULL,
  `suivi_armoire_n` int(11) DEFAULT NULL,
  `aide_telephone` float DEFAULT NULL,
  `aide_prep_matos` float DEFAULT NULL,
  `aide_examen_compl` float DEFAULT NULL,
  `aide_soins` float DEFAULT NULL,
  `aide_formation` float DEFAULT NULL,
  `aide_autre` float DEFAULT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `suivi_hebdomadaire2007`
--

CREATE TABLE `suivi_hebdomadaire2007` (
  `cabinet` varchar(15) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `info_asalee` float DEFAULT NULL,
  `info_dossiermed` float DEFAULT NULL,
  `nb_consult_suividiab` int(11) DEFAULT NULL,
  `tps_consult_suividiab` float DEFAULT NULL,
  `nb_consult_depdiab` int(11) DEFAULT NULL,
  `tps_consult_depdiab` float DEFAULT NULL,
  `nb_consult_depcancer` int(11) DEFAULT NULL,
  `tps_consult_depcancer` float DEFAULT NULL,
  `nb_consult_memoire` int(11) DEFAULT NULL,
  `tps_consult_memoire` float DEFAULT NULL,
  `nb_consult_autota` int(11) DEFAULT NULL,
  `tps_consult_autota` float DEFAULT NULL,
  `nb_consult_hta` int(11) DEFAULT NULL,
  `tps_consult_hta` float DEFAULT NULL,
  `nb_consult_autre` int(11) DEFAULT NULL,
  `tps_consult_autre` float DEFAULT NULL,
  `ecg` float DEFAULT NULL,
  `autoformation` float DEFAULT NULL,
  `formation` float DEFAULT NULL,
  `stagiaires` float DEFAULT NULL,
  `reunion` float DEFAULT NULL,
  `telephone` float DEFAULT NULL,
  `autre` float DEFAULT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `suivi_hebdo_temps_passe`
--

CREATE TABLE `suivi_hebdo_temps_passe` (
  `cabinet` varchar(255) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `info_asalee` float DEFAULT NULL,
  `info_dossiermed` float DEFAULT NULL,
  `nb_contact_tel_patient` int(11) DEFAULT '0',
  `tps_contact_tel_patient` float DEFAULT '0',
  `autoformation` float DEFAULT NULL,
  `formation` float DEFAULT NULL,
  `stagiaires` float DEFAULT NULL,
  `nb_reunion_medecin` int(11) DEFAULT NULL,
  `tps_reunion_medecin` float DEFAULT NULL,
  `nb_reunion_infirmiere` int(11) DEFAULT NULL,
  `tps_reunion_infirmiere` float DEFAULT NULL,
  `tps_passe_cabinet` float DEFAULT NULL,
  `non_atribue` float DEFAULT NULL,
  `precision_contribution_dev_asalee` text,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `suivi_hebdo_temps_passe_infirmiere`
--

CREATE TABLE `suivi_hebdo_temps_passe_infirmiere` (
  `semaine` date NOT NULL,
  `cabinet` varchar(30) NOT NULL,
  `infirmiere` varchar(30) NOT NULL,
  `duree` float(10,1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `suivi_reunion_medecin`
--

CREATE TABLE `suivi_reunion_medecin` (
  `id_reu` int(11) NOT NULL,
  `cabinet` varchar(150) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `date_reunion` date DEFAULT NULL,
  `duree` float NOT NULL DEFAULT '0',
  `medecin` varchar(250) NOT NULL DEFAULT '',
  `infirmiere` text,
  `motif` text NOT NULL,
  `id_mg` varchar(200) NOT NULL,
  `id_inf` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `support`
--

CREATE TABLE `support` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `infirmiere` varchar(50) DEFAULT NULL,
  `cabinet` varchar(50) DEFAULT NULL,
  `titre` varchar(255) DEFAULT NULL,
  `corps` text,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `temp_dashboard`
--

CREATE TABLE `temp_dashboard` (
  `id` int(11) NOT NULL,
  `cabinet` varchar(255) NOT NULL,
  `is_ok` tinyint(1) NOT NULL DEFAULT '0',
  `is_actif` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tension_arterielle`
--

CREATE TABLE `tension_arterielle` (
  `id` varchar(100) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `momment_journee` enum('matin','soir') NOT NULL DEFAULT 'matin',
  `indice` tinyint(4) NOT NULL DEFAULT '0',
  `systole` smallint(6) NOT NULL DEFAULT '0',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `diastole` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tension_arterielle_moyenne`
--

CREATE TABLE `tension_arterielle_moyenne` (
  `id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `date_debut` date NOT NULL DEFAULT '0000-00-00',
  `nombre_jours` int(11) NOT NULL DEFAULT '0',
  `moyenne_sys_matin` smallint(6) NOT NULL DEFAULT '0',
  `moyenne_sys_soir` smallint(6) NOT NULL DEFAULT '0',
  `moyenne_sys` smallint(6) NOT NULL DEFAULT '0',
  `moyenne_dia_matin` smallint(6) NOT NULL DEFAULT '0',
  `moyenne_dia_soir` smallint(6) NOT NULL DEFAULT '0',
  `moyenne_dia` smallint(6) NOT NULL DEFAULT '0',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `thematique_relation`
--

CREATE TABLE `thematique_relation` (
  `PK_them_relation` int(11) NOT NULL,
  `FK_activite` int(11) DEFAULT '0',
  `FK_structure` int(11) DEFAULT '0',
  `them_id` varchar(10) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `trouble_cognitif`
--

CREATE TABLE `trouble_cognitif` (
  `id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `suivi_type` set('mmse','gds','iadl','horl','dubois') DEFAULT NULL,
  `date_rappel` date DEFAULT NULL,
  `dep_type` enum('coll','indiv') DEFAULT NULL,
  `raison_dep` text,
  `sortir_rappel` tinyint(1) DEFAULT NULL,
  `raison_sortie` text,
  `mmse_annee` tinyint(1) DEFAULT NULL,
  `mmse_saison` tinyint(1) DEFAULT NULL,
  `mmse_mois` tinyint(1) DEFAULT NULL,
  `mmse_jour_mois` tinyint(1) DEFAULT NULL,
  `mmse_jour_semaine` tinyint(1) DEFAULT NULL,
  `mmse_nom_hop` tinyint(1) DEFAULT NULL,
  `mmse_nom_ville` tinyint(1) DEFAULT NULL,
  `mmse_nom_dep` tinyint(1) DEFAULT NULL,
  `mmse_region` tinyint(1) DEFAULT NULL,
  `mmse_etage` tinyint(1) DEFAULT NULL,
  `mmse_cigare1` tinyint(1) DEFAULT NULL,
  `mmse_fleur1` tinyint(1) DEFAULT NULL,
  `mmse_porte1` tinyint(1) DEFAULT NULL,
  `mmse_93` tinyint(1) DEFAULT NULL,
  `mmse_86` tinyint(1) DEFAULT NULL,
  `mmse_79` tinyint(1) DEFAULT NULL,
  `mmse_72` tinyint(1) DEFAULT NULL,
  `mmse_65` tinyint(1) DEFAULT NULL,
  `mmse_monde` varchar(10) DEFAULT NULL,
  `mmse_cigare2` tinyint(1) DEFAULT NULL,
  `mmse_fleur2` tinyint(1) DEFAULT NULL,
  `mmse_porte2` tinyint(1) DEFAULT NULL,
  `mmse_crayon` tinyint(1) DEFAULT NULL,
  `mmse_montre` tinyint(1) DEFAULT NULL,
  `mmse_repete_phrase` tinyint(1) DEFAULT NULL,
  `mmse_feuille_prise` tinyint(1) DEFAULT NULL,
  `mmse_feuille_pliee` tinyint(1) DEFAULT NULL,
  `mmse_feuille_jetee` tinyint(1) DEFAULT NULL,
  `mmse_fermer_yeux` tinyint(1) DEFAULT NULL,
  `mmse_ecrit_phrase` tinyint(1) DEFAULT NULL,
  `mmse_copie_dessin` tinyint(1) DEFAULT NULL,
  `gds_satisf` char(3) DEFAULT NULL,
  `gds_renonce_act` char(3) DEFAULT NULL,
  `gds_vie_vide` char(3) DEFAULT NULL,
  `gds_ennui` char(3) DEFAULT NULL,
  `gds_avenir_opt` char(3) DEFAULT NULL,
  `gds_cata` char(3) DEFAULT NULL,
  `gds_bonne_humeur` char(3) DEFAULT NULL,
  `gds_besoin_aide` char(3) DEFAULT NULL,
  `gds_prefere_seul` char(3) DEFAULT NULL,
  `gds_mauvaise_mem` char(3) DEFAULT NULL,
  `gds_heureux_vivre` char(3) DEFAULT NULL,
  `gds_bon_rien` char(3) DEFAULT NULL,
  `gds_energie` char(3) DEFAULT NULL,
  `gds_desespere_sit` char(3) DEFAULT NULL,
  `gds_sit_autres_best` char(3) DEFAULT NULL,
  `iadl_telephone` varchar(15) DEFAULT NULL,
  `iadl_transport` varchar(15) DEFAULT NULL,
  `iadl_med` varchar(15) DEFAULT NULL,
  `iadl_budget` varchar(15) DEFAULT NULL,
  `horloge` int(11) DEFAULT NULL,
  `dubois_immediatsi` int(11) DEFAULT NULL,
  `dubois_immediatai` int(11) DEFAULT NULL,
  `dubois_diffsi` int(11) DEFAULT NULL,
  `dubois_diffai` int(11) DEFAULT NULL,
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Troubles Cognitifs';

-- --------------------------------------------------------

--
-- Structure de la table `type_groupe`
--

CREATE TABLE `type_groupe` (
  `PK_type_groupe` int(11) NOT NULL,
  `groupe_id` varchar(4) NOT NULL DEFAULT '',
  `groupe_lib` varchar(50) NOT NULL DEFAULT '',
  `list_id` varchar(6) NOT NULL DEFAULT '',
  `list_lib` varchar(80) NOT NULL DEFAULT '',
  `groupe_link` varchar(4) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `date_MAJ` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `PK_utilisateur` int(11) NOT NULL,
  `login` varchar(20) NOT NULL DEFAULT '',
  `pwd` varchar(10) NOT NULL DEFAULT '',
  `adresse` varchar(50) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `date_MAJ` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ville`
--

CREATE TABLE `ville` (
  `PK_ville` int(11) NOT NULL,
  `ville_id` int(11) NOT NULL DEFAULT '0',
  `nom` varchar(20) DEFAULT NULL,
  `cp` varchar(5) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `_old_remuneration_mg_jours_forfaitaires`
--

CREATE TABLE `_old_remuneration_mg_jours_forfaitaires` (
  `id_mg` int(11) NOT NULL,
  `cabinet` varchar(100) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `nbre_jours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la vue `carte_grise_grille_taux_actuelle`
--
DROP TABLE IF EXISTS `carte_grise_grille_taux_actuelle`;
-- utilisé(#1046 - Aucune base n'a été sélectionnée)

-- --------------------------------------------------------

--
-- Structure de la vue `demande_carte_grise_vue_detaillee`
--
DROP TABLE IF EXISTS `demande_carte_grise_vue_detaillee`;

CREATE ALGORITHM=UNDEFINED DEFINER=`informed`@`localhost` SQL SECURITY DEFINER VIEW `demande_carte_grise_vue_detaillee`  AS  select `demande_carte_grise_identification`.`id` AS `id`,`demande_carte_grise_suivi`.`id` AS `identifiant_suivi`,`demande_carte_grise_identification`.`id` AS `identifiant_demande`,cast(`demande_carte_grise_identification`.`dcreat` as date) AS `date_demande`,`demande_carte_grise_identification`.`intitule` AS `titre`,`demande_carte_grise_historique`.`id_demandeur` AS `id_demandeur`,`demande_carte_grise_historique`.`login_demandeur` AS `login_demandeur`,(select concat(`annuaire`.`identifications`.`prenom`,' ',`annuaire`.`identifications`.`nom`) from `annuaire`.`identifications` where (`annuaire`.`identifications`.`login` = `demande_carte_grise_historique`.`login_demandeur`)) AS `nom_demandeur`,`demande_carte_grise_historique`.`date_obtention` AS `date_obtention`,`demande_carte_grise_historique`.`puissance` AS `puissance`,`demande_carte_grise_historique`.`precisions` AS `precisions`,`demande_carte_grise_historique`.`justificatif` AS `justificatif`,`demande_carte_grise_suivi`.`id_utilisateur` AS `dernier_intervenant_id`,`demande_carte_grise_suivi`.`login_utilisateur` AS `dernierIntervenant`,(select concat(`annuaire`.`identifications`.`prenom`,' ',`annuaire`.`identifications`.`nom`) from `annuaire`.`identifications` where (`annuaire`.`identifications`.`login` = `demande_carte_grise_suivi`.`login_utilisateur`)) AS `nom_intervenant`,`demande_carte_grise_status`.`intitule` AS `dernierStatus`,`demande_carte_grise_status`.`id` AS `id_status`,cast(`demande_carte_grise_suivi`.`dcreat` as date) AS `date_dernierStatut`,`demande_carte_grise_suivi`.`notes` AS `notes` from (((`demande_carte_grise_identification` join `demande_carte_grise_suivi` on((`demande_carte_grise_suivi`.`id_demande_carte_grise` = `demande_carte_grise_identification`.`id`))) join `demande_carte_grise_historique` on((`demande_carte_grise_historique`.`id` = `demande_carte_grise_suivi`.`id_historique`))) join `demande_carte_grise_status` on((`demande_carte_grise_status`.`id` = `demande_carte_grise_suivi`.`id_status`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `demande_carte_grise_vue_resumee`
--
DROP TABLE IF EXISTS `demande_carte_grise_vue_resumee`;

CREATE ALGORITHM=UNDEFINED DEFINER=`informed`@`localhost` SQL SECURITY DEFINER VIEW `demande_carte_grise_vue_resumee`  AS  select `demande_carte_grise_identification`.`id` AS `id`,`demande_carte_grise_suivi`.`id` AS `identifiant_suivi`,`demande_carte_grise_identification`.`id` AS `identifiant_demande`,cast(`demande_carte_grise_identification`.`dcreat` as date) AS `date_demande`,`demande_carte_grise_identification`.`intitule` AS `titre`,`demande_carte_grise_historique`.`id_demandeur` AS `id_demandeur`,`demande_carte_grise_historique`.`login_demandeur` AS `login_demandeur`,(select concat(`annuaire`.`identifications`.`prenom`,' ',`annuaire`.`identifications`.`nom`) from `annuaire`.`identifications` where (`annuaire`.`identifications`.`login` = `demande_carte_grise_historique`.`login_demandeur`)) AS `nom_demandeur`,`demande_carte_grise_historique`.`date_obtention` AS `date_obtention`,`demande_carte_grise_historique`.`puissance` AS `puissance`,`demande_carte_grise_historique`.`precisions` AS `precisions`,`demande_carte_grise_historique`.`justificatif` AS `justificatif`,`demande_carte_grise_suivi`.`id_utilisateur` AS `dernier_intervenant_id`,`demande_carte_grise_suivi`.`login_utilisateur` AS `dernierIntervenant`,(select concat(`annuaire`.`identifications`.`prenom`,' ',`annuaire`.`identifications`.`nom`) from `annuaire`.`identifications` where (`annuaire`.`identifications`.`login` = `demande_carte_grise_suivi`.`login_utilisateur`)) AS `nom_intervenant`,`demande_carte_grise_status`.`intitule` AS `dernierStatus`,`demande_carte_grise_status`.`id` AS `id_status`,cast(`demande_carte_grise_suivi`.`dcreat` as date) AS `date_dernierStatut`,`demande_carte_grise_suivi`.`notes` AS `notes` from ((((((select `demande_carte_grise_suivi`.`id_demande_carte_grise` AS `id_demande_carte_grise`,max(`demande_carte_grise_suivi`.`id`) AS `id_suivi` from `demande_carte_grise_suivi` group by `demande_carte_grise_suivi`.`id_demande_carte_grise`)) `demande_carte_grise_dernier_suivi` join `demande_carte_grise_suivi` on(((`demande_carte_grise_suivi`.`id_demande_carte_grise` = `demande_carte_grise_dernier_suivi`.`id_demande_carte_grise`) and (`demande_carte_grise_suivi`.`id` = `demande_carte_grise_dernier_suivi`.`id_suivi`)))) join `demande_carte_grise_identification` on((`demande_carte_grise_identification`.`id` = `demande_carte_grise_suivi`.`id_demande_carte_grise`))) join `demande_carte_grise_historique` on((`demande_carte_grise_historique`.`id` = `demande_carte_grise_suivi`.`id_historique`))) join `demande_carte_grise_status` on((`demande_carte_grise_status`.`id` = `demande_carte_grise_suivi`.`id_status`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `demande_frais_vue_detaillee`
--
DROP TABLE IF EXISTS `demande_frais_vue_detaillee`;

CREATE ALGORITHM=UNDEFINED DEFINER=`informed`@`localhost` SQL SECURITY DEFINER VIEW `demande_frais_vue_detaillee`  AS  select `demande_frais_identification`.`id` AS `id`,`demande_frais_suivi`.`id` AS `identifiant_suivi`,`demande_frais_identification`.`id` AS `identifiant_demande`,cast(`demande_frais_identification`.`dcreat` as date) AS `date_demande`,`demande_frais_identification`.`intitule` AS `titre`,`demande_frais_historique`.`id_demandeur` AS `id_demandeur`,`demande_frais_historique`.`login_demandeur` AS `login_demandeur`,(select concat(`annuaire`.`identifications`.`prenom`,' ',`annuaire`.`identifications`.`nom`) from `annuaire`.`identifications` where (`annuaire`.`identifications`.`login` = `demande_frais_historique`.`login_demandeur`)) AS `nom_demandeur`,`demande_frais_historique`.`date_frais` AS `date_frais`,`demande_frais_historique`.`nature` AS `nature`,`demande_frais_historique`.`motif` AS `motif`,`demande_frais_historique`.`distance` AS `distance`,`demande_frais_historique`.`taux_applique` AS `taux_applique`,`demande_frais_historique`.`montant` AS `montant`,`demande_frais_historique`.`justificatif` AS `justificatif`,`demande_frais_suivi`.`id_utilisateur` AS `dernier_intervenant_id`,`demande_frais_suivi`.`login_utilisateur` AS `dernierIntervenant`,(select concat(`annuaire`.`identifications`.`prenom`,' ',`annuaire`.`identifications`.`nom`) from `annuaire`.`identifications` where (`annuaire`.`identifications`.`login` = `demande_frais_suivi`.`login_utilisateur`)) AS `nom_intervenant`,`demande_frais_status`.`intitule` AS `dernierStatus`,`demande_frais_status`.`id` AS `id_status`,cast(`demande_frais_suivi`.`dcreat` as date) AS `date_dernierStatut`,`demande_frais_suivi`.`notes` AS `notes` from (((`demande_frais_identification` join `demande_frais_suivi` on((`demande_frais_suivi`.`id_frais` = `demande_frais_identification`.`id`))) join `demande_frais_historique` on((`demande_frais_historique`.`id` = `demande_frais_suivi`.`id_historique`))) join `demande_frais_status` on((`demande_frais_status`.`id` = `demande_frais_suivi`.`id_status`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `demande_frais_vue_resumee`
--
DROP TABLE IF EXISTS `demande_frais_vue_resumee`;

CREATE ALGORITHM=UNDEFINED DEFINER=`informed`@`localhost` SQL SECURITY DEFINER VIEW `demande_frais_vue_resumee`  AS  select `demande_frais_identification`.`id` AS `id`,`demande_frais_suivi`.`id` AS `identifiant_suivi`,cast(`demande_frais_identification`.`dcreat` as date) AS `date_demande`,`demande_frais_identification`.`intitule` AS `titre`,`demande_frais_historique`.`id_demandeur` AS `id_demandeur`,`demande_frais_historique`.`login_demandeur` AS `login_demandeur`,(select concat(`annuaire`.`identifications`.`prenom`,' ',`annuaire`.`identifications`.`nom`) from `annuaire`.`identifications` where (`annuaire`.`identifications`.`login` = `demande_frais_historique`.`login_demandeur`)) AS `nom_demandeur`,`demande_frais_historique`.`date_frais` AS `date_frais`,`demande_frais_historique`.`nature` AS `nature`,`demande_frais_historique`.`motif` AS `motif`,`demande_frais_historique`.`distance` AS `distance`,`demande_frais_historique`.`taux_applique` AS `taux_applique`,`demande_frais_historique`.`montant` AS `montant`,`demande_frais_historique`.`justificatif` AS `justificatif`,`demande_frais_suivi`.`id_utilisateur` AS `dernier_intervenant_id`,`demande_frais_suivi`.`login_utilisateur` AS `dernierIntervenant`,(select concat(`annuaire`.`identifications`.`prenom`,' ',`annuaire`.`identifications`.`nom`) from `annuaire`.`identifications` where (`annuaire`.`identifications`.`login` = `demande_frais_suivi`.`login_utilisateur`)) AS `nom_intervenant`,`demande_frais_status`.`intitule` AS `dernierStatus`,`demande_frais_status`.`id` AS `id_status`,cast(`demande_frais_suivi`.`dcreat` as date) AS `date_dernierStatut`,`demande_frais_suivi`.`notes` AS `notes` from ((((((select `demande_frais_suivi`.`id_frais` AS `id_frais`,max(`demande_frais_suivi`.`id`) AS `id_suivi` from `demande_frais_suivi` group by `demande_frais_suivi`.`id_frais`)) `demande_frais_dernier_suivi` join `demande_frais_suivi` on(((`demande_frais_suivi`.`id_frais` = `demande_frais_dernier_suivi`.`id_frais`) and (`demande_frais_suivi`.`id` = `demande_frais_dernier_suivi`.`id_suivi`)))) join `demande_frais_identification` on((`demande_frais_identification`.`id` = `demande_frais_suivi`.`id_frais`))) join `demande_frais_historique` on((`demande_frais_historique`.`id` = `demande_frais_suivi`.`id_historique`))) join `demande_frais_status` on((`demande_frais_status`.`id` = `demande_frais_suivi`.`id_status`))) ;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`cabinet`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `account_psaet`
--
ALTER TABLE `account_psaet`
  ADD PRIMARY KEY (`user`);

--
-- Index pour la table `account_psam`
--
ALTER TABLE `account_psam`
  ADD PRIMARY KEY (`medecin`);

--
-- Index pour la table `activite`
--
ALTER TABLE `activite`
  ADD PRIMARY KEY (`PK_activite`),
  ADD UNIQUE KEY `PK_activite` (`PK_activite`);

--
-- Index pour la table `amberieu`
--
ALTER TABLE `amberieu`
  ADD PRIMARY KEY (`numero`);

--
-- Index pour la table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_u` (`type`,`redacteur`,`dcreat`,`sujet`);

--
-- Index pour la table `cardio_autre_consult`
--
ALTER TABLE `cardio_autre_consult`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `cardio_diag_educ`
--
ALTER TABLE `cardio_diag_educ`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `cardio_premiere_consult`
--
ALTER TABLE `cardio_premiere_consult`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `cardio_vasculaire_depart`
--
ALTER TABLE `cardio_vasculaire_depart`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `competences_infirmier`
--
ALTER TABLE `competences_infirmier`
  ADD UNIQUE KEY `infirmier` (`login`);

--
-- Index pour la table `competences_infirmier_v1`
--
ALTER TABLE `competences_infirmier_v1`
  ADD UNIQUE KEY `infirmier` (`login`);

--
-- Index pour la table `conges`
--
ALTER TABLE `conges`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`PK_contact`);

--
-- Index pour la table `courriers_bilans`
--
ALTER TABLE `courriers_bilans`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `dashboard_results`
--
ALTER TABLE `dashboard_results`
  ADD UNIQUE KEY `cabinet` (`cabinet`,`periode`);

--
-- Index pour la table `dashboard_results_sauv`
--
ALTER TABLE `dashboard_results_sauv`
  ADD UNIQUE KEY `cabinet` (`cabinet`,`periode`);

--
-- Index pour la table `demande_carte_grise_grille`
--
ALTER TABLE `demande_carte_grise_grille`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demande_carte_grise_historique`
--
ALTER TABLE `demande_carte_grise_historique`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demande_carte_grise_identification`
--
ALTER TABLE `demande_carte_grise_identification`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demande_carte_grise_status`
--
ALTER TABLE `demande_carte_grise_status`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demande_carte_grise_suivi`
--
ALTER TABLE `demande_carte_grise_suivi`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demande_frais_historique`
--
ALTER TABLE `demande_frais_historique`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demande_frais_identification`
--
ALTER TABLE `demande_frais_identification`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demande_frais_status`
--
ALTER TABLE `demande_frais_status`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demande_frais_suivi`
--
ALTER TABLE `demande_frais_suivi`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `depistage_aomi`
--
ALTER TABLE `depistage_aomi`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `depistage_colon`
--
ALTER TABLE `depistage_colon`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `depistage_colon_old`
--
ALTER TABLE `depistage_colon_old`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `depistage_diabete`
--
ALTER TABLE `depistage_diabete`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `depistage_sein`
--
ALTER TABLE `depistage_sein`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `depistage_uterus`
--
ALTER TABLE `depistage_uterus`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `diagnostic_educatif`
--
ALTER TABLE `diagnostic_educatif`
  ADD UNIQUE KEY `id_dossier` (`id_dossier`,`type`,`statut`);

--
-- Index pour la table `donnees_cardio`
--
ALTER TABLE `donnees_cardio`
  ADD PRIMARY KEY (`cabinet`);

--
-- Index pour la table `dossier`
--
ALTER TABLE `dossier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_key` (`numero`,`cabinet`);

--
-- Index pour la table `dossier_kc`
--
ALTER TABLE `dossier_kc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_key` (`numero`,`cabinet`);

--
-- Index pour la table `entretienAnnuel`
--
ALTER TABLE `entretienAnnuel`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `epices`
--
ALTER TABLE `epices`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `equivalence_no`
--
ALTER TABLE `equivalence_no`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `evaluation_infirmier`
--
ALTER TABLE `evaluation_infirmier`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `evaluation_infirmier_avec_cab`
--
ALTER TABLE `evaluation_infirmier_avec_cab`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `evaluation_medecin`
--
ALTER TABLE `evaluation_medecin`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `evaluation_patient`
--
ALTER TABLE `evaluation_patient`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `eval_continue`
--
ALTER TABLE `eval_continue`
  ADD PRIMARY KEY (`id`,`numero_eval`);

--
-- Index pour la table `exam_chatillon`
--
ALTER TABLE `exam_chatillon`
  ADD KEY `exam` (`exam`),
  ADD KEY `numero` (`numero`),
  ADD KEY `date_exam` (`date_exam`),
  ADD KEY `valeur` (`valeur`),
  ADD KEY `id` (`id`);

--
-- Index pour la table `fond_oeil`
--
ALTER TABLE `fond_oeil`
  ADD PRIMARY KEY (`id`,`date`,`oeil`);

--
-- Index pour la table `fragilite`
--
ALTER TABLE `fragilite`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `frais`
--
ALTER TABLE `frais`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `groupes`
--
ALTER TABLE `groupes`
  ADD PRIMARY KEY (`id_groupe`);

--
-- Index pour la table `hemocult`
--
ALTER TABLE `hemocult`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `historique_account`
--
ALTER TABLE `historique_account`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `historique_medecin`
--
ALTER TABLE `historique_medecin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `histo_account`
--
ALTER TABLE `histo_account`
  ADD PRIMARY KEY (`cabinet`,`d_modif`);

--
-- Index pour la table `hyper_tension`
--
ALTER TABLE `hyper_tension`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD PRIMARY KEY (`PK_inscription`);

--
-- Index pour la table `integration`
--
ALTER TABLE `integration`
  ADD UNIQUE KEY `indexintegration` (`cabinet`,`dintegration`);

--
-- Index pour la table `liste_exam`
--
ALTER TABLE `liste_exam`
  ADD PRIMARY KEY (`id`,`numero`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_amberieu_u`
--
ALTER TABLE `liste_exam_amberieu_u`
  ADD PRIMARY KEY (`dossier`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_Argenton_u`
--
ALTER TABLE `liste_exam_Argenton_u`
  ADD PRIMARY KEY (`dossier`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_avallon_u`
--
ALTER TABLE `liste_exam_avallon_u`
  ADD PRIMARY KEY (`dossier`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_Blanzac_u`
--
ALTER TABLE `liste_exam_Blanzac_u`
  ADD PRIMARY KEY (`dossier`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_Bouille_u`
--
ALTER TABLE `liste_exam_Bouille_u`
  ADD PRIMARY KEY (`dossier`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_brieuc1_u`
--
ALTER TABLE `liste_exam_brieuc1_u`
  ADD PRIMARY KEY (`dossier`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_chalais2_u`
--
ALTER TABLE `liste_exam_chalais2_u`
  ADD PRIMARY KEY (`dossier`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_chazelles_u`
--
ALTER TABLE `liste_exam_chazelles_u`
  ADD PRIMARY KEY (`dossier`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_coullons_u`
--
ALTER TABLE `liste_exam_coullons_u`
  ADD PRIMARY KEY (`dossier`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_marsan_u`
--
ALTER TABLE `liste_exam_marsan_u`
  ADD PRIMARY KEY (`dossier`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_montbron_u`
--
ALTER TABLE `liste_exam_montbron_u`
  ADD PRIMARY KEY (`dossier`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_no_asalee`
--
ALTER TABLE `liste_exam_no_asalee`
  ADD PRIMARY KEY (`cabinet`,`numero`),
  ADD UNIQUE KEY `u_key` (`numero`,`cabinet`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_Ruelle_u`
--
ALTER TABLE `liste_exam_Ruelle_u`
  ADD PRIMARY KEY (`dossier`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_saulieu_u`
--
ALTER TABLE `liste_exam_saulieu_u`
  ADD PRIMARY KEY (`dossier`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `liste_exam_venissieuxcroizat_u`
--
ALTER TABLE `liste_exam_venissieuxcroizat_u`
  ADD PRIMARY KEY (`dossier`,`type_exam`,`date_exam`),
  ADD KEY `type_exam` (`type_exam`),
  ADD KEY `date_exam` (`date_exam`);

--
-- Index pour la table `medecin`
--
ALTER TABLE `medecin`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `u_key` (`cabinet`,`nom`,`prenom`);

--
-- Index pour la table `medecins_generalistes`
--
ALTER TABLE `medecins_generalistes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_key` (`cabinet`,`nom`,`prenom`);

--
-- Index pour la table `medecin_ruelle`
--
ALTER TABLE `medecin_ruelle`
  ADD PRIMARY KEY (`numero`);

--
-- Index pour la table `no_chatillon`
--
ALTER TABLE `no_chatillon`
  ADD PRIMARY KEY (`ancien`);

--
-- Index pour la table `p6`
--
ALTER TABLE `p6`
  ADD UNIQUE KEY `u_id` (`id`,`deval`);

--
-- Index pour la table `p630062016`
--
ALTER TABLE `p630062016`
  ADD UNIQUE KEY `u_id` (`id`,`deval`);

--
-- Index pour la table `pat_mediclic`
--
ALTER TABLE `pat_mediclic`
  ADD PRIMARY KEY (`numero`,`cabinet`),
  ADD KEY `nom` (`nom`),
  ADD KEY `prenom` (`prenom`),
  ADD KEY `ville` (`ville`),
  ADD KEY `dnaiss` (`dnaiss`),
  ADD KEY `tel` (`tel`);

--
-- Index pour la table `phpbb_auth_access`
--
ALTER TABLE `phpbb_auth_access`
  ADD KEY `group_id` (`group_id`),
  ADD KEY `forum_id` (`forum_id`);

--
-- Index pour la table `phpbb_banlist`
--
ALTER TABLE `phpbb_banlist`
  ADD PRIMARY KEY (`ban_id`),
  ADD KEY `ban_ip_user_id` (`ban_ip`,`ban_userid`);

--
-- Index pour la table `phpbb_categories`
--
ALTER TABLE `phpbb_categories`
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `cat_order` (`cat_order`);

--
-- Index pour la table `phpbb_config`
--
ALTER TABLE `phpbb_config`
  ADD PRIMARY KEY (`config_name`);

--
-- Index pour la table `phpbb_confirm`
--
ALTER TABLE `phpbb_confirm`
  ADD PRIMARY KEY (`session_id`,`confirm_id`);

--
-- Index pour la table `phpbb_disallow`
--
ALTER TABLE `phpbb_disallow`
  ADD PRIMARY KEY (`disallow_id`);

--
-- Index pour la table `phpbb_forums`
--
ALTER TABLE `phpbb_forums`
  ADD PRIMARY KEY (`forum_id`),
  ADD KEY `forums_order` (`forum_order`),
  ADD KEY `cat_id` (`cat_id`),
  ADD KEY `forum_last_post_id` (`forum_last_post_id`);

--
-- Index pour la table `phpbb_forum_prune`
--
ALTER TABLE `phpbb_forum_prune`
  ADD PRIMARY KEY (`prune_id`),
  ADD KEY `forum_id` (`forum_id`);

--
-- Index pour la table `phpbb_groups`
--
ALTER TABLE `phpbb_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `group_single_user` (`group_single_user`);

--
-- Index pour la table `phpbb_posts`
--
ALTER TABLE `phpbb_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `forum_id` (`forum_id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `poster_id` (`poster_id`),
  ADD KEY `post_time` (`post_time`);

--
-- Index pour la table `phpbb_posts_text`
--
ALTER TABLE `phpbb_posts_text`
  ADD PRIMARY KEY (`post_id`);

--
-- Index pour la table `phpbb_privmsgs`
--
ALTER TABLE `phpbb_privmsgs`
  ADD PRIMARY KEY (`privmsgs_id`),
  ADD KEY `privmsgs_from_userid` (`privmsgs_from_userid`),
  ADD KEY `privmsgs_to_userid` (`privmsgs_to_userid`);

--
-- Index pour la table `phpbb_privmsgs_text`
--
ALTER TABLE `phpbb_privmsgs_text`
  ADD PRIMARY KEY (`privmsgs_text_id`);

--
-- Index pour la table `phpbb_ranks`
--
ALTER TABLE `phpbb_ranks`
  ADD PRIMARY KEY (`rank_id`);

--
-- Index pour la table `phpbb_search_results`
--
ALTER TABLE `phpbb_search_results`
  ADD PRIMARY KEY (`search_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Index pour la table `phpbb_search_wordlist`
--
ALTER TABLE `phpbb_search_wordlist`
  ADD PRIMARY KEY (`word_text`),
  ADD KEY `word_id` (`word_id`);

--
-- Index pour la table `phpbb_search_wordmatch`
--
ALTER TABLE `phpbb_search_wordmatch`
  ADD KEY `post_id` (`post_id`),
  ADD KEY `word_id` (`word_id`);

--
-- Index pour la table `phpbb_sessions`
--
ALTER TABLE `phpbb_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Index pour la table `phpbb_sessions_keys`
--
ALTER TABLE `phpbb_sessions_keys`
  ADD PRIMARY KEY (`key_id`,`user_id`),
  ADD KEY `last_login` (`last_login`);

--
-- Index pour la table `phpbb_smilies`
--
ALTER TABLE `phpbb_smilies`
  ADD PRIMARY KEY (`smilies_id`);

--
-- Index pour la table `phpbb_themes`
--
ALTER TABLE `phpbb_themes`
  ADD PRIMARY KEY (`themes_id`);

--
-- Index pour la table `phpbb_themes_name`
--
ALTER TABLE `phpbb_themes_name`
  ADD PRIMARY KEY (`themes_id`);

--
-- Index pour la table `phpbb_topics`
--
ALTER TABLE `phpbb_topics`
  ADD PRIMARY KEY (`topic_id`),
  ADD KEY `forum_id` (`forum_id`),
  ADD KEY `topic_moved_id` (`topic_moved_id`),
  ADD KEY `topic_status` (`topic_status`),
  ADD KEY `topic_type` (`topic_type`);

--
-- Index pour la table `phpbb_topics_watch`
--
ALTER TABLE `phpbb_topics_watch`
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `notify_status` (`notify_status`);

--
-- Index pour la table `phpbb_users`
--
ALTER TABLE `phpbb_users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_session_time` (`user_session_time`);

--
-- Index pour la table `phpbb_user_group`
--
ALTER TABLE `phpbb_user_group`
  ADD KEY `group_id` (`group_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `phpbb_vote_desc`
--
ALTER TABLE `phpbb_vote_desc`
  ADD PRIMARY KEY (`vote_id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Index pour la table `phpbb_vote_results`
--
ALTER TABLE `phpbb_vote_results`
  ADD KEY `vote_option_id` (`vote_option_id`),
  ADD KEY `vote_id` (`vote_id`);

--
-- Index pour la table `phpbb_vote_voters`
--
ALTER TABLE `phpbb_vote_voters`
  ADD KEY `vote_id` (`vote_id`),
  ADD KEY `vote_user_id` (`vote_user_id`),
  ADD KEY `vote_user_ip` (`vote_user_ip`);

--
-- Index pour la table `phpbb_words`
--
ALTER TABLE `phpbb_words`
  ADD PRIMARY KEY (`word_id`);

--
-- Index pour la table `planning`
--
ALTER TABLE `planning`
  ADD PRIMARY KEY (`infirmiere`,`dmaj`);

--
-- Index pour la table `planning_infirmieres`
--
ALTER TABLE `planning_infirmieres`
  ADD UNIQUE KEY `infirmiere` (`infirmiere`,`cabinet`);

--
-- Index pour la table `professionel`
--
ALTER TABLE `professionel`
  ADD PRIMARY KEY (`PK_professionnel`),
  ADD UNIQUE KEY `PK_professionnel` (`PK_professionnel`);

--
-- Index pour la table `proprietaire`
--
ALTER TABLE `proprietaire`
  ADD PRIMARY KEY (`PK_proprietaire`),
  ADD UNIQUE KEY `PK_proprietaire` (`PK_proprietaire`);

--
-- Index pour la table `questionnaire_medecin`
--
ALTER TABLE `questionnaire_medecin`
  ADD PRIMARY KEY (`medecin`);

--
-- Index pour la table `satisf_patient`
--
ALTER TABLE `satisf_patient`
  ADD PRIMARY KEY (`no_questionnaire`);

--
-- Index pour la table `satisf_patient2007`
--
ALTER TABLE `satisf_patient2007`
  ADD PRIMARY KEY (`no_questionnaire`);

--
-- Index pour la table `satisf_patient2009`
--
ALTER TABLE `satisf_patient2009`
  ADD PRIMARY KEY (`no_questionnaire`);

--
-- Index pour la table `session`
--
ALTER TABLE `session`
  ADD KEY `userid` (`userid`);

--
-- Index pour la table `sevrage_tabac`
--
ALTER TABLE `sevrage_tabac`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Index pour la table `sevrage_tabac_old`
--
ALTER TABLE `sevrage_tabac_old`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Index pour la table `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `structure`
--
ALTER TABLE `structure`
  ADD PRIMARY KEY (`PK_structure`),
  ADD UNIQUE KEY `PK_structure` (`PK_structure`);

--
-- Index pour la table `structure_relation`
--
ALTER TABLE `structure_relation`
  ADD PRIMARY KEY (`PK_struct_struct`),
  ADD UNIQUE KEY `PK_struct_struct` (`PK_struct_struct`),
  ADD KEY `FK_structure` (`FK_structure`);

--
-- Index pour la table `suivi_diabete`
--
ALTER TABLE `suivi_diabete`
  ADD PRIMARY KEY (`dossier_id`,`dsuivi`);

--
-- Index pour la table `suivi_hebdomadaire`
--
ALTER TABLE `suivi_hebdomadaire`
  ADD PRIMARY KEY (`date`,`cabinet`);

--
-- Index pour la table `suivi_hebdomadaire2007`
--
ALTER TABLE `suivi_hebdomadaire2007`
  ADD PRIMARY KEY (`date`,`cabinet`);

--
-- Index pour la table `suivi_hebdo_temps_passe`
--
ALTER TABLE `suivi_hebdo_temps_passe`
  ADD PRIMARY KEY (`date`,`cabinet`);

--
-- Index pour la table `suivi_hebdo_temps_passe_infirmiere`
--
ALTER TABLE `suivi_hebdo_temps_passe_infirmiere`
  ADD UNIQUE KEY `date` (`semaine`,`cabinet`,`infirmiere`);

--
-- Index pour la table `suivi_reunion_medecin`
--
ALTER TABLE `suivi_reunion_medecin`
  ADD PRIMARY KEY (`id_reu`);

--
-- Index pour la table `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `temp_dashboard`
--
ALTER TABLE `temp_dashboard`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tension_arterielle`
--
ALTER TABLE `tension_arterielle`
  ADD PRIMARY KEY (`id`,`date`,`momment_journee`,`indice`);

--
-- Index pour la table `tension_arterielle_moyenne`
--
ALTER TABLE `tension_arterielle_moyenne`
  ADD PRIMARY KEY (`id`,`group_id`);

--
-- Index pour la table `thematique_relation`
--
ALTER TABLE `thematique_relation`
  ADD PRIMARY KEY (`PK_them_relation`),
  ADD UNIQUE KEY `PK_them_relation` (`PK_them_relation`),
  ADD KEY `FK_activite` (`FK_activite`),
  ADD KEY `FK_structure` (`FK_structure`);

--
-- Index pour la table `trouble_cognitif`
--
ALTER TABLE `trouble_cognitif`
  ADD PRIMARY KEY (`id`,`date`);

--
-- Index pour la table `type_groupe`
--
ALTER TABLE `type_groupe`
  ADD PRIMARY KEY (`PK_type_groupe`),
  ADD UNIQUE KEY `PK_type_groupe` (`PK_type_groupe`),
  ADD KEY `groupe_id` (`groupe_id`,`list_id`),
  ADD KEY `groupe_link` (`groupe_link`),
  ADD KEY `groupe_lib` (`groupe_lib`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`PK_utilisateur`),
  ADD UNIQUE KEY `PK_utilisateur` (`PK_utilisateur`);

--
-- Index pour la table `ville`
--
ALTER TABLE `ville`
  ADD PRIMARY KEY (`PK_ville`),
  ADD UNIQUE KEY `ville_id` (`ville_id`),
  ADD KEY `nom` (`nom`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1117;
--
-- AUTO_INCREMENT pour la table `activite`
--
ALTER TABLE `activite`
  MODIFY `PK_activite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT pour la table `conges`
--
ALTER TABLE `conges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10297;
--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `PK_contact` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `courriers_bilans`
--
ALTER TABLE `courriers_bilans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `demande_carte_grise_grille`
--
ALTER TABLE `demande_carte_grise_grille`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45205;
--
-- AUTO_INCREMENT pour la table `demande_carte_grise_historique`
--
ALTER TABLE `demande_carte_grise_historique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45215;
--
-- AUTO_INCREMENT pour la table `demande_carte_grise_identification`
--
ALTER TABLE `demande_carte_grise_identification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45206;
--
-- AUTO_INCREMENT pour la table `demande_carte_grise_status`
--
ALTER TABLE `demande_carte_grise_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45205;
--
-- AUTO_INCREMENT pour la table `demande_carte_grise_suivi`
--
ALTER TABLE `demande_carte_grise_suivi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45209;
--
-- AUTO_INCREMENT pour la table `demande_frais_historique`
--
ALTER TABLE `demande_frais_historique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45211;
--
-- AUTO_INCREMENT pour la table `demande_frais_identification`
--
ALTER TABLE `demande_frais_identification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45230;
--
-- AUTO_INCREMENT pour la table `demande_frais_status`
--
ALTER TABLE `demande_frais_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45205;
--
-- AUTO_INCREMENT pour la table `demande_frais_suivi`
--
ALTER TABLE `demande_frais_suivi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45230;
--
-- AUTO_INCREMENT pour la table `depistage_aomi`
--
ALTER TABLE `depistage_aomi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=636;
--
-- AUTO_INCREMENT pour la table `dossier`
--
ALTER TABLE `dossier`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283813;
--
-- AUTO_INCREMENT pour la table `dossier_kc`
--
ALTER TABLE `dossier_kc`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179470;
--
-- AUTO_INCREMENT pour la table `entretienAnnuel`
--
ALTER TABLE `entretienAnnuel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `eval_continue`
--
ALTER TABLE `eval_continue`
  MODIFY `numero_eval` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `fragilite`
--
ALTER TABLE `fragilite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=449;
--
-- AUTO_INCREMENT pour la table `frais`
--
ALTER TABLE `frais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42981;
--
-- AUTO_INCREMENT pour la table `groupes`
--
ALTER TABLE `groupes`
  MODIFY `id_groupe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2327;
--
-- AUTO_INCREMENT pour la table `historique_account`
--
ALTER TABLE `historique_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1098;
--
-- AUTO_INCREMENT pour la table `historique_medecin`
--
ALTER TABLE `historique_medecin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3674;
--
-- AUTO_INCREMENT pour la table `inscription`
--
ALTER TABLE `inscription`
  MODIFY `PK_inscription` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `liste_exam`
--
ALTER TABLE `liste_exam`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `liste_exam_no_asalee`
--
ALTER TABLE `liste_exam_no_asalee`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `medecin`
--
ALTER TABLE `medecin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3504;
--
-- AUTO_INCREMENT pour la table `medecins_generalistes`
--
ALTER TABLE `medecins_generalistes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=342;
--
-- AUTO_INCREMENT pour la table `phpbb_banlist`
--
ALTER TABLE `phpbb_banlist`
  MODIFY `ban_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `phpbb_categories`
--
ALTER TABLE `phpbb_categories`
  MODIFY `cat_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `phpbb_disallow`
--
ALTER TABLE `phpbb_disallow`
  MODIFY `disallow_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `phpbb_forum_prune`
--
ALTER TABLE `phpbb_forum_prune`
  MODIFY `prune_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `phpbb_groups`
--
ALTER TABLE `phpbb_groups`
  MODIFY `group_id` mediumint(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `phpbb_posts`
--
ALTER TABLE `phpbb_posts`
  MODIFY `post_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `phpbb_privmsgs`
--
ALTER TABLE `phpbb_privmsgs`
  MODIFY `privmsgs_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `phpbb_ranks`
--
ALTER TABLE `phpbb_ranks`
  MODIFY `rank_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `phpbb_search_wordlist`
--
ALTER TABLE `phpbb_search_wordlist`
  MODIFY `word_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT pour la table `phpbb_smilies`
--
ALTER TABLE `phpbb_smilies`
  MODIFY `smilies_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT pour la table `phpbb_themes`
--
ALTER TABLE `phpbb_themes`
  MODIFY `themes_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `phpbb_topics`
--
ALTER TABLE `phpbb_topics`
  MODIFY `topic_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `phpbb_vote_desc`
--
ALTER TABLE `phpbb_vote_desc`
  MODIFY `vote_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `phpbb_words`
--
ALTER TABLE `phpbb_words`
  MODIFY `word_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `professionel`
--
ALTER TABLE `professionel`
  MODIFY `PK_professionnel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `proprietaire`
--
ALTER TABLE `proprietaire`
  MODIFY `PK_proprietaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `satisf_patient`
--
ALTER TABLE `satisf_patient`
  MODIFY `no_questionnaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=324;
--
-- AUTO_INCREMENT pour la table `satisf_patient2007`
--
ALTER TABLE `satisf_patient2007`
  MODIFY `no_questionnaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=324;
--
-- AUTO_INCREMENT pour la table `satisf_patient2009`
--
ALTER TABLE `satisf_patient2009`
  MODIFY `no_questionnaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;
--
-- AUTO_INCREMENT pour la table `sevrage_tabac`
--
ALTER TABLE `sevrage_tabac`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34144;
--
-- AUTO_INCREMENT pour la table `sevrage_tabac_old`
--
ALTER TABLE `sevrage_tabac_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6834;
--
-- AUTO_INCREMENT pour la table `sms`
--
ALTER TABLE `sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `structure`
--
ALTER TABLE `structure`
  MODIFY `PK_structure` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `structure_relation`
--
ALTER TABLE `structure_relation`
  MODIFY `PK_struct_struct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `suivi_reunion_medecin`
--
ALTER TABLE `suivi_reunion_medecin`
  MODIFY `id_reu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85705;
--
-- AUTO_INCREMENT pour la table `support`
--
ALTER TABLE `support`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1018;
--
-- AUTO_INCREMENT pour la table `temp_dashboard`
--
ALTER TABLE `temp_dashboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=415;
--
-- AUTO_INCREMENT pour la table `thematique_relation`
--
ALTER TABLE `thematique_relation`
  MODIFY `PK_them_relation` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `type_groupe`
--
ALTER TABLE `type_groupe`
  MODIFY `PK_type_groupe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `PK_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `ville`
--
ALTER TABLE `ville`
  MODIFY `PK_ville` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
