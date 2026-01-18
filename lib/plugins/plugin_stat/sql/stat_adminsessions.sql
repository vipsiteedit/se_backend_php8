CREATE TABLE IF NOT EXISTS `stat_adminsessions` ( 
 `hash` varchar(32),
 `login` varchar(32),
 `time_first` datetime,
 `time_last` datetime,
 `ip` varchar(64),
 `c` integer unsigned NOT NULL
) ENGINE=innoDB DEFAULT CHARSET=utf8;

