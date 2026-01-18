<?php
function module_mshop_cart_big52($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_cart_big52';
 else $__MDL_URL = 'modules/mshop_cart_big52';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_cart_big52';
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
 $ajax = isRequest('ajax'.$razdel);
 
 if ($section->parametrs->param16=='Y')
     $ajax &= !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
 
 if (isRequest('get_region') && $ajax){
     $list = array();
     if (isRequest('country_id'))
         $list['region'] = getRegionList('region', getRequest('country_id', 1));
     if (isRequest('region_id'))
         $list['city'] = getRegionList('city', getRequest('region_id', 1));
     echo json_encode($list);
     exit;
 }   
                 
 if (isRequest('payment_type_id'))    
     $_SESSION['payment_type_id'] = getRequest('payment_type_id', VAR_INT);
 
 $baseCurr = se_getMoney();
 $lang = se_getLang();           
 $user_id = seUserId();
 $error_message = '';
 $group_name = seUserGroupName();
 $not_delivery = null;
 $use_coupon = ($section->parametrs->param8 == 'Y');
 $required_reg = ($section->parametrs->param4 == 'Y'); 
 if ($section->parametrs->param5 == 'Y')
     $not_delivery = array('id'=>0, 'name' => (string)$section->parametrs->param6, 'price' => 0, 'time' => (int)$section->parametrs->param14, 'addr' => false);
 
 $options_cart = array(
     'round' => ($section->parametrs->param7 == 'Y'),
     'type_price' => ($group_name == $section->parametrs->param13) ? 1 : (($group_name == $section->parametrs->param12) ? 2 : 0),
     'presence' => '', 
     'curr' => se_getMoney() 
 );
     
 $plugin_cart = new plugin_shopCart($options_cart);
 
 if (isRequest('countitem')){
     $plugin_cart->updateCart();
 }
 
 if (isRequest('cart_clear')){
     $plugin_cart->clearCart();
     if (!$ajax){
         header('Location: '.seMultiDir().'/'.$_page.'/?'.time());
         exit;
     }
 }
 
 if (isRequest('cart_reload') && !ajax){
     header('Location: '.seMultiDir().'/'.$_page.'/?'.time());
     exit;
 }
 
 if (isRequest('delcartname')){
     $delete_item = $plugin_cart->delCart();
     if (!$ajax){
         header('Location: '.seMultiDir().'/'.$_page.'/?'.time());
         exit;
     }    
 }
 
 $plugin_delivery = new plugin_shopDelivery($not_delivery);
 
 $total = $plugin_cart->getTotalCart();
 
 $sum_total_order = $total['sum_total'];
 
 if ($use_coupon){
     $coupon = $plugin_cart->getCoupon();
     $find_coupon = false;
     if ($coupon){
         $find_coupon = $coupon['find'] = true;
         $coupon['note'] = '<span class="cpnNoteTitle">'.$section->language->lang036.'&nbsp;<span class="cpnNoteCode">'.$coupon['code'].'</span>:</span>&nbsp;<span class="cpnDiscountTitle">'.$section->language->lang037.'&nbsp;<span class="cpnDiscountValue">'.$coupon['value'].'</span></span>';
         $sum_total_order -= $coupon['discount'];    
     }
     else{
         $coupon['find'] = false;
         $coupon['note'] = (string)$section->language->lang038; 
     }
 }
 
 $select_delivery = $plugin_delivery->getDelivery();
 
 if (!empty($select_delivery)){
     $sum_total_order += $select_delivery['price'];
 }
 
 $sum_total_order = se_formatMoney($sum_total_order, $baseCurr, '&nbsp;', $options_cart['round']);
 
 if (isset($_POST['test_order'])){
 
     $contact_errors = array();
     
     $c_name = trim(getRequest('contact_name', 3));
     if (empty($user_id) && empty($c_name))
         $contact_errors['name'] = $section->language->lang054;
     
     $c_email = trim(getRequest('contact_email', 3));
     if (empty($c_email))
         $contact_errors['email'] = $section->language->lang055;
     elseif(!filter_var($c_email, FILTER_VALIDATE_EMAIL))
         $contact_errors['email'] = $section->language->lang056;
     
     if ($section->parametrs->param10 == 'Y'){
         $c_phone = trim(getRequest('contact_phone', 3));
         if (empty($c_phone))
             $contact_errors['phone'] = $section->language->lang057;
     }   
     
     if ($section->parametrs->param11 == 'Y' && !empty($select_delivery['addr'])){
         $c_post_index = trim(getRequest('contact_post_index', 3));
         if (empty($c_post_index))
             $contact_errors['post_index'] = $section->language->lang058;
         elseif(!validPostindex($c_post_index))
             $contact_errors['post_index'] = $section->language->lang059;
     }
         
     if (!empty($select_delivery['addr'])){
         $c_addr = trim(getRequest('contact_address', 3));
         if (empty($c_addr))
             $contact_errors['address'] = $section->language->lang060; 
     }
     
     if (!empty($contact_errors)){
         $error_message = $section->language->lang061;
     }
     
     if (!$user_id && $required_reg == 'Y'){
         $error_message = $section->language->lang062;        
     }
     
     if (seUserGroup() > 0 && $user_id == 0)
         $error_message = $section->language->lang063;
     
     if ((float)$section->parametrs->param3 > 0){
         $min_sum = se_MoneyConvert((float)$section->parametrs->param3, se_baseCurrency(), $total['curr']);
         if ($min_sum > $total['sum_total'])
             $error_message = $section->language->lang064.' '.se_formatMoney($min_sum, $total['curr']);
     }     
             
     if (isRequest('contact_name')){
         $_SESSION['cartcontact']['name'] = trim($contact_name = getRequest('contact_name', 3));
     } elseif($user_id){
         $contact_name = seUserName();
     }
     $_SESSION['cartcontact']['email'] = $contact_email = trim(getRequest('contact_email', 3));
     $_SESSION['cartcontact']['phone'] = $contact_phone = trim(getRequest('contact_phone', 3));
     $_SESSION['cartcontact']['post_index'] = $contact_post_index = trim(getRequest('contact_post_index', 3));
     $_SESSION['cartcontact']['address'] = $contact_address= trim(getRequest('contact_address', 3));
     $_SESSION['cartcontact']['comment'] = $contact_comment= trim(getRequest('contact_comment', 3));
 
 
     if (empty($error_message) && $section->parametrs->param17=='Y'){
         $__data->goSubName($section, 'confirm');
     }
 }
 
 
 
 if(isset($_POST['confirm_order']) && empty($error_message)) {
   
     if (empty($user_id)){
         $user_id = cartRegUser();    
     }
             
     $plugin_order = new plugin_shopOrder($user_id, $plugin_cart->getGoodsCart(), $_SESSION['cartcontact']['name']);
             
     $order_delivery = array(
         'id' => $plugin_delivery->delivery_type_id,
         'phone' => $_SESSION['cartcontact']['phone'],
         'address' => $_SESSION['cartcontact']['address'],
         'postindex' => $_SESSION['cartcontact']['post_index'],
         'summ' => se_Money_Convert($select_delivery['price'], $baseCurr, se_baseCurrency())
     );
     $plugin_order->commentary = $_SESSION['cartcontact']['comment'];
     
     $coupon_used = $plugin_cart->useCoupon();
     
     $order_discount = ($use_coupon && $coupon_used['discount'] > 0) ? $coupon_used['discount'] : 0;
     
     $order_id = $plugin_order->execute($order_delivery, $_SESSION['cartcontact']['email'], '', $order_discount);
     
     //exit('execute');
     if ($coupon_used['id'] && $order_id && $user_id){
         $shop_coupon_history = new seTable('shop_coupons_history');
         $shop_coupon_history->insert();
         $shop_coupon_history->code_coupon = $_SESSION['code_coupon'];
         $shop_coupon_history->id_coupon = $coupon_used['id'];
         $shop_coupon_history->id_user = $user_id;
         $shop_coupon_history->id_order = $order_id;
         $shop_coupon_history->discount = $coupon_used['discount'];
         $shop_coupon_history->save();    
     } 
     
     $plugin_cart->clearCart();
     
     if (!empty($_SESSION['payment_type_id']) && $section->parametrs->param9 == 'Y' && $order_id){ 
         $payment_id = $_SESSION['payment_type_id'];
         
         $order_tlb = new seTable('shop_order', 'so');
         $order_tlb->select('so.id, so.payment_type');
         $order_tlb->where("id = ?", $order_id); 
         $order_tlb->fetchOne();
         $order_tlb->payment_type = $_SESSION['payment_type_id'];
         $order_tlb->save();
         
         header('Location: '.seMultiDir().'/'.$section->parametrs->param2.'/invnum/'.$payment_id.'_'.$order_id.'/'.md5($payment_id.'_'.$order_id.'3dfgvj').'/');
     }
     else{
         header('Location: '.seMultiDir().'/'.$section->parametrs->param2.'/?'.time());    
     }
     exit;   
 }
 
 
 $total_sum_goods = $total['show_total'];
 $total_sum_discount = $total['show_discount'];
 $total_weight_goods = $total['weight'] . '&nbsp<span class="measure">кг</span>';
 $total_volume_goods = $total['volume'] . '&nbsp<span class="measure">см3</span>';
 
 if ($ajax){ 
     if ($use_coupon && isRequest('code_coupon')){        
         $coupon['show'] = (!empty($coupon['show'])) ? '-'.$coupon['show'] : null;
         $ajax_response['coupon'] = $coupon;
     }
     
     if (isRequest('set_region')){
         $_SESSION['userregion']['country'] = getRequest('country_id', 1);
         $_SESSION['userregion']['region'] = getRequest('region_id', 1);
         $_SESSION['userregion']['city'] = getRequest('city_id', 1);
         $ajax_response['region_name'] = getRegionName();
     }
     
     if (isRequest('countitem') || isRequest('delcartname') || isRequest('cart_clear') || isRequest('getcart') || getRequest('addcart', 1)){
         if (getRequest('addcart', 1))
             $plugin_cart->addCart();
         $incart = $plugin_cart->getGoodsCart();
         $ajax_response['incart'] = array();
         $shop_image = new plugin_ShopImages();
         foreach ($incart as $val){
             $product = array();
             $product['id'] = $val['key'];
             $product['count'] = $val['count'];
             $product['sum'] = $val['newsum'];
             $product['link'] = seMultiDir() . '/' . $section->parametrs->param1 . '/show/' . $val['code'] . '/';
             $product['name'] = $val['name'];
             if (!empty($val['paramsname']))
                 $product['name'] .= ' (' . $val['paramsname'] . ')';
             $product['shortname'] = $val['name'];
             $product['params'] = $val['paramsname'];
             $product['img'] = $shop_image->getPictFromImage($val['img'], $section->parametrs->param15, 'w');
             $product['article'] = $val['article'];
             $product['presence'] = $val['presence_count'];
             $product['oldprice'] = $val['oldprice'];
             $product['newprice'] = $val['newprice'];
             $ajax_response['incart'][] = $product;    
         }    
     }
     
     if ($total['count'] > 0 && (isRequest('delcartname') || isRequest('countitem') || isRequest('set_region') || isRequest('getcart'))){
         $last_delivery_id = $plugin_delivery->delivery_type_id; 
         $html = '';
         foreach($plugin_delivery->getDeliveryList($not_delivery) as $key => $val){
             $html .= "<div class=\"deliveryType\" data-id=\"{$val['id']}\">\n";
             $html .= "<label title=\"{$section->language->lang030}\">\n";
             $check = ($plugin_delivery->delivery_type_id == $val['id']) ? ' checked' : '';
             $html .= "<input class=\"radioDeliveryType\" type=\"radio\" name=\"delivery_type_id\" value=\"{$val['id']}\"$check data-addr=\"{$val['addr']}\"> \n";
             $html .= "<span class=\"deliveryTypeName\">{$val['name']}</span>\n";
             $html .= "</label>\n";
             
             if ((float)$val['price'] > 0)
                 $val['price'] = se_FormatMoney($val['price'], $options_cart['curr'], '&nbsp;', $options_cart['round']);
             else 
                 $val['price'] = $section->language->lang021;
             $val['note'] = !empty($val['note']) ? $val['note'] : '';
             $html .= "<div class=\"deliveryTypePriceTime\"><span class=\"deliveryTypePrice\">{$val['price']}</price>, <span class=\"deliveryTypeTime\">".getTimeWord($val['time'])."</span></div>\n";
             $html .= "<div class=\"deliveryTypeNote\">{$val['note']}</div>\n</div>";    
         }
         $delivery['html'] = $html;
         $ajax_response['delivery'] = $delivery;   
     }
     
     if (($section->parametrs->param9 == 'Y') && (isRequest('delivery_type_id') || !empty($print_payment))){        
         $payment_list = getPaymentList($plugin_delivery->delivery_type_id);
         $payment['selected'] = $_SESSION['payment_type_id'];;
         foreach($payment_list as $val){
             $payment['id'][] = $val['id'];
         }
         $ajax_response['payment'] = $payment;      
     }
     unset($plugin_cart, $plugin_delivery);
     
     $total_cart['count'] = $total['count'];
     $total_cart['amount'] = $total_sum_goods;    
     $total_cart['discount'] = $total_sum_discount;
     $total_cart['weight'] = $total_weight_goods;
     $total_cart['volume'] = $total_volume_goods;
     $total_cart['sum_order'] = $sum_total_order;
     if (!empty($coupon['show'])) $total_cart['coupon'] = '-' . $coupon['show'];
     
     $ajax_response['total'] = $total_cart;
     
     echo json_encode($ajax_response);
     exit;
 }
 
 if ($use_coupon && $find_coupon){
     $sum_coupon = $coupon['show'];
     $note_coupon = $coupon['note'];
     $code_coupon = $coupon['code'];
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
 //BeginSubPagepayment_list
 $__module_subpage['payment_list']['admin'] = "";
 $__module_subpage['payment_list']['group'] = 0;
 $__module_subpage['payment_list']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='payment_list' && file_exists($__MDL_ROOT . "/tpl/subpage_payment_list.tpl")){
	include $__MDL_ROOT . "/php/subpage_payment_list.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_payment_list");
	$__module_subpage['payment_list']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagepayment_list
 //BeginSubPageconfirm
 $__module_subpage['confirm']['admin'] = "";
 $__module_subpage['confirm']['group'] = 0;
 $__module_subpage['confirm']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='confirm' && file_exists($__MDL_ROOT . "/tpl/subpage_confirm.tpl")){
	include $__MDL_ROOT . "/php/subpage_confirm.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_confirm");
	$__module_subpage['confirm']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageconfirm
 //BeginSubPagegoods_list
 $__module_subpage['goods_list']['admin'] = "";
 $__module_subpage['goods_list']['group'] = 0;
 $__module_subpage['goods_list']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='goods_list' && file_exists($__MDL_ROOT . "/tpl/subpage_goods_list.tpl")){
	include $__MDL_ROOT . "/php/subpage_goods_list.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_goods_list");
	$__module_subpage['goods_list']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagegoods_list
 //BeginSubPagedelivery_list
 $__module_subpage['delivery_list']['admin'] = "";
 $__module_subpage['delivery_list']['group'] = 0;
 $__module_subpage['delivery_list']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='delivery_list' && file_exists($__MDL_ROOT . "/tpl/subpage_delivery_list.tpl")){
	include $__MDL_ROOT . "/php/subpage_delivery_list.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_delivery_list");
	$__module_subpage['delivery_list']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagedelivery_list
 //BeginSubPagecontact
 $__module_subpage['contact']['admin'] = "";
 $__module_subpage['contact']['group'] = 0;
 $__module_subpage['contact']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='contact' && file_exists($__MDL_ROOT . "/tpl/subpage_contact.tpl")){
	include $__MDL_ROOT . "/php/subpage_contact.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_contact");
	$__module_subpage['contact']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagecontact
 //BeginSubPageselectregion
 $__module_subpage['selectregion']['admin'] = "";
 $__module_subpage['selectregion']['group'] = 0;
 $__module_subpage['selectregion']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='selectregion' && file_exists($__MDL_ROOT . "/tpl/subpage_selectregion.tpl")){
	include $__MDL_ROOT . "/php/subpage_selectregion.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_selectregion");
	$__module_subpage['selectregion']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageselectregion
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}