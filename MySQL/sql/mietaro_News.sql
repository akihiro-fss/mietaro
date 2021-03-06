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
-- Table structure for table `News`
--

DROP TABLE IF EXISTS `News`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `News` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ニュースid',
  `news` mediumtext NOT NULL COMMENT 'ニュース内容',
  `val` tinyint(3) NOT NULL DEFAULT '1' COMMENT '削除フラグ 1:表示 0:削除',
  `created_at` int(11) unsigned NOT NULL COMMENT '作成日',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新日',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='お知らせ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `News`
--

LOCK TABLES `News` WRITE;
/*!40000 ALTER TABLE `News` DISABLE KEYS */;
INSERT INTO `News` VALUES (1,'お知らせ\r\nテスト\r\nテストテスト\r\nテストテストテスト\r\nテスト・テストテストテスト\r\nテスト・テストテストテストテスト',1,1500737110,0),(2,'test',1,1532007669,1532007669),(3,'aaaa',1,1532161419,1532161419),(4,'111',1,1532961960,1532961960),(5,'テスト',1,1537515814,1537515814);
/*!40000 ALTER TABLE `News` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-26  1:29:13
