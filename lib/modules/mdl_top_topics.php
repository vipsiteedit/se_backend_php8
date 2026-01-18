<?php
function module_top_topics($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/top_topics';
 else $__MDL_URL = 'modules/top_topics';
 $__MDL_ROOT = dirname(__FILE__).'/top_topics';
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
 //  запрос в БД
     $sql ="(SELECT CONCAT_WS( ' ', `first_name` , `last_name` ) FROM person p WHERE p.id = bp.id_user) AS fio,
             (SELECT SUM(rating) FROM blog_post_rating WHERE id_post = bp.id) AS rating";
     $top = new seTable('blog_posts', 'bp');
     $top->select("id_user, url, title, $sql");
     if ($section->parametrs->param3=='last'){
        $top->orderby('id',1);
     } else {
        $top->orderby('rating',1);                    //echo $top->getsql();
     }
     $toplist = $top->getList(0, intval($section->parametrs->param1));
     foreach($toplist as $line){
         if(!$line['fio'])
             $line['fio'] = 'Администратор';
         if(!$line['rating'])
             $line['rating'] = 0;
         $__data->setItemList($section, 'topics', $line);
     }

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