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
-- Table structure for table `email_notifications`
--

DROP TABLE IF EXISTS `email_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_notifications`
--

LOCK TABLES `email_notifications` WRITE;
/*!40000 ALTER TABLE `email_notifications` DISABLE KEYS */;
INSERT INTO `email_notifications` VALUES (9,3,'kabasawa@caregiver.co.jp','2019-04-04 08:49:49','2019-04-04 08:53:00'),(10,2,'kabasawa@caregiver.co.jp','2019-04-04 08:53:11','2019-04-04 08:53:11'),(11,1,'kabasawa@caregiver.co.jp','2019-04-04 08:53:20','2019-04-04 08:53:20'),(12,4,'kabasawa@caregiver.co.jp','2019-04-04 08:53:29','2019-04-04 08:53:29'),(13,5,'kabasawa@caregiver.co.jp','2019-04-04 08:53:36','2019-04-04 08:53:36'),(14,6,'kabasawa@caregiver.co.jp','2019-04-04 08:53:47','2019-04-04 08:53:47'),(15,1,'recruit@caregiver.co.jp','2019-04-04 08:54:11','2019-04-04 08:54:11'),(16,2,'recruit@caregiver.co.jp','2019-04-04 08:54:18','2019-04-04 08:54:18'),(17,3,'recruit@caregiver.co.jp','2019-04-04 08:54:24','2019-04-04 08:54:24'),(18,4,'recruit@caregiver.co.jp','2019-04-04 08:54:42','2019-04-04 08:54:42'),(19,5,'recruit@caregiver.co.jp','2019-04-04 08:54:48','2019-04-04 08:54:48'),(20,5,'t-kagamiyama@caregiver.co.jp','2019-04-04 08:55:06','2019-04-04 08:55:06'),(21,4,'t-kagamiyama@caregiver.co.jp','2019-04-04 08:55:18','2019-04-04 08:55:18'),(22,3,'h-honda@caregiver.co.jp','2019-04-04 08:55:37','2019-04-04 08:55:37'),(23,2,'y-okagawa@caregiver.co.jp','2019-04-04 08:55:55','2019-04-04 08:55:55'),(24,1,'tachi@caregiver.co.jp','2019-04-04 08:56:08','2019-04-04 08:56:08'),(26,5,'a-saito@caregiver.co.jp','2019-04-04 08:56:42','2019-04-04 08:56:42'),(28,5,'r-usui@caregiver.co.jp','2019-04-04 08:57:12','2019-04-04 08:57:12'),(29,4,'a-saito@caregiver.co.jp','2019-04-04 08:57:25','2019-04-04 08:57:25'),(31,3,'a-saito@caregiver.co.jp','2019-04-04 09:05:25','2019-04-04 09:05:25'),(33,3,'r-usui@caregiver.co.jp','2019-04-04 09:05:43','2019-04-04 09:05:43'),(34,2,'a-saito@caregiver.co.jp','2019-04-04 09:05:57','2019-04-04 09:05:57'),(36,2,'r-usui@caregiver.co.jp','2019-04-04 09:06:21','2019-04-04 09:06:21'),(37,1,'a-saito@caregiver.co.jp','2019-04-04 09:06:34','2019-04-04 09:06:34'),(39,1,'r-usui@caregiver.co.jp','2019-04-04 09:06:56','2019-04-04 09:06:56'),(40,5,'y-ueyama@caregiver.co.jp','2021-06-02 01:24:21','2021-06-02 01:24:21'),(41,1,'y-ueyama@caregiver.co.jp','2021-06-03 00:20:50','2021-06-03 00:20:50'),(42,2,'y-ueyama@caregiver.co.jp','2021-06-03 00:21:17','2021-06-03 00:21:17'),(43,3,'y-ueyama@caregiver.co.jp','2021-06-03 00:21:30','2021-06-03 00:21:30'),(44,4,'y-ueyama@caregiver.co.jp','2021-06-03 00:22:18','2021-06-03 00:22:18');
/*!40000 ALTER TABLE `email_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_allowances`
