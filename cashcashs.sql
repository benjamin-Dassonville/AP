-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 08 déc. 2025 à 10:01
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cashcashs`
--

-- --------------------------------------------------------

--
-- Structure de la table `Agence`
--

CREATE TABLE `Agence` (
  `Numero_Agence` int(11) NOT NULL,
  `Nom_agence` varchar(255) NOT NULL,
  `Adresse_agence` text DEFAULT NULL,
  `Telephone_Agence` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Agence`
--

INSERT INTO `Agence` (`Numero_Agence`, `Nom_agence`, `Adresse_agence`, `Telephone_Agence`) VALUES
(1, 'CashCash Paris', '15 Avenue des Champs-u00c9lysu00e9es, 75008 Paris', '01 45 67 89 01'),
(2, 'CashCash Lyon', '45 Rue de la Ru00e9publique, 69002 Lyon', '04 78 90 12 34'),
(3, 'CashCash Marseille', '30 La Canebiu00e8re, 13001 Marseille', '04 91 23 45 67'),
(4, 'CashCash Toulouse', '12 Place du Capitole, 31000 Toulouse', '05 61 34 56 78'),
(5, 'CashCash Lille', '8 Rue Faidherbe, 59000 Lille', '03 20 45 67 89'),
(6, 'CashCash Paris', '15 Avenue des Champs-Élysées, 75008 Paris', '01 45 67 89 01'),
(7, 'CashCash Lyon', '45 Rue de la République, 69002 Lyon', '04 78 90 12 34'),
(8, 'CashCash Marseille', '30 La Canebière, 13001 Marseille', '04 91 23 45 67'),
(9, 'CashCash Toulouse', '12 Place du Capitole, 31000 Toulouse', '05 61 34 56 78'),
(10, 'CashCash Lille', '8 Rue Faidherbe, 59000 Lille', '03 20 45 67 89'),
(11, 'CashCash Bordeaux', '22 Rue Sainte-Catherine, 33000 Bordeaux', '05 56 78 90 12'),
(12, 'CashCash Nantes', '18 Cours Cambronne, 44000 Nantes', '02 40 12 34 56'),
(13, 'CashCash Strasbourg', '10 Place Kléber, 67000 Strasbourg', '03 88 23 45 67');

-- --------------------------------------------------------

--
-- Structure de la table `Client`
--

