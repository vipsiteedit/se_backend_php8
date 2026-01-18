<?php

unset($section->objects);
$__data->setList($section, 'objects', $plugin_cart->getGoodsCart());

if (isRequest('contact_name')){
    $_SESSION['cartcontact']['name'] = trim($contact_name = getRequest('contact_name', 3));
}
elseif($user_id){
    $contact_name = seUserName();
}

$_SESSION['cartcontact']['email'] = $contact_email = trim(getRequest('contact_email', 3));
$_SESSION['cartcontact']['phone'] = $contact_phone = trim(getRequest('contact_phone', 3));
$_SESSION['cartcontact']['post_index'] = $contact_post_index = trim(getRequest('contact_post_index', 3));
$_SESSION['cartcontact']['address'] = $contact_address= trim(getRequest('contact_address', 3));
$_SESSION['cartcontact']['comment'] = $contact_comment= trim(getRequest('contact_comment', 3));

if ((float)$select_delivery['price'] > 0){
    $delivery_price= se_FormatMoney($select_delivery['price'], $options_cart['curr'], '&nbsp;', $options_cart['round']);
}
else $delivery_price = $section->language->lang021;

$delivery_name = (!empty($select_delivery['name'])) ? $select_delivery['name'] : $section->language->lang022;
$payment_name = ($section->parametrs->param9 == 'Y' && ($payment_type = getPaymentType())) ? $payment_type['name'] : $section->language->lang022;

$time = time();

?>