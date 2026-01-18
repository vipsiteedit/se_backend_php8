<?php
function module_mshop_catalog($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_catalog';
 else $__MDL_URL = 'modules/mshop_catalog';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_catalog';
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
 
     $PRICEMENU = getHtmlCatalog($tree, $parents, (string)$section->parametrs->param1, $section->parametrs->param2 == 'Y', $section->parametrs->param4);
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