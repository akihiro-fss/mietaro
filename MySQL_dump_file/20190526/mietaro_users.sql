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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group` int(11) NOT NULL DEFAULT '1',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_login` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `login_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `profile_fields` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ep_id` int(11) DEFAULT NULL COMMENT '企業id',
  `str_id` int(11) DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`),
  KEY `idx_str_id` (`str_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','nhx7BHeH8j5Vj1pk476nofidJxZVZFBF4QT+j0tFxpU=',100,'admaian@example.com','1558798006','45e638764433f31fbeee21305fbd8300c73a13b7','a:0:{}',1,3,1500643041,1558796790),(25,'test','nhx7BHeH8j5Vj1pk476nofidJxZVZFBF4QT+j0tFxpU=',1,'assfawefat@gmail.com','1557140020','99d65f81bffe3ee18677c1ec28f522603fff88b4','a:0:{}',2,3,1532007918,0),(26,'wl_taniyama','nhx7BHeH8j5Vj1pk476nofidJxZVZFBF4QT+j0tFxpU=',1,'wl_taniyama@example.com','1532907844','acb61cf7be92f3e257a948b7498850f477d530f6','a:0:{}',6,1,1532907831,1535075090),(27,'test00','B0+AF6sqXL3obZ6sMUu8pZjHnajKlEN2ER2tr0YhUQk=',1,'test00@testtest.com','0','','a:0:{}',6,1,1537516071,0),(28,'leader','nhx7BHeH8j5Vj1pk476nofidJxZVZFBF4QT+j0tFxpU=',1,'administer@example.com','1547874256','e781ee7e994af636130be287b00fdaa3747ddca5','a:0:{}',6,1,1547874196,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-26  1:29:19
