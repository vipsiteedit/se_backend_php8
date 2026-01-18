<?php
function module_news($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $_page = $__data->req->page;
 $_razdel = $__data->req->razdel;
 $_sub = $__data->req->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/news';
 else $__MDL_URL = 'modules/news';
 $__MDL_ROOT = dirname(__FILE__).'/news';
 $this_url_module = $__MDL_ROOT;
 $url_module = $__MDL_URL;
 if (file_exists($__MDL_ROOT.'/php/lib.php')){
	require_once $__MDL_ROOT.'/php/lib.php';
 }
 if (count($section->objects))
	foreach($section->objects as $record){ $__record_first = $record->id; break; }
 if (file_exists($__MDL_ROOT.'/php/parametrs.php')){
   include $__MDL_ROOT.'/php/parametrs.php';
 }
   $section->objectcount = intval($section->parametrs->param1);
 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/content.tpl";
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 // include show.tpl
 ob_start();
 if ($razdel == $_razdel && $__data->req->object){
 $record = $__data->getObject($section, $__data->req->object);
 include $__MDL_ROOT . "/tpl/show.tpl";
 }
 $__module_content['show'] =  ob_get_contents();
 ob_end_clean();
 // include arhiv.tpl
 ob_start();
 if (isRequest('arhiv') && ($razdel==$_razdel)) 
   $section->objectcount = 30;
 include $__MDL_ROOT . "/tpl/arhiv.tpl";
 $__module_content['arhiv'] =  ob_get_contents();
 ob_end_clean();
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}