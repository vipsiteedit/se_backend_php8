<?php
function module_mshop_catalog_picture_menu($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_catalog_picture_menu';
 else $__MDL_URL = 'modules/mshop_catalog_picture_menu';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_catalog_picture_menu';
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
 $__data->setVirtualPage($section->parametrs->param5, 'choose_group_'.$_page); 
 $plugin_groups = plugin_shopgroups::getInstance();
 
 if (isRequest('loadsub')) {
     $id = getRequest('id', 1);
     $level = getRequest('level', 1);
     $res = $plugin_groups->getTree($id);
     if (!empty($res)) { 
         include $__MDL_ROOT . "/php/subpage_smenu.php";
         include $__data->include_tpl($section, "subpage_smenu");
     }
     else {
         echo '';
     }
     exit();
 }  
 
 $res = $plugin_groups->getTree(trim($section->parametrs->param5));
 
 if (!empty($res)) {
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
     if (!empty($select_group))
         $parents = $plugin_groups->getParentsId((int)$select_group, true);
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
 //BeginSubPagemenu
 $__module_subpage['menu']['admin'] = "";
 $__module_subpage['menu']['group'] = 0;
 $__module_subpage['menu']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='menu' && file_exists($__MDL_ROOT . "/tpl/subpage_menu.tpl")){
	include $__MDL_ROOT . "/php/subpage_menu.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_menu");
	$__module_subpage['menu']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagemenu
 //BeginSubPagesmenu
 $__module_subpage['smenu']['admin'] = "";
 $__module_subpage['smenu']['group'] = 0;
 $__module_subpage['smenu']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='smenu' && file_exists($__MDL_ROOT . "/tpl/subpage_smenu.tpl")){
	include $__MDL_ROOT . "/php/subpage_smenu.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_smenu");
	$__module_subpage['smenu']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagesmenu
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}

