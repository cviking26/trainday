-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 15. Jan 2014 um 17:19
-- Server Version: 5.5.27
-- PHP-Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `trainday`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `exercise`
--

CREATE TABLE IF NOT EXISTS `exercise` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `planid` int(10) NOT NULL,
  `name` varchar(90) COLLATE utf8_unicode_ci NOT NULL,
  `desc` varchar(90) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `exercise`
--

INSERT INTO `exercise` (`id`, `planid`, `name`, `desc`) VALUES
(1, 1, 'Bankdrücken', 'Mittelfinger in die einkerbung, 3 - 0 - 1');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `key`
--

CREATE TABLE IF NOT EXISTS `key` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `value` varchar(90) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `key`
--

INSERT INTO `key` (`id`, `value`) VALUES
(1, 'rep'),
(2, 'set'),
(3, 'weight');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `keyvalue`
--

CREATE TABLE IF NOT EXISTS `keyvalue` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `keyvalueid` int(10) NOT NULL,
  `excerciseid` int(10) NOT NULL,
  `value` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plan`
--

CREATE TABLE IF NOT EXISTS `plan` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `name` varchar(90) COLLATE utf8_unicode_ci NOT NULL,
  `desc` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `plan`
--

INSERT INTO `plan` (`id`, `userid`, `name`, `desc`) VALUES
(1, 1, 'Brust - Bizeps', 'Immer Montags, denk an die BCAA''s');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `set`
--

CREATE TABLE IF NOT EXISTS `set` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `values` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(90) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(90) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `username`, `password`) VALUES
(1, 'marlon', '1023');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_exerc_sets`
--

CREATE TABLE IF NOT EXISTS `user_exerc_sets` (
  `userId` int(10) NOT NULL,
  `exerciseId` int(10) NOT NULL,
  `setId` int(10) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `user_exerc_sets`
--

INSERT INTO `user_exerc_sets` (`userId`, `exerciseId`, `setId`, `date`) VALUES
(1, 4, 5, '0000-00-00 00:00:00'),
(1, 3, 5, '2014-01-15 17:02:42');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
