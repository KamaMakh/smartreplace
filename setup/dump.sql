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
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sr_groups`
--

LOCK TABLES `sr_groups` WRITE;
/*!40000 ALTER TABLE `sr_groups` DISABLE KEYS */;
INSERT INTO `sr_groups` VALUES (1,7,'[{\"template_id\":\"3\",\"project_id\":\"7\",\"param\":\"div.mp-timer__container>div.mp-timer__layout:nth-child(1)>p\",\"type\":\"\\u0422\\u0435\\u043a\\u0441\\u0442\",\"data\":\"\\u041c\\u044b \\u043f\\u0440\\u0435\\u0434\\u043b\\u0430\\u0433\\u0430\\u0435\\u043c \\u0441\\u043e\\u0437\\u0434\\u0430\\u043d\\u0438\\u0435 \\u0441\\u0430\\u0439\\u0442\\u043e\\u0432 \\u0432 \\u041c\\u043e\\u0441\\u043a\\u0432\\u0435 \\u0438 \\u043f\\u043e \\u0432\\u0441\\u0435\\u0439 \\u0420\\u043e\\u0441\\u0441\\u0438\\u0438: \\u043e\\u0442 \\u0432\\u0438\\u0437\\u0438\\u0442\\u043e\\u043a \\u0434\\u043e \\u0418\\u043d\\u0442\\u0435\\u0440\\u043d\\u0435\\u0442-\\u043c\\u0430\\u0433\\u0430\\u0437\\u0438\\u043d\\u043e\\u0432. \\u0410 \\u0442\\u0430\\u043a\\u0436\\u0435 \\u043a\\u043e\\u043d\\u0442\\u0435\\u043a\\u0441\\u0442\\u043d\\u043e\\u0435 \\u0438 SEO-\\u043f\\u0440\\u043e\\u0434\\u0432\\u0438\\u0436\\u0435\\u043d\\u0438\\u0435 \\u0432 \\u043f\\u043e\\u0438\\u0441\\u043a\\u043e\\u0432\\u044b\\u0445 \\u0441\\u0438\\u0441\\u0442\\u0435\\u043c\\u0430\\u0445, \\u0441\\u0435\\u0440\\u0432\\u0438\\u0441\\u044b \\u043f\\u043e\\u0432\\u044b\\u0448\\u0435\\u043d\\u0438\\u044f \\u043f\\u0440\\u043e\\u0434\\u0430\\u0436 \\u0438 \\u0442\\u0435\\u0445\\u043d\\u0438\\u0447\\u0435\\u0441\\u043a\\u0443\\u044e \\u043f\\u043e\\u0434\\u0434\\u0435\\u0440\\u0436\\u043a\\u0443 \\u043f\\u043e \\u0441\\u0438\\u0441\\u0442\\u0435\\u043c\\u0435 \\u0443\\u043f\\u0440\\u0430\\u0432\\u043b\\u0435\\u043d\\u0438\\u044f.\",\"replacements\":null,\"name\":\"111\"},{\"template_id\":\"4\",\"project_id\":\"7\",\"param\":\"div.mp-wrapp.mp-switch__wrapp>h2.mp-title.mp-title--small:nth-of-type(1)\",\"type\":\"\\u0422\\u0435\\u043a\\u0441\\u0442\",\"data\":\"\\u0412\\u044b\\u0431\\u0435\\u0440\\u0438\\u0442\\u0435 \\u0442\\u0438\\u043f \\u0441\\u0430\\u0439\\u0442\\u0430\",\"replacements\":null,\"name\":\"222\"},{\"template_id\":\"5\",\"project_id\":\"7\",\"param\":\"div.mp-wrapp.mp-switch__wrapp>p.mp-ex-title\",\"type\":\"\\u0422\\u0435\\u043a\\u0441\\u0442\",\"data\":\"\\u0445\\u043e\\u0442\\u0438\\u0442\\u0435 \\u0431\\u043e\\u043b\\u044c\\u0448\\u0435?\",\"replacements\":null,\"name\":\"333\"}]'),(18,NULL,'null'),(24,7,'[{\"template_id\":\"3\",\"project_id\":\"7\",\"param\":\"div.mp-timer__container>div.mp-timer__layout:nth-child(1)>p\",\"type\":\"\\u0422\\u0435\\u043a\\u0441\\u0442\",\"name\":\"111\"},{\"template_id\":\"4\",\"project_id\":\"7\",\"param\":\"div.mp-wrapp.mp-switch__wrapp>h2.mp-title.mp-title--small:nth-of-type(1)\",\"type\":\"\\u0422\\u0435\\u043a\\u0441\\u0442\",\"name\":\"222\"},{\"template_id\":\"5\",\"project_id\":\"7\",\"param\":\"div.mp-wrapp.mp-switch__wrapp>p.mp-ex-title\",\"type\":\"\\u0422\\u0435\\u043a\\u0441\\u0442\",\"name\":\"333\"}]'),(25,NULL,'null'),(26,NULL,'null'),(27,NULL,'null'),(28,11,'[{\"template_id\":\"6\",\"project_id\":\"11\",\"param\":\"div.list>div.item-outer.bx-clone:nth-child(16)>div.item>span.title>span.align-elem\",\"type\":\"\\u0411\\u043b\\u043e\\u043a \\u0441 \\u043a\\u043e\\u0434\\u043e\\u043c\",\"data\":\"\\u0412\\u0438\\u0442\\u0440\\u0430\\u0436\\u043d\\u044b\\u0435 \\u043f\\u043e\\u0442\\u043e\\u043b\\u043a\\u0438\",\"replacements\":null,\"name\":\"test\"},{\"template_id\":\"7\",\"project_id\":\"11\",\"param\":\"div.horizontal_blocklist.horizontal_blocklist-28.widget-18.horizontal_mode.widget-type-block_list_horizontal.editorElement.layer-type-widget>div.header:nth-child(1)>div.header_text>div.align-elem\",\"type\":\"\\u0411\\u043b\\u043e\\u043a \\u0441 \\u043a\\u043e\\u0434\\u043e\\u043c\",\"data\":\"\\u0410\\u043a\\u0446\\u0438\\u0438 \\u0438 \\u0441\\u043f\\u0435\\u0446\\u043f\\u0440\\u0435\\u0434\\u043b\\u043e\\u0436\\u0435\\u043d\\u0438\\u044f\",\"replacements\":null,\"name\":\"\\u0430\\u043a\\u0446\\u0438\\u0438\"}]'),(29,12,'[{\"template_id\":\"8\",\"project_id\":\"12\",\"param\":\"div.topic-list>div:nth-child(2)>a>span\",\"type\":\"\\u0422\\u0435\\u043a\\u0441\\u0442\",\"data\":\"(PART 7) Seeking eager campers to join Voyage cohort! (AKA gain team developer experience)\",\"replacements\":null,\"name\":\"text\"}]'),(30,13,'[{\"template_id\":\"9\",\"project_id\":\"13\",\"param\":\"div.products-inner>div.block-title:nth-of-type(1)\",\"type\":\"\\u0422\\u0435\\u043a\\u0441\\u0442\",\"data\":\"\\u0423\\u0441\\u043b\\u0443\\u0433\\u0438\",\"replacements\":null,\"name\":\"\\u0417\\u0430\\u0433\\u043e\\u043b\\u043e\\u0432\\u043e\\u043a \\u0443\\u0441\\u043b\\u0443\\u0433\"}]');
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sr_projects`
--

