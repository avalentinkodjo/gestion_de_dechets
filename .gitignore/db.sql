-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 21 mai 2025 à 12:27
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_dechets`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `citoyen_id` int NOT NULL,
  `collecteur_id` int NOT NULL,
  `demande_id` int NOT NULL,
  `note` int DEFAULT NULL,
  `commentaire` text,
  `date_avis` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `citoyen_id` (`citoyen_id`),
  KEY `collecteur_id` (`collecteur_id`),
  KEY `demande_id` (`demande_id`)
) ;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id`, `citoyen_id`, `collecteur_id`, `demande_id`, `note`, `commentaire`, `date_avis`) VALUES
(1, 2, 6, 1, 4, 'Le travaille est excellent', '2025-03-30 21:29:44');

-- --------------------------------------------------------

--
-- Structure de la table `collecteurs`
--

DROP TABLE IF EXISTS `collecteurs`;
CREATE TABLE IF NOT EXISTS `collecteurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entreprise_id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `statut` enum('disponible','occupé') DEFAULT 'disponible',
  `adresse` varchar(255) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `notifications` text,
  `collecteur_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `entreprise_id` (`entreprise_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `collecteurs`
--

INSERT INTO `collecteurs` (`id`, `entreprise_id`, `nom`, `telephone`, `email`, `statut`, `adresse`, `ville`, `photo`, `notifications`, `collecteur_id`) VALUES
(6, 3, 'AYAME', '70 24 89 45', 'iz@gmail.com', 'disponible', 'Amoutiévé', 'Lomé', 'Portrait d\'un vrai homme africain noir sans expression pour la photo d\'identité ou de passeport _ Photo Premium.jpg', 'Nouvelle collecte assignée : Ramco - organique à 2025-03-28 19:12:00', 6),
(7, 3, 'ADELAN Komi', '92 60 49 12', 'komi9@gmail.com', 'disponible', 'Gbadago', 'Lomé', 'télécharger (9).jpg', 'Vous êtes recruté par l\'entreprise : Teck - Teck@gmail.com', 5),
(8, 3, 'Drakovic AMOUSSOU', '93569017', 'valentinamoussou962@gmail.com', 'occupé', 'Elavagnon', 'Lomé', '', 'Nouvelle collecte assignée : Amoutiévé - plastique à 2025-03-31 14:11:00', 8),
(13, 3, 'FAVI Bernard', '98 57 46 90', 'be@gmail.com', 'disponible', 'Gbadago', 'Lomé', '0b40ee93-edad-4870-bb55-51e6521f1dda.jpg', 'Nouvelle collecte assignée : Zongo - organique à 2025-03-28 20:04:00', 7),
(15, 14, 'FOTSO', '97569043', 'fos@gmail.com', 'disponible', 'Adjidogomé', 'Lomé', 'télécharger (11).jpg', 'Vous êtes recruté par l\'entreprise : CleanBien - cle@gmail.com', 13);

-- --------------------------------------------------------

--
-- Structure de la table `demandes`
--

DROP TABLE IF EXISTS `demandes`;
CREATE TABLE IF NOT EXISTS `demandes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `localisation` varchar(255) DEFAULT NULL,
  `type_dechet` enum('plastique','metal','organique','autre') DEFAULT NULL,
  `date_heure` datetime DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `statut` enum('en attente','en cours','en attente_validation','validée','annulée') DEFAULT 'en attente',
  `date_demande` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `collecteur_id` int DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `confirmation_collecteur` tinyint(1) DEFAULT '0',
  `preuve_photo` varchar(255) DEFAULT NULL,
  `client_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `fk_collecteur` (`collecteur_id`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `demandes`
--

INSERT INTO `demandes` (`id`, `user_id`, `localisation`, `type_dechet`, `date_heure`, `photo`, `statut`, `date_demande`, `collecteur_id`, `latitude`, `longitude`, `confirmation_collecteur`, `preuve_photo`, `client_id`) VALUES
(1, 3, 'Tokoin Elavagnon', 'plastique', '2025-03-27 18:59:00', '1742757112_Les dérives du continent plastique.jpg', 'validée', '2025-03-23 19:11:52', 6, NULL, NULL, 1, '1743115796_Kenya roads_.jpg', 2),
(2, 3, 'Ramco', 'organique', '2025-03-28 19:12:00', NULL, 'validée', '2025-03-23 19:13:02', 6, NULL, NULL, 1, '1746489737_Traditional village street _ Premium AI-generated image.jpg', 2),
(3, 3, 'Amoutiévé', 'metal', '2025-03-28 20:04:00', '1742760310_Metal Recycling_ How to Recycle Metal and its Importance - Conserve Energy Future.jpg', 'validée', '2025-03-23 20:05:10', 7, NULL, NULL, 1, '1746489318_télécharger (12).jpg', 2),
(4, 3, 'Zongo', 'organique', '2025-03-28 20:04:00', NULL, 'validée', '2025-03-23 20:14:31', 13, NULL, NULL, 1, '1746489899_Beauty of village life.jpg', 2),
(5, 3, 'lomé', 'plastique', '2025-03-12 23:30:00', NULL, 'validée', '2025-03-23 23:30:54', 8, 6.146070, 1.226006, 1, '1743116685_Diebougou, Burkina Faso in West Africa.jpg', 2),
(7, 3, 'Amoutiévé', 'plastique', '2025-03-31 14:11:00', NULL, 'en cours', '2025-03-30 21:09:20', 8, 0.000000, 0.000000, 0, NULL, 2),
(8, 3, 'Tokoin Elavagnon', 'metal', '2025-03-19 12:30:00', NULL, 'en cours', '2025-03-30 21:24:25', NULL, 6.146554, 1.225712, 0, NULL, 2),
(9, 0, 'Lomé', 'metal', '2025-05-31 07:19:00', '1745997591_Les dérives du continent plastique.jpg', 'en attente', '2025-04-30 07:19:51', NULL, 0.000000, 0.000000, 0, NULL, 2),
(10, 14, 'Lomé', 'plastique', '2025-05-06 23:00:00', '1746399712_Les dérives du continent plastique.jpg', 'en cours', '2025-05-04 23:01:52', 15, 6.144956, 1.226289, 0, NULL, 2),
(11, 0, 'Lomé', 'metal', '2025-05-15 23:38:00', '1746401932_Throwaway culture_ The truth about recycling.jpg', 'en attente', '2025-05-04 23:38:52', NULL, 6.146137, 1.226225, 0, NULL, 2),
(12, 0, 'to', 'metal', '2025-05-06 23:45:00', '1746402347_Throwaway culture_ The truth about recycling.jpg', 'en attente', '2025-05-04 23:45:47', NULL, 0.000000, 0.000000, 0, NULL, 2),
(13, 3, 'Lomé', 'metal', '2025-05-06 16:24:00', '1746462286_Throwaway culture_ The truth about recycling.jpg', 'en attente', '2025-05-05 16:24:46', NULL, 6.151022, 1.237174, 0, NULL, 2);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `expediteur_id` int NOT NULL,
  `destinataire_id` int NOT NULL,
  `contenu` text NOT NULL,
  `date_envoi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('non_lu','lu') DEFAULT 'non_lu',
  `message_parent_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expediteur_id` (`expediteur_id`),
  KEY `destinataire_id` (`destinataire_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `expediteur_id`, `destinataire_id`, `contenu`, `date_envoi`, `statut`, `message_parent_id`) VALUES
(1, 2, 3, 'J&#039;ai besoin de votre service de collecte', '2025-03-29 21:19:59', 'lu', NULL),
(2, 3, 3, 'salue', '2025-03-29 21:37:03', 'lu', NULL),
(3, 3, 3, 'Bonjour', '2025-03-29 21:45:25', 'lu', NULL),
(4, 3, 2, 'dfh', '2025-03-29 22:55:57', 'lu', 1),
(5, 3, 2, 'Tu vas bien', '2025-03-29 23:40:02', 'lu', 1),
(6, 2, 2, 'Hello', '2025-05-04 23:40:33', 'lu', 1),
(7, 2, 2, 'J&#039;ais besoin que vous venez ramasser les ordures', '2025-05-04 23:51:01', 'lu', 1),
(8, 2, 2, 'Si possible, venez vite', '2025-05-04 23:53:20', 'lu', 1),
(9, 2, 2, 'Merci!', '2025-05-04 23:56:25', 'lu', 1),
(10, 2, 2, '????????', '2025-05-21 12:08:15', 'lu', 1);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `destinataire_role` varchar(50) NOT NULL,
  `statut` enum('non_lu','lu') DEFAULT 'non_lu',
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_vue` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `type`, `destinataire_role`, `statut`, `date`, `date_vue`) VALUES
(5, 'Vous avez reçu un nouveau message.', 'message', '3', 'non_lu', '2025-03-29 21:22:06', NULL),
(4, 'Vous avez reçu un nouveau message.', 'message', '3', 'non_lu', '2025-03-29 21:20:50', NULL),
(15, 'Vous avez un nouveau message de Valentin Kodjo AMOUSSOU', 'message', '2', 'non_lu', '2025-05-21 12:08:15', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `profil_collecteurs`
--

DROP TABLE IF EXISTS `profil_collecteurs`;
CREATE TABLE IF NOT EXISTS `profil_collecteurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT 'default.jpg',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('pas recruté','déjà recruté') DEFAULT 'pas recruté',
  `user_id` int NOT NULL,
  `carte_identite` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `telephone` (`telephone`),
  UNIQUE KEY `email` (`email`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `profil_collecteurs`
--

INSERT INTO `profil_collecteurs` (`id`, `nom`, `telephone`, `email`, `adresse`, `ville`, `photo`, `date_creation`, `statut`, `user_id`, `carte_identite`) VALUES
(4, 'AYAME', '70 24 89 45', 'iz@gmail.com', 'Amoutiévé', 'Lomé', 'Portrait d\'un vrai homme africain noir sans expression pour la photo d\'identité ou de passeport _ Photo Premium.jpg', '2025-03-26 22:01:56', 'déjà recruté', 6, NULL),
(7, 'ADELAN Komi', '92604912', 'komi9@gmail.com', 'Gbadago 3', 'Lomé', '', '2025-05-05 01:33:45', 'déjà recruté', 5, ''),
(5, 'FAVI Bernard', '98 57 46 90', 'be@gmail.com', 'Gbadago', 'Lomé', '0b40ee93-edad-4870-bb55-51e6521f1dda.jpg', '2025-03-26 22:06:34', 'déjà recruté', 7, NULL),
(6, 'Drakovic AMOUSSOU', '93569017', 'valentinamoussou962@gmail.com', 'Elavagnon', 'Lomé', '', '2025-03-27 10:22:04', 'déjà recruté', 8, NULL),
(12, 'ADEY', '90453784', 'ad@gmail.com', 'Amoutiévé', 'Lomé', 'Portrait complet d\'un jeune homme noir souriant d\'une cinquantaine d\'années en tenue décontractée _ Image Premium générée à base d’IA.jpg', '2025-05-05 19:48:39', 'déjà recruté', 9, 'Carte Scolaire.jpg'),
(13, 'FOTSO', '97569043', 'fos@gmail.com', 'Adjidogomé', 'Lomé', 'télécharger (11).jpg', '2025-05-05 21:01:08', 'déjà recruté', 13, 'télécharger (10).jpg');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `role` enum('citoyen','collecteur','entreprise','gestionnaire','admin') NOT NULL,
  `date_inscription` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `role`, `date_inscription`) VALUES
(2, 'Valentin Kodjo AMOUSSOU', 'valamoussou14@gmail.com', '$2y$10$raDgYQWsAJI5mThifj6HPe3SRj1dVHQgsCOwY2EQ9Z.qkII2jAvUW', 'citoyen', '2025-03-22 22:26:54'),
(3, 'Teck', 'Teck@gmail.com', '$2y$10$2w6Dz2S.hg0cKIlqf42qF.gIlRsZtI8vwEc5dXd1VOKLpAgup1uD6', 'entreprise', '2025-03-23 18:50:51'),
(4, 'Alphonse AMOUSSOU', 'aalphonse1260@gmail.com', '$2y$10$nxoTZuuFnaz0nlaxJP2lpOW.XGr8k2ULPI2qc.XJ0HlYyoFj8XKUm', 'gestionnaire', '2025-03-24 19:29:57'),
(5, 'ADELAN Komi', 'komi9@gmail.com', '$2y$10$Jp6GaSbqysVZL8vchZHSiuP4cTOVVuYvnWIHJutjSc9/qLg6fECae', 'collecteur', '2025-03-24 21:12:32'),
(6, 'AYAME', 'iz@gmail.com', '$2y$10$yo3XgatjkTIRcbtPH.ElZOpxNX.sai9qikBf1sTQkU8OoFELR1vtG', 'collecteur', '2025-03-26 21:56:48'),
(7, 'FAVI Bernard', 'be@gmail.com', '$2y$10$0iBqqQ2SnRzELYeyMhRODuzQL1DVT2yFe4kAVlxfFQcewOFU3tXgO', 'collecteur', '2025-03-26 22:05:13'),
(8, 'Drakovic AMOUSSOU', 'valentinamoussou962@gmail.com', '$2y$10$LTZL7X3.vU3.LuqoUcCXX.Yf19P/dEgSpHzpXgjVua7Ji0Gnn0/vq', 'collecteur', '2025-03-27 10:21:24'),
(9, 'ADEYEMON', 'ad@gmail.com', '$2y$10$AnQNdlW/OE4SFB/fy10Z/usHeFEc5bif5ZYZlxp6g3FSjZpsOuOvm', 'collecteur', '2025-05-05 02:02:41'),
(10, 'AMENOUGLO', 'gil@gmail.com', '$2y$10$i0AhsPmISP7YB/86FuOSMujP03ayCyWULETw8JtcWYo3eLmBKohmS', 'citoyen', '2025-05-05 03:25:35'),
(12, 'Teck', 'telc@gmail.com', '$2y$10$PYNBtAR4P/p0soRBD7zKi.GAiRAaco9SloncwF78nH3rUQJ5RniD.', 'entreprise', '2025-05-05 16:26:00'),
(13, 'FOTSO Jordi', 'fos@gmail.com', '$2y$10$WGfVO9VbmzteTEX4C4EcrO./Kk9kyaUTWKH488.9NAeCRwxjZ0Acy', 'collecteur', '2025-05-05 20:52:52'),
(14, 'CleanBien', 'cle@gmail.com', '$2y$10$/01cIJz6Ccwp9BJA3z3nfOL6HD/8ystMs1ho/kkSg5CvPI2tIyAEy', 'entreprise', '2025-05-05 21:02:45'),
(16, 'Valentin Kodjo AMOUSSOU', 'val14@gmail.com', '$2y$10$NDDPEcwRF56Bo/G8L6a.L.vjyl57BBv9ygLjqkzlUrTPvTHgFn4OC', 'admin', '2025-05-08 01:59:08');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
