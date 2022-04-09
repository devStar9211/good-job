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
-- Table structure for table `office_remote_labels`
--

DROP TABLE IF EXISTS `office_remote_labels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `office_remote_labels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office_remote_labels`
--

LOCK TABLES `office_remote_labels` WRITE;
/*!40000 ALTER TABLE `office_remote_labels` DISABLE KEYS */;
INSERT INTO `office_remote_labels` VALUES (1,'jigyo_number_1','2017-05-02 21:38:06','2018-03-29 06:26:06'),(2,'jigyo_id_1','2017-05-02 21:38:06','2018-03-29 06:26:06'),(3,'jigyo_number_2','2017-05-02 21:38:12','2018-03-29 06:26:06'),(4,'jigyo_id_2','2017-05-02 21:38:12','2018-03-29 06:26:06'),(5,'jigyo_number_3','2017-05-03 01:40:07','2018-03-29 06:26:06'),(6,'jigyo_id_3','2017-05-03 01:54:11','2018-03-29 06:26:06'),(7,'jigyo_number_4','2017-05-03 17:13:02','2018-03-29 06:26:06'),(8,'jigyo_id_4','2017-05-03 17:14:16','2018-03-29 06:26:06'),(9,'#9','2017-05-03 17:14:16','2018-03-29 06:26:06'),(10,'#10','2017-05-03 17:14:16','2018-03-29 06:26:06'),(11,'#11','2017-05-03 17:14:16','2018-03-29 06:26:06'),(12,'#12','2017-05-03 17:14:16','2018-03-29 06:26:06'),(13,'#13','2017-05-03 17:14:16','2018-03-29 06:26:06'),(14,'#14','2017-05-03 17:14:16','2018-03-29 06:26:06'),(15,'#15','2017-05-03 17:14:16','2018-03-29 06:26:06'),(16,'#16','2017-05-03 17:14:16','2018-03-29 06:26:06'),(17,'#17','2017-05-03 17:14:16','2018-03-29 06:26:06'),(18,'#18','2017-05-03 17:14:16','2018-03-29 06:26:06'),(19,'#19','2017-05-03 17:14:16','2018-03-29 06:26:06'),(20,'#20','2017-05-03 17:14:16','2018-03-29 06:26:06');
/*!40000 ALTER TABLE `office_remote_labels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `office_remotes`
