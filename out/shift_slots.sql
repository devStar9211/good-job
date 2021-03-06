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
-- Table structure for table `shift_slots`
--

DROP TABLE IF EXISTS `shift_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shift_slots` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shift_id` int(10) unsigned NOT NULL,
  `employee_id` int(10) unsigned DEFAULT NULL,
  `employee_hiring_pattern_id` int(10) unsigned DEFAULT NULL,
  `position_type` smallint(5) unsigned NOT NULL,
  `employee_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `occupations` json DEFAULT NULL,
  `allowances` json DEFAULT NULL,
  `employee_office_id` int(10) unsigned DEFAULT NULL,
  `employee_office_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pay_calc_flg` smallint(5) unsigned NOT NULL DEFAULT '1',
  `data` json DEFAULT NULL,
  `decision_table` json DEFAULT NULL,
  `basic_salary` double DEFAULT NULL,
  `daily_wage` double DEFAULT NULL,
  `hourly_wage` double DEFAULT NULL,
  `public_transportation` double DEFAULT NULL,
  `vehicle_cost` double DEFAULT NULL,
  `one_way_transportation` double DEFAULT NULL,
  `round_trip_transportation` double DEFAULT NULL,
  `social_insurance` double DEFAULT NULL,
  `employment_insurance` double DEFAULT NULL,
  `day_shift_total_days` int(10) unsigned NOT NULL DEFAULT '0',
  `night_shift_total_days` int(10) unsigned NOT NULL DEFAULT '0',
  `rental_total_days` int(10) unsigned NOT NULL DEFAULT '0',
  `day_shift_total_time` time NOT NULL DEFAULT '00:00:00',
  `day_shift_break_time` time NOT NULL DEFAULT '00:00:00',
  `night_shift_total_time` time NOT NULL DEFAULT '00:00:00',
  `night_shift_break_time` time NOT NULL DEFAULT '00:00:00',
  `night_shift_allowance` json DEFAULT NULL,
  `welfare_expenses` int(10) NOT NULL DEFAULT '0',
  `adjustment` int(10) NOT NULL DEFAULT '0',
  `total_salary` int(10) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shift_slots_shift_id_foreign` (`shift_id`),
  KEY `shift_slots_position_type_index` (`position_type`),
  KEY `shift_slots_employee_id_index` (`employee_id`),
  KEY `shift_slots_employee_office_id_index` (`employee_office_id`),
  CONSTRAINT `shift_slots_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33166 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shifts`
