CREATE TABLE `sr_projects` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `code_status` tinyint(1) DEFAULT '0',
  `project_alias` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4;