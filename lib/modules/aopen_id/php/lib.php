<?php

if (!function_exists('genRandomPassword')){
    function genRandomPassword ($len=6, $char_list='a-z,0-9') {
  $chars = array();
  // предустановленные наборы символов
  $chars['a-z'] = 'qwertyuiopasdfghjklzxcvbnm';
  $chars['A-Z'] = strtoupper($chars['a-z']);
  $chars['0-9'] = '0123456789';
  $chars['~'] = '~!@#$%^&*()_+=-:";\'/\\?><,.|{}[]';
  
  // набор символов для генерации
  $charset = '';
  // пароль
  $password = '';
  
  if (!empty($char_list)) {
   $char_types = explode(',', $char_list);
   
   foreach ($char_types as $type) {
    if (array_key_exists($type, $chars)) {
     $charset .= $chars[$type];
    } else {
     $charset .= $type;
    }
   }
  }
  
  for ($i=0; $i<$len; $i++) {
   $password .= $charset[ rand(0, strlen($charset)-1) ];
  }
  
  return $password;
    }
}

if (!function_exists('updateOpenId')){
    function updateOpenId() {
        $file_path = getcwd().'/system/logs/open_id.php';
        if(!file_exists($file_path)){
            $err = '';
            $sql = " CREATE TABLE IF NOT EXISTS `se_loginza` (
                `id` int(10) unsigned NOT NULL auto_increment,
                `uid` varchar(50) NOT NULL,
                `user_id` int(10) unsigned NOT NULL,
                `identity` varchar(255) NOT NULL,
                `provider` varchar(255) NOT NULL,
                `email` varchar(20) default NULL,
                `photo` varchar(255),
                `real_user_id` int(10),
                `updated_at` timestamp NOT NULL ,
                `created_at` timestamp NOT NULL ,
                PRIMARY KEY  (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
            se_db_query($sql); 
            $err .= mysql_error();
    
            if (!se_db_is_field('se_loginza', 'photo')) {
                se_db_query("ALTER TABLE `se_loginza` ADD `photo` varchar(255)");
            }
            $err .= mysql_error();
    
            if (!se_db_is_field('se_loginza', 'real_user_id')) {
                se_db_query("ALTER TABLE `se_loginza` ADD `real_user_id` int(10)");
            }
            $err .= mysql_error();
    
            if(!$err) {
                if(!is_dir(getcwd().'/system/logs/')) mkdir(getcwd().'/system/logs/');
                file_put_contents('system/logs/open_id.php', '');
            }
        }
    }
}
?>