-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 24, 2013 at 11:17 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cbapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `listed_jobs`
--

CREATE TABLE IF NOT EXISTS `listed_jobs` (
  `job_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_city` varchar(75) NOT NULL,
  `state_fk` varchar(75) NOT NULL,
  `company` varchar(75) NOT NULL,
  `relocation` enum('true','false') NOT NULL,
  `job_document_identifier` varchar(30) NOT NULL,
  `onet_code` varchar(20) NOT NULL,
  `oNet_friendly_title` varchar(75) NOT NULL,
  `distance` varchar(30) NOT NULL,
  `employment_type` varchar(15) NOT NULL,
  `blank_application_service_url` text NOT NULL,
  `location_city` varchar(85) DEFAULT NULL,
  `location_latitude` float(9,6) DEFAULT NULL,
  `location_longitude` float(9,6) DEFAULT NULL,
  `job_title` varchar(50) NOT NULL,
  `posted_date` int(10) NOT NULL,
  `time_response_sent` int(10) NOT NULL,
  PRIMARY KEY (`job_id`),
  KEY `parent_city` (`parent_city`),
  KEY `state_fk` (`state_fk`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Table structure for table `parent_cities`
--

CREATE TABLE IF NOT EXISTS `parent_cities` (
  `city_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(75) NOT NULL,
  `state` varchar(75) NOT NULL,
  `country_code` varchar(75) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`city_id`),
  UNIQUE KEY `city` (`city`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `listed_jobs`
--
ALTER TABLE `listed_jobs`
  ADD CONSTRAINT `listed_jobs_ibfk_1` FOREIGN KEY (`parent_city`) REFERENCES `parent_cities` (`city`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
