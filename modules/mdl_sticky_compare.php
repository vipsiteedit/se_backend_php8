<?php
function module_sticky_compare($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/sticky_compare';
 else $__MDL_URL = 'modules/sticky_compare';
 $__MDL_ROOT = dirname(__FILE__).'/sticky_compare';
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
 $type = getRequest('category', 1);
 $plugin_compare = new plugin_shopcompare($type);
 
 //подключение плагина
 if (isRequest('del_compare')) {
     $id_price = getRequest('del_compare', 1);
     $plugin_compare->removeFromCompare($id_price);
     echo 'item_remove-success';
     exit();
 }
 if (isRequest('clear_compare')) {
     $plugin_compare->clearCompare();
     $__data->go301(seMultiDir() . '/' . $_page . '/');
 }
 
 if (isRequest('addcart')) {       
     $shopcart = new plugin_ShopCart();
     $shopcart->addCart();                                         
     $__data->go301(seMultiDir() . '/' . $_page . '/');
     exit();
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
 //BeginSubPagegoods
 $__module_subpage['goods']['admin'] = "";
 $__module_subpage['goods']['group'] = 0;
 $__module_subpage['goods']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='goods' && file_exists($__MDL_ROOT . "/tpl/subpage_goods.tpl")){
	include $__MDL_ROOT . "/php/subpage_goods.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_goods");
	$__module_subpage['goods']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagegoods
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}