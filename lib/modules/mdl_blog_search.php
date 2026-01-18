<?php
function module_blog_search($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/blog_search';
 else $__MDL_URL = 'modules/blog_search';
 $__MDL_ROOT = dirname(__FILE__).'/blog_search';
 $this_url_module = $__MDL_ROOT;
 $url_module = $__MDL_URL;
 if (file_exists($__MDL_ROOT.'/php/lib.php')){
	require_once $__MDL_ROOT.'/php/lib.php';
 }
 if (count($section->objects))
	foreach($section->objects as $record){ $__record_first = $record->id; break; }
 if (file_exists($__MDL_ROOT.'/i18n/'.se_getlang().'.xml')){
	$__langlist = simplexml_load_file($__MDL_ROOT.'/i18n/'.se_getlang().'.xml');
	append_simplexml($section->language, $__langlist);
	foreach($section->language as $__langitem){
	  foreach($__langitem as $__name=>$__value){
	   $__name = strval($__name);
	   $__value = strval($section->traslates->$__name);
	   if (!empty($__value))
	     $section->language->$__name = $__value;
	  }
	}
 }
 if (file_exists($__MDL_ROOT.'/php/parametrs.php')){
   include $__MDL_ROOT.'/php/parametrs.php';
 }
 // START PHP
 //  русский язык в нижний регистр
     function strtolower_ru($text) {
         $alfavitlover = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');
         $alfavitupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');
         return str_replace($alfavitupper, $alfavitlover, $text);
     }
 
 //  получить данные из БД
     $lang = (SE_DIR == '') ? se_getLang() : substr(SE_DIR,0,-1);
     $topic = new seTable('blog_posts');
     $topic->select('title, url, tags, country');
     $topic->where("`lang`='?'", $lang); 
     $topic->andwhere("`visible`='Y'"); 
     $topic->andwhere("`beginning`<=UNIX_TIMESTAMP(NOW())"); 
     $topic->orderby('title');     
     $topicList = $topic->getList();
     unset($topic);
     
     //  теги
     $tag = '';
     $topicListDecode = array();
     foreach($topicList as $key=>$line){
         $tag .= $line['tags'].',';
         $topicList[$key]['title'] = stripslashes(htmlspecialchars_decode($line['title']));
     }
     
     $tag = explode(",", $tag);
     $tagList = $copy_line = array();
     foreach($tag as $line){
         $line = trim($line);
         $s_line = strtolower_ru($line);
         if(!empty($s_line) && !in_array($s_line, $copy_line)){
             $tagList[]['title'] = stripslashes(htmlspecialchars_decode($line));
             $copy_line[] = $s_line;
         }
     }
     
     //  страны
     $country = '';
     foreach($topicList as $line){
         $country .= $line['country'].',';
     }
 
     $country = explode(",", $country);
     $countryList = $copy_line = array();
     foreach($country as $line){
         $line = trim($line);
         $s_line = strtolower_ru($line);
         if(!empty($s_line) && !in_array($s_line, $copy_line)){
             $countryList[]['title'] = stripslashes(htmlspecialchars_decode($line));
             $copy_line[] = $s_line;
         }
     }
 
     $__data->setList($section, 'topics', $topicList);
     $__data->setList($section, 'tags', $tagList);
     $__data->setList($section, 'countries', $countryList);

 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__data->include_tpl($section, "content");
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}