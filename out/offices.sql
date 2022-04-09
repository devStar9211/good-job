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
-- Table structure for table `offices`
--

DROP TABLE IF EXISTS `offices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `company_group_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `office_group_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` int(11) DEFAULT NULL,
  `prefecture` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `municipal_town` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fax` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_number` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_start` varchar(9) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_end` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_start` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_end` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remuneration_factor` float DEFAULT NULL,
  `region_classification_factor` float DEFAULT NULL,
  `api_shift_office_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_remote_2` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_remote_3` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_remote_4` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_remote_5` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_remote_6` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `day_capacity` double DEFAULT NULL,
  `max_capacity` int(11) DEFAULT NULL,
  `honobono_office_id` double DEFAULT NULL,
  `display_on_shift` tinyint(1) NOT NULL,
  `display_in_budget_ranking` tinyint(1) NOT NULL DEFAULT '1',
  `sortable` tinyint(11) DEFAULT '2',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `company_groups_id` (`company_group_id`),
  KEY `division_id` (`division_id`),
  KEY `office_group_id` (`office_group_id`),
  CONSTRAINT `offices_ibfk_1` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`),
  CONSTRAINT `offices_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  CONSTRAINT `offices_ibfk_3` FOREIGN KEY (`office_group_id`) REFERENCES `office_groups` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offices`
--

