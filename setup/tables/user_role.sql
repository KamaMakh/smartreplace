CREATE TABLE `user_role` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL DEFAULT '',
  `is_superuser` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_moderator` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_reporter` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`role_id`)
);