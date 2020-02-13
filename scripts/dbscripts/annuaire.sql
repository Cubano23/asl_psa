-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Lun 19 Novembre 2018 à 12:27
-- Version du serveur :  5.7.24-0ubuntu0.18.04.1
-- Version de PHP :  5.6.38-1+ubuntu18.04.1+deb.sury.org+2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `annuaire`
--
CREATE DATABASE IF NOT EXISTS `annuaire` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `annuaire`;

-- --------------------------------------------------------

--
-- Structure de la table `allowedcabinets`
--

CREATE TABLE `allowedcabinets` (
  `id` int(11) NOT NULL,
  `login` varchar(65) NOT NULL,
  `cabinet` varchar(65) NOT NULL,
  `recordstatus` int(11) NOT NULL DEFAULT '0',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
-- Structure de la table `habilitations`
--

CREATE TABLE `habilitations` (
  `login` varchar(65) NOT NULL,
  `pass` varchar(65) NOT NULL,
  `psa` int(11) NOT NULL DEFAULT '0',
  `psae` int(11) NOT NULL DEFAULT '1',
  `psaet` int(11) NOT NULL DEFAULT '0',
  `psv` int(11) NOT NULL DEFAULT '0',
  `psvae` int(11) NOT NULL DEFAULT '0',
  `psar` int(11) NOT NULL DEFAULT '0',
  `admin` int(11) NOT NULL DEFAULT '0',
  `erp` int(11) NOT NULL DEFAULT '0',
  `psamed` int(11) NOT NULL DEFAULT '0',
  `idcps` varchar(64) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `recordstatus` int(11) NOT NULL DEFAULT '0',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `historique_allowedcabinets`
--

CREATE TABLE `historique_allowedcabinets` (
  `id` int(11) NOT NULL,
  `login` varchar(65) NOT NULL,
  `cabinet` varchar(65) NOT NULL,
  `actualstatus` int(11) NOT NULL DEFAULT '0',
  `dstatus` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `hpasswords`
--

CREATE TABLE `hpasswords` (
  `login` varchar(65) NOT NULL,
  `hpassword` varchar(128) NOT NULL,
  `dcreat` datetime NOT NULL,
  `hpassword2` varchar(128) NOT NULL,
  `dcreat2` datetime NOT NULL,
  `hpassword3` varchar(128) NOT NULL,
  `dcreat3` datetime NOT NULL,
  `salt` varchar(64) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `identifications`
--

CREATE TABLE `identifications` (
  `id` int(11) NOT NULL,
  `login` varchar(65) NOT NULL,
  `nom` varchar(65) NOT NULL,
  `prenom` varchar(65) NOT NULL,
  `telephone` varchar(32) NOT NULL,
  `email` varchar(65) NOT NULL,
  `profession` varchar(65) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL DEFAULT 'Infirmière',
  `photo` blob,
  `departement` int(11) NOT NULL DEFAULT '0',
  `rfu1` int(11) NOT NULL DEFAULT '0',
  `rfu2` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `adeli` varchar(9) NOT NULL,
  `rpps` varchar(11) NOT NULL,
  `recordstatus` int(11) NOT NULL DEFAULT '0',
  `dmaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email2` varchar(65) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `allowedcabinets`
--
ALTER TABLE `allowedcabinets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_key` (`login`,`cabinet`);

--
-- Index pour la table `habilitations`
--
ALTER TABLE `habilitations`
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `login_2` (`login`);

--
-- Index pour la table `historique_allowedcabinets`
--
ALTER TABLE `historique_allowedcabinets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `hpasswords`
--
ALTER TABLE `hpasswords`
  ADD KEY `login` (`login`);

--
-- Index pour la table `identifications`
--
ALTER TABLE `identifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `allowedcabinets`
--
ALTER TABLE `allowedcabinets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2845;
--
-- AUTO_INCREMENT pour la table `historique_allowedcabinets`
--
ALTER TABLE `historique_allowedcabinets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3255;
--
-- AUTO_INCREMENT pour la table `identifications`
--
ALTER TABLE `identifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=805;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
