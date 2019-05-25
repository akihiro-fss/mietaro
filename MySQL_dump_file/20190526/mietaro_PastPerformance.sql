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
-- Table structure for table `PastPerformance`
--

DROP TABLE IF EXISTS `PastPerformance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PastPerformance` (
  `str_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '店舗ID',
  `p_year` int(11) NOT NULL COMMENT '導入前実績で入力した年',
  `january_kwh` int(11) NOT NULL COMMENT '1月使用電力量',
  `january_kw` int(11) NOT NULL COMMENT '1月最大デマンド値',
  `february_kwh` int(11) NOT NULL COMMENT '2月使用電力量',
  `february_kw` int(11) NOT NULL COMMENT '2月最大デマンド値',
  `march_kwh` int(11) NOT NULL COMMENT '3月使用電力量',
  `march_kw` int(11) NOT NULL COMMENT '3月最大デマンド値',
  `april_kwh` int(11) NOT NULL COMMENT '4月使用電力量',
  `april_kw` int(11) NOT NULL COMMENT '4月最大デマンド値',
  `may_kwh` int(11) NOT NULL COMMENT '5月使用電力量',
  `may_kw` int(11) NOT NULL COMMENT '5月最大デマンド値',
  `june_kwh` int(11) NOT NULL COMMENT '6月使用電力量',
  `june_kw` int(11) NOT NULL COMMENT '6月最大デマンド値',
  `july_kwh` int(11) NOT NULL COMMENT '7月使用電力量',
  `july_kw` int(11) NOT NULL COMMENT '7月最大デマンド値',
  `august_kwh` int(11) NOT NULL COMMENT '8月使用電力量',
  `august_kw` int(11) NOT NULL COMMENT '8月最大デマンド値',
  `september_kwh` int(11) NOT NULL COMMENT '9月使用電力量',
  `september_kw` int(11) NOT NULL COMMENT '9月最大デマンド値',
  `october_kwh` int(11) NOT NULL COMMENT '10月使用電力量',
  `october_kw` int(11) NOT NULL COMMENT '10月最大デマンド値',
  `november_kwh` int(11) NOT NULL COMMENT '11月使用電力量',
  `november_kw` int(11) NOT NULL COMMENT '11月最大デマンド値',
  `december_kwh` int(11) NOT NULL COMMENT '12月使用電力量',
  `december_kw` int(11) NOT NULL COMMENT '12月最大デマンド値',
  `val` tinyint(3) NOT NULL DEFAULT '1' COMMENT '削除フラグ　1:表示 0:削除',
  `created_at` int(11) unsigned NOT NULL COMMENT '作成日',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新日',
  PRIMARY KEY (`str_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='導入前実績';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PastPerformance`
--

LOCK TABLES `PastPerformance` WRITE;
/*!40000 ALTER TABLE `PastPerformance` DISABLE KEYS */;
INSERT INTO `PastPerformance` VALUES (1,2012,88950,253,71329,241,71329,222,69279,174,70737,187,79392,203,97870,245,101371,264,87748,232,81377,198,72456,205,85494,252,1,1512916166,1512916166);
/*!40000 ALTER TABLE `PastPerformance` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-26  1:29:10
