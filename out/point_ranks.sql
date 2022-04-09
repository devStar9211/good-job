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
-- Table structure for table `point_ranks`
--

DROP TABLE IF EXISTS `point_ranks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `point_ranks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_group_id` int(11) NOT NULL,
  `rank_name` varchar(100) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `stage_id` int(11) NOT NULL,
  `necessary_point` double DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `subsidize_rate` float NOT NULL,
  `priority` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `working_time` int(11) NOT NULL,
  `rank_card` varchar(200) DEFAULT NULL,
  `color` varchar(7) NOT NULL,
  `working_time_id` int(11) DEFAULT NULL,
  `use_necessary_point` tinyint(2) NOT NULL DEFAULT '1',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_group_id` (`company_group_id`),
  KEY `stage_id` (`stage_id`),
  KEY `working_time_id` (`working_time_id`),
  CONSTRAINT `point_ranks_ibfk_1` FOREIGN KEY (`working_time_id`) REFERENCES `working_times` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `point_ranks`
--

LOCK TABLES `point_ranks` WRITE;
/*!40000 ALTER TABLE `point_ranks` DISABLE KEYS */;
INSERT INTO `point_ranks` VALUES (1,1,'A',1,NULL,5,10,1,6,0,'rankcard_master.png','#1c1c1c',NULL,0,'2017-04-09 14:16:18','2018-09-03 08:02:16'),(2,1,'B',2,1000000,4,4,2,5,0,'rankcard_platinum.png','#656565',NULL,1,'2017-04-09 14:16:18','2018-09-03 08:02:16'),(3,1,'C',3,500000,4,3,3,4,0,'rankcard_gold.png','#fcb946',NULL,1,'2017-04-09 14:22:04','2018-09-03 08:02:16'),(4,1,'D',4,200000,0,2,4,3,0,'rankcard_silver.png','#969695',NULL,1,'2017-04-09 14:22:04','2018-09-03 08:02:16'),(5,1,'E',5,NULL,0,1,5,2,1,'rankcard_regurar.png','#3063a6',1,0,'2017-04-09 14:22:04','2018-09-03 08:02:16'),(6,1,'F',6,NULL,0,0,6,1,0,'rankcard_geginner.png','#31b8a9',2,0,'2017-04-09 14:22:04','2018-09-03 08:02:16'),(7,2,'A',1,1000000,5,10,1,6,0,'rankcard_master.png','#1c1c1c',NULL,0,'2017-04-09 07:16:18','2017-06-30 09:26:29'),(8,2,'B',2,1000000,4,4,2,5,0,'rankcard_platinum.png','#656565',NULL,1,'2017-04-09 07:16:18','2017-06-30 09:26:29'),(9,2,'C',3,500000,4,3,3,4,0,'rankcard_gold.png','#fcb946',NULL,1,'2017-04-09 07:22:04','2017-06-30 09:26:29'),(10,2,'D',4,200000,0,3,4,3,0,'rankcard_silver.png','#969695',NULL,1,'2017-04-09 07:22:04','2017-06-30 09:26:29'),(11,2,'E',5,NULL,0,1,5,2,1,'rankcard_regurar.png','#3063a6',1,0,'2017-04-09 07:22:04','2017-06-30 09:26:29'),(12,2,'F',6,NULL,0,0.5,6,1,0,'rankcard_geginner.png','#31b8a9',2,0,'2017-04-09 07:22:04','2017-06-30 09:26:29');
/*!40000 ALTER TABLE `point_ranks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `point_types`
