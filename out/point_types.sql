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
-- Table structure for table `point_types`
--

DROP TABLE IF EXISTS `point_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `point_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(7) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `point_types`
--

LOCK TABLES `point_types` WRITE;
/*!40000 ALTER TABLE `point_types` DISABLE KEYS */;
INSERT INTO `point_types` VALUES (1,'業績ポイント','#9c69ad','2017-03-29 13:31:01','2017-06-16 06:54:30'),(2,'検定ポイント','#877ad4','2017-03-29 13:31:21','2017-06-16 06:54:49'),(3,'キャンペーンポイント','#9db6db','2017-03-29 15:21:59','2017-06-16 06:55:02'),(4,'紹介ポイント','#95d1eb','2017-03-29 15:22:01','2017-06-16 06:55:23'),(6,'研修ポイント','#93d6be','2017-06-01 08:25:24','2017-06-16 06:55:35'),(7,'5月検定ポイント','#bfe680','2017-06-01 11:21:44','2017-06-16 06:55:44'),(8,'資格ポイント','#e3de73','2017-06-01 11:22:27','2017-06-16 06:56:06'),(9,'4月業績ポイント','#c094db','2017-06-16 06:49:39','2017-06-16 06:56:29'),(10,'前期業績ポイント','#9c69ad','2017-11-15 10:34:11','2017-11-15 10:35:19'),(11,'前期検定ポイント','#877ad4','2017-11-15 10:34:42','2017-11-15 10:35:09'),(12,'前期キャンペーンポイント','#9db6db','2017-11-15 10:35:38','2017-11-15 10:35:38'),(13,'前期紹介ポイント','#95d1eb','2017-11-15 10:35:55','2017-11-15 10:35:55'),(14,'前期研修ポイント','#93d6be','2017-11-15 10:36:13','2017-11-15 10:36:13'),(15,'前期資格ポイント','#e3de73','2017-11-15 10:37:01','2017-11-15 10:37:01'),(16,'前期凍結ポイント','#2ec4e6','2018-08-16 03:09:32','2018-08-16 03:09:46'),(17,'勤続ポイント','#1ab876','2018-11-01 04:40:52','2018-11-01 04:41:15'),(18,'エリア業績ポイント','#668aeb','2020-02-03 10:13:00','2020-02-03 10:13:25'),(19,'(4月5月分)検定修正ポイント','#00ffff','2020-11-11 08:57:42','2020-11-11 08:58:19');
/*!40000 ALTER TABLE `point_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `positions`
