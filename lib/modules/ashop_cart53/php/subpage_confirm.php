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

if (isRequest('user_type')) {
    $_SESSION['user_type'] = getRequest('user_type');
}

$is_urid = !empty($_SESSION['user_type']) && $_SESSION['user_type'] == 'u';

if ($section->parametrs->param17 == 'Y' && $is_urid) {
    
    $_SESSION['requisite']['company_name'] = $company_name = trim(getRequest('company_name', 3));
    $_SESSION['requisite']['company_director'] = $company_director = trim(getRequest('company_director', 3));
    $_SESSION['requisite']['company_phone'] = $company_phone = trim(getRequest('company_phone', 3));
    $_SESSION['requisite']['company_fax'] = $company_fax= trim(getRequest('company_fax', 3));
    $_SESSION['requisite']['company_addr'] = $company_addr= trim(getRequest('company_addr', 3));
    
       
    $requisite = getRequisite();
    if (!empty($requisite)) {
        foreach($requisite as $val) {
            $val['value'] = $_SESSION['requisite'][$val['code']] = trim(getRequest('bank_' . $val['code']));
            $__data->setItemList($section, 'requisite', $val);
        }
    }
}

if ($section->parametrs->param19 == 'Y') {      
    $fields = getUserFields();
    if (!empty($fields)) {
        foreach($fields as $val) {
            if ($val['is_group'])
                continue;
            $val['value'] = $_SESSION['userfields'][$val['code']] = getRequest('field_' . $val['code']);
            if (is_array($val['value']))
                $val['value'] = join(', ', $val['value']);    
            $__data->setItemList($section, 'userfields', $val);
        }
    }
} 





?>
