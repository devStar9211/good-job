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
-- Table structure for table `business_categories`
--

DROP TABLE IF EXISTS `business_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `business_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_categories`
--

LOCK TABLES `business_categories` WRITE;
/*!40000 ALTER TABLE `business_categories` DISABLE KEYS */;
INSERT INTO `business_categories` VALUES (1,'デイサービス',1,'2017-03-16 02:37:32','2017-03-29 02:58:50'),(2,'住宅型有料老人ホーム',2,'2017-03-16 10:15:30','2017-03-29 02:58:50'),(3,'訪問看護事業所',3,'2017-03-16 10:15:43','2017-03-29 02:58:50'),(4,'居宅介護支援事業所',4,'2017-03-16 10:15:54','2017-03-29 02:58:50'),(5,'巡回看護事業所',5,'2017-03-16 10:16:03','2017-03-29 02:58:50'),(6,'送迎委託事業所',6,'2017-03-16 10:16:11','2017-03-29 02:58:50'),(7,'給食事業',7,'2017-03-17 08:44:03','2017-03-29 08:37:28'),(8,'本社',NULL,'2017-04-14 02:04:02','2017-04-14 02:04:02'),(9,'管理部',NULL,'2017-04-14 02:20:03','2017-04-14 02:20:03'),(10,'訪問介護事業所',NULL,'2019-04-24 08:41:43','2019-04-24 08:41:43'),(11,'紹介営業事業',NULL,'2019-10-29 05:28:30','2019-11-15 06:27:50'),(12,'福祉用具貸与事業',NULL,'2021-04-05 08:01:43','2021-04-05 08:01:43');
/*!40000 ALTER TABLE `business_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cake_sessions`
