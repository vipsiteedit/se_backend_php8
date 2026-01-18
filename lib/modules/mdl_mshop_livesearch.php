<?php
function module_mshop_livesearch($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_livesearch';
 else $__MDL_URL = 'modules/mshop_livesearch';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_livesearch';
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
 if ($section->parametrs->param16) {
     $page_vitrine = $section->parametrs->param16;
 } else {
     $page_vitrine = $__data->getVirtualPage('shop_vitrine');
 }
 
 $srch = plugin_shopsearch40::getInstance();
 $srch_price_min = round($srch->get('price','min'));
 
 $lang = se_getlang();
 if (trim($section->parametrs->param1) != '') {
     $srch_page = $section->parametrs->param1;
 } else {
     $section->parametrs->param1 = $_page;
 }
 
 //$search = addslashes($search);
 //$search = htmlspecialchars($search);
 //$search = stripslashes($search);
 
 $start_group = trim($section->parametrs->param15);
 $group = plugin_shopgroup40::getInstance($start_group);
 $start_tree_group = $group->getGroups();
 
 // Обработка живого поиска
 if (isRequest('ajax_levesearch')) {
     $shopsearch = urldecode(getRequest('ajax_levesearch', 3));
     $shopsearch = strtr($shopsearch, array('&quot;' => '"', '&#39;' => "'"));
     $arr = getReqSearch('article', $shopsearch, $start_tree_group);
     $arr = array_merge(getReqSearch('name', $shopsearch, $start_tree_group), $arr);
     //$arr = array_merge(getReqSearch('note', $shopsearch, $start_tree_group), $arr);
     echo json_encode($arr);
     exit();
 }
 
 
 
 if(isRequest('find_shop')){
     $srch->newRequest();
     header("Location: ".seMultiDir()."/".$page_vitrine.'/');
     exit;     
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