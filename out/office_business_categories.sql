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
-- Table structure for table `office_business_categories`
--

DROP TABLE IF EXISTS `office_business_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `office_business_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `office_id` int(11) NOT NULL,
  `business_category_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `office_id` (`office_id`),
  KEY `business_category_id` (`business_category_id`),
  CONSTRAINT `office_business_categories_ibfk_2` FOREIGN KEY (`business_category_id`) REFERENCES `business_categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `office_business_categories_ibfk_3` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=396 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office_business_categories`
--

LOCK TABLES `office_business_categories` WRITE;
/*!40000 ALTER TABLE `office_business_categories` DISABLE KEYS */;
INSERT INTO `office_business_categories` VALUES (60,44,8,'2017-04-14 02:05:42','2017-04-14 02:05:42'),(62,46,9,'2017-04-14 02:24:39','2017-04-14 02:24:39'),(63,47,9,'2017-04-14 02:26:13','2017-04-14 02:26:13'),(64,48,9,'2017-04-14 02:26:55','2017-04-14 02:26:55'),(65,49,9,'2017-04-14 02:27:32','2017-04-14 02:27:32'),(74,30,1,'2017-04-14 02:53:46','2017-04-14 02:53:46'),(147,14,1,'2017-05-11 04:30:42','2017-05-11 04:30:42'),(148,14,2,'2017-05-11 04:30:42','2017-05-11 04:30:42'),(153,10,1,'2017-05-11 04:31:25','2017-05-11 04:31:25'),(155,8,1,'2017-05-11 04:31:50','2017-05-11 04:31:50'),(157,6,1,'2017-05-11 04:32:11','2017-05-11 04:32:11'),(160,3,1,'2017-05-11 04:32:40','2017-05-11 04:32:40'),(170,20,5,'2017-05-12 08:03:20','2017-05-12 08:03:20'),(194,22,1,'2017-06-02 04:02:31','2017-06-02 04:02:31'),(221,7,1,'2017-11-01 03:53:25','2017-11-01 03:53:25'),(229,29,1,'2017-11-01 08:07:33','2017-11-01 08:07:33'),(230,29,4,'2017-11-01 08:07:33','2017-11-01 08:07:33'),(232,30,2,'2018-01-31 11:38:16','2018-01-31 11:38:16'),(236,4,1,'2018-03-26 02:42:24','2018-03-26 02:42:24'),(237,27,1,'2018-04-02 03:10:23','2018-04-02 03:10:23'),(239,15,1,'2018-04-02 03:11:28','2018-04-02 03:11:28'),(240,15,2,'2018-04-02 03:11:28','2018-04-02 03:11:28'),(244,5,1,'2018-04-02 03:13:54','2018-04-02 03:13:54'),(246,25,9,'2018-04-02 03:58:23','2018-04-02 03:58:23'),(247,24,9,'2018-04-02 03:58:37','2018-04-02 03:58:37'),(256,2,1,'2019-02-19 11:02:40','2019-02-19 11:02:40'),(259,21,6,'2019-05-22 09:59:04','2019-05-22 09:59:04'),(260,1,1,'2019-07-06 03:27:20','2019-07-06 03:27:20'),(262,11,1,'2019-07-06 03:29:44','2019-07-06 03:29:44'),(264,19,4,'2019-07-09 02:17:55','2019-07-09 02:17:55'),(274,34,1,'2019-11-20 10:56:26','2019-11-20 10:56:26'),(275,34,2,'2019-11-20 10:56:26','2019-11-20 10:56:26'),(282,18,3,'2019-11-25 03:15:50','2019-11-25 03:15:50'),(283,16,1,'2019-11-25 06:37:39','2019-11-25 06:37:39'),(284,16,2,'2019-11-25 06:37:39','2019-11-25 06:37:39'),(285,31,2,'2019-11-25 06:37:47','2019-11-25 06:37:47'),(293,13,1,'2019-12-09 05:54:59','2019-12-09 05:54:59'),(302,9,1,'2020-03-23 10:29:02','2020-03-23 10:29:02'),(306,28,1,'2020-10-15 09:35:24','2020-10-15 09:35:24'),(307,12,1,'2020-10-19 02:34:25','2020-10-19 02:34:25'),(331,38,1,'2021-09-13 10:30:09','2021-09-13 10:30:09'),(332,38,12,'2021-09-13 10:30:09','2021-09-13 10:30:09'),(334,42,1,'2021-09-30 10:30:39','2021-09-30 10:30:39'),(335,42,4,'2021-09-30 10:30:39','2021-09-30 10:30:39'),(338,26,9,'2021-10-01 09:13:08','2021-10-01 09:13:08'),(378,43,1,'2021-11-01 01:11:33','2021-11-01 01:11:33'),(379,43,7,'2021-11-01 01:11:33','2021-11-01 01:11:33'),(380,41,3,'2021-11-01 01:11:42','2021-11-01 01:11:42'),(381,41,10,'2021-11-01 01:11:42','2021-11-01 01:11:42'),(382,40,3,'2021-11-01 01:11:49','2021-11-01 01:11:49'),(383,40,6,'2021-11-01 01:11:49','2021-11-01 01:11:49'),(384,39,2,'2021-11-01 01:11:57','2021-11-01 01:11:57'),(385,39,5,'2021-11-01 01:11:57','2021-11-01 01:11:57'),(386,37,1,'2021-11-01 01:12:10','2021-11-01 01:12:10'),(387,37,7,'2021-11-01 01:12:10','2021-11-01 01:12:10'),(388,36,1,'2021-11-01 01:12:19','2021-11-01 01:12:19'),(389,36,3,'2021-11-01 01:12:19','2021-11-01 01:12:19'),(390,35,1,'2021-11-01 01:12:27','2021-11-01 01:12:27'),(391,35,10,'2021-11-01 01:12:27','2021-11-01 01:12:27'),(392,33,11,'2021-11-01 01:12:38','2021-11-01 01:12:38'),(393,32,2,'2021-11-01 01:12:48','2021-11-01 01:12:48'),(394,23,9,'2021-11-01 01:13:03','2021-11-01 01:13:03'),(395,17,7,'2021-11-01 01:13:25','2021-11-01 01:13:25');
/*!40000 ALTER TABLE `office_business_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `office_evaluations`