LOCK TABLES `offices` WRITE;
/*!40000 ALTER TABLE `offices` DISABLE KEYS */;
INSERT INTO `offices` VALUES (1,3,1,2,2,'西永福',NULL,'東京都','杉並区下高井戸3-32-32','03-6379-7363','03-6379-7364','1371506229','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1371506229',NULL,NULL,NULL,NULL,NULL,18,9,1,1,1,2,'2017-03-17 08:54:54','2019-07-06 03:27:20'),(2,3,1,2,2,'梅ヶ丘',NULL,'東京都','世田谷区松原6-9-17 ジュノ羽根木公園101号','03-5355-5613','03-5355-5612','1371208941','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1371208941',NULL,NULL,NULL,NULL,NULL,16,7,2,1,1,2,'2017-03-17 09:03:17','2019-02-19 11:02:40'),(3,3,1,2,2,'東松原',NULL,'東京都','世田谷区松原6-9-17 ジュノ羽根木公園102号','03-6304-7508','03-6304-7509','1371209485','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1371209485',NULL,NULL,NULL,NULL,NULL,16,7,3,1,1,2,'2017-03-17 09:05:54','2017-05-11 04:32:40'),(4,6,2,2,2,'中野',NULL,'東京都','中野区中野2-4-1','03-6382-8182','03-6382-8183','1391400429','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1391400429',NULL,NULL,NULL,NULL,NULL,15,7,4,0,0,2,'2017-04-13 07:44:48','2018-03-26 02:42:24'),(5,3,1,2,2,'高円寺',NULL,'東京都','杉並区高円寺北3-29-7','03-5356-6918','03-5356-6919','1371507128','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1371507128',NULL,NULL,NULL,NULL,NULL,13,6,5,1,1,2,'2017-03-17 08:57:07','2018-04-02 03:13:54'),(6,3,1,2,2,'田園調布',NULL,'東京都','世田谷区東玉川2-16-10','03-6425-6413','03-6425-6414','1371211168','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1371211168',NULL,NULL,NULL,NULL,NULL,18,7,6,1,1,2,'2017-03-17 09:08:16','2017-05-11 04:32:10'),(7,3,1,2,2,'沼袋',NULL,'東京都','中野区沼袋2-6-7','03-5942-5891','03-5942-5892','1371404318','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1371404318',NULL,NULL,NULL,NULL,NULL,13,5,7,1,1,2,'2017-03-17 09:40:24','2017-11-01 03:53:25'),(8,3,1,2,2,'桜新町',NULL,'東京都','世田谷区深沢7-24-18','03-6809-8341','03-6809-8342','1371211929','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1371211929',NULL,NULL,NULL,NULL,NULL,18,8,8,1,1,2,'2017-03-17 09:11:32','2017-05-11 04:31:50'),(9,3,1,2,2,'駒場',NULL,'東京都','目黒区駒場2-14-5','03-6407-1560','03-6407-1570','1371003996','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1371003995',NULL,NULL,NULL,NULL,NULL,15,6,9,1,0,2,'2017-03-17 09:33:43','2020-03-23 10:29:02'),(10,3,1,2,2,'新駒場',NULL,'東京都','目黒区駒場1-24-2','03-5790-9958','03-5790-9959','1371004183','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1371004183',NULL,NULL,NULL,NULL,NULL,14,7,10,1,1,2,'2017-03-17 09:35:52','2017-05-11 04:31:25'),(11,3,1,2,2,'新高円寺',NULL,'東京都','杉並区成田東1-40-11','03-5929-9173','03-5929-9174','1371508969','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1371508969',NULL,NULL,NULL,NULL,NULL,17,7,11,1,1,2,'2017-03-17 08:59:04','2019-07-06 03:29:44'),(12,3,1,2,2,'都立大学',NULL,'東京都','目黒区八雲3-3-11','03-6421-4128','03-6421-4129','1371004175','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1371004175',NULL,NULL,NULL,NULL,NULL,15,7,12,1,1,2,'2017-03-17 09:38:07','2020-10-19 02:34:25'),(13,3,1,2,4,'東中野',NULL,'東京都','中野区東中野2-28-14 メディカルコートⅡ-1F','03-5937-3277','03-5937-3278','1371405448','8:00 AM','6:00 PM','9:00 AM','5:00 PM',1.069,0,'1371405448',NULL,NULL,NULL,NULL,NULL,20,8,13,1,1,2,'2017-03-17 09:43:18','2019-12-09 05:54:59'),(14,2,1,2,1,'調布',NULL,'東京都','調布市染地2-8-3 そめちクリニック2F','042-452-8005','042-452-8006','1374202917','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1374202917',NULL,NULL,NULL,NULL,NULL,50,NULL,15,1,1,2,'2017-03-17 08:48:38','2017-05-11 04:30:42'),(15,2,1,2,1,'国分寺',NULL,'東京都','小平市上水南町1-14-6','042-359-4813','042-359-4814','1374302626','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1374302626',NULL,NULL,NULL,NULL,NULL,40,9,16,1,1,2,'2017-03-17 08:43:12','2018-04-02 03:11:28'),(16,2,1,2,1,'一橋',NULL,'東京都','小平市喜平町1-2-5','042-359-4106','042-359-4107','1374302675','8:00 AM','6:00 PM','9:00 AM','5:00 PM',0,0,'1374302675',NULL,NULL,NULL,NULL,NULL,80,NULL,19,1,1,2,'2017-03-17 08:39:15','2019-11-25 06:37:38'),(17,4,1,2,3,'ケアサポート',NULL,'東京都','小平市喜平町1-2-5','042-312-0775','','1111111092','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,2,'2017-03-17 08:45:42','2021-11-01 01:13:25'),(18,4,1,3,3,'マザース中野',NULL,'東京都','中野区東中野2-28-14 メディカルコートⅡ-2F-2D','03-5989-1567','03-5989-1568','1361490145','9:00 AM','6:30 PM','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,2,'2017-03-17 09:48:27','2019-11-25 03:15:50'),(19,3,1,2,3,'グランマ中野',NULL,'東京都','中野区東中野2-17-13-205','03-5937-2727','03-5937-2783','1371405182','8:30 AM','5:30 PM','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,0,0,NULL,0,0,2,'2017-03-17 09:50:46','2019-07-09 02:17:55'),(20,4,1,3,3,'巡回看護',NULL,'東京都','中野区東中野2-28-14 メディカルコートⅡ-2F','03-0000-0000','','1111111112','','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,2,'2017-03-17 09:44:53','2017-05-12 08:03:20'),(21,4,1,2,3,'送迎委託',NULL,'東京都','中野区東中野2-28-14 メディカルコートⅡ-2F','03-0000-0000','','1111111111','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,2,'2017-03-17 09:46:04','2019-05-22 09:59:04'),(22,5,1,1,3,'本部',NULL,'東京都','港区芝大門2-3-11　芝清水ビル2F','03-6809-1280','','1111111078','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,2,'2017-04-14 02:05:42','2017-06-02 04:02:30'),(23,5,1,2,3,'橘ｴﾘｱ管理費',NULL,'東京都','港区芝大門2-3-11　芝清水ビル2F','03-6809-1280','','1','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,3,'2017-04-14 02:24:39','2021-11-01 01:13:03'),(24,2,1,2,3,'岡川ｴﾘｱ管理費',NULL,'東京都','港区芝大門2-3-11　芝清水ビル2F','03-6809-1280','','2','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,3,'2017-04-14 02:26:13','2018-04-02 03:58:37'),(25,3,1,2,3,'本田ｴﾘｱ管理費',NULL,'東京都','港区芝大門2-3-11　芝清水ビル2F','03-6809-1280','','3','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,3,'2017-04-14 02:26:55','2018-04-02 03:58:23'),(26,4,1,2,3,'鏡山管理費',NULL,'東京都','港区芝大門2-3-11　芝清水ビル2F','03-6809-1280','','4','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,3,'2017-04-14 02:27:32','2021-10-01 09:13:08'),(27,3,1,2,4,'堀ノ内',NULL,'東京都','杉並区堀ノ内3-4-12 シルバーピア1F','03-5929-8762','03-5929-8763','1371509579','8:00 AM','6:00 PM','9:00 AM','5:00 PM',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,25,9,1371509579,1,1,2,'2017-05-25 02:26:21','2018-04-02 03:10:23'),(28,3,1,2,4,'落合',NULL,'東京都','新宿区西落合3-15-5','03-6908-3511','03-6908-3521','1370406173','8:00 AM','6:00 PM','9:00 AM','5:00 PM',1.059,10.9,'1370406173',NULL,NULL,NULL,NULL,NULL,22,0,NULL,1,1,2,'2017-08-25 02:32:06','2020-10-15 09:35:24'),(29,3,1,2,3,'グランマ世田谷',NULL,'東京都','世田谷区東松原玉川2-16-10','03-6451-7905','03-6451-7906','1371214741','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,2,'2017-10-02 08:53:20','2017-11-01 08:07:33'),(30,2,1,2,1,'調布ガーデン',NULL,'東京都','港区','0368091280','','9999999999','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,'2018-01-31 11:38:16','2018-01-31 11:38:16'),(31,2,1,2,1,'一橋ガーデン',NULL,'東京都','港区','0368091280','','8888888888','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,'2018-01-31 11:39:17','2019-11-25 06:37:47'),(32,3,1,2,5,'成城',NULL,'東京都','世田谷区成城','03-1234-56789','','9876543210','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,2,'2019-04-24 08:47:52','2021-11-01 01:12:48'),(33,4,1,3,6,'D\'eview',NULL,'東京都','港区芝大門2-3-11-2F','03-6809-1280','','7777777777','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,2,'2019-10-29 05:34:36','2021-11-01 01:12:38'),(34,2,1,2,5,'国分寺ガーデン',NULL,'東京都','小平市','03-1111-2222','','9786543210','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,'2019-11-20 10:53:05','2019-11-20 10:56:26'),(35,3,1,2,7,'ナラティブケア成城',NULL,'東京都','世田谷区成城','03-1111-2222','','9876643210','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,'2019-11-21 07:08:35','2021-11-01 01:12:27'),(36,3,1,2,7,'マザース成城',NULL,'東京都','世田谷区成城','03-2222-3333','','9876543211','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,'2019-11-21 09:31:35','2021-11-01 01:12:19'),(37,3,1,3,5,'キッチン成城',NULL,'東京都','世田谷区成城','03-6666-5555','','8765432190','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,'2019-11-21 11:01:40','2021-11-01 01:12:10'),(38,4,1,3,8,'D\'eview(福祉用具事業部）',NULL,'東京都','港区芝大門2-3-11-2F','0368091280','','0987654321','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,2,'2021-04-05 08:06:23','2021-09-13 10:30:09'),(39,2,1,2,5,'府中若松',NULL,'東京都','府中市','03-1111-2222','','5555555555','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,2,'2021-04-08 05:40:50','2021-11-01 01:11:56'),(40,2,1,2,7,'マザース府中若松',NULL,'北海道','府中市','03-2222-3333','','4444444444','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,'2021-04-08 05:41:33','2021-11-01 01:11:49'),(41,2,1,2,7,'ナラティブケア府中若松',NULL,'北海道','府中市','03-3333-4444','','6666666666','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,'2021-04-08 05:42:12','2021-11-01 01:11:42'),(42,3,1,2,4,'富士見台',NULL,'東京都','練馬区貫井4-14-15','03-5848-6277','','1684975849','8:00 AM','6:00 PM','9:00 AM','5:00 PM',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,25,9,NULL,1,1,2,'2021-06-14 09:21:00','2021-09-30 10:30:39'),(43,2,1,3,5,'キッチン府中若松',NULL,'東京都','府中市若松町4-22-5','042-0000-0000','','654321098','','','','',NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,'2021-10-28 02:41:46','2021-11-01 01:11:33');
/*!40000 ALTER TABLE `offices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `past_highest_sales`
