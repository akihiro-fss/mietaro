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
-- Table structure for table `BasicInfo`
--

DROP TABLE IF EXISTS `BasicInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BasicInfo` (
  `str_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '店舗iD',
  `str_na` varchar(45) NOT NULL COMMENT '店舗名',
  `ep_id` int(11) NOT NULL COMMENT '企業ID',
  `pref_id` int(11) DEFAULT NULL COMMENT '都道府県',
  `str_pos_code` varchar(45) DEFAULT NULL COMMENT '郵便番号',
  `str_street_addres` varchar(45) DEFAULT NULL COMMENT '店舗住所',
  `str_phone_num` varchar(45) DEFAULT NULL COMMENT '店舗電話番号',
  `str_fax_num` varchar(45) DEFAULT NULL COMMENT '店舗fax番号',
  `str_info` text COMMENT '事業所情報',
  `latitude` varchar(45) DEFAULT NULL COMMENT '緯度',
  `longitude` varchar(45) DEFAULT NULL COMMENT '経度',
  `str_email_addres` varchar(45) DEFAULT NULL COMMENT '緊急連絡先 メールアドレス',
  `str_weather_region` varchar(45) DEFAULT NULL COMMENT '気象庁地域区分',
  `str_memo` text COMMENT 'メモ',
  `str_ct_1` varchar(45) DEFAULT NULL COMMENT 'CT比1次側',
  `str_ct_2` varchar(45) DEFAULT NULL COMMENT 'CT比2次側',
  `str_vt_1` varchar(45) DEFAULT NULL COMMENT 'VT比1次側',
  `str_vt_2` varchar(45) DEFAULT NULL COMMENT 'VT比2次側',
  `power_com_id` int(11) DEFAULT NULL COMMENT '電力会社ID',
  `contract_de` int(11) NOT NULL DEFAULT '0' COMMENT '契約電力',
  `demand_alarm` int(11) DEFAULT NULL COMMENT 'デマンド警報値',
  `emission_factor` float NOT NULL DEFAULT '0' COMMENT 'co2排出係数',
  `conversion_factor` float NOT NULL DEFAULT '0' COMMENT '原油換算係数',
  `created_at` int(11) unsigned DEFAULT NULL COMMENT '作成日',
  `updated_at` int(11) unsigned DEFAULT NULL COMMENT '更新日',
  PRIMARY KEY (`str_id`),
  KEY `idx_power_com` (`power_com_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BasicInfo`
--

LOCK TABLES `BasicInfo` WRITE;
/*!40000 ALTER TABLE `BasicInfo` DISABLE KEYS */;
INSERT INTO `BasicInfo` VALUES (1,'ジャパンニューアルファ　厚木金田店',6,14,'243-0806','厚木市下依知1-11-1','046-244-1811','046-244-1811','　','35.471225','139.368277','natsuki@hitex-jpan.com','関東甲信地方','　','6600','110','100','5',1,377,204,0.000486,0.001,1532614304,1535110615),(2,'ワンダーランド　うきはバイパス店',6,40,'839-1343','うきは市吉井町鷹取54-1','0943-76-2700','　','　','33.348333','130.729327','natsuki@hitex-jpan.com','九州北部地方','　','6600','110','20','5',9,0,548,0.000462,0.001,1532613679,1535112103),(3,'ワンダーランド　谷山',6,46,'891-0122','鹿児島市南栄5丁目10番地41','099-267-7773','　','　','31.581319','130.544519','natsuki@hitex-jpan.com',' ','　','6600','110','100','5',9,0,665,1,0,1532614574,1558798792);
/*!40000 ALTER TABLE `BasicInfo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-26  1:29:00
