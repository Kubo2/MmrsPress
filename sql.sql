-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Sob 29. pro 2012, 14:08
-- Verze MySQL: 5.5.25-MariaDB
-- Verze PHP: 5.3.19

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `mmrs_komercial`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `mmrs_book`
--

CREATE TABLE IF NOT EXISTS `mmrs_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_bin NOT NULL,
  `email` varchar(50) COLLATE utf8_bin NOT NULL,
  `web` varchar(100) COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  `mesage` varchar(5000) COLLATE utf8_bin NOT NULL,
  `avatar` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struktura tabulky `mmrs_counter`
--

CREATE TABLE IF NOT EXISTS `mmrs_counter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `info` varchar(10000) COLLATE utf8_bin NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struktura tabulky `mmrs_counter_all`
--

CREATE TABLE IF NOT EXISTS `mmrs_counter_all` (
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struktura tabulky `mmrs_gallery`
--

CREATE TABLE IF NOT EXISTS `mmrs_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(35) COLLATE utf8_bin NOT NULL,
  `label` varchar(500) COLLATE utf8_bin NOT NULL,
  `folder` varchar(35) COLLATE utf8_bin NOT NULL,
  `public` int(2) NOT NULL,
  `autor` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struktura tabulky `mmrs_layoutSet`
--

CREATE TABLE IF NOT EXISTS `mmrs_layoutSet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_bin NOT NULL,
  `description` varchar(250) COLLATE utf8_bin NOT NULL,
  `padding` int(11) NOT NULL,
  `layout` varchar(30) COLLATE utf8_bin NOT NULL,
  `logo` varchar(20) COLLATE utf8_bin NOT NULL,
  `float` varchar(2) COLLATE utf8_bin NOT NULL,
  `width` varchar(20) COLLATE utf8_bin NOT NULL,
  `backColor` varchar(8) COLLATE utf8_bin NOT NULL,
  `Acolor` varchar(8) COLLATE utf8_bin NOT NULL,
  `ContentColor` varchar(8) COLLATE utf8_bin NOT NULL,
  `AcolorH` varchar(8) COLLATE utf8_bin NOT NULL,
  `pageColor` varchar(8) COLLATE utf8_bin NOT NULL,
  `textColor` varchar(8) COLLATE utf8_bin NOT NULL,
  `hColor` varchar(8) COLLATE utf8_bin NOT NULL,
  `footer` varchar(8) COLLATE utf8_bin NOT NULL,
  `menuA` varchar(8) COLLATE utf8_bin NOT NULL,
  `menuH` varchar(8) COLLATE utf8_bin NOT NULL,
  `galleryTd` varchar(8) COLLATE utf8_bin NOT NULL,
  `galleryTdH` varchar(8) COLLATE utf8_bin NOT NULL,
  `tdColor` varchar(8) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struktura tabulky `mmrs_news`
--

CREATE TABLE IF NOT EXISTS `mmrs_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8_bin NOT NULL,
  `menu` varchar(30) COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  `autor` varchar(50) COLLATE utf8_bin NOT NULL,
  `section` varchar(30) COLLATE utf8_bin NOT NULL,
  `public` int(2) NOT NULL,
  `publicDate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struktura tabulky `mmrs_photos`
--

CREATE TABLE IF NOT EXISTS `mmrs_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(300) COLLATE utf8_bin NOT NULL,
  `photo` varchar(30) COLLATE utf8_bin NOT NULL,
  `folder` varchar(35) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struktura tabulky `mmrs_section`
--

CREATE TABLE IF NOT EXISTS `mmrs_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(100) COLLATE utf8_bin NOT NULL,
  `public` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struktura tabulky `mmrs_setImg`
--

CREATE TABLE IF NOT EXISTS `mmrs_setImg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thumbImg` int(11) NOT NULL,
  `wiewImg` int(11) NOT NULL,
  `newsImg` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struktura tabulky `mmrs_setRss`
--

CREATE TABLE IF NOT EXISTS `mmrs_setRss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news` int(2) NOT NULL,
  `pages` int(2) NOT NULL,
  `book` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struktura tabulky `mmrs_settings`
--

CREATE TABLE IF NOT EXISTS `mmrs_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `select` varchar(100) COLLATE utf8_bin NOT NULL,
  `count` int(11) NOT NULL,
  `public` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struktura tabulky `mmrs_users`
--

CREATE TABLE IF NOT EXISTS `mmrs_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users` varchar(30) COLLATE utf8_bin NOT NULL,
  `password` varchar(120) COLLATE utf8_bin NOT NULL,
  `web` varchar(100) COLLATE utf8_bin NOT NULL,
  `email` varchar(120) COLLATE utf8_bin NOT NULL,
  `role` int(2) DEFAULT NULL,
  `avatar` varchar(50) COLLATE utf8_bin NOT NULL,
  `active` varchar(32) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
