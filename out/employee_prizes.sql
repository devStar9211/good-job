-- MySQL dump 10.13  Distrib 5.7.17, for Linux (x86_64)
--
-- Host: localhost    Database: timeset_daily
-- ------------------------------------------------------
-- Server version	5.7.17

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

/* -- Split with mysqldumpsplitter (http://goo.gl/WIWj6d) -- */
-- Table structure for table `employee_prizes`
--

DROP TABLE IF EXISTS `employee_prizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_prizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `prize_id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `month` int(2) NOT NULL,
  `value` double NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`employee_id`,`prize_id`,`year`,`month`),
  KEY `prize_id` (`prize_id`),
  CONSTRAINT `employee_prizes_ibfk_1` FOREIGN KEY (`prize_id`) REFERENCES `prizes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employee_prizes_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_prizes`
--

LOCK TABLES `employee_prizes` WRITE;
/*!40000 ALTER TABLE `employee_prizes` DISABLE KEYS */;
INSERT INTO `employee_prizes` VALUES (2,1164,3,2017,5,4000,'2017-05-11 04:58:40','2017-05-12 09:28:02'),(3,1164,4,2017,5,2000,'2017-05-11 04:58:55','2017-05-12 09:27:00'),(4,1164,5,2017,5,3000,'2017-05-11 04:59:03','2017-05-12 09:27:22'),(5,1162,4,2017,5,8000,'2017-05-12 09:27:00','2017-05-12 09:27:00'),(6,1162,5,2017,5,8000,'2017-05-12 09:27:22','2017-05-12 09:27:22'),(7,1162,6,2017,5,4000,'2017-05-12 09:27:33','2017-05-12 09:27:33'),(8,1162,7,2017,5,30000,'2017-05-12 09:27:43','2017-05-12 09:27:43'),(9,1162,3,2017,5,90562,'2017-05-12 09:28:02','2017-05-12 09:28:02');
/*!40000 ALTER TABLE `employee_prizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_relationships`
