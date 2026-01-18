<?php
function module_ashop_flycart53($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/ashop_flycart53';
 else $__MDL_URL = 'modules/ashop_flycart53';
 $__MDL_ROOT = dirname(__FILE__).'/ashop_flycart53';
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
 
 $type_price = ($group_name == $section->parametrs->param2) ? 1 : (($group_name == $section->parametrs->param1) ? 2 : 0); 
 $rounded = ($section->parametrs->param21 == 'Y');
 
 $plugin_cart = new plugin_shopcart(array('round' => $rounded, 'type_price' => $type_price, 'presence' => '', 'curr' => $pricemoney));
 
 if (isRequest('ajax'.$razdel) && (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' || param23 != 'Y')){
     $result = array();
     if (isRequest('cart_clear')){
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
         
         if (strpos($last_addcart, 'error:') !== false) {
             $result['error'] = '<div class="addcart-error"><h3>Ошибка</h3> <p>' . str_replace('error:', '', $last_addcart) . '</p> <div class="panel-footer text-right"><button class="btn btn-default">Принять</span></div></div>';
         }
         elseif ($section->parametrs->param22 == 'm' || $section->parametrs->param22 == 'fm') {
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
 $shop_image = new plugin_ShopImages();
 
 if(!empty($getGoodsCart)){
     foreach ($getGoodsCart as $key => $val) {
         $val['link'] = seMultiDir().'/'.$section->parametrs->param3.'/show/' . $val['code'] . '/';
         if (!empty($val['paramsname']))
             $val['name'] .= ' (' . $val['paramsname'] . ')';
         $val['name'] = htmlspecialchars($val['name']);
         $val['image'] = $shop_image->getPictFromImage($val['img'], '100x100', 's');
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
 //BeginSubPagerelated
 $__module_subpage['related']['admin'] = "";
 $__module_subpage['related']['group'] = 0;
 $__module_subpage['related']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='related' && file_exists($__MDL_ROOT . "/tpl/subpage_related.tpl")){
	include $__MDL_ROOT . "/php/subpage_related.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_related");
	$__module_subpage['related']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagerelated
 //BeginSubPageexamples
 $__module_subpage['examples']['admin'] = "";
 $__module_subpage['examples']['group'] = 0;
 $__module_subpage['examples']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='examples' && file_exists($__MDL_ROOT . "/tpl/subpage_examples.tpl")){
	include $__MDL_ROOT . "/php/subpage_examples.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_examples");
	$__module_subpage['examples']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageexamples
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
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}

