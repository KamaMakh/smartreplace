CREATE TABLE `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_type_id` int(10) unsigned NOT NULL DEFAULT '0',
  `outer_user_id` int(10) unsigned NOT NULL,
  `first_name` varchar(45) NOT NULL DEFAULT '',
  `last_name` varchar(45) NOT NULL DEFAULT '',
  `patronymic` varchar(45) NOT NULL DEFAULT '',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name_expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role_id` int(10) unsigned NOT NULL DEFAULT '0',
  `email` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `outer_id_type_id_UQI` (`user_type_id`,`outer_user_id`)
);