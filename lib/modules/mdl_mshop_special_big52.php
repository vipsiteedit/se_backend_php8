<?php
function module_mshop_special_big52($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_special_big52';
 else $__MDL_URL = 'modules/mshop_special_big52';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_special_big52';
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
 //upd
 $vitrine_page = (string)$section->parametrs->param10;    
 if (!$vitrine_page){
     $vitrine_page = $__data->getVirtualPage('shop_vitrine');
 }
 if ((isRequest('ajax'.$razdel)) && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
     $response = array();
     if (isRequest('param')) {
         $param = getRequest('param', 1);
         $value = getRequest('value', 1);
         $id_price = getRequest('id_price', 1);
         $group = getRequest('group', 1);
         $response = array();
     
         $plugin_modifications = new plugin_shopmodifications($id_price, 'N');
         $response['params'] = $plugin_modifications->changeModifications($group, $param, $value);
         $response['type'] = (string)$section->parametrs->param49;
         $selected = !empty($_SESSION['modifications'][$id_price]) ? $_SESSION['modifications'][$id_price] : '';    
     
         $plugin_amount = new plugin_shopamount($id_price, '', $price_type, 1, $selected);
     
         $response['count'] = (string)$plugin_amount->getPresenceCount();
         $response['presence'] = (string)$plugin_amount->showPresenceCount($section->language->lang012, $section->language->lang013);
         $response['price'] = array(
             'new' => $plugin_amount->showPrice(true, $rounded, $separator),
             'old' => $plugin_amount->showPrice(false, $rounded, $separator)   
         );
     }
     echo json_encode($response);
     exit();        
 }
 if (isRequest('addcart')) {
     $shopcart = new plugin_ShopCart();
     $shopcart->addCart();
     
     if($section->parametrs->param8=='N') {
         header('Location: ' . seMultiDir() . "/{$_page}/?" . time());
     } else {
         header('Location: ' . seMultiDir() . "/{$section->parametrs->param4}/?" . time());
     }
     exit();
 }
 
 $lang = se_getLang();
 $spec_count = (int)$section->parametrs->param5 > 0 ? (int)$section->parametrs->param5 : 10;
 $spec_form = $section->parametrs->param18;
 $sort = array($section->parametrs->param6, $section->parametrs->param20 == 'desc');
 
 $select_list = 'sp.id, sp.code, sp.id_group, sp.presence_count';
 if ($section->parametrs->param24 == 'Y'){
     $select_list .= ', sp.name';     
 }
 if ($section->parametrs->param25 == 'Y'){
     $select_list .= ', sp.article';     
 }
 if ($section->parametrs->param26 == 'Y'){
     $select_list .= ', sp.price, sp.curr, sp.discount';     
 }
 if ($section->parametrs->param26 == 'Y'){
     $select_list .= ', sp.note';     
 }
 if ($section->parametrs->param22 == 'Y'){
     $select_list .= ', sp.img, sp.img_alt';     
 }
 if ($section->parametrs->param21 == 'Y'){
     $select_list .= ', (SELECT AVG(sr.mark) FROM shop_reviews sr WHERE sr.id_price=sp.id) AS rating';     
 }
 if ($section->parametrs->param23 == 'Y'){
     $select_list .= ', (SELECT name FROM shop_group WHERE id=sp.id_group) AS group_name, (SELECT code_gr FROM shop_group WHERE id=sp.id_group) AS code_gr';     
 }
 
 $select_list .= ', sp.flag_hit, sp.flag_new, sp.unsold, (SELECT 1 FROM shop_modifications sm WHERE sm.id_price=sp.id LIMIT 1) AS modifications';
  
 $shop_price = new seTable('shop_price', 'sp');
 $shop_price->select($select_list); 
 $shop_price->where("sp.lang ='?'", $lang);
 $shop_price->andWhere("sp.enabled ='?'", 'Y');
 $id_list = false;
 
 switch ($spec_form){
     case 'all':
         break;
     case 'special':
         $shop_price->innerjoin('shop_leader sl', 'sp.id = sl.id_price');
         break;
     case 'unsold':
         $shop_price->andWhere("sp.unsold ='?'", 'Y');
         break;
     case 'hit':
         $shop_price->andWhere("sp.flag_hit ='?'", 'Y');
         break;
     case 'new':
         $shop_price->andWhere("sp.flag_new ='?'", 'Y');
         break;
     case 'discount':
         $shop_price->having('(SELECT DISTINCT 1 FROM shop_discount sd WHERE sd.id_price = sp.id OR sd.id_group = sp.id_group LIMIT 1) IS NOT NULL');
         break;    
     case 'oldOrders':
         $id_list = true;
         $shop_price->select('sp.id');
         $shop_price->orderBy('(SELECT so.updated_at FROM shop_tovarorder st INNER JOIN shop_order so ON (so.id = st.id_order) WHERE st.id_price = sp.id AND so.status <> "N" ORDER BY st.created_at DESC LIMIT 1)', 1);
         break;
     case 'topOrders':
         $id_list = true;
         $shop_price->select('sp.id');
         $shop_price->orderBy('(SELECT count(*) FROM shop_tovarorder st INNER JOIN shop_order so ON (so.id = st.id_order) WHERE st.id_price = sp.id AND so.status <> "N" GROUP BY st.id_price LIMIT 1)', 1);
         break;
     case 'topVotes':
         $id_list = true;
         $shop_price->select('sp.id');
         $shop_price->orderBy('sp.votes', 1);
         break;
     case 'topLooks':
         $id_list = true;
         $shop_price->select('sp.id');
         $shop_price->orderBy('sp.vizits', 1);
         break;
     case 'nowLooks':
         $id_list = true; 
         $shop_price->select('sp.id');
         $shop_price->leftjoin('shop_nowlooks sn', 'sp.id=sn.id');
         $shop_price->orderBy('sn.count_looks', 1);
         $shop_price->addorderBy('sn.time_expire', 1);
         break;
     case 'userLooks':
         $id_goods = '';
         if (isset($_COOKIE['look_goods'])){
             foreach ($_COOKIE['look_goods'] as $key => $val){
                 $arr = explode('#', $val);
                 $list[$key] = (int)$arr[0] + (int)$arr[1] * 60;    
             }
             arsort($list);
             $id_goods = join(', ', array_keys($list));
             unset($list, $arr);
         }
         $shop_price->where("sp.id IN ($id_goods)");
         break;  
 }
 
 if (!$id_list) {
     $shop_price->orderBy($sort[0], $sort[1]);   
 } 
 
 if ($section->parametrs->param13 != 'all') {
     $group = plugin_shopgroup::getInstance((string)$section->parametrs->param14);
     if ($section->parametrs->param13 == 'Y')
         $groups = $group->getGroups();
     else
         $groups = $group->getId(); 
     $shop_price->innerJoin('shop_group sg', 'sp.id_group=sg.id');
     $shop_price->andWhere("(sg.id IN ($groups) OR sp.id IN (SELECT price_id FROM shop_group_price WHERE (group_id IN ($groups))) OR sp.id_group IN (SELECT group_id FROM shop_crossgroup WHERE id IN ($groups)))");
 }
 
 $pricelist = $shop_price->getList(0, $spec_count);
 
 if ($id_list && !empty($pricelist)) {
     $in_id = array();
     foreach($pricelist as $val){
         $in_id[] = $val['id'];        
     }
     if (!empty($in_id)) {
         $shop_price->select($select_list); 
         $shop_price->where('sp.id IN (?)', join(',', $in_id));
         $shop_price->orderBy($sort[0], $sort[1]); 
         $pricelist = $shop_price->getList();
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
 //BeginSubPagemodification
 $__module_subpage['modification']['admin'] = "";
 $__module_subpage['modification']['group'] = 0;
 $__module_subpage['modification']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='modification' && file_exists($__MDL_ROOT . "/tpl/subpage_modification.tpl")){
	include $__MDL_ROOT . "/php/subpage_modification.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_modification");
	$__module_subpage['modification']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagemodification
 //BeginSubPageblockproduct
 $__module_subpage['blockproduct']['admin'] = "";
 $__module_subpage['blockproduct']['group'] = 0;
 $__module_subpage['blockproduct']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='blockproduct' && file_exists($__MDL_ROOT . "/tpl/subpage_blockproduct.tpl")){
	include $__MDL_ROOT . "/php/subpage_blockproduct.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_blockproduct");
	$__module_subpage['blockproduct']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageblockproduct
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}