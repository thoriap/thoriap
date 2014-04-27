-- phpMyAdmin SQL Dump
-- version 4.0.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 28, 2014 at 01:27 AM
-- Server version: 5.5.35-0ubuntu0.12.04.2
-- PHP Version: 5.5.9-1+sury.org~precise+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `thoriap`
--
CREATE DATABASE IF NOT EXISTS `thoriap` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `thoriap`;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `group_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `group_access` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`, `group_description`, `group_access`) VALUES
(1, 'Administrator', 'Üst düzey yönetici yetkisine sahip kullanıcı grubu.', 'a:1:{s:13:"administrator";b:1;}');

-- --------------------------------------------------------

--
-- Table structure for table `groups_to_users`
--

CREATE TABLE IF NOT EXISTS `groups_to_users` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `group_id` (`group_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `groups_to_users`
--

INSERT INTO `groups_to_users` (`group_id`, `user_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `lang_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `lang_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lang_alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lang_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `lang_active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `lang_default` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`lang_id`),
  UNIQUE KEY `NAME` (`lang_name`),
  UNIQUE KEY `ALIAS` (`lang_alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`lang_id`, `lang_name`, `lang_alias`, `lang_code`, `lang_active`, `lang_default`) VALUES
(3, 'Türkçe', 'tr', 'tr', '1', '1'),
(4, 'English', 'en', 'en', '1', '0');

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE IF NOT EXISTS `plugins` (
  `plugin_id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `plugin_installed` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `plugin_active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `plugin_default` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`plugin_id`),
  UNIQUE KEY `PLUGIN_NAME` (`plugin_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `plugins`
--

INSERT INTO `plugins` (`plugin_id`, `plugin_name`, `plugin_installed`, `plugin_active`, `plugin_default`) VALUES
(1, 'kernel', '1', '1', '1'),
(2, 'themes', '1', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE IF NOT EXISTS `themes` (
  `theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `theme_active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`theme_id`),
  UNIQUE KEY `THEME_NAME` (`theme_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`theme_id`, `theme_name`, `theme_active`) VALUES
(1, 'default', '1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_access` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `USERNAME` (`username`),
  UNIQUE KEY `EMAIL` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `first_name`, `last_name`, `email`, `user_access`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Yalçın', 'Ceylan', 'yali_4_233@hotmail.com', 'a:1:{s:13:"administrator";s:1:"1";}');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
