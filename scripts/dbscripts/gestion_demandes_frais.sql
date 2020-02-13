drop table IF EXISTS demande_frais_identification;
drop table IF EXISTS demande_frais_suivi;
drop table IF EXISTS demande_frais_historique;
drop table IF EXISTS demande_frais_status;
DROP VIEW IF EXISTS demande_frais_vue_resumee;
DROP VIEW IF EXISTS demande_frais_vue_detaillee;
DROP VIEW IF EXISTS	demande_frais_dernier_suivi;

CREATE TABLE IF NOT EXISTS `demande_frais_identification` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`intitule` varchar(255) NOT NULL DEFAULT '',
	`dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM	DEFAULT CHARSET=latin1 AUTO_INCREMENT=45205 ;

CREATE TABLE IF NOT EXISTS `demande_frais_suivi` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_frais` int(11) NOT NULL,
	`id_status` int(11) NOT NULL,
	`id_historique` int(11) NOT NULL,
	`id_utilisateur` int(11) NOT NULL,
	`login_utilisateur` varchar(50) NOT NULL,
	`notes` varchar(250) NOT NULL DEFAULT '',
	`dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM	DEFAULT CHARSET=latin1 AUTO_INCREMENT=45205 ;

CREATE TABLE IF NOT EXISTS `demande_frais_historique` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_demandeur` int(11) NOT NULL,
	`login_demandeur` varchar(50) NOT NULL,
	`date_frais` date NOT NULL DEFAULT '1970-01-01',
	`nature` varchar(255) NOT NULL DEFAULT '',
	`motif` varchar(255) NOT NULL DEFAULT '',
	`distance` FLOAT(15,2) NOT NULL DEFAULT 0.00,
	`puissance` FLOAT(15,2) NOT NULL DEFAULT 0.00,
	`taux_applique` FLOAT(15,3) NOT NULL DEFAULT 0.000,
	`montant` FLOAT(15,2) NOT NULL ,
	`justificatif` varchar(255) DEFAULT NULL,
	`dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM	DEFAULT CHARSET=latin1 AUTO_INCREMENT=45205 ;

CREATE TABLE IF NOT EXISTS `demande_frais_status` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`intitule` varchar(255) NOT NULL DEFAULT '',
	`dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM	DEFAULT CHARSET=latin1 AUTO_INCREMENT=45205 ;


INSERT INTO `demande_frais_status` (id,intitule) VALUES
	(1, "Soumise"),
	(2, "En attente d'informations supplémentaires"),
	(3, "Validée en attente de virement"),
	(4, "Virement effectué"),
	(5, "Rejetée");


CREATE OR REPLACE VIEW demande_frais_vue_detaillee AS
	SELECT demande_frais_identification.id, demande_frais_suivi.id as identifiant_suivi, demande_frais_identification.id as identifiant_demande, DATE(demande_frais_identification.dcreat) as date_demande, demande_frais_identification.intitule as titre, demande_frais_historique.id_demandeur, demande_frais_historique.login_demandeur, (SELECT CONCAT(identifications.prenom, ' ', identifications.nom) FROM annuaire.identifications WHERE login = demande_frais_historique.login_demandeur) AS nom_demandeur, demande_frais_historique.date_frais,demande_frais_historique.nature,demande_frais_historique.motif,demande_frais_historique.distance,demande_frais_historique.puissance,demande_frais_historique.taux_applique,demande_frais_historique.montant,demande_frais_historique.justificatif, demande_frais_suivi.id_utilisateur as dernier_intervenant_id, demande_frais_suivi.login_utilisateur as dernierIntervenant, (SELECT CONCAT(identifications.prenom, ' ', identifications.nom) FROM annuaire.identifications WHERE login = demande_frais_suivi.login_utilisateur) AS nom_intervenant, demande_frais_status.intitule as dernierStatus, demande_frais_status.id as id_status, demande_frais_suivi.dcreat as date_dernierStatut, demande_frais_suivi.notes
	FROM demande_frais_identification
		inner join demande_frais_suivi on demande_frais_suivi.id_frais = demande_frais_identification.id
		inner join demande_frais_historique on demande_frais_historique.id = demande_frais_suivi.id_historique
		inner join demande_frais_status on demande_frais_status.id = demande_frais_suivi.id_status;

CREATE OR REPLACE VIEW demande_frais_dernier_suivi AS
	select id_frais, max(demande_frais_suivi.id) as id_suivi
	from demande_frais_suivi
	group by demande_frais_suivi.id_frais;

