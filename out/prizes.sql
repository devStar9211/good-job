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
-- Table structure for table `prizes`
--

DROP TABLE IF EXISTS `prizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prizes`
--

LOCK TABLES `prizes` WRITE;
/*!40000 ALTER TABLE `prizes` DISABLE KEYS */;
INSERT INTO `prizes` VALUES (3,'業績ポイント','2017-05-12 09:24:38','2017-05-12 09:24:38'),(4,'研修ポイント','2017-04-14 06:32:25','2017-04-14 06:32:25'),(5,'検定ポイント','2017-05-12 09:25:09','2017-05-12 09:25:09'),(6,'キャンペーンポイント','2017-05-12 09:25:21','2017-05-12 09:25:21'),(7,'紹介ポイント','2017-05-12 09:25:53','2017-05-12 09:25:53');
/*!40000 ALTER TABLE `prizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revenues`
