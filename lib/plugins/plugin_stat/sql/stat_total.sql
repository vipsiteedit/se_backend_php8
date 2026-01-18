CREATE TABLE IF NOT EXISTS `stat_total` ( 
 `date` integer unsigned NOT NULL auto_increment,
 `views` integer unsigned,
 `hits` integer unsigned,
 `hosts` integer unsigned,
 `users` integer unsigned,
 PRIMARY KEY  (`date`)
) ENGINE=innoDB DEFAULT CHARSET=utf8;

