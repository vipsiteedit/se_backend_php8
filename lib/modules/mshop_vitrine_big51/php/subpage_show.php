<?php

$price_fields = $psg->showGoodsDescription($viewgoods);
// Кол-во посещений группы/товара
$psg->countVizit($price_fields['id_group']);   
$psg->countGoodsVizit($viewgoods);

$product_modifications = showParamList($__data, $__MDL_ROOT, $section->parametrs->param310, $section->parametrs->param318, $viewgoods, true);

// Подробный просмотр товара
$product_measure = $price_fields['measure'];
$product_name = $price_fields['name'];
$product_code = $price_fields['article'];
$product_note = $price_fields['note'];
$product_rating = !empty($price_fields['rating']) ? round($price_fields['rating'], 1) : '0';
$product_marks = (int)$price_fields['marks'];
$rating_percent = round($product_rating/0.05);
$product_id = $viewgoods;
$product_link = seMultiDir() . '/' . $virtualpage . '/show/' . urlencode($price_fields['code']) .'/'; 
$product_brand = '';
$brand_link = '';  
$price_img_alt = !empty($price_fields['img_alt']) ? $price_fields['img_alt'] : $price_fields['name'];
if (!empty($price_fields['brand'])) {
    list($price_fields['brand'], $price_fields['brand_code'], $price_fields['brand_image']) = explode('||', $price_fields['brand']);
    $product_brand = $price_fields['brand'];  
    $brand_link = seMultiDir() . '/' . $_page . '/cat/' . $price_fields['code_gr'] . '/?brand=' . urlencode($price_fields['brand_code']);
    if (!empty($price_fields['brand_image']))
        $brand_image = $psg->getBrandImage($price_fields['brand_image']);
}                             
$__data->page->titlepage = $price_fields['title'];
//$__data->page->title = $price_fields['title'];
$__data->page->description = $price_fields['description'];   
$__data->page->keywords = $price_fields['keywords']; 

//текущая валюта сайта
$pricemoney = se_getMoney(); 
 
$selected = !empty($_SESSION['modifications'][$viewgoods]) ? $_SESSION['modifications'][$viewgoods] : '';                                                                  
 
$plugin_amount = new plugin_shopamount($viewgoods, '', $price_type, 1, $selected);
    
$maxcnts = $plugin_amount->getPresenceCount();

$step = $plugin_amount->getStepCount();

$realprice =  $plugin_amount->getPrice(true);

$discount = $plugin_amount->getDiscount(); 

$product_count = $plugin_amount->showPresenceCount($section->language->lang021, $section->language->lang020);

$product_price = $price_fields['price'] = $price_fields['new price'] = $plugin_amount->showPrice(true, $rounded, $separator);   

$product_oldprice = '';

$price_fields['discount'] = '';
if ($discount > 0) {
    $product_oldprice = $price_fields['old price'] = $plugin_amount->showPrice(false, $rounded, $separator);
    $price_fields['discount'] = $plugin_amount->getDiscountProc(); 
}                       
unset($plugin_amount);

if (((string)$section->parametrs->param305 == 'man' || empty($price_fields['title'])) && !empty($section->parametrs->param303)){
    $__data->page->titlepage = $psg->parseUserMask((string)$section->parametrs->param303, $price_fields);
}

if (((string)$section->parametrs->param305 == 'man' || empty($price_fields['description'])) && !empty($section->parametrs->param304)){
    $__data->page->description = $psg->parseUserMask((string)$section->parametrs->param304, $price_fields);
}

if (((string)$section->parametrs->param305 == 'man' || empty($price_fields['keywords'])) && !empty($section->parametrs->param328)){
    $__data->page->keywords = $psg->parseUserMask((string)$section->parametrs->param328, $price_fields);
}

//основная картинка
/*
list($img_full, $nofoto) = $psg->getGoodsImage($price_fields['img'], $section->parametrs->param293, 's', $section->parametrs->param292, $section->parametrs->param325, $section->parametrs->param326);
list($img_mid, $nofoto) = $psg->getGoodsImage($price_fields['img'], $section->parametrs->param286, 's', $section->parametrs->param292, $section->parametrs->param325, $section->parametrs->param326);
list($img_prev, $nofoto) = $psg->getGoodsImage($price_fields['img'], $section->parametrs->param285, 's', $section->parametrs->param292, $section->parametrs->param325, $section->parametrs->param326);
*/
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

// Путь по каталогу к товару
if ($section->parametrs->param246!='N') {  
    $dt = $psg->getPathGroup($price_fields['id_group']);
    while (count($dt)) {
        $__data->setItemList($section, 'pathg', array_pop($dt));
    }
}

?>