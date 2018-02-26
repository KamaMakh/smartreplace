CREATE TABLE `sr_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `elements` text,
  `channel_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
);