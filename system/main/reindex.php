<?php

function se_ReIndexPage($fname)
{
  if ($fname == '.xml') $fname = 'home.xml';
  $path = getcwd().'/projects/' . SE_DIR;
  $page = basename($fname,'.xml');
  if (!SE_DB_ENABLE) return;
  //se_clearDir($path . 'searchdata/'.$page . '/');
    //rmdir($path . 'searchdata/'.$page);
  //}
//echo $path . 'pages/' . $fname;

	if (!file_exists(SE_ROOT .'system/logs/se_search.upd'))
	{
		if (!is_dir(SE_ROOT .'system/logs')) mkdir(SE_ROOT .'system/logs');
		se_db_query("CREATE TABLE IF NOT EXISTS `se_search` (
		`project` varchar(40) NOT NULL,
		`page` varchar(255) NOT NULL,
		`url` varchar(255) NOT NULL,
		`size` bigint(20) NOT NULL,
		`filetime` int(14) NOT NULL,
		`title` varchar(255) NOT NULL,
		`titlepage` varchar(255) NOT NULL,
		`keywords` varchar(255) NOT NULL,
		`description` varchar(255) NOT NULL,
		`searchtext` mediumtext NOT NULL,
		`modules` text NOT NULL,
		`updated_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
		`created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
		KEY  (`project`),
		KEY  (`page`),
		KEY  (`url`),
		KEY `filetime` (`filetime`),
		FULLTEXT KEY `searchtext` (`searchtext`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		if (se_db_error() == ''){
		    $fp = fopen(SE_ROOT .'system/logs/se_search.upd', "w+");
		    fclose($fp);
		}
	}



 if (!file_exists($path . 'pages/' . $fname)) return;
  $xml = simplexml_load_file($path . 'pages/' . $fname);

 $project = str_replace('/','', SE_DIR);
 $ftime = filectime($path.'pages/'.$fname);
 
 $srch = new seTable('se_search', 'ss');
 $srch->select('ss.page');
 $srch->where("ss.`filetime`=?", $ftime);
 $srch->andWhere("ss.page='?'", $page);
 $srch->andWhere("ss.project='?'", $project);
 //echo $srch->getSql();
 $res = $srch->fetchOne();
 //echo mysql_error();
 if (!empty($res)) return;
//echo $srch->page.'!!!';
 se_db_query("DELETE FROM `se_search` WHERE `project`='{$project}' AND `page`='{$page}'");
 
 $srch->insert;
 $srch->project = $project;
 $srch->page = $page;


// Достаем из страницы все текстовые данные
  $link='/'.$page.'/';
  $srch->url = $link;
  $srch->size = round(filesize($path.'pages/'.$fname)/1024);
  $srch->filetime = $ftime;
  $srch->titlepage = $xml->titlepage;
  $srch->title = $xml->title;
  $srch->keywords = $xml->keywords;
  $srch->description = $xml->description;


  $texts = $xml->enteringtext;
  $modularr = array();
    foreach($xml->sections as $section)
    {
        if (!in_array(strval($section->type), $modularr))
		  $modularr[] = strval($section->type);
		//$srch->modules .= $section->type;
		
		if (!empty($section->title))    
		$texts .=  $section->title.' ';
                if (!empty($section->text))    
		$texts .=  $section->text.' ';
	
	
	    foreach($section->objects as $object)
	    {
                if (!empty($object->title))    
		$texts .=  $object->title.' ';
                if (!empty($object->note))		
		$texts .=  $object->note.' ';
                  if ($object->text != "") {
			$srchs = new seTable('se_search');
			$srchs->project = $project;
			$srchs->page = $page;
			$srchs->url = '/'.$page.'/'.$section->id.'/'.$object->id.'/';
			$srchs->size = $srch->size;
			$srchs->filetime = $srch->filetime;
			$srchs->titlepage = '';
			$srchs->title = $object->title;
			$srchs->keywords = '';
			$srchs->description = '';
			$dt = strip_tags($object->text);
			$dt = preg_replace("/[><\'\"]+/u"," ", $dt);
			$dt = preg_replace ("/[\s]+/u", " ", $dt);
                    $dt = str_replace("^^","",$dt);
                    $dt = str_replace("|","",$dt);
                    $dt=wordwrap($dt, 80, "\n");
			$srchs->searchtext = $dt;
			$srchs->save();
			unset($srchs);
                  }
                if (!empty($object->text1))
		$texts .=  $object->text1.' ';
                if (!empty($object->text2))
		$texts .=  $object->text2.' ';
                if (!empty($object->text3))
		$texts .=  $object->text3.' ';
                if (!empty($object->text4))
		$texts .=  $object->text4.' ';
                if (!empty($object->text5))
		$texts .=  $object->text5.' ';
                if (!empty($object->image_alt))
		$texts .=  $object->image_alt.' ';
	    }
	}

	$texts.= (!empty($xml->closingtext)) ? $xml->closingtext : '';
	$texts = preg_replace("/<.+?>/u"," ",$texts);
	if (function_exists('conditions'))
	{
		$texts = conditions($texts);
	}
	$texts = preg_replace("/[><\'\"]+/u"," ",$texts);
	$texts = preg_replace ("/[\s]+/u", " ", $texts);
	
  	$texts = str_replace("^^","",$texts);
  	$texts = str_replace("&#10;","\n",$texts);
  	$texts = str_replace("|","",$texts);
  	$texts = str_replace('[SE_PARTSELECTOR]','',$texts);
        $texts = str_replace("&nbsp;"," ",$texts);
  	$texts=wordwrap($texts, 80, "\n");
  //echo $texts;
  //Пишем в файл
  //echo $path.'searchdata/'.$page.'.dat'."\n";
  $srch->modules = join("\r\n", $modularr);
  $srch->searchtext = $texts;
  $srch->save();
  //$f=fopen($path.'searchdata/'.$page.'.dat', "w");
  //fwrite($f, $texts);
  //fclose($f);
}

function reindexsite() {
  if (SE_DB_ENABLE && file_exists(SE_ROOT.'projects/' . SE_DIR . 'pages.xml'))
  {
	if (file_exists(SE_ROOT .'system/logs/se_search.upd'))
	{
	     $project = str_replace('/','', SE_DIR);
	     se_db_query("DELETE FROM `se_search` WHERE `project`='$project'");
	}

	$pages = simplexml_load_file(SE_ROOT.'projects/' . SE_DIR . 'pages.xml');
	foreach($pages->page as $page)
	{
	    $fname = $page[0]['name'];
	    se_ReIndexPage($fname . '.xml');
	}
  }
  if (!is_dir(SE_ROOT . 'projects/' . SE_DIR . 'searchdata')){
    mkdir( SE_ROOT . 'projects/' . SE_DIR . 'searchdata');
  }
}
?>