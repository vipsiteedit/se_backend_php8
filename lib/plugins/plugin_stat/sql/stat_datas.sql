CREATE TABLE IF NOT EXISTS `stat_datas` ( 
 `id` integer unsigned NOT NULL auto_increment,
 `type` enum('dm','br','os','ct','ss','rb','tp','ml'),
 `name` varchar(100),
 `d1` varchar(255),
 `d2` varchar(255),
 `d3` varchar(255),
 PRIMARY KEY  (`id`),
 KEY `type` (`type`)
) ENGINE=innoDB DEFAULT CHARSET=utf8;

