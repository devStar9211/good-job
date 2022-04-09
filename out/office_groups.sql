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
-- Table structure for table `office_groups`
--

DROP TABLE IF EXISTS `office_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `office_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office_groups`
--

LOCK TABLES `office_groups` WRITE;
/*!40000 ALTER TABLE `office_groups` DISABLE KEYS */;
INSERT INTO `office_groups` VALUES (1,'デイサービス併設型有料老人ホーム',1,'2017-04-18 08:12:55','2017-04-21 14:27:37'),(2,'地域密着型通所介護',2,'2017-04-18 08:13:08','2017-04-21 14:27:29'),(3,'会社本部',4,'2017-04-18 08:13:18','2017-06-02 05:37:36'),(4,'指定通所介護事業所',3,'2017-05-25 02:16:21','2017-06-02 05:37:32'),(5,'有料老人ホーム',5,'2019-04-24 08:42:03','2019-04-24 08:42:03'),(6,'紹介営業事業',6,'2019-10-29 05:31:11','2019-11-15 06:28:06'),(7,'訪問看護・介護事業所',7,'2019-11-15 06:47:58','2019-11-15 06:47:58'),(8,'福祉用具貸与事業所',8,'2021-04-05 08:02:58','2021-04-05 08:02:58');
/*!40000 ALTER TABLE `office_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `office_managers`
