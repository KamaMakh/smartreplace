CREATE TABLE `sr_pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `code_status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4;