LOCK TABLES `sr_projects` WRITE;
/*!40000 ALTER TABLE `sr_projects` DISABLE KEYS */;
INSERT INTO `sr_projects` VALUES (7,27,'http://megagroup.ru',NULL),(8,27,'http://oper.ru',NULL),(9,27,'http://opera.ru',NULL),(10,27,'https://megagroup.ru',NULL),(11,27,'http://kamron.oml.ru',NULL),(12,27,'https://forum.freecodecamp.org',NULL),(13,27,'http://d-karta.oml.ru',NULL);
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
  `data` longtext,
  `get_param` varchar(255) NOT NULL,
  `channel_name` varchar(255) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`replace_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sr_replacements`
--

LOCK TABLES `sr_replacements` WRITE;
/*!40000 ALTER TABLE `sr_replacements` DISABLE KEYS */;
INSERT INTO `sr_replacements` VALUES (9,7,'{\"div.mp-timer__container>div.mp-timer__layout:nth-child(1)>p\":\"cccc\",\"div.mp-wrapp.mp-switch__wrapp>h2.mp-title.mp-title--small:nth-of-type(1)\":\"cccc\",\"div.mp-wrapp.mp-switch__wrapp>p.mp-ex-title\":\"cccc\"}','1s7','test3',1),(10,7,'{\"div.mp-timer__container>div.mp-timer__layout:nth-child(1)>p\":\"cc\",\"div.mp-wrapp.mp-switch__wrapp>h2.mp-title.mp-title--small:nth-of-type(1)\":\"cccc\",\"div.mp-wrapp.mp-switch__wrapp>p.mp-ex-title\":\"ccc\"}','24s7','test4',24),(11,11,'{\"div.list>div.item-outer.bx-clone:nth-child(16)>div.item>span.title>span.align-elem\":\"test\",\"div.horizontal_blocklist.horizontal_blocklist-28.widget-18.horizontal_mode.widget-type-block_list_horizontal.editorElement.layer-type-widget>div.header:nth-child(1)>div.header_text>div.align-elem\":\"qwerty\"}','28s11','test',28),(12,12,'{\"div.topic-list>div:nth-child(2)>a>span\":\"aaaaaa\"}','29s12','tetete',29),(13,13,'{\"div.products-inner>div.block-title:nth-of-type(1)\":\"\\u041c\\u044b \\u043f\\u0440\\u0435\\u0434\\u043b\\u043e\\u0433\\u0430\\u0435\\u043c\"}','30s13','реклама в гугле',30);
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
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sr_templates`
--

LOCK TABLES `sr_templates` WRITE;
/*!40000 ALTER TABLE `sr_templates` DISABLE KEYS */;
INSERT INTO `sr_templates` VALUES (3,7,'div.mp-timer__container>div.mp-timer__layout:nth-child(1)>p','Текст','Мы предлагаем создание сайтов в Москве и по всей России: от визиток до Интернет-магазинов. А также контекстное и SEO-продвижение в поисковых системах, сервисы повышения продаж и техническую поддержку по системе управления.',NULL,'111'),(4,7,'div.mp-wrapp.mp-switch__wrapp>h2.mp-title.mp-title--small:nth-of-type(1)','Текст','Выберите тип сайта',NULL,'222'),(5,7,'div.mp-wrapp.mp-switch__wrapp>p.mp-ex-title','Текст','хотите больше?',NULL,'333'),(6,11,'div.list>div.item-outer.bx-clone:nth-child(16)>div.item>span.title>span.align-elem','Блок с кодом','Витражные потолки',NULL,'test'),(7,11,'div.horizontal_blocklist.horizontal_blocklist-28.widget-18.horizontal_mode.widget-type-block_list_horizontal.editorElement.layer-type-widget>div.header:nth-child(1)>div.header_text>div.align-elem','Блок с кодом','Акции и спецпредложения',NULL,'акции'),(8,12,'div.topic-list>div:nth-child(2)>a>span','Текст','(PART 7) Seeking eager campers to join Voyage cohort! (AKA gain team developer experience)',NULL,'text'),(9,13,'div.products-inner>div.block-title:nth-of-type(1)','Текст','Услуги',NULL,'Заголовок услуг');
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sr_users`
--

LOCK TABLES `sr_users` WRITE;
/*!40000 ALTER TABLE `sr_users` DISABLE KEYS */;
INSERT INTO `sr_users` VALUES (27,'meganyuton1@gmail.com','$2y$10$EzumMZlt.OoIF7Va8BQLLOdF6E6NaqwJjsn9l1a05xRKycMbqPwnG','Kamron',0,'8eekvq10mo59otiknb9kn0opn1');
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

-- Dump completed on 2018-01-25 12:55:50
