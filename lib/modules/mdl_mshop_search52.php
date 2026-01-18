<?php
function module_mshop_search52($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_search52';
 else $__MDL_URL = 'modules/mshop_search52';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_search52';
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
 
 $query = isRequest('q', 1) ? trim(urldecode($_GET['q'])) : '';
 
 // Обработка живого поиска
 if (isRequest('ajax'.$razdel)) {
     $start_group = trim($section->parametrs->param15);
     if (!empty($start_group)) {
         $group = plugin_shopgroup::getInstance($start_group);
         $start_tree_group = $group->getGroups();
     }
     $count = ((int)$section->parametrs->param17 > 0) ? (int)$section->parametrs->param17 : 10;
     $responce = array();
     $query = htmlspecialchars_decode($query, ENT_QUOTES);
     $responce['query'] = $query;
     if (!empty($query)){
         $lang = se_getLang();
         $shop_price = new seTable('shop_price','sp');
         $shop_price->select('DISTINCT sp.id, sp.name, sp.code, sp.article, sp.price, sp.discount, sp.max_discount, sp.curr, sp.id_group, (SELECT 1 FROM shop_modifications sm WHERE sm.id_price=sp.id LIMIT 1) AS modifications, (SELECT si.picture FROM shop_img si WHERE si.id_price = sp.id ORDER BY si.`default` DESC, si.sort ASC LIMIT 1) AS img');
         $shop_price->innerjoin('shop_group sg','sp.id_group = sg.id');
         $shop_price->where("sp.name LIKE '%?%'", $query);
         if ($section->parametrs->param21 == 'Y')
             $shop_price->orWhere("sp.article LIKE '%?%'", $query);
         if(!empty($start_tree_group)){
             $shop_price->andWhere("sp.id_group IN (?)", $start_tree_group);
         }  
         $shop_price->andWhere("sg.active = 'Y'");
         $shop_price->andWhere("sg.lang = '?'", $lang);
         $shop_price->andWhere("sp.enabled = 'Y'");
         $goods_list = $shop_price->getList(0, $count);
         //$responce['sql'] = $shop_price->getSql();
         if (!empty($goods_list)) {
             $responce['goods'] = array();
             $shop_image = new plugin_ShopImages();
             $img_style = $shop_image->getSizeStyle($section->parametrs->param18);  
             $query = str_replace(array('(',')'), array('\(', '\)'), $query);
             foreach($goods_list as $val) {
                 $goods = array();
                 $goods['url'] = seMultiDir() . "/{$page_vitrine}/show/{$val['code']}/";
                 
                 $goods['name'] = preg_replace("/($query)/ui", "<strong>\${1}</strong>", $val['name']);
                 $goods['image'] = '<img src="' . ($shop_image->getPictFromImage($val['img'], $section->parametrs->param18)) . '" style="' . $img_style . '">';  
                 $goods['suggest'] = htmlspecialchars($val['name'], ENT_QUOTES);  
                 if ($section->parametrs->param21 == 'Y')
                     $val['article'] = preg_replace("/($query)/ui", "<strong>\${1}</strong>", $val['article']);  
                 $goods['article'] = (!empty($val['article'])) ? '# ' . $val['article'] : '';
                 if ($val['modifications']) {
                     $plugin_modifications = new plugin_shopmodifications($val['id']);
                     $plugin_modifications->getModifications(true);
                 }
                 $selected = !empty($_SESSION['modifications'][$val['id']]) ? $_SESSION['modifications'][$val['id']] : ''; 
                 $plugin_amount = new plugin_shopamount53(0, $val, 0, 1, $selected);
                 
                 $goods['price'] = $plugin_amount->showPrice();                      
                 $responce['goods'][] = $goods;        
             }
         }
     }
     echo json_encode($responce);
     exit();
 } 
 $query = htmlspecialchars($query, ENT_QUOTES);  
 
 if ($section->parametrs->param20 == 'N' && $page_vitrine == $_page)
     $page_vitrine = '';    

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