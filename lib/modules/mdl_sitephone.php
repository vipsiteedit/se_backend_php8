<?php
function module_sitephone($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $_page = $__data->req->page;
 $_razdel = $__data->req->razdel;
 $_sub = $__data->req->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/sitephone';
 else $__MDL_URL = 'modules/sitephone';
 $__MDL_ROOT = dirname(__FILE__).'/sitephone';
 $this_url_module = $__MDL_ROOT;
 $url_module = $__MDL_URL;
 if (file_exists($__MDL_ROOT.'/php/lib.php')){
	require_once $__MDL_ROOT.'/php/lib.php';
 }
 if (count($section->objects))
	foreach($section->objects as $record){ $__record_first = $record->id; break; }
 if (file_exists($__MDL_ROOT.'/i18n/'.se_getlang().'.xml')){
	append_simplexml($section->language,simplexml_load_file($__MDL_ROOT.'/i18n/'.se_getlang().'.xml'));
	foreach($section->language as $__langitem){
	  foreach($__langitem as $__name=>$__value){
	   if (!empty($section->traslates->$__name))
	     $section->language->$__name = $__value;
	  }
	}
 }
 if (file_exists($__MDL_ROOT.'/php/parametrs.php')){
   include $__MDL_ROOT.'/php/parametrs.php';
 }
 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/content.tpl";
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}