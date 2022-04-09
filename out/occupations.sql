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
-- Table structure for table `occupations`
--

DROP TABLE IF EXISTS `occupations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `occupations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `occupations`
--

LOCK TABLES `occupations` WRITE;
/*!40000 ALTER TABLE `occupations` DISABLE KEYS */;
INSERT INTO `occupations` VALUES (1,'管理者','2017-03-16 10:53:26','2017-03-29 02:03:59'),(2,'生活相談員','2017-03-16 10:53:34','2017-03-29 02:03:59'),(3,'機能訓練指導員','2017-03-16 10:53:41','2017-05-15 04:06:43'),(4,'看護師','2017-03-16 10:53:52','2017-05-15 04:06:50'),(5,'介護職員','2017-03-16 10:53:59','2017-05-15 04:06:56'),(6,'ヘルパー','2017-03-16 10:54:06','2017-03-29 02:04:58'),(7,'ケアマネジャー','2017-03-16 10:54:14','2017-03-29 02:05:01'),(8,'ドライバー','2017-04-20 01:47:03','2017-05-15 02:35:43'),(9,'副管理者','2017-04-20 02:44:46','2017-04-20 02:45:24'),(10,'調理職員','2017-04-20 02:45:43','2017-05-15 03:52:03'),(11,'ブランク','2017-04-20 02:54:17','2017-05-15 04:14:55'),(12,'理学療法士','2017-04-20 02:54:59','2017-04-20 02:54:59'),(13,'事務員','2017-04-20 02:55:11','2017-04-20 02:55:11'),(14,'ガーデンスタッフ','2018-07-11 03:35:45','2018-11-17 01:47:57'),(15,'精神保険福祉士','2018-12-13 06:50:31','2018-12-13 06:50:31'),(16,'清掃スタッフ','2019-04-26 10:29:58','2019-04-26 10:29:58'),(17,'マネージャー','2019-10-29 05:43:31','2019-10-29 05:43:31'),(18,'本部職員','2019-11-14 05:01:16','2019-11-14 05:01:16'),(19,'営業','2020-03-02 08:03:46','2020-03-02 08:03:46'),(20,'生相候補・要件','2020-04-23 02:19:22','2020-04-23 08:22:44'),(21,'サ責','2021-07-19 01:55:46','2021-07-19 01:57:36');
/*!40000 ALTER TABLE `occupations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `office_addition_judgments`
