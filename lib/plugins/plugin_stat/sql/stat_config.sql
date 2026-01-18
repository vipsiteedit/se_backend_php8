CREATE TABLE IF NOT EXISTS `stat_config` ( 
 `variable` varchar(50) NOT NULL,
 `value` varchar(255),
 PRIMARY KEY  (`variable`)
) ENGINE=innoDB DEFAULT CHARSET=utf8;



INSERT INTO `stat_config`(`variable`,`value`) VALUES
 ('language','russian'),
 ('mail_day','0'),
 ('mail_email',''),
 ('mail_subject','SiteEdit Satistics Report [%d.%m.%Y]'),
 ('mail_content','7'),
 ('version','1.1'),
 ('hints','1'),
 ('gauge','1'),
 ('percents','1'),
 ('graphic','2'),
 ('antialias','1'),
 ('date_format','d.m.Y'),
 ('shortdate_format','m.Y'),
 ('datetime_format','d.m.Y H:i:s'),
 ('datetimes_format','d.m.Y H:i'),
 ('shortdm_format','d.m'),
 ('dataupdate','1263112012'),
 ('adminlogin','admin'),
 ('adminpassword','4a3b9b8de6489c966f211b94c7cbaadd'),
 ('disablepassword','0'),
 ('savelogday','90'),
 ('timeoffset','0'),
 ('senderrorsbymail','0'),
 ('adminemail',''),
 ('timelastclear','0')