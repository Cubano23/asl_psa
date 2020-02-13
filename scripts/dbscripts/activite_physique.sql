drop table if exists `activite_physique`;
CREATE TABLE `activite_physique` (

  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dossier_id` int(11) DEFAULT NULL,
  `dossier_numero` varchar(45) DEFAULT NULL,

  `distance_parcourue_en_metres` VARCHAR(45) DEFAULT NULL,
  `evaluation_marche` VARCHAR(45) NULL DEFAULT NULL,
  `poids` VARCHAR(45) DEFAULT NULL,
  `contour_du_ventre` VARCHAR(45) DEFAULT NULL,
  `patient_sortant_du_protocole` VARCHAR(20) NULL DEFAULT NULL,
  `essoufflement` INT(11) NULL DEFAULT NULL,
  `douleurs_eva` INT(11) NULL DEFAULT NULL,
  `motivation` INT(11) NULL DEFAULT NULL,
  `atteinte_des_objectifs` INT(11) NULL DEFAULT NULL,
  `fatigue` INT(11) NULL DEFAULT NULL,
  `tps_activ_sept_jrs` INT(11) NULL DEFAULT NULL,
  `tps_sed_sept_jrs` INT(11) NULL DEFAULT NULL,
  `nombre_de_pas_24h` INT(11) NULL DEFAULT NULL,
  `utilisation_d_un_compteur_de_pas` VARCHAR(20) NULL DEFAULT NULL,
  `utilisation_d_un_compteur_description` VARCHAR(55) NULL DEFAULT NULL,
  `qualite_de_vie`  LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `qualite_sommeil` INT(11) NULL DEFAULT NULL,
  `modification_alimentaire` VARCHAR(20) NULL DEFAULT NULL,
  `bien_etre` INT(11) NULL DEFAULT NULL,
  `confiance` INT(11) NULL DEFAULT NULL,
  `isolement_social_ressenti` VARCHAR(20) NULL DEFAULT NULL,
  `activites_physiques_annexes` VARCHAR(20) NULL DEFAULT NULL,
  `activites_physiques_annexes_description` VARCHAR(55) NULL DEFAULT NULL,
  `patient_sortant_du_protocole_description` VARCHAR(55) NULL DEFAULT NULL,
  `lien_sur_le_territoire` VARCHAR(20) NULL DEFAULT NULL,
  `lien_sur_le_territoire_description` VARCHAR(55) NULL DEFAULT NULL,

  `infAjout` varchar(45) DEFAULT NULL,
  `cabinet` varchar(45) DEFAULT NULL,
  `dateAjout` datetime DEFAULT NULL,
  `date_maj` date DEFAULT NULL,
  `estActif` tinyint(4) DEFAULT NULL,

  PRIMARY KEY (`id`)
) ENGINE=MyISAM;
