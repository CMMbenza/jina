CREATE TABLE `employment_details` (
 `id` int NOT NULL AUTO_INCREMENT,
 `user_id` int NOT NULL,
 `nom_entreprise` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `poste` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `about_entreprise` text COLLATE utf8mb4_unicode_ci,
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`),
 CONSTRAINT `employment_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
freelance_details	CREATE TABLE `freelance_details` (
 `id` int NOT NULL AUTO_INCREMENT,
 `user_id` int NOT NULL,
 `nom_entreprise` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `desc_entreprise` text COLLATE utf8mb4_unicode_ci,
 `tel_bureau` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `adresse_bureau` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `logo_entreprise` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`),
 CONSTRAINT `freelance_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
profiles	CREATE TABLE `profiles` (
 `id` int NOT NULL AUTO_INCREMENT,
 `user_id` int NOT NULL,
 `nom` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `prenom` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `titre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `bio` text COLLATE utf8mb4_unicode_ci,
 `tel_perso` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `photo_profil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `photo_couverture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `identify` text COLLATE utf8mb4_unicode_ci NOT NULL,
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`),
 CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
users	CREATE TABLE `users` (
 `id` int NOT NULL AUTO_INCREMENT,
 `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
 `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
 `PASSWORD` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `account_type` enum('employer','freelance') COLLATE utf8mb4_unicode_ci NOT NULL,
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
user_catalogues	CREATE TABLE `user_catalogues` (
 `id` int NOT NULL AUTO_INCREMENT,
 `user_id` int NOT NULL,
 `nom_produit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `image_produit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`),
 CONSTRAINT `user_catalogues_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
user_competences	CREATE TABLE `user_competences` (
 `id` int NOT NULL AUTO_INCREMENT,
 `user_id` int NOT NULL,
 `competence` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `fk_competence_user` (`user_id`),
 CONSTRAINT `fk_competence_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
user_services	CREATE TABLE `user_services` (
 `id` int NOT NULL AUTO_INCREMENT,
 `user_id` int NOT NULL,
 `titre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`),
 CONSTRAINT `user_services_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
user_socials	CREATE TABLE `user_socials` (
 `id` int NOT NULL AUTO_INCREMENT,
 `user_id` int NOT NULL,
 `type_reseau` enum('perso','entreprise','freelance') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `plateforme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`),
 CONSTRAINT `user_socials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci