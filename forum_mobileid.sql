-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : jeu. 05 fév. 2026 à 14:45
-- Version du serveur : 8.0.40
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `forum_mobileid`
--

-- --------------------------------------------------------

--
-- Structure de la table `agenda`
--

CREATE TABLE `agenda` (
  `id` int NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `category` varchar(60) NOT NULL,
  `title` varchar(180) NOT NULL,
  `description` longtext,
  `created_at` datetime NOT NULL,
  `event_date_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `agenda`
--

INSERT INTO `agenda` (`id`, `start_time`, `end_time`, `category`, `title`, `description`, `created_at`, `event_date_id`) VALUES
(3, '08:00:00', '09:00:00', 'Cérémonie', 'Cérémonie d\'ouverture', 'Accueil des participants et presentation des objectifs du forum.', '2026-02-05 14:33:33', 3),
(4, '09:00:00', '09:10:00', 'Discours', 'Discours du Directeur de la DGPF', NULL, '2026-02-05 14:34:03', 3),
(5, '09:10:00', '09:20:00', 'Discours', 'Discours du Directeur de l\'ANC', NULL, '2026-02-05 14:34:23', 3),
(6, '09:20:00', '09:30:00', 'Discours', 'Discours du Directeur de TO7 Network', NULL, '2026-02-05 14:35:04', 3),
(7, '09:30:00', '09:45:00', 'Autre', 'Presentation de l\'identité numérique a Djibouti', NULL, '2026-02-05 14:35:47', 3),
(8, '09:45:00', '10:00:00', 'Panel', 'Intervention d\'un expert en identité numérique', 'Expert francophone (Rwanda).', '2026-02-05 14:36:32', 3),
(9, '10:00:00', '10:45:00', 'Panel', 'Identification numérique : defis et opportunités', 'Inclusion financière durable.', '2026-02-05 14:37:06', 3),
(10, '11:00:00', '11:45:00', 'Discours', 'Identification numérique et services publics', 'Échanges entre administrations et partenaires techniques.', '2026-02-05 14:37:49', 3),
(11, '12:30:00', '14:00:00', 'Pause', 'Pause déjeuner', NULL, '2026-02-05 14:38:11', 3),
(12, '14:00:00', '14:45:00', 'Panel', 'Valeur ajoutée de Mobile ID', NULL, '2026-02-05 14:38:29', 3),
(13, '09:00:00', '09:15:00', 'Discours', 'Discours du Ministre délégué chargé de l\'Économie Numérique et de l\'Innovation (MDENI)', NULL, '2026-02-05 14:39:37', 4),
(14, '09:15:00', '09:30:00', 'Discours', 'Discours du Ministre de l\'Intérieur', NULL, '2026-02-05 14:40:20', 4),
(15, '09:30:00', '09:45:00', 'Discours', 'Discours du Directeur general de TO7', NULL, '2026-02-05 14:40:40', 4),
(16, '09:45:00', '10:15:00', 'Discours', 'Discours de Son Excellence M. le President Ismail Omar Guelleh', NULL, '2026-02-05 14:41:10', 4),
(17, '10:15:00', '11:30:00', 'Signature', 'Signature de lancement du projet Mobile ID', 'Signature entre TO7 et le Ministère de l\'Intérieur (prise de photo).', '2026-02-05 14:41:44', 4),
(18, '11:30:00', '12:30:00', 'Autre', 'Cérémonie de cloture', 'Synthèse des travaux et remerciements officiels.', '2026-02-05 14:42:33', 4);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20260204095109', '2026-02-04 09:51:18', 13),
('DoctrineMigrations\\Version20260204203624', '2026-02-04 20:36:36', 20),
('DoctrineMigrations\\Version20260205052747', '2026-02-05 05:27:58', 48),
('DoctrineMigrations\\Version20260205063234', '2026-02-05 06:32:43', 35);

-- --------------------------------------------------------

--
-- Structure de la table `event_date`
--

CREATE TABLE `event_date` (
  `id` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `event_date`
--

INSERT INTO `event_date` (`id`, `start_date`, `end_date`, `is_active`, `created_at`) VALUES
(3, '2026-02-08', '2026-02-08', 1, '2026-02-05 14:32:23'),
(4, '2026-02-09', '2026-02-09', 0, '2026-02-05 14:32:30');

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

CREATE TABLE `inscription` (
  `id` int NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `institution` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `roles`, `password`, `full_name`, `is_active`, `created_at`) VALUES
(1, 'mohamed.abdillahi.nour@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$r1k9f15Lq2H7hO4PWVJE9utKvzS1QmSNGF1KY9Hltgm8B98UM12y.', 'Mohamed Abdillahi Nour', 1, '2026-02-04 10:03:57');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2CEDC8773DC09FC4` (`event_date_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `event_date`
--
ALTER TABLE `event_date`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750` (`queue_name`,`available_at`,`delivered_at`,`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `event_date`
--
ALTER TABLE `event_date`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `inscription`
--
ALTER TABLE `inscription`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `agenda`
--
ALTER TABLE `agenda`
  ADD CONSTRAINT `FK_2CEDC8773DC09FC4` FOREIGN KEY (`event_date_id`) REFERENCES `event_date` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
