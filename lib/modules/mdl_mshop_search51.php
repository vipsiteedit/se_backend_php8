<?php
function module_mshop_search51($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_search51';
 else $__MDL_URL = 'modules/mshop_search51';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_search51';
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
 $page_vitrine = trim($section->parametrs->param16);
 if (empty($page_vitrine)){
     $page_vitrine = $__data->getVirtualPage('shop_vitrine');
 }
 if (empty($page_vitrine)) {
     $page_vitrine = $_page;
 }
 
 $plugin_search = new plugin_shopsearch($page_vitrine);
 
 if (isRequest('ajax'.$razdel)) {
     $responce = array();
     $options = array(
         'start_group' => trim($section->parametrs->param15),
         'max_count' => (int)$section->parametrs->param17,
         'image_size' => (string)$section->parametrs->param18,
         'search_article' => $section->parametrs->param21 == 'Y',
         'sort' => trim($section->parametrs->param22)
     );
     
     $responce['goods'] = $plugin_search->getProducts($options);
     header('Content-Type: application/json');
     echo json_encode($responce);
     exit();
 } 
 
 if ($section->parametrs->param20 == 'N' && $page_vitrine == $_page)
     $page_vitrine = '';   
     
 $query = htmlspecialchars($plugin_search->getQuery(), ENT_QUOTES);    

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