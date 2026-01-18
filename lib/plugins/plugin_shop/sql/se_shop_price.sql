CREATE TABLE IF NOT EXISTS `se_shop_price` ( 
 `id` bigint unsigned NOT NULL auto_increment,
 `articles` varchar(30) NOT NULL,
 `code` varchar(30) NOT NULL,
 `name` varchar(125) NOT NULL,
 `description` text,
 `description_short` text,
 `action_id` integer,
 `picture_id` integer,
 `group_id` integer NOT NULL,
 `price` double(10) NOT NULL,
 `price_wholesale` double(10),
 `price_bonus` double(10) NOT NULL,
 `tax` float(5) NOT NULL,
 `discount_max` float(5) NOT NULL default '100.00',
 `has_discount` boolean NOT NULL,
 `has_special` boolean NOT NULL,
 `is_active` boolean NOT NULL default '1',
 `count` integer unsigned,
 `measure` varchar(50),
 `volume` float(5),
 `weight` float(5),
 `presence` varchar(50),
 `manufacturer_id` integer,
 `manufactured_date` date,
 `expiration_date` integer,
 `country_id` integer NOT NULL,
 `currency_id` integer NOT NULL,
 `updated_at` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
 `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
 PRIMARY KEY  (`id`),
 UNIQUE KEY `code` (`code`),
 KEY `group_id` (`group_id`),
 KEY `country_id` (`country_id`),
 KEY `currency_id` (`currency_id`),
 KEY `picture_id` (`picture_id`),
 KEY `manufacturer_id` (`manufacturer_id`),
 KEY `action_id` (`action_id`)
) ENGINE=innoDB DEFAULT CHARSET=utf8;

ALTER TABLE `se_shop_price` ADD CONSTRAINT `se_shop_price_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `se_shop_group` (`id`) ON UPDATE CASCADE;
ALTER TABLE `se_shop_price` ADD CONSTRAINT `se_shop_price_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `se_country` (`id`) ON UPDATE CASCADE;
ALTER TABLE `se_shop_price` ADD CONSTRAINT `se_shop_price_ibfk_3` FOREIGN KEY (`currency_id`) REFERENCES `se_currency` (`id`) ON UPDATE CASCADE;
ALTER TABLE `se_shop_price` ADD CONSTRAINT `se_shop_price_ibfk_4` FOREIGN KEY (`picture_id`) REFERENCES `se_shop_picture` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `se_shop_price` ADD CONSTRAINT `se_shop_price_ibfk_5` FOREIGN KEY (`manufacturer_id`) REFERENCES `se_shop_manufacturer` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `se_shop_price` ADD CONSTRAINT `se_shop_price_ibfk_6` FOREIGN KEY (`action_id`) REFERENCES `se_shop_action` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
