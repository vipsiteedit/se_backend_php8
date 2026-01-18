CREATE TABLE IF NOT EXISTS `se_shop_price_parameter` ( 
 `id` bigint unsigned NOT NULL auto_increment,
 `price_id` integer unsigned NOT NULL,
 `parameter_id` integer unsigned NOT NULL,
 `value` text,
 `updated_at` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
 `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
 PRIMARY KEY  (`id`),
 KEY `price_id` (`price_id`),
 KEY `parameter_id` (`parameter_id`)
) ENGINE=innoDB DEFAULT CHARSET=utf8;

ALTER TABLE `se_shop_price_parameter` ADD CONSTRAINT `se_shop_price_parameter_ibfk_1` FOREIGN KEY (`price_id`) REFERENCES `se_shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `se_shop_price_parameter` ADD CONSTRAINT `se_shop_price_parameter_ibfk_2` FOREIGN KEY (`parameter_id`) REFERENCES `se_shop_parameter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
