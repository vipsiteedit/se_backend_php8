<?php

//  права доступа
    if (!function_exists('bloggerAccess')){
        function bloggerAccess($section,$user_group,$user_id) {
        //  Проверка на модератора прописанного в параметрах
            $moderi = trim($section->parametrs->param2);
            $moders = explode(",", $moderi);
            if (!empty($moders)) {
                foreach($moders as $userlogin){
                    $userlogin = trim($userlogin);
                    if (($userlogin == seUserLogin()) && (seUserLogin() != "")) {
                        $user_group = 2;
                    }
                }
            }
        //  кто пришел              
            if ($user_group == 3) {
                $who = 'admin';
            } else if (($user_group == 2) && ($user_id > 0)) {
                $who = 'moderator';
            } else if (($user_group != 3) && ($user_group != 2) && ($user_id > 0)) {
                $who = 'user';
            } else {
                $who = 'nouser';
            }
            
        //  доступ на создание записи
            if (($who == 'admin') 
                || (($who == 'moderator') &&  ((int)$section->parametrs->param3 < 3))
                || (($who == 'user') && ((int)$section->parametrs->param3 == 1))
            ) {
                $create = 'create';
            } else {
                $create = 'nocreate';
            }
            
        //  доступ на редактирование записи
            if ($who == 'admin') {
                $edit = 3;
            } else if ($who == 'moderator') {
                $edit = 2;            
            } else if($who == 'user') {
                $edit = 1;
            } else {
                $edit = 0;
            }
            
        //  return array() - кто пришел, возможность создавать, взможность редактировать
            return array($who, $create, $edit);
            
        }
    }

//  создать личную папку пользователя для загрузки файлов
    if (!function_exists('personalFolder')){
        function personalFolder($folder) {
            if ($folder == '') return;
            if (!is_dir(getcwd().'/'.SE_DIR.'images/'.$folder.'/')) {
                mkdir(getcwd().'/'.SE_DIR.'images/'.$folder.'/');
            }
            return '/'.SE_DIR.'images/'.$folder.'/';
        }
    }

//  проверка можно ли содавать пост 
    if (!function_exists('noPosting')){
        function noPosting($limit, $lastTime) {        //  limit - число (в часах), lastTime - дата
            $noPost = (intval($limit)!=0) ? intval($limit)*60*60 : 0;      //  время ограничения в секундах
            $noPost = $noPost - (strtotime('now') - strtotime($lastTime));        
            $access = ($noPost >= 0) ? false : true;
            return array($access, abs($noPost));
        }
    }
//  создание дерева кoмментарии
    if (!function_exists('tree_print')){
        function tree_print(&$newList, &$commentlist, $k_parent = 0, $level = 0){  
            if(empty($newList[$k_parent]))
                return;
            $rr = count($newList[$k_parent]);
            for($i=0;$i<$rr;$i++){                          
                $newList[$k_parent][$i]['level'] = $level; 
                $commentlist[] = $newList[$k_parent][$i];
                tree_print($newList, $commentlist, $newList[$k_parent][$i]['id'], $level+1);
            }
        }
    }
  
//  формирование даты
    if (!function_exists('blogPreloadDate')){
        function blogPreloadDate($time = '', $type = ''){
            if($time == '') 
                return;
            $date = array('today'=>'');
            list($date['year'], $date['month'], $date['day'], $date['weekday'], $date['hour'], $date['min']) = explode('-', date('Y-m-d-w-H-i', $time));
            $wday = array('воскресенье',
                          'понедельник',
                          'вторник',
                          'среда',
                          'четверг',
                          'пятница',
                          'суббота');
            $date['weekday'] = $wday[$date['weekday']];
            switch($type){
                case 'full':    //  понедельник 01 января 2000
                    $mon = array('01'=>'января', 
                                 '02'=>'февраля', 
                                 '03'=>'марта', 
                                 '04'=>'апреля',
                                 '05'=>'мая',
                                 '06'=>'июня',
                                 '07'=>'июля',
                                 '08'=>'августа',
                                 '09'=>'сентября',
                                 '10'=>'октября',
                                 '11'=>'ноября',
                                 '12'=>'декабря');
                    $date['month'] = $mon[$date['month']];
                    break;
                case 'comment': //  сегодня, 00:00 или 01.01.2000, 00:00
                    $d = explode('-', date('Y-m-d'));
                    if(($d[0] == $date['year']) && ($d[1] == $date['month']) && ($d[2] == $date['day'])){
                        $date['today'] = 'Сегодня';
                    } else if(($d[0] == $date['year']) && ($d[1] == $date['month']) && (($d[2]-1) == $date['day'])){
                        $date['today'] = 'Вчера';
                    }
                    
            }
            return $date;    
        }
    }
    
