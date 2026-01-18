<?php

$nchar = intval($section->parametrs->param237);
if (!$nchar) {
    $nchar = 60;
}  

unset($section->objects);
if (empty($size_image)) {
    if ($section->parametrs->param184 == 't')
        $size_image = $section->parametrs->param206;
    else
        $size_image = $section->parametrs->param289;
}

$shop_image = new plugin_ShopImages(); 
$img_style = $shop_image->getSizeStyle($size_image);

$shopcart = new plugin_shopcart();

foreach($pricelist as $goods) {                                                            
    $goods['modifications'] = ($goods['modifications']) ? showParamList($__data, $__MDL_ROOT, $section->parametrs->param310, $section->parametrs->param318, $goods['id']) : '';
    
    $selected = !empty($_SESSION['modifications'][$goods['id']]) ? $_SESSION['modifications'][$goods['id']] : '';
        
    $plugin_amount = new plugin_shopamount(0, $goods, $price_type, 1, $selected);    

    $goods['maxcount'] = (int)$plugin_amount->getPresenceCount();    
    
    $goods['realprice'] =  $plugin_amount->getPrice(true);    
    
    $goods['count'] = $plugin_amount->showPresenceCount($section->language->lang021, $section->language->lang020);
    
    $goods['newprice'] = $goods['price'] = $goods['new price'] = $plugin_amount->showPrice(true, $rounded, $separator);
    
    $goods['step'] = $plugin_amount->getStepCount();
     
    $discount = $plugin_amount->getDiscount();
    
    $goods['discount_date_start'] = $goods['discount_date_end'] = '';
    
    $goodsdiscount_date = $plugin_amount->getDiscountDate(); 

    if (!empty($discount_date)) {
        $goods['discount_date_start'] = $discount_date['start'];
        $goods['discount_date_end'] = $discount_date['end'];
    }
    
    $goods['oldprice'] = '';
    if (($discount > 0)){
        $goods['oldprice'] = $goods['old price'] = $plugin_amount->showPrice(false, $rounded, $separator);
        $goods['percent'] = 0 - $plugin_amount->getDiscountProc();    
    }

    unset($plugin_amount);
    
    if (!empty($goods['brand']))
        list($goods['brand'], $goods['brand_code'], ) = explode('||', $goods['brand']);
        
    $goods['image_prev'] = $shop_image->getPictFromImage($goods['img'], $size_image);    
    $goods['name'] = $shop_variables->parseProductText($goods['name'], $goods); 
    $goods['note'] = $shop_variables->parseProductText($goods['note'], $goods);
    
    $goods['img_alt'] = empty($goods['img_alt']) ? htmlspecialchars($goods['name']) : htmlspecialchars($goods['img_alt']);
                 
    if (utf8_strlen($goods['name']) > $nchar) {
        $goods['name'] = se_LimitString($goods['name'], $nchar, ' ...');
    }
    if (($section->parametrs->param207 != '-1') && (utf8_strlen($goods['note']) > intval($section->parametrs->param207))) {
      $goods['note'] = se_limitstring($goods['note'], intval($section->parametrs->param207));
    }

    $goods['linkshow'] = seMultiDir() . '/' . $virtualpage . '/show/' . urlencode($goods['code']) . '/';       
    
    $goods['rating'] = !empty($goods['rating']) ? round($goods['rating'], 1) : 0;
    $goods['rating_percent'] = round($goods['rating']/0.05);
    
    $goods['compare'] = $plugin_compare->inCompare($goods['id']);
    $goods['incart'] = $shopcart->inCart($goods['id']);
    $__data->setItemList($section, 'objects', $goods);
   
}

?>
