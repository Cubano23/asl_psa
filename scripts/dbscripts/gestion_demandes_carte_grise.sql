drop table IF EXISTS demande_carte_grise_identification;
drop table IF EXISTS demande_carte_grise_suivi;
drop table IF EXISTS demande_carte_grise_historique;
drop table IF EXISTS demande_carte_grise_status;
drop table IF EXISTS demande_carte_grise_grille;
DROP VIEW IF EXISTS demande_carte_grise_vue_resumee;
DROP VIEW IF EXISTS demande_carte_grise_vue_detaillee;
DROP VIEW IF EXISTS carte_grise_grille_taux_actuelle;

CREATE TABLE IF NOT EXISTS `demande_carte_grise_identification` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`intitule` varchar(255) NOT NULL DEFAULT '',
	`dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM	DEFAULT CHARSET=latin1 AUTO_INCREMENT=45205 ;



CREATE TABLE IF NOT EXISTS `demande_carte_grise_suivi` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_demande_carte_grise` int(11) NOT NULL,
	`id_status` int(11) NOT NULL,
	`id_historique` int(11) NOT NULL,
	`id_utilisateur` int(11) NOT NULL,
	`login_utilisateur` varchar(50) NOT NULL,
	`notes` varchar(250) NOT NULL DEFAULT '',
	`dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM	DEFAULT CHARSET=latin1 AUTO_INCREMENT=45205 ;

CREATE TABLE IF NOT EXISTS `demande_carte_grise_historique` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_demandeur` int(11) NOT NULL,
	`login_demandeur` varchar(50) NOT NULL,
	`date_obtention` date NOT NULL DEFAULT '1970-01-01',
	`puissance` FLOAT(15,3) NOT NULL,
	`precisions` varchar(255) NOT NULL DEFAULT '',
	`justificatif` varchar(255) DEFAULT NULL,
	`dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM	DEFAULT CHARSET=latin1 AUTO_INCREMENT=45205 ;

CREATE TABLE IF NOT EXISTS `demande_carte_grise_status` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`intitule` varchar(255) NOT NULL DEFAULT '',
	`dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM	DEFAULT CHARSET=latin1 AUTO_INCREMENT=45205 ;