//  переименовать загружаемую картинку
    if (!function_exists('blogRenameImg')){
        function blogRenameImg($img = '', $add = '0'){
            if($img=='') return;
            $ext = end(explode(".", $img));
            $new_img = $add.time().".".$ext;
            return $new_img;    
        }
    }
    
//  транслит URL
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

//  создание таблиц в БД
    if(!function_exists('blogUpdate')){
        function blogUpdate(){   
            $file = 'marketing_blog6.php';
            if(!file_exists(getcwd().'/system/logs/'.$file)){
                $err = '';
                $sql = "
                     CREATE TABLE IF NOT EXISTS `blog_posts` (
                      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                      `id_user` int(10) unsigned NOT NULL,
                      `lang` varchar(40) NOT NULL DEFAULT 'rus',
                      `url` varchar(255) NOT NULL,
                      `title` varchar(255) NOT NULL,
                      `picture` varchar(255) DEFAULT NULL,
                      `listimages` TEXT DEFAULT NULL COMMENT 'Подпись к фото',
                      `listimages_alt` TEXT DEFAULT NULL COMMENT 'Подпись к фото',
                      `short` text NOT NULL,
                      `full` text NOT NULL,
                      `foto_param` text DEFAULT '',
                      `views` int(10) unsigned NOT NULL DEFAULT '0',
                      `rating` int(10) NOT NULL DEFAULT '0',  
                      `country` varchar(255) DEFAULT NULL,
                      `tags` varchar(255) DEFAULT NULL,
                      `commenting` enum('Y','N') NOT NULL DEFAULT 'Y',
                      `show_anons` enum('Y','N') NOT NULL DEFAULT 'N',
                      `visible` enum('Y','N') NOT NULL DEFAULT 'Y',
                      `keywords` varchar(255) DEFAULT NULL,
                      `description` varchar(255) DEFAULT NULL,
                      `beginning` int(11) unsigned NOT NULL DEFAULT '0',
                      `price` varchar(255) NULL,
                      `hit` TINYINT(1) NOT NULL DEFAULT '0',
                      `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
                      `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                      PRIMARY KEY (`id`),
                      KEY `title` (`title`),
                      KEY `tags` (`tags`),
                      KEY `id_user` (`id_user`),
                      KEY `lang` (`lang`)
                     ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
                se_db_query($sql);
                $err .= se_db_error();
                
                if (!se_db_is_field('blog_posts','rating_correction')){ 
                    $sql = "ALTER TABLE `blog_posts` 
                                CHANGE `rating` `rating_correction` INT( 10 ) NOT NULL DEFAULT '0' COMMENT 'админская накрутка рейтинга'";
                    se_db_query($sql);
                    $err .= se_db_error();
                }
    
                if (!se_db_is_field('blog_posts','listimages')){ 
                    $sql = "ALTER TABLE `blog_posts` 
                                ADD `listimages` TEXT DEFAULT NULL COMMENT 'Список картинок' AFTER `picture` ";
                    se_db_query($sql);
                    $err .= se_db_error();
                }

                if (!se_db_is_field('blog_posts','listimages_alt')){ 
                    $sql = "ALTER TABLE `blog_posts` 
                                ADD `listimages_alt` TEXT DEFAULT NULL COMMENT 'Подпись к картинкам' AFTER `listimages` ";
                    se_db_query($sql);
                    $err .= se_db_error();
                }

                if (!se_db_is_field('blog_posts','foto_param')){ 
                    $sql = "ALTER TABLE `blog_posts` 
                                ADD `foto_param` TEXT DEFAULT '' AFTER `full` ";
                    se_db_query($sql);
                    $err .= se_db_error();
                }

                $sql = "
                      CREATE TABLE IF NOT EXISTS `blog_category_post` (
                       `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                       `id_category` int(11) unsigned NOT NULL,
                       `id_post` int(11) unsigned NOT NULL,
                       `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
                       `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                       PRIMARY KEY (`id`),
                       KEY `id_category` (`id_category`),
                       KEY `id_post` (`id_post`)
                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
                se_db_query($sql);
                $err .= se_db_error();

                $sql = "
                    ALTER TABLE `blogcategories` 
                        ADD CONSTRAINT `blog_category_post_ibfk_1` FOREIGN KEY ( `id` ) REFERENCES `blog_category_post` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE,
                        ADD CONSTRAINT `blog_category_post_ibfk_2` FOREIGN KEY ( `id_post` ) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ; ";
                se_db_query($sql);
                $err .= (strpos(se_db_error(),'errno: 121')) ? '' : se_db_error();

                $sql = "
                     CREATE TABLE IF NOT EXISTS `blog_comments` (
                      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                      `upid` int(11) unsigned DEFAULT NULL,
                      `lang` varchar(3) NOT NULL DEFAULT 'rus',
                      `id_post` int(11) unsigned NOT NULL,
                      `message` text NOT NULL,
                      `id_user` int(10) unsigned NOT NULL,
                      `rating` int(10) DEFAULT '0',
                      `user_name` varchar(255) DEFAULT NULL,
                      `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
                      `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                      PRIMARY KEY (`id`),
                      KEY `upid` (`upid`),
                      KEY `id_user` (`id_user`),
                      KEY `id_post` (`id_post`)
                     ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
                se_db_query($sql);
                $err .= se_db_error();

                if (!se_db_is_field('blog_comments','rating_correction')){ 
                    $sql = "ALTER TABLE `blog_comments` 
                                CHANGE `rating` `rating_correction` INT( 10 ) NOT NULL DEFAULT '0' COMMENT 'админская накрутка рейтинга'";
                    se_db_query($sql);
                    $err .= se_db_error();
                }

                $sql = "
                     ALTER TABLE `blog_comments`
                        ADD CONSTRAINT `blog_comments_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                        ADD CONSTRAINT `blog_comments_ibfk_2` FOREIGN KEY (`upid`) REFERENCES `blog_comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                        ADD CONSTRAINT `blog_comments_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `se_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
                se_db_query($sql);
                $err .= (strpos(se_db_error(),'errno: 121')) ? '' : se_db_error();

                $sql = "
                     CREATE TABLE IF NOT EXISTS `blog_comment_rating` (
                      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                      `id_post` int(11) unsigned NOT NULL,
                      `id_user` int(10) unsigned NOT NULL,
                      `id_comment` int(11) unsigned NOT NULL,
                      `rating` int(1) DEFAULT '0',
                      `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
                      `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                      PRIMARY KEY (`id`),
                      KEY `id_post` (`id_post`),
                      KEY `id_user` (`id_user`),
                      KEY `id_comment` (`id_comment`)
                     ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
                se_db_query($sql);
                $err .= se_db_error();

                $sql = "
                     ALTER TABLE `blog_comment_rating`
                          ADD CONSTRAINT `blog_comment_rating_ibfk_1` FOREIGN KEY (`id_comment`) REFERENCES `blog_comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                          ADD CONSTRAINT `blog_comment_rating_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
                se_db_query($sql);
                $err .= (strpos(se_db_error(),'errno: 121')) ? '' : se_db_error();

                $sql = "
                     CREATE TABLE IF NOT EXISTS `blog_post_rating` (
                      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                      `id_post` int(11) unsigned NOT NULL,
                      `id_user` int(10) unsigned NOT NULL,
                      `rating` int(1) DEFAULT '0',
                      `favorite` int(1) DEFAULT '0',
                      `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
                      `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                      PRIMARY KEY (`id`),
                      KEY `id_post` (`id_post`),
                      KEY `id_user` (`id_user`)
                     ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
                se_db_query($sql);
                $err .= se_db_error();

                $sql = "
                     ALTER TABLE `blog_post_rating`
                          ADD CONSTRAINT `blog_post_rating_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
                se_db_query($sql);
                $err .= (strpos(se_db_error(),'errno: 121')) ? '' : se_db_error();
    
                $sql = "
                     CREATE TABLE IF NOT EXISTS `blog_user_subscribe` (
                      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                      `id_blogger` int(10) unsigned NOT NULL,
                      `id_user` int(10) unsigned NOT NULL,
                      `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
                      `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                      PRIMARY KEY (`id`),
                      KEY `id_blogger` (`id_blogger`),
                      KEY `id_user` (`id_user`)
                     ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";
                se_db_query($sql);
                $err .= se_db_error();
    
                if (!$err) {
                    if (!is_dir(getcwd().'/system/logs/')) {
                        mkdir(getcwd().'/system/logs/');
                    }
                    file_put_contents(getcwd().'/system/logs/' . $file, '');
                } else {
                    file_put_contents(getcwd().'/system/logs/' . $file.'_err',$err);                    
                }
            }
            return ;
        }
    }

?>