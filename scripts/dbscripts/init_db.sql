--
-- Contenu de la table `identifications`
--

INSERT INTO `annuaire`.`identifications` (`id`, `login`, `nom`, `prenom`, `telephone`, `email`, `profession`, `photo`, `departement`, `rfu1`, `rfu2`, `status`, `type`, `adeli`, `rpps`, `recordstatus`, `dmaj`, `email2`) VALUES
(1, 'ztest', 'Derville', 'Amaury', '0610178031', 'aderville@asalee.fr', 'Infirmière', NULL, 0, 0, 0, 0, 2, '', '', 0, '2015-04-24 09:23:50', ''),
(510, 'arizk', 'Rizk', 'Antoine', '06 10 95 59 81', 'antoine.rizk@gisgo.fr', 'gestionnaire', NULL, 0, 0, 0, 0, 2, '', '', 0, '2018-11-09 19:24:56', '');



--
-- Contenu de la table `allowedcabinets`
--

INSERT INTO `annuaire`.`allowedcabinets` (`id`, `login`, `cabinet`, `recordstatus`, `dmaj`) VALUES
(980, 'arizk', 'zTest', 0, '2017-04-05 16:49:53');



--
-- Contenu de la table `habilitations`
--

INSERT INTO `annuaire`.`habilitations` (`login`, `pass`, `psa`, `psae`, `psaet`, `psv`, `psvae`, `psar`, `admin`, `erp`, `psamed`, `idcps`, `recordstatus`, `dmaj`) VALUES
('arizk', '', 2, 2, 1, 2, 2, 2, 0, 2, 1, '', 0, '2018-08-03 17:31:38');



--
-- Contenu de la table `account`
--

INSERT INTO `informed3`.`account` (`id`, `cabinet`, `password`, `nom_complet`, `ville`, `contact`, `telephone`, `courriel`, `total_pat`, `total_sein`, `total_cogni`, `total_colon`, `total_uterus`, `total_diab2`, `total_HTA`, `infirmiere`, `nom_cab`, `portable`, `code_postal`, `adresseCabinet`, `region`, `logiciel`, `log_ope`, `recordstatus`, `dmaj`, `tdb_export`) VALUES
(334, 'zTest', '', 'Cabinet fictif pour équipe support', 'VilleTest', 'Equipe support ASALEE', '', '', 20800, 3900, 3120, 7800, 4680, 1300, 3900, '', 'ztest', '', '75001', NULL, 'Ile-de-France', 'mediclic5', 1, 0, '2018-11-06 09:53:48', '2016-01-01');



--
-- Contenu de la table `dossier`
--

INSERT INTO `informed3`.`dossier` (`id`, `cabinet`, `numero`, `dnaiss`, `sexe`, `taille`, `actif`, `dconsentement`, `dcreat`, `dmaj`, `encnir`, `enckey`) VALUES
(77, 'zTest', '01', '1966-05-05', 'F', 169, 'oui', '2015-07-26', '2004-06-07', '2004-06-07 11:29:13', '', 0),
(283, 'zTest', '1', '1974-11-29', 'M', 178, 'oui', '2015-07-26', '2004-07-21', '2018-02-27 15:21:35', '', 0);