CREATE OR REPLACE VIEW demande_frais_vue_resumee AS
	select demande_frais_identification.id, demande_frais_suivi.id as identifiant_suivi, DATE(demande_frais_identification.dcreat) as date_demande, demande_frais_identification.intitule as titre , demande_frais_historique.id_demandeur, demande_frais_historique.login_demandeur, (SELECT CONCAT(identifications.prenom, ' ', identifications.nom) FROM annuaire.identifications WHERE login = demande_frais_historique.login_demandeur) AS nom_demandeur, demande_frais_historique.date_frais,demande_frais_historique.nature,demande_frais_historique.motif,demande_frais_historique.distance,demande_frais_historique.puissance,demande_frais_historique.taux_applique,demande_frais_historique.montant,demande_frais_historique.justificatif,demande_frais_suivi.id_utilisateur as dernier_intervenant_id,demande_frais_suivi.login_utilisateur as dernierIntervenant, (SELECT CONCAT(identifications.prenom, ' ', identifications.nom) FROM annuaire.identifications WHERE login = demande_frais_suivi.login_utilisateur) AS nom_intervenant, demande_frais_status.intitule as dernierStatus ,demande_frais_status.id as id_status, demande_frais_suivi.dcreat as date_dernierStatut,demande_frais_suivi.notes,nbre_occurences.nbOcc
	from demande_frais_dernier_suivi
		inner join demande_frais_suivi on demande_frais_suivi.id_frais = demande_frais_dernier_suivi.id_frais and demande_frais_suivi.id = demande_frais_dernier_suivi.id_suivi
		inner join demande_frais_identification on demande_frais_identification.id = demande_frais_suivi.id_frais
		inner join demande_frais_historique on demande_frais_historique.id = demande_frais_suivi.id_historique
		inner join demande_frais_status on demande_frais_status.id = demande_frais_suivi.id_status
		inner join (
									select demande_frais_historique.montant, demande_frais_historique.date_frais, demande_frais_historique.id_demandeur, count(*) as nbOcc
									from demande_frais_dernier_suivi
									inner join demande_frais_suivi on demande_frais_suivi.id_frais = demande_frais_dernier_suivi.id_frais and demande_frais_suivi.id = demande_frais_dernier_suivi.id_suivi
									inner join demande_frais_identification on demande_frais_identification.id = demande_frais_suivi.id_frais
									inner join demande_frais_historique on demande_frais_historique.id = demande_frais_suivi.id_historique
									inner join demande_frais_status on demande_frais_status.id = demande_frais_suivi.id_status
									where 
									(
										demande_frais_status.id != 5 
										OR
										demande_frais_historique.date_frais BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()
									)
									group by demande_frais_historique.montant, demande_frais_historique.date_frais, demande_frais_historique.id_demandeur
		) as nbre_occurences on demande_frais_historique.montant = nbre_occurences.montant and demande_frais_historique.date_frais = nbre_occurences.date_frais and demande_frais_historique.id_demandeur = nbre_occurences.id_demandeur
		;




--
--
-- INSERT INTO `demande_frais_identification`(id,intitule) VALUES
-- 	(1, "Demande ari_test"),
-- 	(2, "Demande ech_test"),
-- 	(3, "Demande llu_test");
--
-- INSERT INTO `demande_frais_suivi` (id,id_frais,id_status,id_historique,id_utilisateur,login_utilisateur,notes) VALUES
-- 	(1, 1, 1 , 1, 510,'arizk',''),
-- 	(2, 1, 2 , 1, 686,'bderville',"Justificatif illisible, nouveau justificatif demandé par mail"),
-- 	(3, 1, 3 , 2, 686,'bderville',""),
-- 	(4, 1, 4 , 2, 686,'bderville',""),
-- 	(5, 2, 1 , 3, 509,'echallita',''),
-- 	(6, 2, 3 , 3, 686,'bderville',""),
-- 	(7, 2, 4 , 3, 686,'bderville',""),
-- 	(8, 3, 1 , 4, 651,'lluneau',''),
-- 	(9, 3, 3 , 4, 686,'bderville',""),
-- 	(10, 3, 5 , 4, 686,'bderville',"");
--
--
-- INSERT INTO `demande_frais_historique` (id,id_demandeur,login_demandeur,date_frais,nature,motif,distance,puissance,taux_applique,montant,justificatif) VALUES
-- 	(1,510,'arizk','2018-10-08', "frais kilométriques", "motif de dev", 50,5,0.41,20.5,"/var/data/home/informed/www/_files/notes_de_frais/frais_kilometrique_ari_v1"),
-- 	(2,510,'arizk','2018-10-08', "frais kilométriques", "motif de dev", 50,6,0.41,20.5,"/var/data/home/informed/www/_files/notes_de_frais/frais_kilometrique_ari_v2"),
-- 	(3,509,'echallita','2018-10-09', "frais dej", "motif de dev", 0,7,0,30,"/var/data/home/informed/www/_files/notes_de_frais/frais_dejeuner_ech"),
-- 	(4,651,'lluneau','2018-10-10', "frais hotel", "motif de dev", 0,8,0,30,"/var/data/home/informed/www/_files/notes_de_frais/frais_dejeuner_ech");