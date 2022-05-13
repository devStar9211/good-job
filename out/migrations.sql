-- MySQL dump 10.13  Distrib 5.7.17, for Linux (x86_64)
--
-- Host: localhost    Database: shift
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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (5,'2017_01_18_075928_create_shifts_table',1),(6,'2017_01_18_080007_create_shift_patterns_table',1),(7,'2017_01_18_080020_create_shift_slots_table',1),(8,'2017_01_18_080041_create_login_lists_table',1),(9,'2017_03_30_191742_alter_shift_slots_table_rename_hiring_pattern_id',2),(10,'2017_04_04_130128_alter_shift_patterns_table_add_break_time',3),(11,'2017_04_04_160813_alter_shift_slots_table_add_day_shift_break_time',3),(12,'2017_04_05_200620_create_honobono_results_table',4),(13,'2017_04_05_200622_create_honobono_schedules_table',4),(14,'2017_04_12_195009_alter_shift_slots_table_add_employee_number',4),(19,'2017_04_14_144058_alter_honobono_results_table_rename_jigyo_id',5),(20,'2017_04_14_144128_alter_honobono_results_table_add_jigyo_id',5),(21,'2017_04_18_150111_alter_shift_slots_table_modify_employee_number',6),(22,'2017_04_18_182718_honobono_user_nums_table',7),(23,'2017_04_18_203411_alter_honobono_results_table_allow_null',8),(24,'2017_04_18_203417_alter_honobono_schedules_table_allow_null',8),(25,'2017_04_21_220001_alter_shifts_table_change_adjustment_signed',9),(26,'2017_04_21_220021_alter_shift_slots_table_change_adjustment_signed',9),(27,'2017_05_15_191856_alter_honobono_results_table_jigyo_number_to_string',10),(28,'2017_05_15_192305_alter_honobono_schedules_table_jigyo_number_to_string',10),(29,'2017_05_15_192714_alter_honobono_user_nums_table_jigyo_number_to_string',10),(30,'2017_05_15_214405_alter_shift_slots_table_add_night_shift_break_time',10),(31,'2017_05_26_155500_create_honobono_kaigo_results_table',11),(32,'2017_05_26_155501_create_honobono_riyou_results_table',11);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shift_patterns`
