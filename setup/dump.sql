-- MySQL dump 10.15  Distrib 10.0.31-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: yagla
-- ------------------------------------------------------
-- Server version	10.0.31-MariaDB-0ubuntu0.16.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `sr_groups`
--

DROP TABLE IF EXISTS `sr_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sr_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `elements` text,
  `replacements` longtext,
  `channel_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sr_groups`
--

LOCK TABLES `sr_groups` WRITE;
/*!40000 ALTER TABLE `sr_groups` DISABLE KEYS */;
INSERT INTO `sr_groups` VALUES (123,19,'[{\"name\":\"Название компании\",\"template_id\":\"100\",\"project_id\":\"19\",\"type\":\"code\",\"param\":\"div.sn-text>div.align-elem\",\"new_text\":\"Test\"}]',NULL,'G1'),(128,21,'[{\"name\":\"Город\",\"template_id\":\"101\",\"project_id\":\"21\",\"type\":\"text\",\"param\":\"div.localcontacts__adress-text.localcontacts__adress-text--nomar.localnomargin>div.localcontacts__adress-inner:nth-child(1)>b\",\"new_text\":\"Казань\"},{\"name\":\"+98945698745\",\"template_id\":\"102\",\"project_id\":\"21\",\"type\":\"text\",\"param\":\"div.localcontacts__adress-text.localcontacts__adress-text--nomar.localnomargin>div.localcontacts__adress-inner:nth-child(1)>a\",\"new_text\":\"+98945698745\"}]',NULL,'Гр1');
/*!40000 ALTER TABLE `sr_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sr_projects`
--

DROP TABLE IF EXISTS `sr_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sr_projects` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `replace_ids` text,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sr_projects`
--

LOCK TABLES `sr_projects` WRITE;
/*!40000 ALTER TABLE `sr_projects` DISABLE KEYS */;
INSERT INTO `sr_projects` VALUES (19,27,'kamron.oml.ru',NULL),(21,27,'kamron.oml.ru/kontakty',NULL);
/*!40000 ALTER TABLE `sr_projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sr_replacements`
--

DROP TABLE IF EXISTS `sr_replacements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sr_replacements` (
  `replace_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `selector` text,
  `new_text` longtext,
  PRIMARY KEY (`replace_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sr_replacements`
--

LOCK TABLES `sr_replacements` WRITE;
/*!40000 ALTER TABLE `sr_replacements` DISABLE KEYS */;
/*!40000 ALTER TABLE `sr_replacements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sr_templates`
--

DROP TABLE IF EXISTS `sr_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sr_templates` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `param` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `data` text,
  `replacements` text,
  `name` varchar(255) DEFAULT NULL,
  `new_text` text,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sr_templates`
--

LOCK TABLES `sr_templates` WRITE;
/*!40000 ALTER TABLE `sr_templates` DISABLE KEYS */;
INSERT INTO `sr_templates` VALUES (100,19,'div.sn-text>div.align-elem','code','\n						Consulting&nbsp;Group\n					',NULL,'Название компании',NULL),(101,21,'div.localcontacts__adress-text.localcontacts__adress-text--nomar.localnomargin>div.localcontacts__adress-inner:nth-child(1)>b','text','Санкт-Петербург',NULL,'Город',NULL),(102,21,'div.localcontacts__adress-text.localcontacts__adress-text--nomar.localnomargin>div.localcontacts__adress-inner:nth-child(1)>a','text','+ 7 (123) 123-45-67',NULL,'+98945698745',NULL);
/*!40000 ALTER TABLE `sr_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sr_users`
--

DROP TABLE IF EXISTS `sr_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sr_users` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `nickname` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `user_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sr_users`
--

LOCK TABLES `sr_users` WRITE;
/*!40000 ALTER TABLE `sr_users` DISABLE KEYS */;
INSERT INTO `sr_users` VALUES (27,'meganyuton1@gmail.com','$2y$10$EzumMZlt.OoIF7Va8BQLLOdF6E6NaqwJjsn9l1a05xRKycMbqPwnG','Kamron',0,'8bg709uq4ggfantrd3pso32v21'),(28,'test@gmail.com','$2y$10$89FVLJHff/58xf0P7PFGaezpId6Tshp7VOapzlLEi8CFmMyGT/ODS','test',0,'sfd6a8t7q7vkkfd7gujn0dbln2');
/*!40000 ALTER TABLE `sr_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-02-08 16:45:07
