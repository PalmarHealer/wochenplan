SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+01:00";
CREATE DATABASE IF NOT EXISTS `wochenplan` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `wochenplan`;

CREATE TABLE IF NOT EXISTS `angebot` (
    `id` int NOT NULL AUTO_INCREMENT,
    `parent_lesson_id` int DEFAULT NULL,
    `identifier` varchar(255) NOT NULL,
    `disabled` tinyint(1) NOT NULL DEFAULT '0',
    `date_type` int NOT NULL DEFAULT '0',
    `date_repeating` int DEFAULT NULL,
    `date` date DEFAULT NULL,
    `name` varchar(500) NOT NULL,
    `description` varchar(500) DEFAULT NULL,
    `location` int NOT NULL,
    `time` int NOT NULL,
    `box_color` varchar(20) NOT NULL DEFAULT '#f6e9e6',
    `notes` varchar(500) DEFAULT NULL,
    `assigned_user_id` int NOT NULL,
    `last_change_from_userid` int DEFAULT NULL,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `tmp` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `lunchdata` (
    `id` int NOT NULL AUTO_INCREMENT,
    `date` date NOT NULL,
    `data` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `psswdresettokens` (
    `id` int NOT NULL AUTO_INCREMENT,
    `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `userid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `registertokens` (
    `id` int NOT NULL AUTO_INCREMENT,
    `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `securitytokens` (
    `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `identifier` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
    `securitytoken` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `settings` (
    `id` int NOT NULL AUTO_INCREMENT,
    `setting` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `suffix` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `value` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `sick` (
    `id` int NOT NULL AUTO_INCREMENT,
    `userid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `start` date NOT NULL,
    `end` date NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `users` (
    `id` int NOT NULL AUTO_INCREMENT,
    `permission_level` int NOT NULL DEFAULT '1',
    `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
    `passwort` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
    `vorname` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
    `nachname` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
COMMIT;