-- Host: localhost:3306
-- Erstellungszeit: 17. Jun 2024 um 23:25
-- Server-Version: 10.6.16-MariaDB-0ubuntu0.22.04.1
-- PHP-Version: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `wochenplan`
--
DROP DATABASE IF EXISTS `wochenplan`;
CREATE DATABASE IF NOT EXISTS `wochenplan` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `wochenplan`;
--
-- Tabellenstruktur für Tabelle `angebot`
--
DROP TABLE IF EXISTS `angebot`;
CREATE TABLE IF NOT EXISTS `angebot` (
                                         `id` int(11) NOT NULL AUTO_INCREMENT,
                                         `parent_lesson_id` int(255) DEFAULT NULL,
                                         `identifier` varchar(255) NOT NULL,
                                         `disabled` tinyint(1) NOT NULL DEFAULT 0,
                                         `date_type` int(255) NOT NULL DEFAULT 0,
                                         `date_repeating` int(255) DEFAULT NULL,
                                         `date` date DEFAULT NULL,
                                         `name` varchar(500) NOT NULL,
                                         `description` varchar(500) DEFAULT NULL,
                                         `location` int(11) NOT NULL,
                                         `time` int(11) NOT NULL,
                                         `box_color` varchar(20) NOT NULL DEFAULT NULL,
                                         `notes` varchar(500) DEFAULT NULL,
                                         `assigned_user_id` int(11) NOT NULL,
                                         `last_change_from_userid` int(11) DEFAULT NULL,
                                         `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                                         `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                                         `deleted_at` timestamp DEFAULT NULL,
                                         PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2974 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
--
-- Tabellenstruktur für Tabelle `lunchdata`
--
DROP TABLE IF EXISTS `lunchdata`;
CREATE TABLE IF NOT EXISTS `lunchdata` (
                                           `id` int(11) NOT NULL AUTO_INCREMENT,
                                           `date` date NOT NULL,
                                           `data` varchar(255) NOT NULL,
                                           PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Tabellenstruktur für Tabelle `passwordresettokens`
--
DROP TABLE IF EXISTS `passwordresettokens`;
CREATE TABLE IF NOT EXISTS `passwordresettokens` (
                                                     `id` int(11) NOT NULL AUTO_INCREMENT,
                                                     `token` varchar(255) NOT NULL,
                                                     `userid` varchar(255) NOT NULL,
                                                     `created` timestamp NOT NULL DEFAULT current_timestamp(),
                                                     PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Tabellenstruktur für Tabelle `registertokens`
--
DROP TABLE IF EXISTS `registertokens`;
CREATE TABLE IF NOT EXISTS `registertokens` (
                                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                                `token` varchar(255) NOT NULL,
                                                `email` varchar(255) NOT NULL,
                                                `created` timestamp NOT NULL DEFAULT current_timestamp(),
                                                PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=284 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Tabellenstruktur für Tabelle `securitytokens`
--
DROP TABLE IF EXISTS `securitytokens`;
CREATE TABLE IF NOT EXISTS `securitytokens` (
                                                `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                                                `user_id` int(11) NOT NULL,
                                                `identifier` varchar(255) NOT NULL,
                                                `securitytoken` varchar(255) NOT NULL,
                                                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                                                PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=271 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
--
-- Tabellenstruktur für Tabelle `settings`
--
DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
                                          `id` int(11) NOT NULL AUTO_INCREMENT,
                                          `setting` varchar(255) NOT NULL,
                                          `suffix` varchar(255) DEFAULT NULL,
                                          `value` text NOT NULL,
                                          PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO `settings` (`id`, `setting`, `suffix`, `value`) VALUES
                                                                (1, 'identifier', NULL, '--/--'),
                                                                (2, 'maintenance', NULL, '0'),
                                                                (10, 'times', '1', 'Den ganzen Tag gültig'),
                                                                (11, 'rooms', '1', 'Raumlos'),
                                                                (12, 'colors', 'Hintergrund', '#f6e9e6'),
                                                                (13, 'plan', 'Example', '<thead><tr id=\"tableHeader\" class=\"hideCell\"><th class=\"Header hideCell\"><\\/th><th class=\"Header hideCell\">A<\\/th>                        <th class=\"Header hideCell\">B<\\/th><th class=\"Header hideCell\">C<\\/th><th class=\"Header hideCell\">D<\\/th><th class=\"Header hideCell\">E<\\/th><th class=\"Header hideCell\">F<\\/th><th class=\"Header hideCell\">G<\\/th><th class=\"Header hideCell\">H<\\/th><th class=\"Header hideCell\">I<\\/th><th class=\"Header hideCell\">J<\\/th><th class=\"Header hideCell\">K<\\/th></tr><\\/thead>                            <tbody>                            <tr style=\"height: 5vh;\"><th class=\"Header hideCell\">1<\\/th><td class=\"center2\" style=\"background-color: rgb(208, 145, 130);\">%date%</td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"5\" rowspan=\"1\" time=\"13\" room=\"10\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"\" style=\"background-color: rgb(208, 145, 130);\" colspan=\"5\" rowspan=\"1\">%sick%</td><td class=\"hideCell\" style=\"background-color: rgb(208, 145, 130);\"></td><td class=\"hideCell\" style=\"background-color: rgb(208, 145, 130);\"></td><td class=\"hideCell\" style=\"background-color: rgb(208, 145, 130);\"></td><td class=\"hideCell\" style=\"background-color: rgb(208, 145, 130);\"></td></tr><tr><th class=\"Header hideCell\">2<\\/th><td class=\"center2\" style=\"background-color: rgb(227, 189, 180);\"><b class=\"bold\">Zeiten l\\/ll<\\/b></td><td class=\"center2\" style=\"background-color: rgb(236, 211, 205);\"><b class=\"bold\">Raum 1<\\/b></td><td class=\"center2\" style=\"background-color: rgb(236, 211, 205);\"><b class=\"bold\">Freiarbeit<\\/b></td><td class=\"center2\" style=\"background-color: rgb(227, 189, 180);\"><b class=\"bold\">Zeiten ll-lV<\\/b></td><td class=\"center2\" style=\"background-color: rgb(236, 211, 205);\"><b class=\"bold\">Raum 2<\\/b></td><td class=\"center2\" style=\"background-color: rgb(236, 211, 205);\"><b class=\"bold\">Raum 3 (HS)<\\/b></td><td class=\"center2\" style=\"background-color: rgb(236, 211, 205);\"><b class=\"bold\">Raum 4 (RS)<\\/b></td><td class=\"center2\" style=\"background-color: rgb(236, 211, 205);\"><b class=\"bold\">Gespr\\u00e4chsraum<\\/b></td><td class=\"center2\" style=\"background-color: rgb(236, 211, 205);\"><b class=\"bold\">Sonnenzimmer<\\/b></td><td class=\"center2\" style=\"background-color: rgb(236, 211, 205);\"><b class=\"bold\">Extern<\\/b></td><td class=\"center2\" style=\"background-color: rgb(236, 211, 205);\"><b class=\"bold\">Ext.<\\/b></td></tr><tr><th class=\"Header hideCell\">3<\\/th><td class=\"\" style=\"background-color: rgb(227, 189, 180);\"><p><\\/p>8:00 \\u2013 9:00<p><\\/p><b class=\"bold\">Morgenband<\\/b></td><td class=\"show-info center2\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"2\" rowspan=\"1\" time=\"1\" room=\"1\"></td><td class=\"hideCell\" style=\"background-color: rgb(255, 255, 255);\"></td><td class=\"\" style=\"background-color: rgb(227, 189, 180);\"><p><\\/p>8:00 \\u2013 9:00<p><\\/p><b class=\"bold\">Morgenband<\\/b></td><td class=\"show-info center2\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"7\" rowspan=\"1\" time=\"1\" room=\"10\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td></tr><tr><th class=\"Header hideCell\">4<\\/th><td class=\"\" style=\"background-color: rgb(227, 189, 180);\"><p>9:00 \\u2013 9:30<\\/p><b class=\"bold\">Morgenkreise<\\/b></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"2\" room=\"1\"></td><td class=\"show-info\" style=\"background-color: rgb(236, 211, 205);\" time=\"2\" room=\"9\"></td><td class=\"\" style=\"background-color: rgb(227, 189, 180);\" colspan=\"1\" rowspan=\"2\"><p>9:00 - 10:00<\\/p><b class=\"bold\">Offene R\\u00e4ume<\\/b></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"6\" room=\"2\" colspan=\"1\" rowspan=\"2\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"6\" room=\"3\" colspan=\"1\" rowspan=\"2\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"6\" room=\"4\" colspan=\"1\" rowspan=\"2\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"6\" room=\"5\" colspan=\"1\" rowspan=\"2\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"6\" room=\"6\" colspan=\"1\" rowspan=\"2\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"6\" room=\"8\" colspan=\"1\" rowspan=\"2\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"6\" room=\"14\" colspan=\"1\" rowspan=\"2\"></td></tr><tr style=\"height: 5vh;\"><th class=\"Header hideCell\">5<\\/th><td class=\"\" style=\"background-color: rgb(227, 189, 180);\" colspan=\"1\" rowspan=\"2\"><p>9:30 \\u2013 10:30<\\/p><b class=\"bold\">Angebot 1<\\/b></td><td class=\"show-info selected\" time=\"3\" room=\"1\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"1\" rowspan=\"2\"></td><td class=\"show-info\" time=\"3\" room=\"9\" style=\"background-color: rgb(236, 211, 205);\" colspan=\"1\" rowspan=\"2\"></td><td class=\"hideCell\" style=\"background-color: rgb(227, 189, 180);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td></tr><tr><th class=\"Header hideCell\">6<\\/th><td class=\"hideCell\" style=\"background-color: rgb(227, 189, 180);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\" time=\"\" room=\"1\"></td><td class=\"hideCell\" style=\"background-color: rgb(236, 211, 205);\" time=\"\" room=\"\"></td><td class=\"\" style=\"background-color: rgb(227, 189, 180);\"><p>10:00 \\u2013 10:30<\\/p><b class=\"bold\">Morgenkreise<\\/b></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"7\" room=\"2\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"7\" room=\"3\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"7\" room=\"4\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"1\" rowspan=\"2\" time=\"7\" room=\"5\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"1\" rowspan=\"2\" time=\"7\" room=\"6\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"1\" rowspan=\"2\" time=\"7\" room=\"8\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"1\" rowspan=\"2\" time=\"7\" room=\"14\"></td></tr><tr><th class=\"Header hideCell\">7<\\/th><td class=\"\" style=\"background-color: rgb(227, 189, 180);\"><b class=\"bold\">R\\u00e4um-Pause <\\/b></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"15\" room=\"1\"></td><td class=\"show-info\" style=\"background-color: rgb(236, 211, 205);\" time=\"15\" room=\"9\"></td><td class=\"\" style=\"background-color: rgb(227, 189, 180);\" colspan=\"1\" rowspan=\"3\"><p>10:30 \\u2013 12:00<\\/p><b class=\"bold\">Gro\\u00dfes Band<\\/b></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"1\" rowspan=\"3\" time=\"8\" room=\"2\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"1\" rowspan=\"3\" time=\"8\" room=\"3\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"1\" rowspan=\"3\" time=\"8\" room=\"4\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td></tr><tr><th class=\"Header hideCell\">8<\\/th><td class=\"\" style=\"background-color: rgb(227, 189, 180);\"><p>10:45 \\u2013 11:45<\\/p><b class=\"bold\">Angebot 2<\\/b></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"4\" room=\"1\"></td><td class=\"show-info\" style=\"background-color: rgb(236, 211, 205);\" time=\"4\" room=\"9\"></td><td class=\"hideCell\" style=\"background-color: rgb(227, 189, 180);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"1\" rowspan=\"2\" time=\"8\" room=\"5\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"1\" rowspan=\"2\" time=\"8\" room=\"6\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"1\" rowspan=\"2\" time=\"8\" room=\"8\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"1\" rowspan=\"2\" time=\"8\" room=\"14\"></td></tr><tr><th class=\"Header hideCell\">9<\\/th><td class=\"\" style=\"background-color: rgb(227, 189, 180);\"><p>11:45 \\u2013 12:00<\\/p><b class=\"bold\">Logbuchzeit<\\/b></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"16\" room=\"1\"></td><td class=\"show-info\" style=\"background-color: rgb(236, 211, 205);\" time=\"16\" room=\"9\"></td><td class=\"hideCell\" style=\"background-color: rgb(227, 189, 180);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td></tr><tr><th class=\"Header hideCell\">10<\\/th><td class=\"\" style=\"background-color: rgb(227, 189, 180);\"><p>12:00 \\u2013 13:00<\\/p><b class=\"bold\">Mittagspause<\\/b></td><td class=\"center2 show-info\" style=\"background-color: rgb(229, 244, 212);\" colspan=\"10\" rowspan=\"1\" time=\"14\" room=\"10\"></td><td class=\"hideCell\" style=\"background-color: rgb(236, 211, 205);\"></td><td class=\"hideCell\" style=\"background-color: rgb(229, 244, 212);\"></td><td class=\"hideCell\" style=\"background-color: rgb(229, 244, 212);\"></td><td class=\"hideCell\" style=\"background-color: rgb(229, 244, 212);\"></td><td class=\"hideCell\" style=\"background-color: rgb(229, 244, 212);\"></td><td class=\"hideCell\" style=\"background-color: rgb(229, 244, 212);\"></td><td class=\"hideCell\" style=\"background-color: rgb(229, 244, 212);\"></td><td class=\"hideCell\" style=\"background-color: rgb(229, 244, 212);\"></td><td class=\"hideCell\" style=\"background-color: rgb(229, 244, 212);\"></td></tr><tr><th class=\"Header hideCell\">11<\\/th><td class=\"\" style=\"background-color: rgb(227, 189, 180);\"><p>13:00 \\u2013 14:30<\\/p><b class=\"bold\">Nachmittagsband<\\/b></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"5\" room=\"1\"></td><td class=\"show-info\" style=\"background-color: rgb(236, 211, 205);\" time=\"5\" room=\"9\"></td><td class=\"\" style=\"background-color: rgb(227, 189, 180);\"><p>13:00 \\u2013 14:30<\\/p><b class=\"bold\">Nachmittagsband<\\/b></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"9\" room=\"2\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"9\" room=\"3\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"9\" room=\"4\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"9\" room=\"5\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"9\" room=\"6\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"9\" room=\"8\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"5\" room=\"14\"></td></tr><tr><th class=\"Header hideCell\">12<\\/th><td class=\"\" style=\"background-color: rgb(227, 189, 180);\"><p>14:15 \\u2013 14:30<\\/p><b class=\"bold\">Logbuchzeit<\\/b></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"15\" room=\"1\"></td><td class=\"show-info\" style=\"background-color: rgb(236, 211, 205);\" time=\"15\" room=\"1\"></td><td class=\"\" style=\"background-color: rgb(227, 189, 180);\"><p>14:15 \\u2013 14:30<\\/p><b class=\"bold\">Logbuchzeit<\\/b></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"3\" rowspan=\"1\" time=\"15\" room=\"2\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"15\" room=\"5\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"15\" room=\"6\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" colspan=\"2\" rowspan=\"1\" time=\"15\" room=\"8\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td></tr><tr><th class=\"Header hideCell\">13<\\/th><td class=\"\" style=\"background-color: rgb(255, 255, 255);\" colspan=\"3\" rowspan=\"3\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"\" style=\"background-color: rgb(227, 189, 180);\" colspan=\"1\" rowspan=\"2\"><p>14:30 \\u2013 15:00<\\/p><b class=\"bold\">Putzen<\\/b></td><td class=\"show-info center2\" style=\"background-color: rgb(248, 233, 190);\" colspan=\"7\" rowspan=\"1\" time=\"12\" room=\"10\"></td><td class=\"hideCell\" style=\"background-color: rgb(248, 233, 190);\"></td><td class=\"hideCell\" style=\"background-color: rgb(248, 233, 190);\"></td><td class=\"hideCell\" style=\"background-color: rgb(248, 233, 190);\"></td><td class=\"hideCell\" style=\"background-color: rgb(248, 233, 190);\"></td><td class=\"hideCell\" style=\"background-color: rgb(248, 233, 190);\"></td><td class=\"hideCell\" style=\"background-color: rgb(248, 233, 190);\"></td></tr><tr><th class=\"Header hideCell\">14<\\/th><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(246, 233, 230);\"></td><td class=\"hideCell\" style=\"background-color: rgb(227, 189, 180);\"></td><td class=\"show-info center2\" style=\"background-color: rgb(248, 233, 190);\" colspan=\"2\" rowspan=\"1\" time=\"12\" room=\"11\"></td><td class=\"hideCell center2\" style=\"background-color: rgb(248, 233, 190);\"></td><td class=\"show-info center2\" style=\"background-color: rgb(248, 233, 190);\" colspan=\"3\" rowspan=\"1\" time=\"12\" room=\"12\"></td><td class=\"hideCell center2\" style=\"background-color: rgb(248, 233, 190);\"></td><td class=\"hideCell center2\" style=\"background-color: rgb(248, 233, 190);\"></td><td class=\"show-info center2\" style=\"background-color: rgb(248, 233, 190);\" colspan=\"2\" rowspan=\"1\" time=\"12\" room=\"13\"></td><td class=\"hideCell\" style=\"background-color: rgb(248, 233, 190);\"></td></tr><tr><th class=\"Header hideCell\">15<\\/th><td class=\"hideCell\"></td><td class=\"hideCell\"></td><td class=\"hideCell\"></td><td class=\"\" style=\"background-color: rgb(227, 189, 180);\"><p>15:00 \\u2013 16:00<\\/p><b class=\"bold\">Sp\\u00e4tes Band<\\/b></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"10\" room=\"2\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"10\" room=\"3\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"10\" room=\"4\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"10\" room=\"5\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"10\" room=\"6\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"10\" room=\"8\"></td><td class=\"show-info\" style=\"background-color: rgb(246, 233, 230);\" time=\"10\" room=\"14\"></td></tr></tbody>');
--
-- Tabellenstruktur für Tabelle `sick`
--
DROP TABLE IF EXISTS `sick`;
CREATE TABLE IF NOT EXISTS `sick` (
                                      `id` int(11) NOT NULL AUTO_INCREMENT,
                                      `userid` varchar(255) NOT NULL,
                                      `start` date NOT NULL DEFAULT current_timestamp(),
                                      `end` date NOT NULL,
                                      PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=302 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Tabellenstruktur für Tabelle `users`
--
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
                                       `id` int(11) NOT NULL AUTO_INCREMENT,
                                       `permission_level` int(11) NOT NULL DEFAULT 1,
                                       `settings` varchar(255) NOT NULL,
                                       `email` varchar(255) NOT NULL,
                                       `passwort` varchar(255) NOT NULL,
                                       `vorname` varchar(255) NOT NULL DEFAULT '',
                                       `nachname` varchar(255) NOT NULL DEFAULT '',
                                       `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                                       `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                                       PRIMARY KEY (`id`),
                                       UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
