<?php

$price_fields = $psg->showGoodsDescription($viewgoods);

//Кол-во посещений товара
$psg->countGoodsVizit($viewgoods);

//текущая валюта сайта
$pricemoney = se_getMoney(); 

if (!empty($price_fields['modifications'])) {
    $price_fields['modifications'] = $product_modifications = showParamList($__data, $__MDL_ROOT, $section->parametrs->param310, $section->parametrs->param318, $viewgoods, true);
}

$selected = !empty($_SESSION['modifications'][$viewgoods]) ? $_SESSION['modifications'][$viewgoods] : '';                                                                  
 
$plugin_amount = new plugin_shopamount(0, $price_fields, $price_type, 1, $selected);
    
$maxcnts = $plugin_amount->getPresenceCount();

$step = $plugin_amount->getStepCount();

$realprice =  $plugin_amount->getPrice(true);

$discount = $plugin_amount->getDiscount(); 

$discount_date_start = $discount_date_end = '';
    
$discount_date = $plugin_amount->getDiscountDate(); 

if (!empty($discount_date)) {
    $discount_date_start = $discount_date['start'];
    $discount_date_end = $discount_date['end'];
}

$product_count = $plugin_amount->showPresenceCount($section->language->lang021, $section->language->lang020);

$product_price = $price_fields['price'] = $price_fields['new price'] = $plugin_amount->showPrice(true, $rounded, $separator);   

$product_oldprice = '';

$price_fields['discount'] = '';
if ($discount > 0) {
    $product_oldprice = $price_fields['old price'] = $plugin_amount->showPrice(false, $rounded, $separator);
    $price_fields['discount'] = $plugin_amount->getDiscountProc(); 
}                       
unset($plugin_amount);

$product_brand = '';
$brand_link = '';  

if (!empty($price_fields['brand'])) {
    list($price_fields['brand'], $price_fields['brand_code'], $price_fields['brand_image']) = explode('||', $price_fields['brand']);
    $product_brand = str_replace("'", "`", $price_fields['brand']);  
    $brand_link = seMultiDir() . '/' . $_page . '/cat/' . $price_fields['code_gr'] . '/?brand=' . urlencode($price_fields['brand_code']);
    if (!empty($price_fields['brand_image']))
        $brand_image = $psg->getBrandImage($price_fields['brand_image']);
}

$product_group = str_replace("'", "`", $price_fields['group_name']);

$product_measure = $price_fields['measure'];
$product_name = str_replace("'", "`", $shop_variables->parseProductText($price_fields['name'], $price_fields));
$product_code = $price_fields['article'];
$product_note = $shop_variables->parseProductText($price_fields['note'], $price_fields);
$product_rating = !empty($price_fields['rating']) ? round($price_fields['rating'], 1) : '0';
$product_marks = (int)$price_fields['marks'];
$rating_percent = round($product_rating/0.05);
$product_id = $viewgoods;
$product_link = seMultiDir() . '/' . $virtualpage . '/show/' . urlencode($price_fields['code']) .'/'; 
                            
$__data->page->titlepage = $shop_variables->parseProductText($price_fields['title'], $price_fields);
if ($section->parametrs->param329 == 'N')
    $__data->page->title = $product_name;
$__data->page->description = $shop_variables->parseProductText($price_fields['description'], $price_fields);   
$__data->page->keywords = $shop_variables->parseProductText($price_fields['keywords'], $price_fields); 

if (((string)$section->parametrs->param305 == 'man' || empty($price_fields['title'])) && !empty($section->parametrs->param303)){
    $__data->page->titlepage = $psg->parseUserMask((string)$section->parametrs->param303, $price_fields);
}

if ((string)$section->parametrs->param305 == 'man' || empty($price_fields['description']) && !empty($section->parametrs->param304)){
    $__data->page->description = $psg->parseUserMask((string)$section->parametrs->param304, $price_fields);
}

if ((string)$section->parametrs->param305 == 'man' || empty($price_fields['keywords']) && !empty($section->parametrs->param328)){
    $__data->page->keywords = $psg->parseUserMask((string)$section->parametrs->param328, $price_fields);
}

// Дополнительное фото  
$extra_photo = $psg->viewgalleryImages($viewgoods, $section->parametrs->param285, $section->parametrs->param286, $section->parametrs->param293, 's', $section->parametrs->param292, $section->parametrs->param325, $section->parametrs->param326);

if(!empty($extra_photo)) {
    $__data->setList($section, 'photos', $extra_photo);

    if ($section->parametrs->param320 == 'M' && count($extra_photo) < 2) { 
        $section->parametrs->param320 = 'N';    
    }
    
    $price_img = current($extra_photo);
    $img_full = $price_img['image'];
    $img_mid = $price_img['image_mid'];
    $price_img_alt = $price_img['alt'];
    
}
else {
    list($img_full,) = $psg->getGoodsImage('', $section->parametrs->param285, 's', $section->parametrs->param292, $section->parametrs->param325, $section->parametrs->param326);
    $img_mid = $img_full;
    $price_img_alt = '';
    $section->parametrs->param311='N';
    $section->parametrs->param282='N';
    $section->parametrs->param320 = 'N';
} 

$product_compare = $plugin_compare->inCompare($product_id);

$shopcart = new plugin_shopcart();

$product_incart = $shopcart->inCart($product_id);

$shop_image = new plugin_ShopImages(); 
$img_prev_style = $shop_image->getSizeStyle($section->parametrs->param285);
$img_mid_style = $shop_image->getSizeStyle($section->parametrs->param286);

// Путь по каталогу к товару
if ($section->parametrs->param246!='N') {  
    $dt = $psg->getPathGroup($price_fields['id_group']);
    while (count($dt)) {
        $__data->setItemList($section, 'pathg', array_pop($dt));
    }
} 

if ($section->parametrs->param334 == 'Y') {
    $delivery = new plugin_shopdelivery();
    $delivery_list = $delivery->getDeliveryList();
}

$product_name = htmlspecialchars($product_name, ENT_QUOTES);
$product_group = htmlspecialchars($product_group, ENT_QUOTES);

?>
