SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+02:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `wochenplan` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `wochenplan`;

CREATE TABLE IF NOT EXISTS `angebot` (
                                         `id` int(11) NOT NULL AUTO_INCREMENT,
                                         `date_type` int(255) NOT NULL DEFAULT 0,
                                         `date_repeating` int(255) DEFAULT NULL,
                                         `date` date DEFAULT NULL,
                                         `name` varchar(255) NOT NULL,
                                         `description` varchar(255) NOT NULL,
                                         `location` int(11) NOT NULL,
                                         `time` int(11) NOT NULL,
                                         `box_color` varchar(20) NOT NULL DEFAULT '#f6e9e6',
                                         `notes` varchar(255) DEFAULT NULL,
                                         `assigned_user_id` int(11) NOT NULL,
                                         PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `registertokens` (
                                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                                `token` varchar(255) NOT NULL,
                                                `email` varchar(255) NOT NULL,
                                                `created` timestamp NOT NULL DEFAULT current_timestamp(),
                                                PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `securitytokens` (
                                                `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                                                `user_id` int(11) NOT NULL,
                                                `identifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                                `securitytoken` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                                                PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `sick` (
                                      `id` int(11) NOT NULL AUTO_INCREMENT,
                                      `userid` varchar(255) NOT NULL,
                                      `start` date NOT NULL DEFAULT current_timestamp(),
                                      `end` date NOT NULL,
                                      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `users` (
                                       `id` int(11) NOT NULL AUTO_INCREMENT,
                                       `permission_level` int(11) NOT NULL DEFAULT 1,
                                       `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                       `passwort` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                       `vorname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                                       `nachname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                                       `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                                       `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                                       `passwortcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                                       `passwortcode_time` timestamp NULL DEFAULT NULL,
                                       PRIMARY KEY (`id`),
                                       UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