CREATE TABLE `Client` (
  `Numero_Client` int(11) NOT NULL,
  `Raison_Sociale` varchar(255) NOT NULL,
  `Siren` varchar(14) DEFAULT NULL,
  `Code_Ape` varchar(10) DEFAULT NULL,
  `Adresse` text DEFAULT NULL,
  `Telephone_Client` varchar(20) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Duree_Deplacement` int(11) DEFAULT NULL,
  `Distance_KM` decimal(6,2) DEFAULT NULL,
  `Numero_Agence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Client`
--

INSERT INTO `Client` (`Numero_Client`, `Raison_Sociale`, `Siren`, `Code_Ape`, `Adresse`, `Telephone_Client`, `Email`, `Duree_Deplacement`, `Distance_KM`, `Numero_Agence`) VALUES
(1, 'TechSolutions SARL', '123456789', '6202A', '25 Rue du Commerce, 75015 Paris', '01 42 33 44 55', 'contact@techsolutions.fr', 30, 12.50, 1),
(2, 'InnovaTech SAS', '987654321', '6201Z', '78 Avenue de la Libertu00e9, 69003 Lyon', '04 72 11 22 33', 'info@innovatech.fr', 45, 18.30, 2),
(3, 'Digital Partners', '456789123', '6202B', '12 Boulevard Longchamp, 13001 Marseille', '04 91 55 66 77', 'hello@digitalpartners.fr', 25, 9.80, 3),
(4, 'WebServices Pro', '789123456', '6312Z', '33 Rue Alsace-Lorraine, 31000 Toulouse', '05 61 88 99 00', 'contact@webservices.fr', 35, 14.20, 4),
(5, 'DataCloud EURL', '321654987', '6311Z', '56 Rue Nationale, 59000 Lille', '03 20 77 88 99', 'support@datacloud.fr', 40, 16.70, 5),
(6, 'SmartBusiness SA', '147258369', '6209Z', '88 Avenue Victor Hugo, 75016 Paris', '01 47 22 33 44', 'contact@smartbusiness.fr', 20, 8.50, 1),
(7, 'SecureIT Solutions', '258369147', '6202A', '15 Quai Saint-Antoine, 69002 Lyon', '04 78 44 55 66', 'info@secureit.fr', 30, 11.20, 2),
(8, 'CloudFirst SARL', '369147258', '6311Z', '42 Rue Paradis, 13006 Marseille', '04 91 66 77 88', 'contact@cloudfirst.fr', 50, 20.10, 3),
(9, 'TechSolutions SARL', '123456789', '6202A', '25 Rue du Commerce, 75015 Paris', '01 42 33 44 55', 'contact@techsolutions.fr', 30, 12.50, 1),
(10, 'InnovaTech SAS', '987654321', '6201Z', '78 Avenue de la Liberté, 69003 Lyon', '04 72 11 22 33', 'info@innovatech.fr', 45, 18.30, 2),
(11, 'Digital Partners', '456789123', '6202B', '12 Boulevard Longchamp, 13001 Marseille', '04 91 55 66 77', 'hello@digitalpartners.fr', 25, 9.80, 3),
(12, 'WebServices Pro', '789123456', '6312Z', '33 Rue Alsace-Lorraine, 31000 Toulouse', '05 61 88 99 00', 'contact@webservices.fr', 35, 14.20, 4),
(13, 'DataCloud EURL', '321654987', '6311Z', '56 Rue Nationale, 59000 Lille', '03 20 77 88 99', 'support@datacloud.fr', 40, 16.70, 5),
(14, 'SmartBusiness SA', '147258369', '6209Z', '88 Avenue Victor Hugo, 75016 Paris', '01 47 22 33 44', 'contact@smartbusiness.fr', 20, 8.50, 1),
(15, 'SecureIT Solutions', '258369147', '6202A', '15 Quai Saint-Antoine, 69002 Lyon', '04 78 44 55 66', 'info@secureit.fr', 30, 11.20, 2),
(16, 'CloudFirst SARL', '369147258', '6311Z', '42 Rue Paradis, 13006 Marseille', '04 91 66 77 88', 'contact@cloudfirst.fr', 50, 20.10, 3),
(17, 'MediaTech Group', '741852963', '6312Z', '67 Avenue Jean Jaurès, 31000 Toulouse', '05 61 99 00 11', 'info@mediatech.fr', 28, 10.50, 4),
(18, 'InfoSystems Corp', '852963741', '6202A', '89 Rue du Molinel, 59800 Lille', '03 20 88 77 66', 'contact@infosystems.fr', 35, 15.30, 5),
(19, 'NetConsult SARL', '963741852', '6311Z', '45 Cours de l\'Intendance, 33000 Bordeaux', '05 56 11 22 33', 'hello@netconsult.fr', 25, 9.20, 6),
(20, 'DevPro Solutions', '159753486', '6201Z', '21 Rue Crébillon, 44000 Nantes', '02 40 33 44 55', 'contact@devpro.fr', 32, 13.80, 7),
(21, 'SysAdmin Plus', '357159486', '6202A', '12 Rue du Vieux Marché, 67000 Strasbourg', '03 88 44 55 66', 'info@sysadmin.fr', 22, 7.90, 8),
(22, 'TechnoServices', '486159357', '6311Z', '90 Rue de la Pompe, 75016 Paris', '01 44 55 66 77', 'tech@technoservices.fr', 18, 6.50, 1),
(23, 'CompuWorld SARL', '753951852', '6202B', '34 Rue Garibaldi, 69003 Lyon', '04 72 22 33 44', 'contact@compuworld.fr', 27, 10.10, 2),
(24, 'ITExperts SA', '159357486', '6312Z', '56 Boulevard Michelet, 13008 Marseille', '04 91 77 88 99', 'experts@itexperts.fr', 38, 17.40, 3),
(25, 'DataCenter Pro', '486753159', '6201Z', '23 Allées Jean Jaurès, 31000 Toulouse', '05 61 00 11 22', 'contact@datacenter.fr', 29, 11.60, 4),
(26, 'NetworkSolutions', '951357486', '6311Z', '78 Avenue du Peuple Belge, 59000 Lille', '03 20 99 00 11', 'info@networksolutions.fr', 33, 14.70, 5),
(27, 'ByteWorks SARL', '357486159', '6202A', '67 Rue Judaïque, 33000 Bordeaux', '05 56 22 33 44', 'contact@byteworks.fr', 24, 8.80, 6),
(28, 'CloudNet Services', '753486159', '6209Z', '89 Quai de la Fosse, 44000 Nantes', '02 40 44 55 66', 'hello@cloudnet.fr', 31, 12.90, 7);

-- --------------------------------------------------------

--
-- Structure de la table `Contrat_de_maintenance`
--

CREATE TABLE `Contrat_de_maintenance` (
  `Numero_de_Contrat` int(11) NOT NULL,
  `Date_signature` date NOT NULL,
  `Date_echeance` date NOT NULL,
  `Numero_Client` int(11) NOT NULL,
  `RefTypeContrat` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Contrat_de_maintenance`
--

INSERT INTO `Contrat_de_maintenance` (`Numero_de_Contrat`, `Date_signature`, `Date_echeance`, `Numero_Client`, `RefTypeContrat`) VALUES
(1, '2024-01-15', '2025-01-15', 1, 'GOLD'),
(2, '2024-03-20', '2025-03-20', 2, 'SILVER'),
(3, '2024-02-10', '2025-02-10', 3, 'PLATINUM'),
(4, '2024-05-05', '2025-05-05', 4, 'BRONZE'),
(5, '2024-06-12', '2025-06-12', 5, 'GOLD'),
(6, '2024-04-18', '2025-04-18', 6, 'SILVER'),
(7, '2024-07-22', '2025-07-22', 7, 'GOLD'),
(8, '2023-08-01', '2024-08-01', 8, 'BRONZE'),
(9, '2024-01-15', '2025-01-15', 1, 'GOLD'),
(10, '2024-03-20', '2025-03-20', 2, 'SILVER'),
(11, '2024-02-10', '2025-02-10', 3, 'PLATINUM'),
(12, '2024-05-05', '2025-05-05', 4, 'BRONZE'),
(13, '2024-06-12', '2025-06-12', 5, 'GOLD'),
(14, '2024-04-18', '2025-04-18', 6, 'SILVER'),
(15, '2024-07-22', '2025-07-22', 7, 'GOLD'),
(16, '2023-08-01', '2024-08-01', 8, 'BRONZE'),
(17, '2024-09-15', '2025-09-15', 9, 'SILVER'),
(18, '2024-10-20', '2025-10-20', 10, 'PLATINUM'),
(19, '2024-08-30', '2025-08-30', 11, 'GOLD'),
(20, '2024-11-05', '2025-11-05', 12, 'BRONZE'),
(21, '2024-01-25', '2025-01-25', 13, 'SILVER'),
(22, '2024-03-12', '2025-03-12', 14, 'GOLD'),
(23, '2024-06-18', '2025-06-18', 15, 'PLATINUM');

-- --------------------------------------------------------

--
-- Structure de la table `Controler`
--

CREATE TABLE `Controler` (
  `Numero_Intervent` int(11) NOT NULL,
  `Numero_de_Serie` varchar(50) NOT NULL,
  `Temps_Passe` int(11) DEFAULT NULL,
  `Commentaire` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Employe`
--

CREATE TABLE `Employe` (
  `Matricule` int(11) NOT NULL,
  `Nom_Employe` varchar(255) NOT NULL,
  `Prenom_Employe` varchar(255) DEFAULT NULL,
  `Adresse_Employe` text DEFAULT NULL,
  `Date_Embauche` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Employe`
--

INSERT INTO `Employe` (`Matricule`, `Nom_Employe`, `Prenom_Employe`, `Adresse_Employe`, `Date_Embauche`) VALUES
(1, 'Dupont', 'Marie', '10 Rue de la Paix, 75002 Paris', '2020-01-15'),
(2, 'Martin', 'Pierre', '22 Avenue Foch, 69006 Lyon', '2019-03-20'),
(3, 'Durand', 'Sophie', '5 Rue Saint-Ferru00e9ol, 13001 Marseille', '2021-06-10'),
(4, 'Bernard', 'Luc', '18 Rue de Metz, 31000 Toulouse', '2018-09-05'),
(5, 'Petit', 'Claire', '30 Rue de Bethune, 59000 Lille', '2022-02-14'),
(6, 'Dubois', 'Thomas', '45 Rue de Rivoli, 75001 Paris', '2020-07-22'),
(7, 'Leroy', 'Julie', '12 Cours Vitton, 69006 Lyon', '2019-11-30'),
(8, 'Moreau', 'Antoine', '8 Boulevard Michelet, 13008 Marseille', '2021-04-18'),
(9, 'Laurent', 'Isabelle', '25 Allu00e9es Jean Jauru00e8s, 31000 Toulouse', '2020-12-01'),
(10, 'Simon', 'David', '14 Rue Esquermoise, 59800 Lille', '2022-08-25'),
(11, 'Dupont', 'Marie', '10 Rue de la Paix, 75002 Paris', '2020-01-15'),
(12, 'Martin', 'Pierre', '22 Avenue Foch, 69006 Lyon', '2019-03-20'),
(13, 'Durand', 'Sophie', '5 Rue Saint-Ferréol, 13001 Marseille', '2021-06-10'),
(14, 'Bernard', 'Luc', '18 Rue de Metz, 31000 Toulouse', '2018-09-05'),
(15, 'Petit', 'Claire', '30 Rue de Bethune, 59000 Lille', '2022-02-14'),
(16, 'Dubois', 'Thomas', '45 Rue de Rivoli, 75001 Paris', '2020-07-22'),
(17, 'Leroy', 'Julie', '12 Cours Vitton, 69006 Lyon', '2019-11-30'),
(18, 'Moreau', 'Antoine', '8 Boulevard Michelet, 13008 Marseille', '2021-04-18'),
(19, 'Laurent', 'Isabelle', '25 Allées Jean Jaurès, 31000 Toulouse', '2020-12-01'),
(20, 'Simon', 'David', '14 Rue Esquermoise, 59800 Lille', '2022-08-25'),
(21, 'Lefebvre', 'Nicolas', '33 Cours de Verdun, 33000 Bordeaux', '2019-05-12'),
(22, 'Michel', 'Céline', '19 Rue de la Bastille, 44000 Nantes', '2021-09-08'),
(23, 'Garcia', 'Marc', '27 Rue du Dôme, 67000 Strasbourg', '2020-03-15'),
(24, 'Roux', 'Sandrine', '41 Avenue Montaigne, 75008 Paris', '2018-11-20'),
(25, 'Blanc', 'Julien', '16 Rue de la Charité, 69002 Lyon', '2022-01-10');

-- --------------------------------------------------------

--
-- Structure de la table `Fiche_Intervention`
--

CREATE TABLE `Fiche_Intervention` (
  `id` int(11) NOT NULL,
  `numero_intervention` varchar(50) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `statut` enum('en_attente','en_cours','terminee','annulee') DEFAULT 'en_attente',
  `priorite` enum('basse','normale','haute','urgente') DEFAULT 'normale',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_intervention` datetime NOT NULL,
  `date_cloture` datetime DEFAULT NULL,
  `technicien_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `equipement_id` varchar(50) DEFAULT NULL,
  `commentaire_interne` text DEFAULT NULL,
  `alerte_active` tinyint(1) DEFAULT 0,
  `alerte_message` varchar(500) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Fiche_Intervention`
--

INSERT INTO `Fiche_Intervention` (`id`, `numero_intervention`, `titre`, `description`, `statut`, `priorite`, `date_creation`, `date_intervention`, `date_cloture`, `technicien_id`, `client_id`, `equipement_id`, `commentaire_interne`, `alerte_active`, `alerte_message`, `created_by`, `updated_at`) VALUES
(1, 'INT-2025-3799', 'caca_toto', 'toto', 'en_cours', 'urgente', '2025-12-05 10:13:08', '2026-05-24 10:20:00', NULL, 6, 2, NULL, 'lalalalalala', 1, 'urgent bb', 4, '2025-12-05 11:05:40'),
(462, 'INT-2025-003', 'Installation PC', 'Nouveau poste', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-01-17 14:00:00', NULL, 4, 2, 'SN-DELL-2024-004', NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(463, 'INT-2025-004', 'Mise à jour serveur', 'Patch sécurité', 'terminee', 'haute', '2025-12-05 13:59:16', '2025-01-18 20:00:00', NULL, 5, 3, 'SN-IBM-2024-006', NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(464, 'INT-2025-005', 'Config réseau', 'VLAN setup', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-01-19 11:00:00', NULL, 6, 3, 'SN-CISCO-2024-007', NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(465, 'INT-2025-012', 'Changement toner', 'Remplacement', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-01-26 14:00:00', NULL, 13, 5, 'SN-HP-2024-011', NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(466, 'INT-2025-015', 'Patch critique', 'Sécurité', 'terminee', 'urgente', '2025-12-05 13:59:16', '2025-01-29 20:00:00', NULL, 4, 3, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(467, 'INT-2025-016', 'Vérif switch', 'Contrôle', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-01-30 09:00:00', NULL, 5, 3, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(468, 'INT-2025-017', 'Formation', 'Nouveau logiciel', 'en_attente', 'basse', '2025-12-05 13:59:16', '2025-12-15 14:00:00', NULL, 6, 7, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(469, 'INT-2025-024', 'Répar Canon', 'Qualité impression', 'en_cours', 'haute', '2025-12-05 13:59:16', '2025-12-06 15:00:00', NULL, 13, 12, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(470, 'INT-2025-027', 'Config VLAN', 'Switch', 'terminee', 'haute', '2025-12-05 13:59:16', '2025-02-11 16:00:00', NULL, 4, 15, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(471, 'INT-2025-028', 'Maintenance HP', 'Contrôle', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-02-12 10:00:00', NULL, 5, 3, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(472, 'INT-2025-029', 'Install laptop', 'Direction', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-02-13 14:00:00', NULL, 6, 5, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(473, 'INT-2025-036', 'Formation Office', 'Users', 'en_attente', 'basse', '2025-12-05 13:59:16', '2025-12-16 14:00:00', NULL, 13, 6, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(474, 'INT-2025-039', 'Config email', 'Messagerie', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-02-23 11:00:00', NULL, 4, 1, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(475, 'INT-2025-040', 'Maintenance annuelle', 'Contrôle', 'en_attente', 'normale', '2025-12-05 13:59:16', '2025-12-18 09:00:00', NULL, 5, 5, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(476, 'INT-2025-041', 'Écran fissuré', 'Remplacement', 'en_attente', 'haute', '2025-12-05 13:59:16', '2025-12-10 13:00:00', NULL, 6, 4, NULL, NULL, 1, 'DG urgent', 4, '2025-12-05 13:59:16'),
(477, 'INT-2025-048', 'Config RAID', 'Serveur', 'terminee', 'haute', '2025-12-05 13:59:16', '2025-03-04 20:00:00', NULL, 13, 10, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(478, 'INT-2025-051', 'Optimisation', 'PC lent', 'en_attente', 'normale', '2025-12-05 13:59:16', '2025-12-19 10:00:00', NULL, 4, 5, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(479, 'INT-2025-052', 'Config ports', 'Switch', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-03-08 15:00:00', NULL, 5, 15, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(480, 'INT-2025-053', 'Répar clavier', 'Laptop', 'en_cours', 'normale', '2025-12-05 13:59:16', '2025-12-05 14:00:00', NULL, 6, 5, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(481, 'INT-2025-060', 'Contrôle parc', 'Général', 'en_attente', 'normale', '2025-12-05 13:59:16', '2025-12-23 09:00:00', NULL, 13, 12, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(482, 'INT-2025-063', 'New user setup', 'Onboarding', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-03-19 09:00:00', NULL, 4, 2, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(483, 'INT-2025-064', 'Server backup', 'Monthly', 'terminee', 'haute', '2025-12-05 13:59:16', '2025-03-20 22:00:00', NULL, 5, 3, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(484, 'INT-2025-065', 'Laptop issue', 'Won\'t boot', 'en_attente', 'urgente', '2025-12-05 13:59:16', '2025-12-08 08:00:00', NULL, 6, 5, NULL, NULL, 1, 'CEO laptop', 4, '2025-12-05 13:59:16'),
(485, 'INT-2025-072', 'Monitor repair', 'Dead pixels', 'en_cours', 'normale', '2025-12-05 13:59:16', '2025-12-05 10:00:00', NULL, 13, 10, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(486, 'INT-2025-075', 'Data recovery', 'Lost files', 'terminee', 'urgente', '2025-12-05 13:59:16', '2025-03-31 08:00:00', NULL, 4, 11, NULL, NULL, 1, 'Important data', 4, '2025-12-05 13:59:16'),
(487, 'INT-2025-076', 'Printer maint', 'Quarterly', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-04-01 10:00:00', NULL, 5, 5, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(488, 'INT-2025-077', 'Network audit', 'Security', 'terminee', 'haute', '2025-12-05 13:59:16', '2025-04-02 14:00:00', NULL, 6, 15, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(489, 'INT-2025-084', 'Mouse replace', 'Not working', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-04-09 11:00:00', NULL, 13, 2, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(490, 'INT-2025-087', 'Email issue', 'Can\'t send', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-04-12 10:00:00', NULL, 4, 4, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(491, 'INT-2025-088', 'Printer jam', 'Paper stuck', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-04-13 14:00:00', NULL, 5, 5, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(492, 'INT-2025-089', 'VPN setup', 'Remote work', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-04-14 11:00:00', NULL, 6, 11, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(493, 'INT-2025-096', 'Copier maint', 'Service', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-04-21 10:00:00', NULL, 13, 12, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(494, 'INT-2025-099', 'Server restart', 'Hung process', 'terminee', 'haute', '2025-12-05 13:59:16', '2025-04-24 08:00:00', NULL, 4, 3, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(495, 'INT-2025-100', 'Printer toner', 'Low', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-04-25 11:00:00', NULL, 5, 1, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(496, 'INT-2025-101', 'Router reboot', 'Connection drop', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-05-01 09:00:00', NULL, 6, 3, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(497, 'INT-2025-108', 'Network test', 'Speed check', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-05-08 10:00:00', NULL, 13, 15, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(498, 'INT-2025-111', 'Email filter', 'Spam', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-05-11 11:00:00', NULL, 4, 1, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(499, 'INT-2025-112', 'Phone upgrade', 'New model', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-05-12 14:00:00', NULL, 5, 4, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(500, 'INT-2025-113', 'Monitor setup', 'Dual screen', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-05-13 10:00:00', NULL, 6, 8, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(501, 'INT-2025-120', 'Email signature', 'Template', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-05-20 14:00:00', NULL, 13, 6, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(502, 'INT-2025-123', 'Printer service', 'Annual', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-06-03 10:00:00', NULL, 4, 12, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(503, 'INT-2025-124', 'Server patch', 'Monthly', 'terminee', 'haute', '2025-12-05 13:59:16', '2025-06-04 21:00:00', NULL, 5, 3, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(504, 'INT-2025-125', 'Network cable', 'Cat6 install', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-06-05 11:00:00', NULL, 6, 10, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(505, 'INT-2025-132', 'Server disk', 'Space low', 'terminee', 'haute', '2025-12-05 13:59:16', '2025-06-12 16:00:00', NULL, 13, 3, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(506, 'INT-2025-135', 'Printer color', 'Calibration', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-07-03 14:00:00', NULL, 4, 7, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(507, 'INT-2025-136', 'Email auto-reply', 'Vacation', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-07-04 10:00:00', NULL, 5, 1, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(508, 'INT-2025-137', 'Phone transfer', 'Call forwarding', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-07-05 13:00:00', NULL, 6, 4, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(509, 'INT-2025-144', 'PC sound', 'No audio', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-07-12 10:00:00', NULL, 13, 6, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(510, 'INT-2025-147', 'PC update', 'Driver', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-08-02 11:00:00', NULL, 4, 8, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(511, 'INT-2025-148', 'Server IIS', 'Config', 'terminee', 'haute', '2025-12-05 13:59:16', '2025-08-03 15:00:00', NULL, 5, 3, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(512, 'INT-2025-149', 'WiFi guest', 'Network setup', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-08-04 13:00:00', NULL, 6, 12, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(513, 'INT-2025-156', 'Printer stapler', 'Fix', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-08-11 11:00:00', NULL, 13, 12, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(514, 'INT-2025-159', 'Router QoS', 'Traffic shaping', 'terminee', 'haute', '2025-12-05 13:59:16', '2025-08-14 15:00:00', NULL, 4, 3, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(515, 'INT-2025-160', 'Phone conference', 'Setup', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-08-15 14:00:00', NULL, 5, 4, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(516, 'INT-2025-161', 'PC graphics', 'Driver update', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-09-01 11:00:00', NULL, 6, 7, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(517, 'INT-2025-168', 'Laptop fan', 'Cleaning', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-09-08 12:00:00', NULL, 13, 9, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(518, 'INT-2025-171', 'Email size limit', 'Increase', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-10-01 13:00:00', NULL, 4, 2, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(519, 'INT-2025-172', 'PC BIOS', 'Update', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-10-02 10:00:00', NULL, 5, 6, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(520, 'INT-2025-173', 'Router logging', 'Enable', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-10-03 15:00:00', NULL, 6, 3, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(521, 'INT-2025-180', 'PC partition', 'Resize', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-10-10 12:00:00', NULL, 13, 8, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(522, 'INT-2025-183', 'Server FTP', 'Setup', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-11-03 14:00:00', NULL, 4, 3, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(523, 'INT-2025-184', 'Printer admin', 'Password reset', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-11-04 11:00:00', NULL, 5, 7, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(524, 'INT-2025-185', 'Email calendar', 'Share', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-11-05 13:00:00', NULL, 6, 2, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(525, 'INT-2025-192', 'Printer default', 'Set', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-11-12 10:00:00', NULL, 13, 1, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(526, 'INT-2025-195', 'Network topology', 'Document', 'terminee', 'normale', '2025-12-05 13:59:16', '2025-11-15 15:00:00', NULL, 4, 15, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(527, 'INT-2025-196', 'Laptop accessories', 'Order', 'terminee', 'basse', '2025-12-05 13:59:16', '2025-11-16 10:00:00', NULL, 5, 9, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16'),
(528, 'INT-2025-197', 'Server replication', 'Check', 'terminee', 'haute', '2025-12-05 13:59:16', '2025-11-17 16:00:00', NULL, 6, 3, NULL, NULL, 0, NULL, 4, '2025-12-05 13:59:16');

-- --------------------------------------------------------

--
-- Structure de la table `Intervention`
--

CREATE TABLE `Intervention` (
  `Numero_Intervent` int(11) NOT NULL,
  `Date_Visite` date NOT NULL,
  `Heure_Visite` time NOT NULL,
  `Matricule_Technicien` int(11) NOT NULL,
  `Numero_Client` int(11) NOT NULL,
  `Numero_de_Serie` varchar(50) NOT NULL,
  `Temps_Passe` int(11) DEFAULT NULL,
  `Commentaire` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Intervention`
--

INSERT INTO `Intervention` (`Numero_Intervent`, `Date_Visite`, `Heure_Visite`, `Matricule_Technicien`, `Numero_Client`, `Numero_de_Serie`, `Temps_Passe`, `Commentaire`) VALUES
(1, '2024-11-10', '09:00:00', 2, 1, 'SN-HP-2024-001', 60, 'Maintenance pru00e9ventive effectuu00e9e avec succu00e8s'),
(2, '2024-11-12', '14:30:00', 3, 2, 'SN-DELL-2024-004', 90, 'Mise u00e0 jour systu00e8me et nettoyage'),
(3, '2024-11-15', '10:00:00', 4, 3, 'SN-IBM-2024-006', 120, 'Vu00e9rification serveur et monitoring'),
(4, '2024-11-18', '11:15:00', 2, 1, 'SN-CANON-2024-002', 45, 'Remplacement cartouche et calibration'),
(5, '2024-11-20', '08:30:00', 5, 5, 'SN-XEROX-2024-010', 75, 'Ru00e9paration bourrage papier'),
(6, '2024-11-22', '15:00:00', 6, 6, 'SN-DELL-2024-012', 50, 'Installation logiciel antivirus'),
(7, '2024-11-25', '09:45:00', 7, 7, 'SN-CANON-2024-013', 40, 'Maintenance courante'),
(8, '2024-11-28', '13:00:00', 3, 4, 'SN-NETGEAR-2024-008', 65, 'Configuration ru00e9seau optimisu00e9e'),
(9, '2024-12-02', '10:30:00', 2, 2, 'SN-HP-2024-005', 55, 'Nettoyage complet et diagnostic'),
(10, '2024-12-04', '14:00:00', 4, 8, 'SN-EPSON-2024-015', 80, 'Ru00e9paration scanner hors garantie');

-- --------------------------------------------------------

--
-- Structure de la table `Materiel`
--

CREATE TABLE `Materiel` (
  `Numero_de_Serie` varchar(50) NOT NULL,
  `Date_de_vente` date NOT NULL,
  `Date_d_installation` date DEFAULT NULL,
  `Prix_de_Vente` decimal(10,2) DEFAULT NULL,
  `Emplacement` varchar(255) DEFAULT NULL,
  `Reference_Interne` varchar(50) NOT NULL,
  `Numero_Client` int(11) DEFAULT NULL,
  `Numero_de_Contrat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Materiel`
--

INSERT INTO `Materiel` (`Numero_de_Serie`, `Date_de_vente`, `Date_d_installation`, `Prix_de_Vente`, `Emplacement`, `Reference_Interne`, `Numero_Client`, `Numero_de_Contrat`) VALUES
('SN-APC-2024-030', '2024-09-18', '2024-09-20', 380.00, 'Salle Serveurs', 'ONDULEUR-APC-001', 10, 10),
('SN-CANON-2024-002', '2024-01-15', '2024-01-18', 380.00, 'Bureau 102', 'IMP-CANON-001', 1, 1),
('SN-CANON-2024-013', '2024-07-18', '2024-07-20', 390.00, 'Accueil', 'IMP-CANON-001', 7, 7),
('SN-CANON-2024-022', '2024-11-02', '2024-11-05', 1800.00, 'Production', 'COPIE-CANON-001', 12, 12),
('SN-CISCO-2024-007', '2024-02-08', '2024-02-10', 650.00, 'Local Technique', 'ROUTE-CISCO-001', 3, 3),
('SN-CISCO-2024-018', '2024-09-01', NULL, 680.00, 'Entrepu00f4t', 'ROUTE-CISCO-001', 2, 2),
('SN-CISCO-2024-025', '2024-06-10', '2024-06-12', 550.00, 'Salle Réseau', 'SWITCH-CISCO-001', 15, 15),
('SN-DELL-2024-004', '2024-03-12', '2024-03-15', 850.00, 'Bureau Direction', 'PC-DELL-001', 2, 2),
('SN-DELL-2024-012', '2024-04-10', '2024-04-13', 890.00, 'Du00e9veloppement', 'PC-DELL-001', 6, 6),
('SN-DELL-2024-016', '2023-08-10', '2023-08-12', 870.00, 'Bureau 303', 'PC-DELL-001', 8, NULL),
('SN-DELL-2024-019', '2024-09-12', '2024-09-15', 920.00, 'Bureau RH', 'PC-DELL-002', 9, 9),
('SN-DELL-2024-024', '2024-03-22', '2024-03-25', 890.00, 'Bureau IT Manager', 'PC-DELL-001', 14, 14),
('SN-DELL-2024-027', '2024-05-20', '2024-05-23', 1100.00, 'Bureau CEO', 'LAPTOP-DELL-001', 5, 5),
('SN-EPSON-2024-003', '2024-02-05', '2024-02-08', 520.00, 'Salle Ru00e9union A', 'SCAN-EPSON-001', 1, 1),
('SN-EPSON-2024-015', '2023-07-20', '2023-07-22', 510.00, 'Archives', 'SCAN-EPSON-001', 8, NULL),
('SN-HP-2024-001', '2024-01-10', '2024-01-12', 450.00, 'Bureau 101', 'IMP-HP-001', 1, 1),
('SN-HP-2024-005', '2024-03-18', '2024-03-20', 780.00, 'Comptabilitu00e9', 'PC-HP-001', 2, 2),
('SN-HP-2024-011', '2024-06-15', '2024-06-18', 460.00, 'Bureau 201', 'IMP-HP-001', 5, 5),
('SN-HP-2024-014', '2024-07-25', '2024-07-28', 810.00, 'Marketing', 'PC-HP-001', 7, 7),
('SN-HP-2024-017', '2024-08-15', NULL, 470.00, 'Stock', 'IMP-HP-001', 1, 1),
('SN-HP-2024-020', '2024-10-05', '2024-10-08', 1250.00, 'Salle Formation', 'SERV-HP-001', 10, 10),
('SN-HP-2024-023', '2024-01-18', '2024-01-20', 320.00, 'Bureau 105', 'IMP-EPSON-001', 13, 13),
('SN-HP-2024-026', '2024-02-15', '2024-02-18', 400.00, 'Bureau 202', 'IMP-HP-002', 3, 3),
('SN-IBM-2024-006', '2024-02-01', '2024-02-05', 3500.00, 'Salle Serveurs', 'SERV-IBM-001', 3, 3),
('SN-IPAD-2024-028', '2024-07-12', '2024-07-15', 850.00, 'Direction Mobile', 'TABLET-IPAD-001', 7, 7),
('SN-LENOVO-2024-021', '2024-08-20', '2024-08-22', 780.00, 'Bureau Commercial', 'PC-LENOVO-001', 11, 11),
('SN-NETGEAR-2024-008', '2024-04-22', '2024-04-25', 420.00, 'Bureau IT', 'SWITCH-NETGEAR-001', 4, 4),
('SN-POLY-2024-009', '2024-05-10', '2024-05-12', 280.00, 'Standard', 'TEL-IP-POLY-001', 4, 4),
('SN-SAMSUNG-2024-029', '2024-08-25', '2024-08-28', 650.00, 'Bureau Mobile', 'TABLET-SAMSUNG-001', 9, 9),
('SN-XEROX-2024-010', '2024-06-05', '2024-06-08', 2200.00, 'Reprographie', 'COPIE-XEROX-001', 5, 5);

-- --------------------------------------------------------

--
-- Structure de la table `Technicien`
--

CREATE TABLE `Technicien` (
  `Matricule` int(11) NOT NULL,
  `Telephone_Mobile` varchar(20) DEFAULT NULL,
  `Qualification` varchar(255) DEFAULT NULL,
  `Date_Obtention` date DEFAULT NULL,
  `Numero_Agence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Technicien`
--

INSERT INTO `Technicien` (`Matricule`, `Telephone_Mobile`, `Qualification`, `Date_Obtention`, `Numero_Agence`) VALUES
(2, '06 12 34 56 78', 'Certification Maintenance Matu00e9riel Informatique', '2019-05-15', 2),
(3, '06 23 45 67 89', 'Certification Ru00e9seaux et Tu00e9lu00e9communications', '2021-08-20', 3),
(4, '06 34 56 78 90', 'Certification Support Technique Niveau 2', '2018-11-10', 4),
(5, '06 45 67 89 01', 'Certification Systu00e8mes et Serveurs', '2022-04-05', 5),
(6, '06 56 78 90 12', 'Certification Matu00e9riel HP et Dell', '2020-09-18', 1),
(7, '06 67 89 01 23', 'Certification Maintenance Imprimantes', '2019-12-22', 2),
(8, '06 78 90 12 34', 'Certification Ru00e9seaux Cisco', '2021-06-30', 3),
(9, '06 89 01 23 45', 'Certification Support Technique Niveau 3', '2020-02-14', 4),
(10, '06 90 12 34 56', 'Certification Sécurité Informatique', '2022-07-11', 5),
(11, '06 01 23 45 67', 'Certification Infrastructure Réseau', '2019-10-25', 6),
(12, '06 12 23 34 45', 'Certification Cloud Computing', '2021-03-19', 7),
(13, '06 23 34 45 56', 'Certification Virtualisation', '2020-08-30', 8);

-- --------------------------------------------------------

--
-- Structure de la table `Type_Contrat`
--

CREATE TABLE `Type_Contrat` (
  `RefTypeContrat` varchar(50) NOT NULL,
  `DelaiIntervention` int(11) DEFAULT NULL,
  `TauxApplicable` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Type_Contrat`
--

INSERT INTO `Type_Contrat` (`RefTypeContrat`, `DelaiIntervention`, `TauxApplicable`) VALUES
('BRONZE', 5, 5.00),
('GOLD', 1, 10.00),
('PLATINUM', 4, 12.50),
('SILVER', 3, 7.50);

-- --------------------------------------------------------

--
-- Structure de la table `Type_Materiel`
--

CREATE TABLE `Type_Materiel` (
  `Reference_Interne` varchar(50) NOT NULL,
  `Libelle_Type_materiel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Type_Materiel`
--

INSERT INTO `Type_Materiel` (`Reference_Interne`, `Libelle_Type_materiel`) VALUES
('COPIE-CANON-001', 'Copieur Canon ImageRunner'),
('COPIE-XEROX-001', 'Copieur Xerox WorkCentre'),
('ECRAN-DELL-001', 'Écran Dell UltraSharp 24\"'),
('ECRAN-HP-001', 'Écran HP EliteDisplay 27\"'),
('IMP-CANON-001', 'Imprimante Canon Pixma'),
('IMP-CANON-002', 'Imprimante Canon ImageClass'),
('IMP-EPSON-001', 'Imprimante Epson EcoTank'),
('IMP-HP-001', 'Imprimante HP LaserJet Pro'),
('IMP-HP-002', 'Imprimante HP OfficeJet'),
('LAPTOP-DELL-001', 'Laptop Dell Latitude'),
('LAPTOP-HP-001', 'Laptop HP EliteBook'),
('ONDULEUR-APC-001', 'Onduleur APC Smart-UPS'),
('PC-DELL-001', 'PC Dell OptiPlex'),
('PC-DELL-002', 'PC Dell Latitude'),
('PC-HP-001', 'PC HP EliteDesk'),
('PC-HP-002', 'PC HP ProDesk'),
('PC-LENOVO-001', 'PC Lenovo ThinkCentre'),
('ROUTE-CISCO-001', 'Routeur Cisco RV'),
('ROUTE-NETGEAR-001', 'Routeur Netgear Nighthawk'),
('SCAN-CANON-001', 'Scanner Canon CanoScan'),
('SCAN-EPSON-001', 'Scanner Epson WorkForce'),
('SERV-DELL-001', 'Serveur Dell PowerEdge'),
('SERV-HP-001', 'Serveur HP ProLiant'),
('SERV-IBM-001', 'Serveur IBM PowerEdge'),
('SWITCH-CISCO-001', 'Switch Cisco Catalyst'),
('SWITCH-NETGEAR-001', 'Switch Netgear GS'),
('TABLET-IPAD-001', 'Tablette iPad Pro'),
('TABLET-SAMSUNG-001', 'Tablette Samsung Galaxy Tab'),
('TEL-IP-CISCO-001', 'Téléphone IP Cisco'),
('TEL-IP-POLY-001', 'Tu00e9lu00e9phone IP Polycom');

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateur`
--

CREATE TABLE `Utilisateur` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('gestionnaire','technicien') NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `matricule_employe` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Utilisateur`
--

INSERT INTO `Utilisateur` (`id`, `username`, `password_hash`, `role`, `nom`, `prenom`, `email`, `matricule_employe`, `created_at`, `last_login`, `active`) VALUES
(4, 'gestionnaire', '$2y$10$krf6U8R70Dvcvjykgb72rO3265q9o0r2LaJkEJad4jDdTyBERf/CG', 'gestionnaire', 'Dupont', 'Marie', 'marie.dupont@cashcash.fr', NULL, '2025-12-05 08:58:14', '2025-12-08 08:24:36', 1),
(5, 'technicien1', '$2y$10$krf6U8R70Dvcvjykgb72rO3265q9o0r2LaJkEJad4jDdTyBERf/CG', 'technicien', 'Martin', 'Pierre', 'pierre.martin@cashcash.fr', NULL, '2025-12-05 08:58:14', '2025-12-05 14:16:03', 1),
(6, 'technicien2', '$2y$10$krf6U8R70Dvcvjykgb72rO3265q9o0r2LaJkEJad4jDdTyBERf/CG', 'technicien', 'Durand', 'Sophie', 'sophie.durand@cashcash.fr', NULL, '2025-12-05 08:58:14', '2025-12-05 14:04:38', 1),
(13, 'technicien3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'technicien', 'Bernard', 'Luc', 'luc.bernard@cashcash.fr', 4, '2025-12-05 10:59:09', NULL, 1),
(14, 'technicien4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'technicien', 'Petit', 'Claire', 'claire.petit@cashcash.fr', 5, '2025-12-05 10:59:09', NULL, 1),
(15, 'gestionnaire2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gestionnaire', 'Dubois', 'Thomas', 'thomas.dubois@cashcash.fr', 6, '2025-12-05 10:59:09', NULL, 1),
(16, 'technicien5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'technicien', 'Leroy', 'Julie', 'julie.leroy@cashcash.fr', 7, '2025-12-05 10:59:09', NULL, 1),
(17, 'technicien6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'technicien', 'Moreau', 'Antoine', 'antoine.moreau@cashcash.fr', 8, '2025-12-05 10:59:09', NULL, 1),
(18, 'technicien7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'technicien', 'Laurent', 'Isabelle', 'isabelle.laurent@cashcash.fr', 9, '2025-12-05 10:59:09', NULL, 1),
(19, 'technicien8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'technicien', 'Simon', 'David', 'david.simon@cashcash.fr', 10, '2025-12-05 10:59:09', NULL, 1),
(20, 'technicien9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'technicien', 'Lefebvre', 'Nicolas', 'nicolas.lefebvre@cashcash.fr', 11, '2025-12-05 10:59:09', NULL, 1),
(21, 'technicien10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'technicien', 'Michel', 'Céline', 'celine.michel@cashcash.fr', 12, '2025-12-05 10:59:09', NULL, 1),
(22, 'technicien11', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'technicien', 'Garcia', 'Marc', 'marc.garcia@cashcash.fr', 13, '2025-12-05 10:59:09', NULL, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Agence`
--
ALTER TABLE `Agence`
  ADD PRIMARY KEY (`Numero_Agence`);

--
-- Index pour la table `Client`
--
ALTER TABLE `Client`
  ADD PRIMARY KEY (`Numero_Client`),
  ADD KEY `fk_client_agence` (`Numero_Agence`);

--
-- Index pour la table `Contrat_de_maintenance`
--
ALTER TABLE `Contrat_de_maintenance`
  ADD PRIMARY KEY (`Numero_de_Contrat`),
  ADD KEY `fk_contrat_client` (`Numero_Client`),
  ADD KEY `fk_contrat_type` (`RefTypeContrat`);

--
-- Index pour la table `Controler`
--
ALTER TABLE `Controler`
  ADD PRIMARY KEY (`Numero_Intervent`,`Numero_de_Serie`),
  ADD KEY `fk_controler_materiel` (`Numero_de_Serie`);

--
-- Index pour la table `Employe`
--
ALTER TABLE `Employe`
  ADD PRIMARY KEY (`Matricule`);

--
-- Index pour la table `Fiche_Intervention`
--
ALTER TABLE `Fiche_Intervention`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_intervention` (`numero_intervention`),
  ADD KEY `fk_fiche_client` (`client_id`),
  ADD KEY `fk_fiche_equipement` (`equipement_id`),
  ADD KEY `fk_fiche_creator` (`created_by`),
  ADD KEY `idx_fiche_statut` (`statut`),
  ADD KEY `idx_fiche_technicien` (`technicien_id`),
  ADD KEY `idx_fiche_date` (`date_intervention`);

--
-- Index pour la table `Intervention`
--
ALTER TABLE `Intervention`
  ADD PRIMARY KEY (`Numero_Intervent`),
  ADD KEY `fk_intervention_technicien` (`Matricule_Technicien`),
  ADD KEY `fk_intervention_client` (`Numero_Client`),
  ADD KEY `fk_intervention_materiel` (`Numero_de_Serie`);

--
-- Index pour la table `Materiel`
--
ALTER TABLE `Materiel`
  ADD PRIMARY KEY (`Numero_de_Serie`),
  ADD KEY `fk_materiel_type` (`Reference_Interne`),
  ADD KEY `fk_materiel_client` (`Numero_Client`),
  ADD KEY `fk_materiel_contrat` (`Numero_de_Contrat`);

--
-- Index pour la table `Technicien`
--
ALTER TABLE `Technicien`
  ADD PRIMARY KEY (`Matricule`),
  ADD KEY `fk_technicien_agence` (`Numero_Agence`);

--
-- Index pour la table `Type_Contrat`
--
ALTER TABLE `Type_Contrat`
  ADD PRIMARY KEY (`RefTypeContrat`);

--
-- Index pour la table `Type_Materiel`
--
ALTER TABLE `Type_Materiel`
  ADD PRIMARY KEY (`Reference_Interne`);

--
-- Index pour la table `Utilisateur`
--
ALTER TABLE `Utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_utilisateur_role` (`role`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Agence`
--
ALTER TABLE `Agence`
  MODIFY `Numero_Agence` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `Client`
--
ALTER TABLE `Client`
  MODIFY `Numero_Client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `Contrat_de_maintenance`
--
ALTER TABLE `Contrat_de_maintenance`
  MODIFY `Numero_de_Contrat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `Employe`
--
ALTER TABLE `Employe`
  MODIFY `Matricule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `Fiche_Intervention`
--
ALTER TABLE `Fiche_Intervention`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=530;

--
-- AUTO_INCREMENT pour la table `Intervention`
--
ALTER TABLE `Intervention`
  MODIFY `Numero_Intervent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `Utilisateur`
--
ALTER TABLE `Utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Client`
--
ALTER TABLE `Client`
  ADD CONSTRAINT `fk_client_agence` FOREIGN KEY (`Numero_Agence`) REFERENCES `Agence` (`Numero_Agence`);

--
-- Contraintes pour la table `Contrat_de_maintenance`
--
ALTER TABLE `Contrat_de_maintenance`
  ADD CONSTRAINT `fk_contrat_client` FOREIGN KEY (`Numero_Client`) REFERENCES `Client` (`Numero_Client`),
  ADD CONSTRAINT `fk_contrat_type` FOREIGN KEY (`RefTypeContrat`) REFERENCES `Type_Contrat` (`RefTypeContrat`);

--
-- Contraintes pour la table `Controler`
--
ALTER TABLE `Controler`
  ADD CONSTRAINT `fk_controler_intervention` FOREIGN KEY (`Numero_Intervent`) REFERENCES `Intervention` (`Numero_Intervent`),
  ADD CONSTRAINT `fk_controler_materiel` FOREIGN KEY (`Numero_de_Serie`) REFERENCES `Materiel` (`Numero_de_Serie`);

--
-- Contraintes pour la table `Fiche_Intervention`
--
ALTER TABLE `Fiche_Intervention`
  ADD CONSTRAINT `fk_fiche_client` FOREIGN KEY (`client_id`) REFERENCES `Client` (`Numero_Client`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_fiche_creator` FOREIGN KEY (`created_by`) REFERENCES `Utilisateur` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_fiche_equipement` FOREIGN KEY (`equipement_id`) REFERENCES `Materiel` (`Numero_de_Serie`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_fiche_technicien` FOREIGN KEY (`technicien_id`) REFERENCES `Utilisateur` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `Intervention`
--
ALTER TABLE `Intervention`
  ADD CONSTRAINT `fk_intervention_client` FOREIGN KEY (`Numero_Client`) REFERENCES `Client` (`Numero_Client`),
  ADD CONSTRAINT `fk_intervention_materiel` FOREIGN KEY (`Numero_de_Serie`) REFERENCES `Materiel` (`Numero_de_Serie`),
  ADD CONSTRAINT `fk_intervention_technicien` FOREIGN KEY (`Matricule_Technicien`) REFERENCES `Technicien` (`Matricule`);

--
-- Contraintes pour la table `Materiel`
--
ALTER TABLE `Materiel`
  ADD CONSTRAINT `fk_materiel_client` FOREIGN KEY (`Numero_Client`) REFERENCES `Client` (`Numero_Client`),
  ADD CONSTRAINT `fk_materiel_contrat` FOREIGN KEY (`Numero_de_Contrat`) REFERENCES `Contrat_de_maintenance` (`Numero_de_Contrat`),
  ADD CONSTRAINT `fk_materiel_type` FOREIGN KEY (`Reference_Interne`) REFERENCES `Type_Materiel` (`Reference_Interne`);

--
-- Contraintes pour la table `Technicien`
--
ALTER TABLE `Technicien`
  ADD CONSTRAINT `fk_technicien_agence` FOREIGN KEY (`Numero_Agence`) REFERENCES `Agence` (`Numero_Agence`),
  ADD CONSTRAINT `fk_technicien_employe` FOREIGN KEY (`Matricule`) REFERENCES `Employe` (`Matricule`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
