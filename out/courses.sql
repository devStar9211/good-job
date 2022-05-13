-- MySQL dump 10.13  Distrib 5.7.17, for Linux (x86_64)
--
-- Host: localhost    Database: test
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
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `course_group_id` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `reference1` varchar(1000) DEFAULT NULL,
  `url1` varchar(1000) DEFAULT NULL,
  `reference2` varchar(1000) DEFAULT NULL,
  `url2` varchar(1000) DEFAULT NULL,
  `reference3` varchar(1000) DEFAULT NULL,
  `url3` varchar(1000) DEFAULT NULL,
  `reference4` varchar(1000) DEFAULT NULL,
  `url4` varchar(1000) DEFAULT NULL,
  `reference5` varchar(1000) DEFAULT NULL,
  `url5` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `courses_course_group_id_foreign` (`course_group_id`),
  CONSTRAINT `courses_course_group_id_foreign` FOREIGN KEY (`course_group_id`) REFERENCES `course_groups` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (14,18,'介助の基本','バイタルチェック･歩行介助マニュアル','https://www.caregiver-manual.com/2day-1-1','','','','','','','','','2018-04-25 16:53:06','2018-04-25 16:53:06'),(15,18,'ロイヤリティ','','','','','','','','','','','2018-04-25 16:53:06','2018-04-25 16:53:06'),(16,18,'褥瘡','褥瘡予防と処置マニュアル','https://www.caregiver-manual.com/blank-6','','','','','','','','','2018-04-25 16:53:06','2018-04-25 16:53:06'),(17,18,'疥癬･感染症','疥癬対応マニュアル','https://www.caregiver-manual.com/blank-3','感染症･食中毒対応マニュアル','https://www.caregiver-manual.com/blank-4','','','','','','','2018-04-25 16:53:06','2018-04-25 16:53:06'),(18,19,'介助の基本','車椅子介助と移乗','https://www.caregiver-manual.com/2day-2-1','','','','','','','','','2018-05-02 00:11:38','2018-05-02 00:11:38'),(19,19,'一般教養','','','','','','','','','','','2018-05-02 00:11:38','2018-05-02 00:11:38'),(20,19,'政治経済-1','','','','','','','','','','','2018-05-02 00:11:38','2018-05-02 00:11:38'),(21,19,'政治経済-2','','','','','','','','','','','2018-05-02 00:11:38','2018-05-02 00:11:38'),(31,23,'介護を知る','介護を知る-1','https://www.caregiver-manual.com/1day-1','','','','','','','','','2018-05-31 18:01:25','2018-05-31 18:01:25'),(32,23,'介助の基本','食事と入浴介助','https://www.caregiver-manual.com/2day-2-1','','','','','','','','','2018-05-31 18:01:25','2018-05-31 18:01:25'),(33,23,'感染症・食中毒対応','感染症・食中毒対応','https://www.caregiver-manual.com/blank-4','','','','','','','','','2018-05-31 18:01:25','2018-05-31 18:01:25'),(34,23,'熱中症対策','熱中症対策','https://www.caregiver-manual.com/blank-5','','','','','','','','','2018-05-31 18:01:25','2018-05-31 18:01:25'),(35,24,'介護を知る','介護を知る-1','https://www.caregiver-manual.com/1day-1','','','','','','','','','2018-05-31 20:21:03','2018-05-31 20:21:03'),(36,24,'介助の基本','食事と入浴介助','https://www.caregiver-manual.com/2day','','','','','','','','','2018-05-31 20:21:03','2018-05-31 20:21:03'),(37,24,'感染症・食中毒対応','感染症・食中毒対応','https://www.caregiver-manual.com/blank-4','','','','','','','','','2018-05-31 20:21:03','2018-05-31 20:21:03'),(38,24,'熱中症対策','熱中症対策','https://www.caregiver-manual.com/blank-5','','','','','','','','','2018-05-31 20:21:03','2018-05-31 20:21:03'),(39,25,'離設対応と予防','離設対応と予防','https://www.caregiver-manual.com/blank-14','','','','','','','','','2018-06-28 19:56:32','2018-06-28 19:56:32'),(40,25,'緊急時対応','緊急時対応','https://www.caregiver-manual.com/blank-17','','','','','','','','','2018-06-28 19:56:32','2018-06-28 19:56:32'),(41,25,'国語-1','','','','','','','','','','','2018-06-28 19:56:32','2018-06-28 19:56:32'),(42,25,'数学理科-1','','','','','','','','','','','2018-06-28 19:56:32','2018-06-28 19:56:32'),(56,27,'熱中症予防','熱中症対策','https://www.caregiver-manual.com/blank-5','','','','','','','','','2018-07-31 18:18:18','2018-07-31 18:18:18'),(57,27,'電話対応','電話対応','https://www.caregiver-manual.com/blank-10','','','','','','','','','2018-07-31 18:18:18','2018-07-31 18:18:18'),(58,27,'国語-2','','','','','','','','','','','2018-07-31 18:18:18','2018-07-31 18:18:18'),(59,27,'数学理科-2','','','','','','','','','','','2018-07-31 18:18:18','2018-07-31 18:18:18'),(60,28,'介助の基本-3','','https://www.caregiver-manual.com/2day-2','','https://www.caregiver-manual.com/4day','','','','','','','2018-08-30 23:09:34','2018-08-30 23:09:34'),(61,28,'コンプライアンス','','','','','','','','','','','2018-08-30 23:09:34','2018-08-30 23:09:34'),(62,28,'車椅子介助','','https://www.caregiver-manual.com/2day-1','','','','','','','','','2018-08-30 23:09:34','2018-08-30 23:09:34'),(63,28,'一般教養-2','','','','','','','','','','','2018-08-30 23:09:34','2018-08-30 23:09:34'),(64,28,'政治経済-2','','','','','','','','','','','2018-08-30 23:09:34','2018-08-30 23:09:34'),(65,29,'感染症・食中毒対応-2','','https://www.caregiver-manual.com/blank-4','','','','','','','','','2018-09-26 21:22:38','2018-09-26 21:22:38'),(66,29,'一般教養-3','','','','','','','','','','','2018-09-26 21:22:38','2018-09-26 21:22:38'),(67,29,'数学理科-3','','','','','','','','','','','2018-09-26 21:22:38','2018-09-26 21:22:38'),(71,31,'介助の基本-2','','','','','','','','','','','2018-10-31 03:37:26','2018-10-31 03:37:26'),(72,31,'一般教養','','','','','','','','','','','2018-10-31 03:37:26','2018-10-31 03:37:26'),(73,31,'ビジネス・政治・社会','','','','','','','','','','','2018-10-31 03:37:26','2018-10-31 03:37:26'),(74,39,'介護一般','','','','','','','','','','','2018-12-09 20:25:01','2018-12-09 20:25:01'),(75,39,'ビジネス・政治・社会','','','','','','','','','','','2018-12-09 20:25:01','2018-12-09 20:25:01'),(79,43,'褥瘡予防と処置','https://www.caregiver-manual.com/blank-6','','','','','','','','','','2019-01-07 05:09:59','2019-01-07 05:09:59'),(80,43,'一般教養-5','','','','','','','','','','','2019-01-07 05:09:59','2019-01-07 05:09:59'),(81,44,'疥癬対応と処置','https://www.caregiver-manual.com/blank-3','','','','','','','','','','2019-01-25 00:38:48','2019-01-25 00:38:48'),(82,44,'一般教養-6','','','','','','','','','','','2019-01-25 00:38:48','2019-01-25 00:38:48'),(83,45,'特変・事故対応','https://www.caregiver-manual.com/3day','','','','','','','','','','2019-02-27 03:39:02','2019-02-27 03:39:02'),(84,45,'一般教養-7','','','','','','','','','','','2019-02-27 03:39:02','2019-02-27 03:39:02'),(85,46,'離設対応と予防','離設対応と予防','https://www.caregiver-manual.com/blank-7','','','','','','','','','2019-03-29 03:28:55','2019-03-29 03:28:55'),(86,46,'特変・事故対応','特変・事故対応','https://www.caregiver-manual.com/3day','','','','','','','','','2019-03-29 03:28:55','2019-03-29 03:28:55'),(87,46,'学習','','','','','','','','','','','2019-03-29 03:28:55','2019-03-29 03:28:55'),(88,46,'ビジネス・政治・社会','','','','','','','','','','','2019-03-29 03:28:56','2019-03-29 03:28:56'),(91,48,'初任者研修','','','','','','','','','','','2019-04-30 02:48:19','2019-04-30 02:48:19'),(92,48,'企業・業界知識','','','','','','','','','','','2019-04-30 02:48:20','2019-04-30 02:48:20'),(93,48,'憲法・法律・裁判','','','','','','','','','','','2019-04-30 02:48:20','2019-04-30 02:48:20'),(94,49,'介護雑学','','','','','','','','','','','2019-05-26 17:50:13','2019-05-26 17:50:13'),(95,49,'世界と日本','','','','','','','','','','','2019-05-26 17:50:13','2019-05-26 17:50:13'),(96,49,'国際機関','','','','','','','','','','','2019-05-26 17:50:13','2019-05-26 17:50:13'),(97,49,'科学','','','','','','','','','','','2019-05-26 17:50:13','2019-05-26 17:50:13'),(98,49,'地学','','','','','','','','','','','2019-05-26 17:50:13','2019-05-26 17:50:13'),(99,50,'記録','','','','','','','','','','','2019-07-02 04:19:11','2019-07-02 04:19:11'),(100,50,'国民の生活①','','','','','','','','','','','2019-07-02 04:19:11','2019-07-02 04:19:11'),(101,50,'国民の生活②','','','','','','','','','','','2019-07-02 04:19:11','2019-07-02 04:19:11'),(102,50,'法律','','','','','','','','','','','2019-07-02 04:19:11','2019-07-02 04:19:11'),(103,51,'記録','','','','','','','','','','','2019-07-02 04:22:05','2019-07-02 04:22:05'),(104,51,'国民の生活①','','','','','','','','','','','2019-07-02 04:22:06','2019-07-02 04:22:06'),(105,51,'一般教養','','','','','','','','','','','2019-07-02 04:22:06','2019-07-02 04:22:06'),(106,52,'コンプライアンス','','https://www.caregiver-manual.com/4day','','','','','','','','','2019-07-31 19:37:30','2019-07-31 19:37:30'),(107,52,'チームケア','','https://www.caregiver-manual.com/4day-1','','','','','','','','','2019-07-31 19:37:30','2019-07-31 19:37:30'),(108,52,'一般教養','','','','','','','','','','','2019-07-31 19:37:30','2019-07-31 19:37:30'),(109,53,'介護リスクマネジメント\n事故防止','','https://www.caregiver-manual.com/blank-13','','','','','','','','','2019-08-30 01:48:53','2019-08-30 01:48:53'),(110,53,'言葉使い','','','','','','','','','','','2019-08-30 01:48:53','2019-08-30 01:48:53'),(111,54,'介護リスクマネジメント\n事故防止','','https://www.caregiver-manual.com/blank-26','','','','','','','','','2019-10-01 18:31:34','2019-10-01 18:31:34'),(112,54,'雑学問題','','','','','','','','','','','2019-10-01 18:31:34','2019-10-01 18:31:34'),(113,55,'介護リスクマネジメント\n事故防止','','https://www.caregiver-manual.com/blank-27','','','','','','','','','2019-10-29 20:48:31','2019-10-29 20:48:31'),(114,55,'雑学問題','','','','','','','','','','','2019-10-29 20:48:31','2019-10-29 20:48:31'),(115,57,'介助の基本１－歩行','','https://www.caregiver-manual.com/1day-4','','','','','','','','','2019-12-01 20:31:13','2019-12-01 20:31:13'),(116,57,'介助の基本２－車椅子介助','','https://www.caregiver-manual.com/2day-1','','','','','','','','','2019-12-01 20:31:13','2019-12-01 20:31:13'),(117,57,'一般常識・作法','','','','','','','','','','','2019-12-01 20:31:13','2019-12-01 20:31:13'),(122,60,'介助の基本・入浴介助','','https://www.caregiver-manual.com/2day','','','','','','','','','2019-12-13 00:10:16','2019-12-13 00:10:16'),(123,60,'雑学','','','','','','','','','','','2019-12-13 00:10:16','2019-12-13 00:10:16'),(126,62,'はじめての食事介助','','https://www.caregiver-manual.com/2day','','','','','','','','','2020-02-02 18:12:14','2020-02-02 18:12:14'),(127,62,'健康クイズ','','','','','','','','','','','2020-02-02 18:12:14','2020-02-02 18:12:14'),(130,64,'介護リスクマネジメント','','https://www.caregiver-manual.com/blank-29','','','','','','','','','2020-02-28 01:33:15','2020-02-28 01:33:15'),(131,64,'一般常識問題','','','','','','','','','','','2020-02-28 01:33:15','2020-02-28 01:33:15'),(132,65,'感染症・食中毒マニュアル','','https://www.caregiver-manual.com/blank-4','','','','','','','','','2020-03-31 18:13:58','2020-03-31 18:13:58'),(133,65,'ウイルス知識問題','','','','','','','','','','','2020-03-31 18:13:58','2020-03-31 18:13:58'),(134,66,'介護施設における感染症対策','','https://www.caregiver-manual.com/blank-31','','','','','','','','','2020-04-30 20:41:05','2020-04-30 20:41:05'),(135,66,'常識問題','','','','','','','','','','','2020-04-30 20:41:05','2020-04-30 20:41:05'),(136,67,'介護施設における感染症対策','','https://www.caregiver-manual.com/2-1','','','','','','','','','2020-05-31 16:42:40','2020-05-31 16:42:40'),(137,67,'一般常識','','','','','','','','','','','2020-05-31 16:42:40','2020-05-31 16:42:40'),(138,68,'ビジネスマナー','','https://www.caregiver-manual.com/blank-32','','','','','','','','','2020-06-24 21:12:49','2020-06-24 21:12:49'),(139,68,'一般常識','','','','','','','','','','','2020-06-24 21:12:49','2020-06-24 21:12:49'),(142,70,'介護職員のための重要用語','','','','','','','','','','','2020-07-31 02:40:05','2020-07-31 02:40:05'),(143,70,'ロイヤリティ','','https://caregiver.co.jp','','','','','','','','','2020-07-31 02:40:05','2020-07-31 02:40:05'),(144,71,'介護職員接遇マニュアル','','https://www.caregiver-manual.com/%E6%8E%A5%E9%81%87-%E7%A4%BE%E4%BC%9A%E4%BA%BA%E3%83%9E%E3%83%8A%E3%83%BC','','','','','','','','','2020-08-31 00:17:09','2020-08-31 00:17:09'),(145,71,'一般常識','','','','','','','','','','','2020-08-31 00:17:09','2020-08-31 00:17:09'),(146,72,'認知症ケア','','','','','','','','','','','2020-09-28 00:52:22','2020-09-28 00:52:22'),(147,72,'一般常識','','','','','','','','','','','2020-09-28 00:52:22','2020-09-28 00:52:22'),(149,74,'認知症介助士','','','','','','','','','','','2020-10-30 00:28:52','2020-10-30 00:28:52'),(150,74,'一般常識問題','','','','','','','','','','','2020-10-30 00:28:52','2020-10-30 00:28:52'),(151,75,'介護基礎知識','','','','','','','','','','','2020-12-02 18:48:17','2020-12-02 18:48:17'),(152,75,'一般常識問題','','','','','','','','','','','2020-12-02 18:48:17','2020-12-02 18:48:17'),(153,76,'介護を知る','','https://www.caregiver-manual.com/1day-1','','https://www.caregiver-manual.com/1day-4','','','','','','','2020-12-28 05:10:34','2020-12-28 05:10:34'),(154,76,'介助の基本','','','','','','','','','','','2020-12-28 05:10:34','2020-12-28 05:10:34'),(155,76,'一般常識問題','','','','','','','','','','','2020-12-28 05:10:34','2020-12-28 05:10:34'),(156,77,'介護福祉士模擬問題','','','','','','','','','','','2021-01-29 01:41:48','2021-01-29 01:41:48'),(157,77,'一般常識','','','','','','','','','','','2021-01-29 01:41:48','2021-01-29 01:41:48'),(158,78,'昭和レトロ問題','','','','','','','','','','','2021-02-25 18:27:29','2021-02-25 18:27:29'),(159,78,'おばあちゃんの知恵袋問題','','','','','','','','','','','2021-02-25 18:27:29','2021-02-25 18:27:29'),(160,79,'おばあちゃんの知恵袋','','','','','','','','','','','2021-03-31 02:16:26','2021-03-31 02:16:26'),(161,79,'認知症豆知識','','','','','','','','','','','2021-03-31 02:16:26','2021-03-31 02:16:26'),(162,80,'特変・事故対応','https://www.caregiver-manual.com/3day','','','','','','','','','','2021-04-29 15:53:42','2021-04-29 15:53:42'),(163,80,'一般教養','','','','','','','','','','','2021-04-29 15:53:42','2021-04-29 15:53:42'),(164,81,'介助の基本','','https://www.caregiver-manual.com/2day-1','','','','','','','','','2021-05-31 19:44:01','2021-05-31 19:44:01'),(165,81,'熱中症予防','','https://www.caregiver-manual.com/blank-5','','','','','','','','','2021-05-31 19:44:01','2021-05-31 19:44:01'),(166,81,'電話対応','','https://www.caregiver-manual.com/blank-10','','','','','','','','','2021-05-31 19:44:01','2021-05-31 19:44:01'),(167,81,'一般常識-漢字','','','','','','','','','','','2021-05-31 19:44:01','2021-05-31 19:44:01'),(168,81,'国語-2','','','','','','','','','','','2021-05-31 19:44:01','2021-05-31 19:44:01'),(169,82,'初任者研修','','','','','','','','','','','2021-06-29 20:11:44','2021-06-29 20:11:44'),(170,82,'一般教養','','','','','','','','','','','2021-06-29 20:11:44','2021-06-29 20:11:44'),(171,83,'感染症・食中毒マニュアル','','https://www.caregiver-manual.com/blank-4','','','','','','','','','2021-07-29 18:34:52','2021-07-29 18:34:52'),(172,83,'一般教養','','','','','','','','','','','2021-07-29 18:34:52','2021-07-29 18:34:52'),(173,84,'介護職員接遇マニュアル','','','','','','','','','','','2021-08-30 01:34:30','2021-08-30 01:34:30'),(174,84,'一般常識','','','','','','','','','','','2021-08-30 01:34:30','2021-08-30 01:34:30'),(177,86,'介護雑学','','','','','','','','','','','2021-09-29 17:15:22','2021-09-29 17:15:22'),(178,86,'雑学問題','','','','','','','','','','','2021-09-29 17:15:22','2021-09-29 17:15:22'),(179,87,'介護リスクマネジメント','','https://www.caregiver-manual.com/blank-29','','','','','','','','','2021-10-29 00:40:58','2021-10-29 00:40:58'),(180,87,'一般常識問題','','','','','','','','','','','2021-10-29 00:40:58','2021-10-29 00:40:58'),(181,88,'認知症ケア','','','','','','','','','','','2021-11-28 20:29:00','2021-11-28 20:29:00'),(182,88,'一般常識','','','','','','','','','','','2021-11-28 20:29:00','2021-11-28 20:29:00'),(183,89,'介助の基本・入浴介助','','https://www.caregiver-manual.com/2day','','','','','','','','','2021-12-26 20:29:28','2021-12-26 20:29:28'),(184,89,'一般常識','','','','','','','','','','','2021-12-26 20:29:28','2021-12-26 20:29:28'),(186,91,'介護基礎知識','','','','','','','','','','','2022-01-31 00:07:04','2022-01-31 00:07:04'),(187,91,'一般常識','','','','','','','','','','','2022-01-31 00:07:04','2022-01-31 00:07:04'),(196,96,'介護知識','','','','','','','','','','','2022-02-28 14:55:29','2022-02-28 14:55:29'),(197,96,'一般常識','','','','','','','','','','','2022-02-28 14:55:29','2022-02-28 14:55:29'),(198,97,'初任者研修','','','','','','','','','','','2022-03-31 18:53:35','2022-03-31 18:53:35'),(199,97,'雑学問題','','','','','','','','','','','2022-03-31 18:53:35','2022-03-31 18:53:35'),(200,98,'介護知識問題','','','','','','','','','','','2022-04-29 16:43:43','2022-04-29 16:43:43'),(201,98,'常識問題','','','','','','','','','','','2022-04-29 16:43:43','2022-04-29 16:43:43');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`