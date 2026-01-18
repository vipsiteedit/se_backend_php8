<?php

if (!function_exists('getTranslitName')) {
    function getTranslitName($str, $delimer = '_') {
        $translate = array(
                        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ж' => 'g', 'з' => 'z',
                        'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p',
                        'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'ы' => 'i', 'э' => 'e', 'А' => 'A',
                        'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ж' => 'G', 'З' => 'Z', 'И' => 'I',
                        'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
                        'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Ы' => 'I', 'Э' => 'E', 'ё' => "yo", 'х' => "h",
                        'ц' => "ts", 'ч' => "ch", 'ш' => "sh", 'щ' => "shch", 'ъ' => "", 'ь' => "", 'ю' => "yu", 'я' => "ya",
                        'Ё' => "YO", 'Х' => "H", 'Ц' => "TS", 'Ч' => "CH", 'Ш' => "SH", 'Щ' => "SHCH", 'Ъ' => "", 'Ь' => "",
                        'Ю' => "YU", 'Я' => "YA", '№' => ""
                    );
        $string = strtr($str, $translate);
        return trim(preg_replace('/[^\w]+/i', $delimer, $string), $delimer);

    }
}

if (!function_exists('bl_getFormatDate')) {
    function bl_getFormatDate($section, $date) {
        $date = explode("-", $date);
        $format = array(
                    $section->language->lang013, $section->language->lang014, $section->language->lang015, $section->language->lang016, $section->language->lang017, $section->language->lang018, 
                    $section->language->lang019, $section->language->lang020, $section->language->lang021, $section->language->lang022, $section->language->lang023, $section->language->lang024);
        return array($date[2], $format[intval($date[1]) - 1], $date[0] . ' года');
    }
}

