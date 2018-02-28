CREATE TABLE `oauth_session` (
  `session_name` varchar(50) NOT NULL,
  `access_token` varchar(40) NOT NULL,
  `refresh_token` varchar(40) NOT NULL,
  `server_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `scope` varchar(50) NOT NULL DEFAULT '',
  `client_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `account_id` int(10) unsigned NOT NULL DEFAULT '0',
  `real_uid` int(10) unsigned NOT NULL DEFAULT '0',
  `expire` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `session_name` (`session_name`,`user_id`,`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
