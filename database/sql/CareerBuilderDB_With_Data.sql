-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: localhost    Database: CareerBuilderDB
-- ------------------------------------------------------
-- Server version	5.5.16-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `listed_jobs`
--

DROP DATABASE IF EXISTS `CareerBuilderDB`; CREATE DATABASE `CareerBuilderDB`; USE `CareerBuilderDB`;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY 'root' WITH GRANT OPTION;

DROP TABLE IF EXISTS `listed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `listed_jobs` (
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
  KEY `state_fk` (`state_fk`),
  CONSTRAINT `listed_jobs_ibfk_1` FOREIGN KEY (`parent_city`) REFERENCES `parent_cities` (`city`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parent_cities`
--

DROP TABLE IF EXISTS `parent_cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parent_cities` (
  `city_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(75) NOT NULL,
  `state` varchar(75) NOT NULL,
  `country_code` varchar(75) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`city_id`),
  UNIQUE KEY `city` (`city`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-02-02 12:46:23
-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: localhost    Database: CareerBuilderDB
-- ------------------------------------------------------
-- Server version	5.5.16-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `parent_cities`
--

DROP TABLE IF EXISTS `parent_cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parent_cities` (
  `city_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(75) NOT NULL,
  `state` varchar(75) NOT NULL,
  `country_code` varchar(75) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`city_id`),
  UNIQUE KEY `city` (`city`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parent_cities`
--

LOCK TABLES `parent_cities` WRITE;
/*!40000 ALTER TABLE `parent_cities` DISABLE KEYS */;
INSERT INTO `parent_cities` VALUES (6,'San Diego','any','US',''),(7,'Los Angeles','any','US',''),(8,'San Francisco','any','US',''),(13,'Denver','any','US',''),(14,'Boulder','any','US',''),(18,'Colorado Springs','any','US',''),(19,'Fort Collins','any','US',''),(20,'Bellvue','any','US',''),(21,'Windsor','any','US',''),(22,'Greeley','any','US',''),(23,'Loveland','any','US',''),(24,'Longmont','any','US',''),(25,'Steamboat Springs','any','US',''),(26,'Craig','any','US',''),(27,'Breckenridge','any','US',''),(28,'Whitefish','any','US',''),(29,'Kalispell','any','US',''),(30,'Olympia','any','US',''),(31,'Seattle','any','US','');
/*!40000 ALTER TABLE `parent_cities` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-02-02 12:46:30
