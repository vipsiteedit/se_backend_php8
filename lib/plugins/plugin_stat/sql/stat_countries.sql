DROP TABLE IF EXISTS `stat_countries`;

CREATE TABLE IF NOT EXISTS `stat_countries` ( 
 `id` integer unsigned NOT NULL auto_increment,
 `domain` char(2),
 `name` varchar(255),
 PRIMARY KEY  (`id`),
 KEY `domain` (`domain`),
 KEY `name` (`name`)
) ENGINE=innoDB DEFAULT CHARSET=utf8;



INSERT INTO `stat_countries`(`id`,`domain`,`name`) VALUES
 ('1','ru','Россия'),
 ('2','us','Соединеные штаты Америки')