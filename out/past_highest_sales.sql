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
-- Table structure for table `past_highest_sales`
--

DROP TABLE IF EXISTS `past_highest_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `past_highest_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `office_id` int(11) NOT NULL,
  `value` double NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `office_id` (`office_id`),
  CONSTRAINT `past_highest_sales_ibfk_1` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `past_highest_sales`
--

LOCK TABLES `past_highest_sales` WRITE;
/*!40000 ALTER TABLE `past_highest_sales` DISABLE KEYS */;
INSERT INTO `past_highest_sales` VALUES (1,15,10906102,'2017-05-02 06:06:56','2022-01-27 07:08:09'),(2,16,29314726,'2017-05-02 06:06:56','2022-01-27 07:08:09'),(3,1,6866578,'2017-05-02 06:07:42','2021-11-30 09:03:09'),(4,5,4758290,'2017-05-02 06:07:42','2021-11-30 09:03:09'),(5,11,6095513,'2017-05-02 06:07:42','2021-11-30 09:03:09'),(6,14,16646831,'2017-05-02 06:07:42','2022-01-27 07:08:09'),(7,2,6124488,'2017-05-02 06:08:38','2021-11-30 09:03:09'),(8,3,5740557,'2017-05-02 06:08:38','2021-11-30 09:03:09'),(9,6,6185306,'2017-05-02 06:08:38','2021-11-30 09:03:09'),(10,8,6583860,'2017-05-02 06:08:38','2021-11-30 09:03:09'),(11,9,4998572,'2017-05-02 06:08:38','2021-11-30 09:03:09'),(12,10,5681692,'2017-05-02 06:08:38','2021-11-30 09:03:09'),(13,12,5874955,'2017-05-02 06:08:38','2021-11-30 09:03:09'),(14,7,5326600,'2017-05-02 06:09:22','2021-11-30 09:03:09'),(15,13,6163394,'2017-06-09 11:44:44','2021-11-30 09:03:09'),(16,4,5008955,'2017-06-09 11:45:03','2017-06-09 11:45:03'),(17,27,6854660,'2017-09-12 02:39:27','2021-11-30 09:03:09'),(18,28,5939378,'2021-03-24 07:51:47','2021-11-30 09:03:09');
/*!40000 ALTER TABLE `past_highest_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `point_bonuses`
