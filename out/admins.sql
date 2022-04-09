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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `data_access_level` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `profile` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_original` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `headquarter` tinyint(1) DEFAULT NULL,
  `employee_register_only` tinyint(1) DEFAULT '0',
  `have_sale_permission` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `group_permission_id` (`account_id`),
  CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,46,0,'good-job!','1502348608_0_0_1798_1800_0.0733.jpg','2017-01-23 23:19:53','2018-07-04 09:18:29','','1502348608.jpg',1,0,0),(14,129,0,'honbuadmin','default-avatar.png','2017-03-21 02:22:15','2017-07-04 08:33:26','本部アドミン','',0,0,0),(15,133,0,'その他','default-avatar.png','2017-03-21 02:31:10','2017-03-21 11:32:52','本社管理部','',NULL,0,0),(20,181,0,'ブログ用','default-avatar.png','2017-04-19 01:30:47','2019-08-19 09:40:44','本社管理部','',0,0,0),(23,1461,0,'コードラバーズ','default-avatar.png','2017-05-12 08:44:10','2020-12-11 02:55:04','','',1,0,1),(33,1580,0,'橘　達也','1504160490_0_0_530_530_0.2491.jpg','2017-08-31 06:21:30','2018-03-10 02:53:15','橘ブログ','1504160490.jpg',1,0,0),(34,1775,0,'一時管理','default-avatar.png','2018-07-10 10:17:18','2018-07-10 10:17:18','一時的管理アドレス','',1,0,0),(35,1911,0,'鏡山俊彦','default-avatar.png','2019-02-27 11:45:52','2019-12-10 10:38:48','専務システム管理用','',1,0,0),(36,1941,0,'ジョブ子','default-avatar.png','2019-04-08 07:45:29','2021-12-19 16:44:59','ジョブ子アカウント','',0,0,0),(38,2147,3,'本田浩美(エリア管理)','1574907732_0_0_240_240_0.55.jpg','2019-11-28 02:18:59','2021-04-05 09:06:35','本田エリア管理用','1574907732.jpg',1,0,0),(39,2189,1,'橘エリア管理(成城)','default-avatar.png','2020-02-05 06:43:25','2021-11-04 08:40:43','成城管理用アドミンユーザー※ケアサポ含む','',0,0,0),(40,2418,0,'good-job!!','default-avatar.png','2021-01-06 08:21:16','2021-01-06 08:21:35','開発用adminアカウント','',1,0,0),(41,2530,2,'岡川洋右','default-avatar.png','2021-05-10 03:16:49','2021-05-25 00:05:55','ブログ用アカウント','',1,0,0);
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `allowances`
