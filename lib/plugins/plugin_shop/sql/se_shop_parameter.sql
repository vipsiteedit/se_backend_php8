CREATE TABLE IF NOT EXISTS `se_shop_parameter` ( 
 `id` int unsigned NOT NULL auto_increment,
 `name` varchar(255),
 `type` enum('string','integer','boolean','choice'),
 `choices` text,
 `pattern` varchar(255),
 `required` boolean default '1',
 `updated_at` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
 `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
 PRIMARY KEY  (`id`),
 KEY `type` (`type`),
 KEY `required` (`required`)
) ENGINE=innoDB DEFAULT CHARSET=utf8;

