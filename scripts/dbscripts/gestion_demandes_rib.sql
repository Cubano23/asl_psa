drop table IF EXISTS demande_rib_identification;
drop table IF EXISTS demande_rib_suivi;
drop table IF EXISTS demande_rib_historique;
drop table IF EXISTS demande_rib_status;
DROP VIEW IF EXISTS demande_rib_vue_resumee;
DROP VIEW IF EXISTS demande_rib_vue_detaillee;


CREATE TABLE IF NOT EXISTS `demande_rib_identification` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`intitule` varchar(255) NOT NULL DEFAULT '' ,
	`dcreat` timestamp  DEFAULT CURRENT_TIMESTAMP,
	`dmaj` timestamp  DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM	DEFAULT CHARSET=latin1 AUTO_INCREMENT=45205 ;

CREATE TABLE IF NOT EXISTS `demande_rib_suivi` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_demande_rib` int(11) NOT NULL,
	`id_status` int(11) NOT NULL,
	`id_historique` int(11) NOT NULL,
	`id_utilisateur` int(11) NOT NULL,
	`login_utilisateur` varchar(50) NOT NULL,
	`notes` varchar(250) NOT NULL DEFAULT '',
	`dcreat` timestamp  DEFAULT CURRENT_TIMESTAMP,
	`dmaj` timestamp  DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM	DEFAULT CHARSET=latin1 AUTO_INCREMENT=45205 ;

CREATE TABLE IF NOT EXISTS `demande_rib_historique` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_demandeur` int(11) NOT NULL,
	`login_demandeur` varchar(50) NOT NULL DEFAULT '',
	`iban` varchar(255) NOT NULL DEFAULT '',
	`justificatif` varchar(255) DEFAULT NULL,
	`dcreat` timestamp  DEFAULT CURRENT_TIMESTAMP,
	`dmaj` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM	DEFAULT CHARSET=latin1 AUTO_INCREMENT=45205 ;

CREATE TABLE IF NOT EXISTS `demande_rib_status` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`intitule` varchar(255) NOT NULL DEFAULT '',
	`dcreat` timestamp  DEFAULT CURRENT_TIMESTAMP,
	`dmaj` timestamp  DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM	DEFAULT CHARSET=latin1 AUTO_INCREMENT=45205 ;


INSERT INTO `demande_rib_status` (id,intitule) VALUES
(1, "Soumise"),
(2, "En attente d'informations supplémentaires"),
(3, "Validée en attente de virement"),
(4, "Virement effectué"),
(5, "Rejetée");

/*
INSERT INTO `demande_rib_status` (id,intitule) VALUES
	(1, "Demande soumise");
	

INSERT INTO `demande_rib_identification`(id,intitule) VALUES
	(1, "Demande ari_test");
	

INSERT INTO `demande_rib_suivi` (id,id_demande_rib,id_status,id_historique,id_utilisateur,login_utilisateur,notes) VALUES
	(1, 1, 1 , 1, 510,'arizk','');
*/	



/*
INSERT INTO `demande_rib_historique` (id,id_demandeur,iban,date_rib,nature,motif,distance,taux_applique,montant,justificatif) VALUES
	(1,510,'arizk','2018-10-08', "rib kilométriques", "motif de dev", 50,0.41,20.5,"/var/data/home/informed/www/_files/notes_de_rib/rib_kilometrique_ari_v1"),
	(2,510,'arizk','2018-10-08', "rib kilométriques", "motif de dev", 50,0.41,20.5,"/var/data/home/informed/www/_files/notes_de_rib/rib_kilometrique_ari_v2"),
	(3,509,'echallita','2018-10-09', "rib dej", "motif de dev", 0,0,30,"/var/data/home/informed/www/_files/notes_de_rib/rib_dejeuner_ech"),
	(4,651,'lluneau','2018-10-10', "rib hotel", "motif de dev", 0,0,30,"/var/data/home/informed/www/_files/notes_de_rib/rib_dejeuner_ech");
*/

CREATE OR REPLACE VIEW demande_rib_vue_detaillee AS
	SELECT demande_rib_identification.id, demande_rib_suivi.id as identifiant_suivi, demande_rib_identification.id as identifiant_demande, DATE(demande_rib_identification.dcreat) as date_demande, demande_rib_identification.intitule as titre, demande_rib_historique.id_demandeur, demande_rib_historique.login_demandeur, demande_rib_historique.iban, (SELECT CONCAT(identifications.prenom, ' ', identifications.nom) FROM annuaire.identifications WHERE login = demande_rib_historique.login_demandeur) AS nom_demandeur, demande_rib_historique.justificatif, demande_rib_suivi.id_utilisateur as dernier_intervenant_id, demande_rib_suivi.login_utilisateur as dernierIntervenant, (SELECT CONCAT(identifications.prenom, ' ', identifications.nom) FROM annuaire.identifications WHERE login = demande_rib_suivi.login_utilisateur) AS nom_intervenant, demande_rib_status.intitule as dernierStatus, demande_rib_status.id as id_status, demande_rib_suivi.dcreat as date_dernierStatut, demande_rib_suivi.notes
	FROM demande_rib_identification
		inner join demande_rib_suivi on demande_rib_suivi.id_demande_rib = demande_rib_identification.id
		inner join demande_rib_historique on demande_rib_historique.id = demande_rib_suivi.id_historique
		inner join demande_rib_status on demande_rib_status.id = demande_rib_suivi.id_status;

CREATE OR REPLACE VIEW demande_rib_vue_resumee AS
	select demande_rib_identification.id, demande_rib_suivi.id as identifiant_suivi, DATE(demande_rib_identification.dcreat) as date_demande, demande_rib_identification.intitule as titre , demande_rib_historique.id_demandeur, demande_rib_historique.login_demandeur, demande_rib_historique.iban, (SELECT CONCAT(identifications.prenom, ' ', identifications.nom) FROM annuaire.identifications WHERE login = demande_rib_historique.login_demandeur) AS nom_demandeur, demande_rib_historique.justificatif,demande_rib_suivi.id_utilisateur as dernier_intervenant_id,demande_rib_suivi.login_utilisateur as dernierIntervenant, (SELECT CONCAT(identifications.prenom, ' ', identifications.nom) FROM annuaire.identifications WHERE login = demande_rib_suivi.login_utilisateur) AS nom_intervenant, demande_rib_status.intitule as dernierStatus ,demande_rib_status.id as id_status, demande_rib_suivi.dcreat as date_dernierStatut,demande_rib_suivi.notes
	from (select id_demande_rib, max(demande_rib_suivi.id) as id_suivi from demande_rib_suivi group by demande_rib_suivi.id_demande_rib) as demande_rib_dernier_suivi
		inner join demande_rib_suivi on demande_rib_suivi.id_demande_rib = demande_rib_dernier_suivi.id_demande_rib and demande_rib_suivi.id = demande_rib_dernier_suivi.id_suivi
		inner join demande_rib_identification on demande_rib_identification.id = demande_rib_suivi.id_demande_rib
		inner join demande_rib_historique on demande_rib_historique.id = demande_rib_suivi.id_historique
		inner join demande_rib_status on demande_rib_status.id = demande_rib_suivi.id_status;

