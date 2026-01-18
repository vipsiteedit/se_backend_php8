<?php
function module_header_shop30($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/header_shop30';
 else $__MDL_URL = 'modules/header_shop30';
 $__MDL_ROOT = dirname(__FILE__).'/header_shop30';
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
 $pricemoney = se_getMoney();      
 $group_name = seUserGroupName();
 $startpage = trim($__data->startpage) == $__data->getPageName();
 $seurl = seMultiDir() . '/';
 
 // Код формы отправки сообщения 
 
 $sid = session_id();
  //капча
 $captcha='<img class="ml001_secimg" alt="capcha" style="width:150px; height:30px" src="/lib/cardimage.php?session='.$sid.'&'.time().'">';
 
 /* Поиск */
 
 $page_vitrine = trim($section->parametrs->param1);
 if (empty($page_vitrine)){
     $page_vitrine = $__data->getVirtualPage('shop_vitrine');
 }
 if (empty($page_vitrine)) {
     $page_vitrine = $_page;
 }
 
 $plugin_search = new plugin_shopsearch($page_vitrine);
 
 /*if (isRequest('ajax'.$razdel)) {
     $responce = array();
     $options = array(
         'start_group' => trim($section->parametrs->param29),
         'max_count' => (int)$section->parametrs->param31,
         'image_size' => (string)$section->parametrs->param32,
         'search_article' => $section->parametrs->param34 == 'Y',
         'sort' => trim($section->parametrs->param35)
     );
     
     $responce['goods'] = $plugin_search->getProducts($options);
     header('Content-Type: application/json');
     echo json_encode($responce);
     exit();
 }else*/
 
 
 
 if ($section->parametrs->param20 == 'N' && $page_vitrine == $_page)
     $page_vitrine = '';   
     
 $query = htmlspecialchars($plugin_search->getQuery(), ENT_QUOTES); 
 
 
 $emailAdmin = '';
 $entertext = $section->parametrs->param10;
 $closetext = $section->parametrs->param11;
 if (!empty($section->parametrs->param12))
     $col = intval($section->parametrs->param13);
 else
     $col = 2500;
 
 $ml001_errtxt = $ml001_name = $ml001_phone = $ml001_email = $ml001_note = '';
 
 $admin = trim(strval($section->parametrs->param15));
 $admins = array();
 if($admin != '') {
     $admins = explode(",", $admin);
     foreach($admins as $key=>$line) {
         $line = trim($line);
         if(!se_CheckMail($line) || ($line == '')) 
             unset($admins[$key]);
     }
 }
 
 if(!empty($admins)) {
     $emailAdmin = implode(",", $admins);
 }
 
 
 
 // Код шапки
 
 $type_price = ($group_name == $section->parametrs->param2) ? 1 : (($group_name == $section->parametrs->param1) ? 2 : 0); 
 $rounded = ($section->parametrs->param21 == 'Y');
 
 $plugin_cart = new plugin_shopcart(array('round' => $rounded, 'type_price' => $type_price, 'presence' => '', 'curr' => $pricemoney));
 
 if (isRequest('ajax'.$razdel) && (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' || param23 != 'Y')){
     $result = array();
     
     if(isRequest('q')){
     $responce = array();
     $options = array(
         'start_group' => trim($section->parametrs->param29),
         'max_count' => (int)$section->parametrs->param31,
         'image_size' => (string)$section->parametrs->param32,
         'search_article' => $section->parametrs->param34 == 'Y',
         'sort' => trim($section->parametrs->param35)
     );
     
     $responce['goods'] = $plugin_search->getProducts($options);
     header('Content-Type: application/json');
     echo json_encode($responce);
     exit();
     }elseif (isRequest('cart_clear')){
         $plugin_cart->clearCart();
     }
     elseif ($delcart = getRequest('delcartname', 3)){
         $plugin_cart->delCart(array('delcartname' => $delcart));        
     }
     elseif (isRequest('countitem')){
         $plugin_cart->updateCart();
     }
     elseif (getRequest('addcart', 1) > 0){
         $id_goods = getRequest('addcart', 1);       
         $result['add'] = $last_addcart = $plugin_cart->addCart();
         if ($section->parametrs->param22 == 'm' || $section->parametrs->param22 == 'fm') {
             ob_start();
             include $__MDL_ROOT . '/php/subpage_addproduct.php';
             include $__data->include_tpl($section, 'subpage_addproduct');
             $result['product'] = ob_get_contents();
             ob_end_clean();
         }
         
         //$plugin_cart->getInCart();             
     }
     
     $total = $plugin_cart->getTotalCart();
     $result['total']  = array(
         'amount' => $total['show_total'], 
         'count' => $total['count'], 
         'discount' => $total['show_discount']
     );
     
     $goods_cart = $plugin_cart->getGoodsCart();
     $result['incart'] = array();
     if (!empty($goods_cart)) {
         foreach ($goods_cart as $val){
             if (!empty($last_addcart) && $last_addcart == $val['key']) {
                 $result['total']['last_amount'] = $val['newsum'];   
             }
             $product = array();
             $product['id'] = $val['key'];
             $product['name'] = $val['name'];
             if (!empty($val['paramsname']))
                 $product['name'] .= ' (' . $val['paramsname'] . ')';
             $product['link'] = seMultiDir() . '/' . $section->parametrs->param3 . '/show/' . $val['code'] . '/';
             $product['count'] = $val['count'];
             $product['sum'] = $val['newsum'];
             $result['incart'][] = $product;    
         }
     }
     if (method_exists($plugin_cart,'getEvents')) {
         $result['events'] = $plugin_cart->getEvents();
     }
     echo json_encode($result);
     exit;
 }
 
 
 $getGoodsCart = $plugin_cart->getGoodsCart();
 
 if(!empty($getGoodsCart)){
     foreach ($getGoodsCart as $key => $val) {
         $val['link'] = seMultiDir().'/'.$section->parametrs->param3.'/show/' . $val['code'] . '/';
         if (!empty($val['paramsname']))
             $val['name'] .= ' (' . $val['paramsname'] . ')';
         $val['name'] = htmlspecialchars($val['name']);
         $__data->setItemList($section, 'objects', $val);
     } 
 }
 
 $total = $plugin_cart->getTotalCart();
  
 $order_summ = $total['show_total'];
 $discount_summ = $total['show_discount'];
 $count_goods = $total['count'];
 
 $no_goods = $isset_goods = '';
 if ($count_goods > 0){
     $no_goods = "style=\"display:none;\"";
 }
 else{
     $isset_goods = "style=\"display:none;\"";
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
 //BeginSubPageaddproduct
 $__module_subpage['addproduct']['admin'] = "";
 $__module_subpage['addproduct']['group'] = 0;
 $__module_subpage['addproduct']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='addproduct' && file_exists($__MDL_ROOT . "/tpl/subpage_addproduct.tpl")){
	include $__MDL_ROOT . "/php/subpage_addproduct.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_addproduct");
	$__module_subpage['addproduct']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageaddproduct
 //BeginSubPagecart
 $__module_subpage['cart']['admin'] = "";
 $__module_subpage['cart']['group'] = 0;
 $__module_subpage['cart']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='cart' && file_exists($__MDL_ROOT . "/tpl/subpage_cart.tpl")){
	include $__MDL_ROOT . "/php/subpage_cart.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_cart");
	$__module_subpage['cart']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagecart
 //BeginSubPageform
 $__module_subpage['form']['admin'] = "";
 $__module_subpage['form']['group'] = 0;
 $__module_subpage['form']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='form' && file_exists($__MDL_ROOT . "/tpl/subpage_form.tpl")){
	include $__MDL_ROOT . "/php/subpage_form.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_form");
	$__module_subpage['form']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageform
 //BeginSubPagelicense
 $__module_subpage['license']['admin'] = "";
 $__module_subpage['license']['group'] = 0;
 $__module_subpage['license']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='license' && file_exists($__MDL_ROOT . "/tpl/subpage_license.tpl")){
	include $__MDL_ROOT . "/php/subpage_license.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_license");
	$__module_subpage['license']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagelicense
 //BeginSubPagebasket
 $__module_subpage['basket']['admin'] = "";
 $__module_subpage['basket']['group'] = 0;
 $__module_subpage['basket']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='basket' && file_exists($__MDL_ROOT . "/tpl/subpage_basket.tpl")){
	include $__MDL_ROOT . "/php/subpage_basket.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_basket");
	$__module_subpage['basket']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagebasket
 //BeginSubPagesearch
 $__module_subpage['search']['admin'] = "";
 $__module_subpage['search']['group'] = 0;
 $__module_subpage['search']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='search' && file_exists($__MDL_ROOT . "/tpl/subpage_search.tpl")){
	include $__MDL_ROOT . "/php/subpage_search.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_search");
	$__module_subpage['search']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagesearch
 //BeginSubPagemessage
 $__module_subpage['message']['admin'] = "";
 $__module_subpage['message']['group'] = 0;
 $__module_subpage['message']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='message' && file_exists($__MDL_ROOT . "/tpl/subpage_message.tpl")){
	include $__MDL_ROOT . "/php/subpage_message.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_message");
	$__module_subpage['message']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagemessage
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}