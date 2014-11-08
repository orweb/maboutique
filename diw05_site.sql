-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 06 Août 2014 à 11:50
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `diw05_site`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id_article` int(5) NOT NULL AUTO_INCREMENT,
  `reference` int(15) NOT NULL,
  `categorie` varchar(70) NOT NULL,
  `titre` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `couleur` varchar(10) NOT NULL,
  `taille` varchar(2) NOT NULL,
  `sexe` enum('m','f') NOT NULL,
  `photo` varchar(250) NOT NULL,
  `prix` double(7,2) NOT NULL,
  `stock` int(4) NOT NULL,
  PRIMARY KEY (`id_article`),
  UNIQUE KEY `reference` (`reference`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Contenu de la table `article`
--

INSERT INTO `article` (`id_article`, `reference`, `categorie`, `titre`, `description`, `couleur`, `taille`, `sexe`, `photo`, `prix`, `stock`) VALUES
(12, 1, 'robe', 'robe 1 ', 'robe 1 robe 1 robe 1 robe 1 robe 1 ', 'bleu', 'M', 'f', '/projects/part3/maboutique/photos/1-robe.jpg', 45.00, 6),
(13, 2, 'chemise', 'chemise 1 ', 'chemise 1 chemise 1 chemise 1 chemise 1 ', 'rouge', 'XL', 'm', '/projects/part3/maboutique/photos/2-chemise.png', 45.00, 8),
(14, 3, 'chaussettes', 'chaussettes', 'chaussettes 1 chaussettes 1 chaussettes 1 chaussettes 1 ', 'blanc', 'L', 'f', '/projects/part3/maboutique/photos/3-chaussettes.jpg', 64.00, 8),
(15, 4, 'robe', 'robe 2 ', 'robe 2 robe 2 robe 2 robe 2 ', 'vert', 'M', 'f', '/projects/part3/maboutique/photos/4-robe.jpg', 5.00, 97),
(16, 5, 'pantalon', 'pantalon 1 ', 'pantalon 1 pantalon 1 pantalon 1 pantalon 1 pantalon 1 ', 'noir', 'S', 'm', '/projects/part3/maboutique/photos/5-pantalon.jpeg', 45.00, 7),
(17, 6, 'manteau', 'manteau 1 ', 'manteau 1 manteau 1 manteau 1 manteau 1 manteau 1 manteau 1 ', 'gris', 'L', 'm', '/projects/part3/maboutique/photos/6-manteau.jpg', 4.00, 46),
(18, 7, 'pantalon', 'pantalon 2 ', 'pantalon 2 pantalon 2 pantalon 2 pantalon 2 pantalon 2 ', 'rose', 'L', 'm', '/projects/part3/maboutique/photos/7-pantalon.jpeg', 49.00, 3),
(19, 8, 'jupe', 'jupe 1 ', 'jupe 1 jupe 1 jupe 1 jupe 1 jupe 1 jupe 1 jupe 1 ', 'noir', 'XL', 'f', '/projects/part3/maboutique/photos/8-jupe.png', 65.00, 97),
(20, 9, 'chapeau', 'chapeau 1 ', 'chapeau 1 chapeau 1 chapeau 1 chapeau 1 chapeau 1 ', 'rose', 'M', 'f', '/projects/part3/maboutique/photos/9--11-chapeau.jpg', 64.00, 8),
(21, 10, 'jupe', 'jupe 2 ', 'jupe 2 jupe 2 jupe 2 jupe 2 jupe 2 jupe 2 jupe 2 ', 'gris', 'M', 'f', '/projects/part3/maboutique/photos/10-jupe.png', 45.00, 47),
(22, 11, 'chapeau', 'chapeau 2', 'chapeau 2 chapeau 2 chapeau 2 chapeau 2 chapeau 2 ', 'noir', 'L', 'm', '/projects/part3/maboutique/photos/11-chapeau.jpg', 68.00, 20);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int(6) NOT NULL AUTO_INCREMENT,
  `id_membre` int(5) DEFAULT NULL,
  `montant` double(7,2) NOT NULL,
  `date` datetime NOT NULL,
  `etat` enum('en cours de traitement','envoyé','livré') NOT NULL DEFAULT 'en cours de traitement',
  PRIMARY KEY (`id_commande`),
  KEY `id_membre` (`id_membre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `details_commande`
--

CREATE TABLE IF NOT EXISTS `details_commande` (
  `id_details_commande` int(5) NOT NULL AUTO_INCREMENT,
  `id_commande` int(6) NOT NULL,
  `id_article` int(5) DEFAULT NULL,
  `quantite` int(4) NOT NULL,
  `prix` double(7,2) NOT NULL,
  PRIMARY KEY (`id_details_commande`),
  KEY `id_article` (`id_article`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int(5) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(15) NOT NULL,
  `mdp` varchar(32) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `sexe` enum('m','f') NOT NULL,
  `ville` varchar(20) NOT NULL,
  `cp` int(5) unsigned zerofill NOT NULL,
  `adresse` text NOT NULL,
  `statut` int(1) NOT NULL,
  PRIMARY KEY (`id_membre`),
  UNIQUE KEY `pseudo` (`pseudo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `sexe`, `ville`, `cp`, `adresse`, `statut`) VALUES
(1, 'test', 'test', 'test', 'test', 'test@site.fr', 'm', 'test', 00092, 'test', 0),
(2, 'admin', 'admin', 'admin', 'admin', 'admin@site.fr', 'm', 'admin', 00075, 'admin', 1),
(3, 'azerty', 'azerty', 'azerty', 'azerty', 'azerty@azerty', 'f', 'azerty', 12345, 'azertyazertyazerty', 0),
(5, 'azertyuiop', 'azertyuiop', 'azert&quot;yuiop', 'azertyuiop', 'azertyuiop@azertyuio', 'f', 'azertyuiop', 65412, 'azertyuiopa&#039;zertyuiop', 0),
(6, 'azty1245iop', 'azeuiop', 'uiop', 'aze', 'uiop@azertyu', 'm', 'azertyuiop', 12512, 'uiop&#039;zertyuiop', 0),
(10, 'test2', 'azeaze', 'test', 'test', 'test@test', 'f', 'test', 69875, 'testtesttest', 0),
(11, 'gfsgfsgfs', 'testggs', '', '', '', 'm', '', 00000, '', 0);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD CONSTRAINT `details_commande_ibfk_1` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
