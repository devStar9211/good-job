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
-- Table structure for table `employee_relationships`
--

DROP TABLE IF EXISTS `employee_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_relationships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `postal_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kana_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `relationship` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `occupation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `employee_relationships_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=781 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_relationships`
--

LOCK TABLES `employee_relationships` WRITE;
/*!40000 ALTER TABLE `employee_relationships` DISABLE KEYS */;
INSERT INTO `employee_relationships` VALUES (765,1021,0,'','','','村田智加栄','むらたちかえ','1973-07-02','妻','ビジネスホテル受付','2022-04-01 09:28:50','2022-04-01 09:28:50'),(766,1570,0,'','','','倉井瑛花','クライエイカ','2007-07-03','次女','小学5年生','2022-04-01 09:28:53','2022-04-01 09:28:53'),(767,1690,0,'','','','横尾 幸子','よこお さちこ','1963-06-20','配偶者','無','2022-04-01 09:28:55','2022-04-01 09:28:55'),(768,1690,0,'','','','横尾 朱香','よこお あやか','1999-09-14','子','専門学校生','2022-04-01 09:28:55','2022-04-01 09:28:55'),(769,1697,0,'','','','石下愛','いしげあい','2002-12-17','娘','高校生','2022-04-01 09:28:55','2022-04-01 09:28:55'),(770,1748,0,'','','','久保田　萌','くぼた　めぐみ','1997-11-19','子','大学生','2022-04-01 09:28:55','2022-04-01 09:28:55'),(771,1748,0,'','','','久保田　一哉','くぼた　かずや','2000-08-11','子','大学生','2022-04-01 09:28:55','2022-04-01 09:28:55'),(772,2052,0,'','','','橋本綾香','はしもとあやか','1986-05-02','妻','パート','2022-04-01 09:28:58','2022-04-01 09:28:58'),(773,2126,0,'','','','今野潤子','こんのじゅんこ','1958-05-12','妻','介護パート','2022-04-01 09:28:59','2022-04-01 09:28:59'),(774,2300,0,'','','','叶谷　直美','カノウヤ　ナオミ','1969-06-05','妻','パート','2022-04-01 09:29:01','2022-04-01 09:29:01'),(775,2300,0,'','','','叶谷　いずみ','カノウヤ　イズミ','2004-09-02','子','学生','2022-04-01 09:29:01','2022-04-01 09:29:01'),(776,2396,0,'','','','日向魁','ひゅうがかい','2004-06-29','子','高校生','2022-04-01 09:29:02','2022-04-01 09:29:02'),(777,2396,0,'','','','日向駿','ひゅうがしゅん','2010-10-31','子','小学生','2022-04-01 09:29:02','2022-04-01 09:29:02'),(778,2413,0,'','','','嵯峨　あぐり','サガ　アグリ','1997-08-14','配偶者','パート','2022-04-01 09:29:02','2022-04-01 09:29:02'),(779,2465,0,'','','','佐々木太一','1980年11月17日','1980-11-17','配偶者','自営業','2022-04-01 09:29:03','2022-04-01 09:29:03'),(780,2491,0,'','','','佐藤　エミ子','さとう　えみこ','1934-06-18','母','あ','2022-04-01 09:29:03','2022-04-01 09:29:03');
/*!40000 ALTER TABLE `employee_relationships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
