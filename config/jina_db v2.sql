-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 24 juil. 2026 à 05:58
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `jina_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `employment_details`
--

CREATE TABLE `employment_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nom_entreprise` varchar(200) DEFAULT NULL,
  `poste` varchar(200) DEFAULT NULL,
  `about_entreprise` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `employment_details`
--

INSERT INTO `employment_details` (`id`, `user_id`, `nom_entreprise`, `poste`, `about_entreprise`) VALUES
(3, 4, 'Mehdou MBULA', 'IT', 'jkjkjk');

-- --------------------------------------------------------

--
-- Structure de la table `freelance_details`
--

CREATE TABLE `freelance_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nom_entreprise` varchar(200) DEFAULT NULL,
  `desc_entreprise` text DEFAULT NULL,
  `tel_bureau` varchar(30) DEFAULT NULL,
  `adresse_bureau` varchar(255) DEFAULT NULL,
  `logo_entreprise` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `titre` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `tel_perso` varchar(30) DEFAULT NULL,
  `photo_profil` varchar(255) DEFAULT NULL,
  `photo_couverture` varchar(255) DEFAULT NULL,
  `identify` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `nom`, `prenom`, `titre`, `bio`, `tel_perso`, `photo_profil`, `photo_couverture`, `identify`) VALUES
(4, 4, 'MBULA', 'Mehdou', 'INFORMATIQUE (TIC)', 'jjj', '0896756567', 'uploads/9c24260ec203d7a169436033dbc765c9.jpg', 'uploads/23f5b166afb125049b41d320e77ddf16.jpg', 'WbJ6fR3ggv');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `account_type` enum('employer','freelance') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `PASSWORD`, `account_type`, `created_at`) VALUES
(4, 'Chris Mbenza', 'cm.chrismbenza@gmail.com', '$2y$10$Sh6algB9tknu3BXvWPHPa.VgaVb72TdqPjmKGHd8Fr0GsFlMfi9le', 'employer', '2026-07-05 04:45:24');

-- --------------------------------------------------------

--
-- Structure de la table `user_catalogues`
--

CREATE TABLE `user_catalogues` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nom_produit` varchar(255) DEFAULT NULL,
  `image_produit` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_competences`
--

CREATE TABLE `user_competences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `competence` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_competences`
--

INSERT INTO `user_competences` (`id`, `user_id`, `competence`, `created_at`) VALUES
(21, 4, 'HTML', '2026-07-05 04:53:23'),
(22, 4, 'CSS', '2026-07-05 04:53:23'),
(23, 4, 'JS', '2026-07-05 04:53:23');

-- --------------------------------------------------------

--
-- Structure de la table `user_services`
--

CREATE TABLE `user_services` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `titre` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_socials`
--

CREATE TABLE `user_socials` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type_reseau` enum('perso','entreprise','freelance') DEFAULT NULL,
  `plateforme` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_socials`
--

INSERT INTO `user_socials` (`id`, `user_id`, `type_reseau`, `plateforme`, `url`) VALUES
(57, 4, 'perso', 'facebook', 'https://web.facebook.com/chirs.mbenza'),
(58, 4, 'perso', 'instagram', 'https://web.facebook.com/chirs.mbenza'),
(59, 4, 'perso', 'linkedin', 'https://web.facebook.com/chirs.mbenza'),
(60, 4, 'entreprise', 'facebook', 'https://web.facebook.com/chirs.mbenza'),
(61, 4, 'entreprise', 'linkedin', 'https://web.facebook.com/chirs.mbenza');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `employment_details`
--
ALTER TABLE `employment_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `freelance_details`
--
ALTER TABLE `freelance_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `user_catalogues`
--
ALTER TABLE `user_catalogues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `user_competences`
--
ALTER TABLE `user_competences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_competence_user` (`user_id`);

--
-- Index pour la table `user_services`
--
ALTER TABLE `user_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `user_socials`
--
ALTER TABLE `user_socials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `employment_details`
--
ALTER TABLE `employment_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `freelance_details`
--
ALTER TABLE `freelance_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user_catalogues`
--
ALTER TABLE `user_catalogues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `user_competences`
--
ALTER TABLE `user_competences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `user_services`
--
ALTER TABLE `user_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `user_socials`
--
ALTER TABLE `user_socials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `employment_details`
--
ALTER TABLE `employment_details`
  ADD CONSTRAINT `employment_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `freelance_details`
--
ALTER TABLE `freelance_details`
  ADD CONSTRAINT `freelance_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user_catalogues`
--
ALTER TABLE `user_catalogues`
  ADD CONSTRAINT `user_catalogues_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user_competences`
--
ALTER TABLE `user_competences`
  ADD CONSTRAINT `fk_competence_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user_services`
--
ALTER TABLE `user_services`
  ADD CONSTRAINT `user_services_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user_socials`
--
ALTER TABLE `user_socials`
  ADD CONSTRAINT `user_socials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