if (!function_exists('dbUpdate')) {
    function dbUpdate() {   
        $file = 'mblog_posts-20130422.php';
        if (!file_exists(getcwd() . '/system/logs/' . $file)) {
            $err = '';
            $sql = 
                "CREATE TABLE IF NOT EXISTS `se_blog_posts` ( " .
                    "`id` int(11) NOT NULL auto_increment, " .
                    "`author_id` int(10) unsigned NOT NULL, " .
                    "`lang` varchar(15) NOT NULL default 'rus', " .
                    "`url` varchar(255) NOT NULL, " .
                    "`title` varchar(255) NOT NULL, " .
                    "`short` text NOT NULL, " .
                    "`full` text NOT NULL, " .
                    "`hits` int(11) NOT NULL default '0', " .
                    "`rating` varchar(20) NOT NULL default '30', " .
                    "`tags` varchar(255) NOT NULL, " .
                    "`commenting` enum('yes','no') NOT NULL default 'yes', " .
                    "`comment_presence` enum('yes','no') NOT NULL default 'no', " .
                    "`label` enum('stream','hidden') NOT NULL default 'stream', " .
                    "`keywords_post` varchar(255) NOT NULL, " .
                    "`description_post` varchar(255) NOT NULL, " .
                    "`date_add` date NOT NULL, " .
                    "`updated_at` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP, " .
                    "`created_at` timestamp NOT NULL default '0000-00-00 00:00:00', " .
                    "PRIMARY KEY  (`id`), " .
                    "KEY `title` (`title`), " .
                    "KEY `tags` (`tags`), " .
                    "KEY `author_id` (`author_id`), " .
                    "KEY `lang` (`lang`) " .
                ") ENGINE=InnoDB  DEFAULT CHARSET=utf8 PACK_KEYS=0 CHECKSUM=1;";
            se_db_query($sql); 
            $err .= se_db_error();
            $sql = 
                "CREATE TABLE IF NOT EXISTS `se_blog_category_post` (" .
                    "`id` int(11) NOT NULL auto_increment, " .
                    "`id_category` int(11) unsigned NOT NULL, " .
                    "`id_post` int(11) NOT NULL, " .
                    "`updated_at` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP, " .
                    "`created_at` timestamp NOT NULL default '0000-00-00 00:00:00', " .
                    "PRIMARY KEY  (`id`), " .
                    "KEY `id_category` (`id_category`), " .
                    "KEY `id_post` (`id_post`) " .
                ") ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
            se_db_query($sql);
            $err .= se_db_error();
            $sql = 
                "CREATE TABLE IF NOT EXISTS `se_blog_comments` (" .
                    "`id` int(10) unsigned NOT NULL auto_increment, " .
                    "`upid` int(10) unsigned default NULL, " .
                    "`idfiles` int(11) default NULL, " .
                    "`message` text, " .
                    "`author` varchar(40) NOT NULL, " .
                    "`date_add` date NOT NULL, " .
                    "`updated_at` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP, " .
                    "`created_at` timestamp NOT NULL default '0000-00-00 00:00:00', " .
                    "PRIMARY KEY  (`id`), " .
                    "KEY `upid` (`upid`), " .
                    "KEY `idfiles` (`idfiles`) " .
                ") ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
            se_db_query($sql);
            $err .= se_db_error();
            $sql = 
                "CREATE TABLE IF NOT EXISTS `blogcategories` ( " .
                    "`id` int(11) unsigned NOT NULL auto_increment, " .
                    "`upid` int(11) unsigned default NULL, " .
                    "`url` char(255) default NULL, " .
                    "`name` char(255) default NULL, " .
                    "`keywords` char(255) default NULL, " .
                    "`description` char(255) default NULL, " .
                    "`updated_at` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP, " .
                    "`created_at` timestamp NOT NULL default '0000-00-00 00:00:00', " .
                    "PRIMARY KEY  (`id`), " .
                    "KEY `upid` (`upid`) " .
                "') ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
            se_db_query($sql); 
            $err .= se_db_error();
            $sql = "ALTER TABLE `se_blog_posts` ADD `lang` VARCHAR( 15 ) NOT NULL DEFAULT 'rus' AFTER `author_id`";
            se_db_query($sql);
            $err .= se_db_error();
            //если нет поля lang
            if (!se_db_is_field('blogcategories', 'lang')) {
                se_db_query("ALTER TABLE `blogcategories` ADD `lang` varchar(15) NOT NULL default 'rus'");
                $err .= se_db_error();
            }
            $sql = "ALTER TABLE `blogcategories` " .
                        "ADD CONSTRAINT `blogcategories_ibfk_1` " .
                            "FOREIGN KEY (`upid`) REFERENCES `blogcategories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE";
            se_db_query($sql);      
            $err .= se_db_error();
            $sql = "ALTER TABLE `se_blog_comments` " .
                        "ADD CONSTRAINT `se_blog_comments_ibfk_1` " .
                            "FOREIGN KEY (`upid`) REFERENCES `se_blog_comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE, " .
                        "ADD CONSTRAINT `se_blog_comments_ibfk_2` " . 
                            "FOREIGN KEY (`idfiles`) REFERENCES `se_blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE";
            se_db_query($sql);
            $err .= se_db_error();
            $sql = "ALTER TABLE `se_blog_category_post` " .
                        "ADD CONSTRAINT `se_blog_category_post_ibfk_1` " . 
                            "FOREIGN KEY (`id_post`) REFERENCES `se_blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE, " .
                        "ADD CONSTRAINT `se_blog_category_post_ibfk_2` " . 
                            "FOREIGN KEY (`id_category`) REFERENCES `blogcategories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE";
            se_db_query($sql);
            $err .= se_db_error();
            $sql = "ALTER TABLE `se_blog_posts` " .
                        "ADD `event` ENUM( 'Y', 'N' ) NOT NULL DEFAULT 'N' AFTER `date_add` , " .
                        "ADD `beginning` INT( 11 ) NOT NULL DEFAULT '0' AFTER `event`";
            se_db_query($sql);
            $err .= se_db_error();
/*
            if (!is_dir(getcwd() . '/system/logs')) {
                mkdir(getcwd() . '/system/logs');
            }
            $fp = se_fopen($logupd, "w+");
            fclose($fp);
//*/
            if (!$err) {
                if (!is_dir(getcwd().'/system/logs/')) {
                    mkdir(getcwd().'/system/logs/');
                }
                file_put_contents('system/logs/' . $file, '');
            }
        }
        return $err;
    }
}
?>