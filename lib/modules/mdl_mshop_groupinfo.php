<?php
function module_mshop_groupinfo($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_groupinfo';
 else $__MDL_URL = 'modules/mshop_groupinfo';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_groupinfo';
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
 $footer_text = '';
 $sgr =  new plugin_shopgroup($section->parametrs->param1);
 if (!empty($sgr->title)){
     $__data->page->titlepage = htmlspecialchars($sgr->title);
 }
 if ($section->parametrs->param2=='Y') {
     $__data->page->titlepage = $sgr->title;
 }
 if (strval($__data->page->titlepage) && getRequest('sheet', 1) > 1){
    $__data->page->titlepage .= ' : '.$section->parametrs->param3.' ' . getRequest('sheet', 1);
 }
 if (!empty($sgr->keywords) && getRequest('sheet', 1) < 2){
     $__data->page->keywords = htmlspecialchars(trim($sgr->keywords));
 }
 if ($sgr->description && getRequest('sheet', 1) < 2) {
    $__data->page->description = htmlspecialchars(strval($sgr->description));
 }
 if (getRequest('sheet', 1) < 2) {
     $footer_text = $sgr->footertext;
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