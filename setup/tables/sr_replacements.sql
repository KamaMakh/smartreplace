CREATE TABLE `sr_replacements` (
  `replace_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `selector` text,
  `new_text` longtext,
  PRIMARY KEY (`replace_id`)
);