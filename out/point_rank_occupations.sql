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
-- Table structure for table `point_rank_occupations`
--

DROP TABLE IF EXISTS `point_rank_occupations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `point_rank_occupations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `point_rank_id` int(11) NOT NULL,
  `occupation_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `point_rank_id` (`point_rank_id`),
  KEY `occupation_id` (`occupation_id`),
  CONSTRAINT `point_rank_occupations_ibfk_1` FOREIGN KEY (`point_rank_id`) REFERENCES `point_ranks` (`id`),
  CONSTRAINT `point_rank_occupations_ibfk_2` FOREIGN KEY (`occupation_id`) REFERENCES `occupations` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `point_rank_occupations`
--

LOCK TABLES `point_rank_occupations` WRITE;
/*!40000 ALTER TABLE `point_rank_occupations` DISABLE KEYS */;
INSERT INTO `point_rank_occupations` VALUES (11,7,1,'2017-06-30 09:26:29','2017-06-30 09:26:29'),(12,10,9,'2017-06-30 09:26:29','2017-06-30 09:26:29'),(13,10,2,'2017-06-30 09:26:29','2017-06-30 09:26:29'),(23,1,1,'2018-09-03 08:02:16','2018-09-03 08:02:16'),(24,4,9,'2018-09-03 08:02:16','2018-09-03 08:02:16'),(25,4,2,'2018-09-03 08:02:16','2018-09-03 08:02:16');
/*!40000 ALTER TABLE `point_rank_occupations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `point_ranks`
