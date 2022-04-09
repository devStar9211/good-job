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
-- Table structure for table `night_shifts`
--

DROP TABLE IF EXISTS `night_shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `night_shifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `price` double DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `night_shifts`
--

LOCK TABLES `night_shifts` WRITE;
/*!40000 ALTER TABLE `night_shifts` DISABLE KEYS */;
INSERT INTO `night_shifts` VALUES (1,'夜',18000,'2017-03-29 06:43:35','2017-04-21 13:18:56'),(2,'夜（社員）',3000,'2017-03-31 06:42:30','2017-04-24 03:17:24'),(3,'夜A',16000,'2017-04-14 01:21:13','2019-09-30 04:57:46'),(4,'夜B',16000,'2017-04-14 01:21:21','2019-09-30 04:57:56'),(5,'夜C',17000,'2017-04-14 01:21:28','2019-09-30 04:58:02'),(6,'夜（管理者）',0,'2017-04-24 03:17:34','2017-04-24 03:17:34'),(7,'夜F',11152,'2017-04-28 09:27:43','2017-04-28 09:27:43'),(8,'夜介',6667,'2019-11-01 07:19:37','2019-11-01 07:19:37'),(9,'夜看',10000,'2019-11-01 07:19:45','2019-11-01 07:19:45'),(10,'夜介',25000,'2019-11-21 07:39:17','2019-11-21 07:42:15'),(11,'夜看',35500,'2019-11-21 07:39:32','2019-11-21 07:39:32'),(12,'夜Ⅰ',40000,'2020-02-28 03:04:44','2020-04-30 03:42:44'),(13,'夜Ⅱ',46000,'2020-02-28 03:04:58','2020-02-28 03:04:58'),(14,'明',0,'2020-02-28 03:48:46','2020-02-28 03:48:46'),(15,'手当A',8000,'2020-02-28 04:10:44','2020-02-28 04:10:44'),(16,'手当B',5000,'2020-02-28 04:10:51','2020-02-28 04:10:51'),(17,'夜G',50000,'2021-07-06 01:11:41','2021-07-06 01:11:41'),(18,'夜H',35000,'2021-07-06 01:11:57','2021-07-06 01:11:57'),(19,'夜Ⅾ',10000,'2021-08-11 03:30:27','2021-08-11 03:32:02'),(20,'夜E',0,'2021-08-11 03:30:42','2021-08-11 03:30:42'),(21,'夜J',16500,'2021-09-21 05:06:31','2021-09-21 05:06:31'),(22,'夜K',17500,'2021-09-21 05:06:57','2021-09-21 05:06:57');
/*!40000 ALTER TABLE `night_shifts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `occupations`
