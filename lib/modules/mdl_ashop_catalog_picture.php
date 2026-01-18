<?php
function module_ashop_catalog_picture($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/ashop_catalog_picture';
 else $__MDL_URL = 'modules/ashop_catalog_picture';
 $__MDL_ROOT = dirname(__FILE__).'/ashop_catalog_picture';
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
 $plugin_groups = plugin_shopgroups::getInstance();
 
 if (isRequest('ajax'.$section->id) && isRequest('loadsub')) {
     $id = getRequest('id', 1);
     $level = getRequest('level', 1);
     
     $plugin_groups = plugin_shopgroups::getInstance();
     $tree = $plugin_groups->getTree($id);
     
     if (!empty($tree))
         echo getHtmlCatalogPicture($section, $tree, null, $level + 1, $id);
     else
         echo '';
         
     exit();   
 }
 
 $tree = $plugin_groups->getTree(trim($section->parametrs->param5));
 $PRICEMENU = '';
     
 if (!empty($tree)) {
     if (isRequest('cat'))
         $select_group = $plugin_groups->getGroupId();
     elseif (isRequest('show')) {
         $shop_price = new seTable('shop_price');
         $shop_price->select('id, id_group');
         $shop_price->where('code="?"', getRequest('show'));
         $shop_price->fetchOne();
         $select_group = $shop_price->id_group;
         unset($shop_price);
     }
 
     $parents = null;
 
     if (!empty($select_group))
         $parents = $plugin_groups->getParentsId((int)$select_group, true);
 
     $PRICEMENU = getHtmlCatalogPicture($section, $tree, $parents);
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