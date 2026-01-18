<?php
function module_anavmenu_catalog($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/anavmenu_catalog';
 else $__MDL_URL = 'modules/anavmenu_catalog';
 $__MDL_ROOT = dirname(__FILE__).'/anavmenu_catalog';
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
 $path = seMultiDir().'/'.$section->parametrs->param1;
 $multipath = '';//seMultiDir();
 if (isRequest('loadsubcatalog')) {
     $id = '0';
     $level = getRequest('level', 1);
     if ($level == 1) {
        $group = $plugin_groups->getGroup($id);
        $description = str_replace(' src="images/', ' src="/images/', $group['commentary']);
     }
     $id = (!empty($id)) ? $id : trim($section->parametrs->param5);
     $tree = $plugin_groups->getTree($id);
     $counts = count($tree);
     $counts = ($counts <= $section->parametrs->param15) ? $counts : $section->parametrs->param15;
     $count_width = str_replace(',','.',(100 / $counts));
         
     include $__MDL_ROOT . "/php/subpage_cat.php";
     include $__data->include_tpl($section, "subpage_cat");
     exit();   
 }
 
 $tree = $plugin_groups->getTree(trim($section->parametrs->param5));
     
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
 
     if (!empty($selct_group))
         $parents = $plugin_groups->getParentsId((int)$select_group, true);
     $groupimages = new plugin_ShopImages('group');
     foreach($tree as $item){
         if (!empty($item['image']))
             $item['image'] = $groupimages->getPictFromImage($item['image']);
         $item['menu'] = (!empty($item['menu'])) ? true : false;
         $__data->setItemList($section, 'catalog', $item);
     }
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
 //BeginSubPagecat
 $__module_subpage['cat']['admin'] = "";
 $__module_subpage['cat']['group'] = 0;
 $__module_subpage['cat']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='cat' && file_exists($__MDL_ROOT . "/tpl/subpage_cat.tpl")){
	include $__MDL_ROOT . "/php/subpage_cat.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_cat");
	$__module_subpage['cat']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagecat
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
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}