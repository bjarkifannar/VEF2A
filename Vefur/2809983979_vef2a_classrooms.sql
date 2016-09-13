-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 13, 2016 at 10:28 AM
-- Server version: 5.7.14-log
-- PHP Version: 5.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `2809983979_vef2a_classrooms`
--

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE IF NOT EXISTS `buildings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`id`, `name`, `address`) VALUES
(1, 'Skólavörðuholt', NULL),
(2, 'Vörðuskóli', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classrooms`
--

CREATE TABLE IF NOT EXISTS `classrooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `building_id` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_building_id` (`building_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `classrooms`
--

INSERT INTO `classrooms` (`id`, `building_id`, `name`) VALUES
(1, 2, '621'),
(2, 2, '629'),
(3, 1, '404'),
(4, 1, '303');

-- --------------------------------------------------------

--
-- Table structure for table `days`
--

CREATE TABLE IF NOT EXISTS `days` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `days`
--

INSERT INTO `days` (`id`, `name`) VALUES
(1, 'Mánudagur'),
(2, 'Þriðjudagur'),
(3, 'Miðvikudagur'),
(4, 'Fimmtudagur'),
(5, 'Föstudagur');

-- --------------------------------------------------------

--
-- Table structure for table `times`
--

CREATE TABLE IF NOT EXISTS `times` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classroom_id` int(11) NOT NULL,
  `time_from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `time_to` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `day_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_day_id` (`day_id`),
  KEY `fk_classroom_id` (`classroom_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `times`
--

INSERT INTO `times` (`id`, `classroom_id`, `time_from`, `time_to`, `day_id`) VALUES
(1, 1, '12:30', '15:30', 4),
(2, 3, '12:30', '13:10', 2),
(3, 2, '15:30', '17:30', 1),
(4, 3, '21:00', '22:30', 1),
(5, 1, '21:00', '22:30', 2),
(6, 2, '07:30', '09:00', 2),
(7, 3, '07:00', '12:00', 3),
(8, 3, '08:10', '10:30', 5),
(9, 1, '12:30', '13:10', 2),
(10, 2, '12:30', '13:10', 2),
(11, 3, '12:30', '13:10', 2),
(12, 4, '12:30', '13:10', 2),
(13, 1, '12:30', '13:10', 1),
(14, 2, '12:30', '13:10', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD CONSTRAINT `fk_building_id` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`);

--
-- Constraints for table `times`
--
ALTER TABLE `times`
  ADD CONSTRAINT `fk_classroom_id` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`),
  ADD CONSTRAINT `fk_day_id` FOREIGN KEY (`day_id`) REFERENCES `days` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
