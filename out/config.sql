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
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `key` varchar(100) NOT NULL,
  `value` text,
  PRIMARY KEY (`key`),
  UNIQUE KEY `config_key_uindex` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES ('daily_settlement_grid_color','a:2:{s:7:\"collumn\";a:16:{s:5:\"col_1\";s:7:\"#ffffff\";s:5:\"col_2\";s:7:\"#ffffff\";s:5:\"col_3\";s:22:\"rgba(202,202,204,0.53)\";s:5:\"col_4\";s:7:\"#ffffff\";s:5:\"col_5\";s:7:\"#ffffff\";s:5:\"col_6\";s:7:\"#ffffff\";s:5:\"col_7\";s:7:\"#ffffff\";s:5:\"col_8\";s:22:\"rgba(202,202,204,0.53)\";s:5:\"col_9\";s:7:\"#ffffff\";s:6:\"col_10\";s:7:\"#ffffff\";s:6:\"col_11\";s:7:\"#ffffff\";s:6:\"col_12\";s:22:\"rgba(202,202,204,0.53)\";s:6:\"col_13\";s:7:\"#ffffff\";s:6:\"col_14\";s:7:\"#ffffff\";s:6:\"col_15\";s:7:\"#ffffff\";s:6:\"col_20\";s:22:\"rgba(202,202,204,0.53)\";}s:7:\"company\";a:6:{i:1;s:7:\"#d9d4e6\";i:2;s:7:\"#c2c8d9\";i:3;s:19:\"rgba(205,222,221,1)\";i:4;s:19:\"rgba(214,227,194,1)\";i:5;s:7:\"#d9d8d8\";i:6;s:7:\"#ffffff\";}}'),('flag_update_sale','a:2:{s:6:\"Config\";a:1:{s:16:\"flag_update_sale\";s:1:\"0\";}i:2017;a:12:{i:1;s:1:\"0\";i:2;s:1:\"0\";i:3;s:1:\"0\";i:4;s:1:\"0\";i:5;s:1:\"0\";i:6;s:1:\"0\";i:7;s:1:\"0\";i:8;s:1:\"0\";i:9;s:1:\"0\";i:10;s:1:\"0\";i:11;s:1:\"0\";i:12;s:1:\"0\";}}'),('grid','a:2:{i:1;a:14:{i:0;s:5:\"col_1\";i:1;s:5:\"col_2\";i:2;s:5:\"col_3\";i:3;s:5:\"col_4\";i:4;s:5:\"col_5\";i:5;s:5:\"col_6\";i:6;s:5:\"col_7\";i:7;s:5:\"col_8\";i:8;s:5:\"col_9\";i:9;s:6:\"col_10\";i:10;s:6:\"col_11\";i:11;s:6:\"col_12\";i:12;s:6:\"col_13\";i:13;s:6:\"col_20\";}i:2;a:15:{i:0;s:5:\"col_1\";i:1;s:5:\"col_2\";i:2;s:5:\"col_3\";i:3;s:5:\"col_4\";i:4;s:5:\"col_5\";i:5;s:5:\"col_6\";i:6;s:5:\"col_7\";i:7;s:5:\"col_8\";i:8;s:5:\"col_9\";i:9;s:6:\"col_10\";i:10;s:6:\"col_11\";i:11;s:6:\"col_12\";i:12;s:6:\"col_13\";i:13;s:6:\"col_14\";i:14;s:6:\"col_20\";}}'),('home_post_number','8');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `divisions`
