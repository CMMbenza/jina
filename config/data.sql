CREATE TABLE `employment_details`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `nom_entreprise` VARCHAR(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `poste` VARCHAR(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `about_entreprise` TEXT COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY(`id`),
    KEY `user_id`(`user_id`),
    CONSTRAINT `employment_details_ibfk_1` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 3 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `freelance_details`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `nom_entreprise` VARCHAR(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `desc_entreprise` TEXT COLLATE utf8mb4_unicode_ci,
    `tel_bureau` VARCHAR(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `adresse_bureau` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `logo_entreprise` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY(`id`),
    KEY `user_id`(`user_id`),
    CONSTRAINT `freelance_details_ibfk_1` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 3 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `profiles`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `nom` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `prenom` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `titre` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `bio` TEXT COLLATE utf8mb4_unicode_ci,
    `tel_perso` VARCHAR(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `photo_profil` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `photo_couverture` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `identify` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY(`id`),
    KEY `user_id`(`user_id`),
    CONSTRAINT `profiles_ibfk_1` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `users`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email` VARCHAR(150) COLLATE utf8mb4_unicode_ci NOT NULL,
    `PASSWORD` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `account_type` ENUM('employer', 'freelance') COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`id`),
    UNIQUE KEY `email`(`email`)
) ENGINE = InnoDB AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `user_catalogues`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `nom_produit` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `image_produit` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY(`id`),
    KEY `user_id`(`user_id`),
    CONSTRAINT `user_catalogues_ibfk_1` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `user_competences`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `competence` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`id`),
    KEY `fk_competence_user`(`user_id`),
    CONSTRAINT `fk_competence_user` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 26 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `user_services`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `titre` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `description` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY(`id`),
    KEY `user_id`(`user_id`),
    CONSTRAINT `user_services_ibfk_1` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 8 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `user_socials`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `type_reseau` ENUM('perso', 'entreprise', 'freelance') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `plateforme` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `url` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY(`id`),
    KEY `user_id`(`user_id`),
    CONSTRAINT `user_socials_ibfk_1` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 50 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci