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
-- Table structure for table `EnterPrice`
--

DROP TABLE IF EXISTS `EnterPrice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EnterPrice` (
  `ep_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '企業ID',
  `ep_na` varchar(45) NOT NULL COMMENT '企業名',
  `ep_pref_id` int(11) NOT NULL COMMENT '都道府県',
  `ep_pos_code` varchar(45) NOT NULL COMMENT '企業郵便番号',
  `ep_street_addres` varchar(45) NOT NULL COMMENT '企業店舗住所',
  `ep_phone_num` varchar(45) NOT NULL COMMENT '企業電話番号',
  `ep_email` varchar(45) NOT NULL COMMENT '企業メールアドレス',
  `created_at` int(11) unsigned DEFAULT NULL COMMENT '作成日',
  `updated_at` int(11) unsigned DEFAULT NULL COMMENT '更新日',
  PRIMARY KEY (`ep_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='企業テーブル';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EnterPrice`
--

LOCK TABLES `EnterPrice` WRITE;
/*!40000 ALTER TABLE `EnterPrice` DISABLE KEYS */;
INSERT INTO `EnterPrice` VALUES (1,'株式会社杏林堂薬局',22,'145-0046','静岡県','03-2334-2222','test@gamil.com',NULL,1547874775),(2,'商店',0,'','','','0',NULL,NULL),(3,'商店',0,'','','','0',NULL,NULL),(4,'aaaadgadfg',15,'113-4432','東京都世田谷区','093-2234-1112','test@gmail.com',1511013141,1511013141),(5,'test',13,'235-4332','5kasjf;ajfioawjefpakfasefawe','123-444-5555','werawpro2ojgai@gmail.com',1532008535,1532008535),(6,'株式会社HITEX',22,'437-0047','静岡県袋井市西田64-6','0538-43-3740','kuremtsu@hitex-japan.com',1532613038,1532613038);
/*!40000 ALTER TABLE `EnterPrice` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-26  1:29:05
