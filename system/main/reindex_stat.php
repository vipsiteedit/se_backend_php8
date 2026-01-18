<?php
//    define('SE_ROOT', './');
//    require 'system/main/init.php';
//    require SE_CORE . 'messages.php';
//    require SE_CORE . 'auth.php';
//    require SE_CORE . 'function.php';
//    require SE_CORE . 'classes/seData.class.php';
//    require SE_CORE . 'serequests.php';
//    require 'lib/lib.php';
function se_ClearDir($dir) {
  $tmpdir = getcwd();
  if (is_dir($dir) && chdir($dir)) {
  	$d = opendir(".");
  	if (!empty($d))
  	while(($f=readdir($d))!==false) {
    	if ($f=='.'||$f=='..' || !is_file($f)) continue;
		unlink($f);
  	}
  	closedir($d);
  }
  chdir($tmpdir);
  //mkdir($dir);
}

function se_ReIndexPage($fname)
{
  $path = getcwd().'/projects/' . SE_DIR;
  $page = basename($fname,'.xml');
  if (is_dir($path . 'searchdata/'. $page)) 
  {
    se_clearDir($path . 'searchdata/'.$page . '/');
    rmdir($path . 'searchdata/'.$page);
  }
//echo $path . 'pages/' . $fname;
 if (!file_exists($path . 'pages/' . $fname)) return;
  $xml = simplexml_load_file($path . 'pages/' . $fname);

// Достаем из страницы все текстовые данные
  $link='/'.$page.'/';

  $texts = $link.chr(1).round(filesize($path.'pages/'.$fname)/1024).chr(1).date("d.m.Y",filectime($path.'pages/'.$fname))."&#10;".$xml->titlepage."&#10;".$xml->title."&#10;".$xml->keywords."&#10;".$xml->description."&#10;";
  $texts.=$xml->enteringtext;
  
	foreach($xml->sections as $section)
	{
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
                    if (!file_exists($path.'searchdata/'.$page)) mkdir($path.'searchdata/'.$page);
                    $dtext=$link.$section->id.'/'.$object->id.'/'.chr(1).round(utf8_strlen($object->text)/1024).chr(1).date("d.m.Y",filectime($path.'pages/'.$fname))."\n\n".$object->title."\n\n\n";
                    $dt = strip_tags($object->text);
	             $dt = preg_replace("/[><\'\"]+/u"," ", $dt);
	             $dt = preg_replace ("/[\s]+/u", " ", $dt);
                    $dt = str_replace("^^","",$dt);
                    $dt = str_replace("|","",$dt);
                    $dt=wordwrap($dt, 80, "\n");
                    $dtext.=$dt;
                    $f=fopen($path."searchdata/$page/".$page."_".$item."_".$object->id.".dat", "w");
                    fwrite($f, $dtext);
                    fclose($f);
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

	$texts.=$closingtext;
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
  $f=fopen($path.'searchdata/'.$page.'.dat', "w");
  fwrite($f, $texts);
  fclose($f);
}

function reindexsite() {
	if (file_exists(SE_ROOT.'projects/' . SE_DIR . 'pages.xml'))
	{

  		if (!is_dir(SE_ROOT.'projects/' . SE_DIR . 'searchdata')) 
  		{
    		mkdir(SE_ROOT.'projects/' . SE_DIR . 'searchdata');
  		}
  		else
  		{
    		    se_clearDir(SE_ROOT.'projects/' . SE_DIR . 'searchdata');
		}
		$pages = simplexml_load_file(SE_ROOT.'projects/' . SE_DIR . 'pages.xml');
		foreach($pages->page as $page)
		{
		    $fname = $page[0]['name'];
		    se_ReIndexPage($fname . '.xml');
		}
	} 

}
?>