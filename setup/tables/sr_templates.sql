CREATE TABLE `sr_templates` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `param` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `data` text,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`template_id`)
);