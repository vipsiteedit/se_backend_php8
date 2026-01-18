<?php
function module_ashop_cart53($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/ashop_cart53';
 else $__MDL_URL = 'modules/ashop_cart53';
 $__MDL_ROOT = dirname(__FILE__).'/ashop_cart53';
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
 $plugin_shopstat = new plugin_shopstat();
 $ajax = isRequest('ajax'.$razdel);
 
 
 if ($section->parametrs->param16=='Y')
     $ajax &= !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
           
 if (isRequest('payment_type_id')) {   
     $_SESSION['payment_type_id'] = getRequest('payment_type_id', VAR_INT);
     $plugin_shopstat->saveEvent('select payment', $_SESSION['payment_type_id']);
 }
 
 if (isRequest('change_contact') && $ajax) {   
     $plugin_shopstat->saveContact(getRequest('cname', 3), getRequest('cvalue', 3));
     if (getRequest('cname', 3) == 'contact_post_index') {
         $_SESSION['cartcontact']['post_index'] = getRequest('cvalue', 3);
         $upd_delivery = true;
     }
     else
         exit();  
 } 
 
 $plugin_shopgeo = new plugin_shopgeo();
 
 if (isRequest('c') && $ajax) {   
     $response['search'] = $search = trim(urldecode(getRequest('c', 3)));
     if (!empty($search)) {
         $response['regions'] = $plugin_shopgeo->getCities($search);           
     }         
     echo json_encode($response);
     exit();
     
 }
 
 if (isRequest('set_region') && $ajax){
     $id_city = getRequest('set_region', 1);
     $city = $plugin_shopgeo->getCity($id_city, true);
     if (!empty($city)) {
         $plugin_shopgeo->confirmCity();
     }
 }
 
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
     
 $plugin_cart = new plugin_shopcart($options_cart);
 
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
 
 $plugin_delivery = new plugin_shopdelivery($not_delivery);
 $plugin_delivery->getDeliveryList();
 
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
 
 if (isRequest('delivery_sub_' . $plugin_delivery->delivery_type_id)) {
     $upd_delivery = true;
 }
 
 $sum_total_order = se_formatMoney($sum_total_order, $baseCurr, '&nbsp;', $options_cart['round']);
 
 $confirm_order = !empty($_SESSION['check_cart']) && isset($_POST['confirm_order']);
 
 if (isset($_POST['place_order'])) {
 
     $contact_errors = array();
     
     $plugin_shopstat->saveEvent('place order');
     
     if ($section->parametrs->param21 == 'Y' || $section->parametrs->param24 == 'Y') {
         if ($section->parametrs->param21 == 'Y' && !isRequest('personal_accepted')) {
             $error_message = $section->language->lang091;    
         }
         
         if ($section->parametrs->param24 == 'Y' && !isRequest('additional_accepted')) {
             $error_message = $section->language->lang091;    
         }
     }
     
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
     
     if (empty($error_message) && $section->parametrs->param19 == 'Y') {
         $fields = getUserFields();
         if (!empty($fields)) {
             foreach($fields as $val) {
                 if ($val['is_group'])
                     continue;
                 if ($val['required']) {
                     $value = getRequest('field_' . $val['code']);
                     
                     if (!is_array($value))
                         $value = trim($value);
                     elseif (($val['type'] == 'select' || $val['type'] == 'radio' || $val['type'] == 'checkbox') && !empty($val['values'])) {
                         $values = explode(',', $val['values']);
                         if (!array_intersect($value, $values)) {
                             $value = '';
                         }                                 
                     }   
                          
                     if (!$value) {
                         $error_message = 'Необходимо заполнить дополнительное поле "' . $val['name'] . '"'; 
                         break;   
                     }
                 }
             }
         }    
     }
             
     if (empty($error_message)){
         $_SESSION['check_cart'] = true;
         if ($section->parametrs->param18 == 'Y')
             $__data->goSubName($section, 'confirm');
         else {
             include $__MDL_ROOT . "/php/subpage_confirm.php";
             $confirm_order = true;
         }
     }   
 }
 
 if (!empty($confirm_order)) {
   
     $_SESSION['check_cart'] = false;
   
     if (empty($user_id)){
         $user_id = cartRegUser((string)$section->parametrs->param17 == 'Y');  
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
     
     $order_id = $plugin_order->execute($order_delivery, $_SESSION['cartcontact']['email'], '', $order_discount, $coupon_used);
     
     if ($section->parametrs->param19 == 'Y') {
         saveUserFields($order_id);
     }
     
     $plugin_shopstat->saveEvent('confirm order', $order_id);
     
     $plugin_cart->clearCart();
     if (!empty($_SESSION['payment_type_id']) && $section->parametrs->param9 == 'Y'){ 
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
 $total_weight_goods = $total['weight'];
 $total_volume_goods = $total['volume'];
 
 if ($ajax){ 
     if ($use_coupon && isRequest('code_coupon')){        
         $coupon['show'] = (!empty($coupon['show'])) ? '-'.$coupon['show'] : null;
         $ajax_response['coupon'] = $coupon;
     }
     
     if (isRequest('countitem') || isRequest('delcartname') || isRequest('cart_clear') || isRequest('getcart')){
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
         if (method_exists($plugin_cart,'getEvents')) {
             $ajax_response['events'] = $plugin_cart->getEvents();
         }    
     }
     
     if ($total['count'] > 0 && (isRequest('delcartname') || isRequest('countitem') || isRequest('set_region') || isRequest('getcart') || isset($upd_delivery))){
         ob_start();
         include $__MDL_ROOT . '/php/subpage_deliveries.php';
         include $__data->include_tpl($section, 'subpage_deliveries');
         $ajax_response['delivery'] = array('html' => ob_get_contents());
         ob_end_clean();
         $print_payment = true;
     }
     
     if (($section->parametrs->param9 == 'Y') && (isRequest('delivery_type_id') || !empty($print_payment))){        
         $plugin_shopstat->saveEvent('select delivery', getRequest('delivery_type_id', 1));
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
 //BeginSubPagepayments
 $__module_subpage['payments']['admin'] = "";
 $__module_subpage['payments']['group'] = 0;
 $__module_subpage['payments']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='payments' && file_exists($__MDL_ROOT . "/tpl/subpage_payments.tpl")){
	include $__MDL_ROOT . "/php/subpage_payments.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_payments");
	$__module_subpage['payments']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagepayments
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
 //BeginSubPageproducts
 $__module_subpage['products']['admin'] = "";
 $__module_subpage['products']['group'] = 0;
 $__module_subpage['products']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='products' && file_exists($__MDL_ROOT . "/tpl/subpage_products.tpl")){
	include $__MDL_ROOT . "/php/subpage_products.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_products");
	$__module_subpage['products']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageproducts
 //BeginSubPagedeliveries
 $__module_subpage['deliveries']['admin'] = "";
 $__module_subpage['deliveries']['group'] = 0;
 $__module_subpage['deliveries']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='deliveries' && file_exists($__MDL_ROOT . "/tpl/subpage_deliveries.tpl")){
	include $__MDL_ROOT . "/php/subpage_deliveries.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_deliveries");
	$__module_subpage['deliveries']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagedeliveries
 //BeginSubPagecontacts
 $__module_subpage['contacts']['admin'] = "";
 $__module_subpage['contacts']['group'] = 0;
 $__module_subpage['contacts']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='contacts' && file_exists($__MDL_ROOT . "/tpl/subpage_contacts.tpl")){
	include $__MDL_ROOT . "/php/subpage_contacts.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_contacts");
	$__module_subpage['contacts']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagecontacts
 //BeginSubPagerequisite
 $__module_subpage['requisite']['admin'] = "";
 $__module_subpage['requisite']['group'] = 0;
 $__module_subpage['requisite']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='requisite' && file_exists($__MDL_ROOT . "/tpl/subpage_requisite.tpl")){
	include $__MDL_ROOT . "/php/subpage_requisite.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_requisite");
	$__module_subpage['requisite']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagerequisite
 //BeginSubPageuserfields
 $__module_subpage['userfields']['admin'] = "";
 $__module_subpage['userfields']['group'] = 0;
 $__module_subpage['userfields']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='userfields' && file_exists($__MDL_ROOT . "/tpl/subpage_userfields.tpl")){
	include $__MDL_ROOT . "/php/subpage_userfields.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_userfields");
	$__module_subpage['userfields']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageuserfields
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