CREATE TABLE IF NOT EXISTS `demande_carte_grise_grille` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`puissance` FLOAT(15,3) NOT NULL,
	`taux` FLOAT(15,3) NOT NULL,
	`dcreat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM	DEFAULT CHARSET=latin1 AUTO_INCREMENT=45205 ;

INSERT INTO `demande_carte_grise_grille` (id,puissance,taux) VALUES
(1 , 1, 0.410),
(2 , 2, 0.410),
(3 , 3, 0.410),
(4 , 4, 0.493),
(5 , 5, 0.543),
(6 , 6, 0.568),
(7 , 7, 0.595),
(8 , 8, 0.595),
(9 , 9, 0.595),
(10, 10, 0.595),
(11, 11, 0.595),
(12, 12, 0.595),
(13, 13, 0.595),
(14, 14, 0.595),
(15, 15, 0.595);


INSERT INTO `demande_carte_grise_status` (id,intitule) VALUES
	(1, "Soumise"),
	(2, "En attente d'informations supplémentaires"),
	(3, "Carte Grise Valide"),
	(4, "Rejetée");


CREATE OR REPLACE VIEW demande_carte_grise_vue_detaillee AS
	SELECT demande_carte_grise_identification.id, demande_carte_grise_suivi.id as identifiant_suivi, demande_carte_grise_identification.id as identifiant_demande, DATE(demande_carte_grise_identification.dcreat) as date_demande, demande_carte_grise_identification.intitule as titre, demande_carte_grise_historique.id_demandeur, demande_carte_grise_historique.login_demandeur, (SELECT CONCAT(identifications.prenom, ' ', identifications.nom) FROM annuaire.identifications WHERE login = demande_carte_grise_historique.login_demandeur) AS nom_demandeur, demande_carte_grise_historique.date_obtention, demande_carte_grise_historique.puissance, demande_carte_grise_historique.precisions, demande_carte_grise_historique.justificatif, demande_carte_grise_suivi.id_utilisateur as dernier_intervenant_id, demande_carte_grise_suivi.login_utilisateur as dernierIntervenant, (SELECT CONCAT(identifications.prenom, ' ', identifications.nom) FROM annuaire.identifications WHERE login = demande_carte_grise_suivi.login_utilisateur) AS nom_intervenant, demande_carte_grise_status.intitule as dernierStatus, demande_carte_grise_status.id as id_status, demande_carte_grise_suivi.dcreat as date_dernierStatut, demande_carte_grise_suivi.notes
	FROM demande_carte_grise_identification
		inner join demande_carte_grise_suivi on demande_carte_grise_suivi.id_demande_carte_grise = demande_carte_grise_identification.id
		inner join demande_carte_grise_historique on demande_carte_grise_historique.id = demande_carte_grise_suivi.id_historique
		inner join demande_carte_grise_status on demande_carte_grise_status.id = demande_carte_grise_suivi.id_status;

CREATE OR REPLACE VIEW demande_carte_grise_vue_resumee AS
	SELECT demande_carte_grise_identification.id, demande_carte_grise_suivi.id as identifiant_suivi, demande_carte_grise_identification.id as identifiant_demande, DATE(demande_carte_grise_identification.dcreat) as date_demande, demande_carte_grise_identification.intitule as titre, demande_carte_grise_historique.id_demandeur, demande_carte_grise_historique.login_demandeur, (SELECT CONCAT(identifications.prenom, ' ', identifications.nom) FROM annuaire.identifications WHERE login = demande_carte_grise_historique.login_demandeur) AS nom_demandeur, demande_carte_grise_historique.date_obtention, demande_carte_grise_historique.puissance, demande_carte_grise_historique.precisions, demande_carte_grise_historique.justificatif, demande_carte_grise_suivi.id_utilisateur as dernier_intervenant_id, demande_carte_grise_suivi.login_utilisateur as dernierIntervenant, (SELECT CONCAT(identifications.prenom, ' ', identifications.nom) FROM annuaire.identifications WHERE login = demande_carte_grise_suivi.login_utilisateur) AS nom_intervenant, demande_carte_grise_status.intitule as dernierStatus, demande_carte_grise_status.id as id_status, demande_carte_grise_suivi.dcreat as date_dernierStatut, demande_carte_grise_suivi.notes
	from (select id_demande_carte_grise, max(demande_carte_grise_suivi.id) as id_suivi from demande_carte_grise_suivi group by demande_carte_grise_suivi.id_demande_carte_grise) as demande_carte_grise_dernier_suivi
		inner join demande_carte_grise_suivi on demande_carte_grise_suivi.id_demande_carte_grise = demande_carte_grise_dernier_suivi.id_demande_carte_grise and demande_carte_grise_suivi.id = demande_carte_grise_dernier_suivi.id_suivi
		inner join demande_carte_grise_identification on demande_carte_grise_identification.id = demande_carte_grise_suivi.id_demande_carte_grise
		inner join demande_carte_grise_historique on demande_carte_grise_historique.id = demande_carte_grise_suivi.id_historique
		inner join demande_carte_grise_status on demande_carte_grise_status.id = demande_carte_grise_suivi.id_status;


CREATE OR REPLACE VIEW carte_grise_grille_taux_actuelle AS
	select demande_carte_grise_vue_resumee.id_demandeur,demande_carte_grise_vue_resumee.login_demandeur,demande_carte_grise_vue_resumee.puissance, demande_carte_grise_grille.taux
	from (select id_demandeur, max(id) as id_demande from demande_carte_grise_vue_resumee where demande_carte_grise_vue_resumee.id_status = 3 group by demande_carte_grise_vue_resumee.id_demandeur) as dernières_cartes_grises_actives
		inner join demande_carte_grise_vue_resumee on demande_carte_grise_vue_resumee.id = dernières_cartes_grises_actives.id_demande
		inner join demande_carte_grise_grille on demande_carte_grise_grille.puissance = demande_carte_grise_vue_resumee.puissance;




--
--
-- INSERT INTO `demande_carte_grise_identification` (id, intitule) VALUES
-- 	(1, "Demande ari_test"),
-- 	(2, "Demande ech_test"),
-- 	(3, "Demande llu_test"),
-- 	(4, "Nouvelle demande ari_test"),
-- 	(5, "Nouvelle demande ari_test 2");
--
-- INSERT INTO `demande_carte_grise_historique` (id,id_demandeur,login_demandeur,date_obtention,puissance,precisions,justificatif) VALUES
-- 	(1,510,'arizk','2018-10-08',8,"Bonjour J'ai ma carte grise","/var/data/home/informed/www/_files/notes_de_carte_grise/carte_grise_ari_v1"),
-- 	(2,510,'arizk','2018-10-08',13,"Bonjour J'ai ma carte grise","/var/data/home/informed/www/_files/notes_de_carte_grise/carte_grise_ari_v2"),
-- 	(3,509,'echallita','2018-10-09', 21,"Bonjour J'ai ma carte grise","/var/data/home/informed/www/_files/notes_de_carte_grise/carte_grise_ech"),
-- 	(4,651,'lluneau','2018-10-10', 34,"Bonjour J'ai ma carte grise truquée", "/var/data/home/informed/www/_files/notes_de_carte_grise/carte_grise_llu"),
-- 	(5,510,'arizk','2018-10-11',34,"Bonjour J'ai ma nouvelle carte grise","/var/data/home/informed/www/_files/notes_de_carte_grise/carte_grise_ari_v3"),
-- 	(6,510,'arizk','2018-10-12',21,"Bonjour J'ai ma nouvelle carte  grise pour ma nouvelle voiture","/var/data/home/informed/www/_files/notes_de_carte_grise/carte_grise_ari_v4");
--
--
-- INSERT INTO `demande_carte_grise_suivi` (id,id_demande_carte_grise,id_status,id_historique,id_utilisateur,login_utilisateur,notes) VALUES
-- 	(1, 1, 1 , 1, 510,'arizk',''),
-- 	(2, 1, 2 , 1, 686,'bderville',"Justificatif illisible, nouveau justificatif demandé par mail"),
-- 	(3, 1, 3 , 2, 686,'bderville',""),
-- 	(4, 2, 1 , 3, 509,'echallita',''),
-- 	(5, 2, 3 , 3, 686,'bderville',""),
-- 	(6, 3, 1 , 4, 651,'lluneau',''),
-- 	(7, 3, 4 , 4, 686,'bderville',""),
-- 	(8, 4, 1 , 5, 510,'arizk',''),
-- 	(9, 4, 3 , 5, 686,'bderville',""),
-- 	(10, 5, 1 , 6, 510,'arizk',''),
-- 	(11, 5, 4 , 6, 686,'bderville',"");
--
--
--
