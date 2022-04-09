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
-- Table structure for table `office_managers`
--

DROP TABLE IF EXISTS `office_managers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `office_managers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `office_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` int(11) DEFAULT '0',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `office_id` (`office_id`),
  CONSTRAINT `office_managers_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  CONSTRAINT `office_managers_ibfk_2` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office_managers`
--

LOCK TABLES `office_managers` WRITE;
/*!40000 ALTER TABLE `office_managers` DISABLE KEYS */;
INSERT INTO `office_managers` VALUES (2,1022,15,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(3,1181,16,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(4,1130,17,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(5,1254,23,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(7,942,1,'2017-07-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(8,934,5,'2017-06-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(9,1096,11,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(10,1178,14,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(11,933,27,'2017-06-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(12,894,2,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(14,907,3,'2017-09-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(15,947,6,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(16,975,8,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(19,1104,12,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(20,1179,25,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(21,1084,28,'2017-09-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(24,1121,13,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(25,1140,18,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(26,1151,19,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(27,1180,20,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(29,1172,4,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(30,962,7,'2017-09-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(31,905,10,'2017-09-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(33,905,3,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(34,933,5,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(35,961,7,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(36,1084,10,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(38,881,1,'2017-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(39,961,10,'2017-11-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(40,905,13,'2017-11-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(41,1153,29,'2017-10-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(42,1390,6,'2018-04-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(44,947,27,'2018-04-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(46,1084,13,'2018-06-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(47,1121,28,'2018-06-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(48,987,14,'2018-09-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(49,1493,7,'2018-10-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(50,1039,16,'2019-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(51,1425,6,'2019-02-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(52,1087,10,'2019-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(53,947,9,'2019-04-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(54,1098,11,'2019-04-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(56,1181,32,'2019-07-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(57,1071,9,'2016-11-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(58,905,9,'2016-03-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(59,1024,15,'2019-10-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(60,1638,14,'2019-10-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(61,947,6,'2019-11-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(62,1071,12,'2019-11-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(63,1676,13,'2019-11-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(64,1104,27,'2019-11-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(66,1871,12,'2020-03-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(67,1763,5,'2020-04-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(68,1038,11,'2020-04-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(69,1356,5,'2018-05-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(70,1356,6,'2020-06-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(71,1638,15,'2020-10-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(72,895,14,'2020-10-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(73,942,5,'2021-04-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(74,1406,13,'2021-04-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(75,1755,11,'2021-04-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(76,1976,1,'2021-04-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(78,1820,32,'2021-05-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(79,1089,7,'2021-07-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(81,1254,39,'2021-05-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(82,1084,41,'2021-05-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(83,1493,42,'2021-07-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(84,1356,2,'2021-09-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(85,2058,6,'2021-09-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(86,1084,39,'2021-09-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(87,2256,38,'2021-05-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(88,1748,1,'2021-11-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(89,2167,13,'2021-12-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(90,2258,40,'2021-05-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(91,2284,40,'2021-07-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(92,2357,41,'2021-12-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(93,2284,40,'2022-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(95,2284,39,'2022-01-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12'),(96,1690,12,'2022-02-01',0,'2022-02-01 04:32:12','2022-02-01 04:32:12');
/*!40000 ALTER TABLE `office_managers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `office_remote_labels`