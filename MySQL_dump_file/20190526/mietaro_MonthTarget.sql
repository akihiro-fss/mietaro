-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: mietaro
-- ------------------------------------------------------
-- Server version	5.7.22

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

--
-- Table structure for table `MonthTarget`
--

DROP TABLE IF EXISTS `MonthTarget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MonthTarget` (
  `str_id` int(11) NOT NULL COMMENT '店舗ID',
  `january` int(11) NOT NULL COMMENT '1月',
  `february` int(11) NOT NULL COMMENT '2月',
  `march` int(11) NOT NULL COMMENT '3月',
  `april` int(11) NOT NULL COMMENT '4月',
  `may` int(11) NOT NULL COMMENT '5月',
  `june` int(11) NOT NULL COMMENT '6月',
  `july` int(11) NOT NULL COMMENT '7月',
  `august` int(11) NOT NULL COMMENT '8月',
  `september` int(11) NOT NULL COMMENT '9月',
  `october` int(11) NOT NULL COMMENT '10月',
  `november` int(11) NOT NULL COMMENT '11月',
  `december` int(11) NOT NULL COMMENT '12月',
  `val` tinyint(3) NOT NULL DEFAULT '1' COMMENT '削除フラグ 1:表示 0:削除',
  `created_at` int(11) unsigned NOT NULL COMMENT '作成日',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新日',
  PRIMARY KEY (`str_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='月間目標値情報';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MonthTarget`
--

LOCK TABLES `MonthTarget` WRITE;
/*!40000 ALTER TABLE `MonthTarget` DISABLE KEYS */;
INSERT INTO `MonthTarget` VALUES (1,75611,66211,66794,68152,76767,84928,94934,96928,84888,76193,65363,68893,1,0,0);
/*!40000 ALTER TABLE `MonthTarget` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-26  1:29:08
