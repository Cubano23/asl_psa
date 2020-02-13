
ALTER TABLE `activite_physique` CHANGE `dateAjout` `dateAjout` DATE NULL DEFAULT NULL;
ALTER TABLE `activite_physique` CHANGE `evaluation_marche` `evaluation_marche` VARCHAR(45) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `patient_sortant_du_protocole` VARCHAR(20) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `essoufflement` INT(11) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `douleurs_eva` INT(11) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `motivation` INT(11) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `atteinte_des_objectifs` INT(11) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `fatigue` INT(11) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `temps_d_activite_7_jours ` INT(11) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `temps_de_sedentarite_7_jours ` INT(11) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `nombre_de_pas_24h` INT(11) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `utilisation_d_un_compteur_de_pas` VARCHAR(20) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `utilisation_d_un_compteur_description` VARCHAR(55) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `qualite_de_vie`  LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `qualite_sommeil ` INT(11) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `modification_alimentaire` VARCHAR(20) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `bien_etre ` INT(11) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `confiance` INT(11) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `isolement_social_ressenti` VARCHAR(20) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `activites_physiques_annexes` VARCHAR(20) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `activites_physiques_annexes_description` VARCHAR(55) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `patient_sortant_du_protocole_description` VARCHAR(55) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `lien_sur_le_territoire` VARCHAR(20) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `lien_sur_le_territoire_description` VARCHAR(55) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `poids` INT(11) NULL DEFAULT NULL;
ALTER TABLE `activite_physique` ADD `contour_du_ventre` INT(11) NULL DEFAULT NULL;












