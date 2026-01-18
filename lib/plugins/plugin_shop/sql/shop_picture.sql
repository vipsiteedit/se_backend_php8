CREATE TABLE IF NOT EXISTS `shop_picture` ( 
 `id` bigint unsigned NOT NULL auto_increment,
 `picture` varchar(255) NOT NULL,
 `title` varchar(50),
 `price_id` integer unsigned,
 `updated_at` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
 `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
 PRIMARY KEY  (`id`),
 KEY `price_id` (`price_id`)
) ENGINE=innoDB DEFAULT CHARSET=utf8;

ALTER TABLE `shop_picture` ADD CONSTRAINT `shop_picture_ibfk_1` FOREIGN KEY (`price_id`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
