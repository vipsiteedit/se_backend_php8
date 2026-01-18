<?php
    global $CONFIG;
    if (empty($CONFIG['prefix'])) $CONFIG['prefix'] = 'se_';
    if (!file_exists(SE_ROOT . "system/logs/se_setting.upd")){
      se_db_query("CREATE TABLE IF NOT EXISTS `{$CONFIG['prefix']}settings` (`version` varchar(10) NOT NULL, PRIMARY KEY (`version`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
      echo mysql_error();
      $fpd = fopen(SE_ROOT . "system/logs/se_setting.upd", "w+");
      fclose($fpd);
    }
    $qver = se_db_query("SELECT `version` FROM `{$CONFIG['prefix']}settings` LIMIT 1");
    list($thisver) = se_db_fetch_row($qver);
    $maxver = '';
    $d = opendir(dirname(__FILE__)."/update/");
    if (!empty($d)){
	require_once SE_LIBS.'yaml/seYaml.class.php';
        while(($f=readdir($d))!==false) {
            if ($f=='.'||$f=='..') continue;
            if ($f <= $thisver) continue;
            
            if (strpos($f, '.php') && !file_exists(SE_ROOT . "system/logs/".str_replace('.php','',$f).'.upd')){
        	//echo dirname(__FILE__)."/update/".$f;
        	require_once dirname(__FILE__)."/update/".$f;
        	$maxver = $f;
		if (!is_dir(SE_ROOT .'system/logs')) mkdir(SE_ROOT .'system/logs');
		$error = mysql_error();
		if (!$error || strpos($error,'errno: 121')){
		    $fpd = fopen(SE_ROOT . "system/logs/".str_replace('.php','',$f).'.upd', "w+");
		    fclose($fpd);
		} else {
		    $fpd = fopen(SE_ROOT . "system/logs/".str_replace('.php','',$f).'_err.upd', "w+");
		    fwrite($fpd,$error);
		    fclose($fpd);		
		} 
            }
        }
        if ($maxver) {
          if ($thisver == '')
             se_db_query("INSERT INTO `{$CONFIG['prefix']}settings`(`version`) VALUES('{$maxver}')");
          else 
            se_db_query("UPDATE `{$CONFIG['prefix']}settings` SET `version`='{$maxver}' WHERE `version`='{$thisver}'");
        }
        closedir($d);
    }
