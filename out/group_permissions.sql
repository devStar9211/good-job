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
-- Table structure for table `group_permissions`
--

DROP TABLE IF EXISTS `group_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_permissions`
--

LOCK TABLES `group_permissions` WRITE;
/*!40000 ALTER TABLE `group_permissions` DISABLE KEYS */;
INSERT INTO `group_permissions` VALUES (1,'super admin','2017-01-18 03:54:30','2017-03-21 10:56:43'),(2,'company admin user','2017-01-18 03:54:30','2017-03-21 10:56:43'),(10,'株式会社ケアギバー・ジャパン','2017-01-18 03:54:30','2017-05-16 05:37:14'),(12,'株式会社島田屋','2017-03-16 09:49:48','2017-05-16 05:48:58'),(13,'create employee only','2017-05-05 07:31:54','2017-05-09 07:56:44'),(14,'sale','2017-07-04 08:36:52','2017-07-04 08:36:52'),(15,'employee and sale','2017-07-04 08:37:05','2017-07-04 08:37:05');
/*!40000 ALTER TABLE `group_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hiring_patterns`
