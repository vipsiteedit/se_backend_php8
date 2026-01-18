<?php

foreach($pricelist as $goods) {                                                            
    $goods['modifications'] = ($goods['modifications']) ? showParamList($__data, $__MDL_ROOT, $section->parametrs->param310, $section->parametrs->param318, $goods['id']) : '';
    
    $selected = !empty($_SESSION['modifications'][$goods['id']]) ? $_SESSION['modifications'][$goods['id']] : '';
          
    $plugin_amount = new plugin_shopAmount(0, $goods, $price_type, 1, $selected);     

    $goods['maxcount'] = (int)$plugin_amount->getPresenceCount();    
    
    $goods['realprice'] =  $plugin_amount->getPrice(true);    
    
    $goods['count'] = $plugin_amount->showPresenceCount($section->language->lang021, $section->language->lang020);
    
    $goods['newprice'] = $plugin_amount->showPrice(true, $rounded, $separator);
    
    $goods['step'] = $plugin_amount->getStepCount();
     
    $discount = $plugin_amount->getDiscount();
    
    $goods['oldprice'] = '';
    if (($discount > 0)){
        $goods['oldprice'] = $plugin_amount->showPrice(false, $rounded, $separator);
        $goods['percent'] = 0 - $plugin_amount->getDiscountProc();    
    }

    unset($plugin_amount);
    
    if (!empty($goods['brand']))
        list($goods['brand'], $goods['brand_code'], ) = explode('||', $goods['brand']);

     // основная картинка
    if(($_SESSION['SHOP_VITRINE'.$_page.$razdel]['type']=='t'))
        list($goods['image_prev'], ) = $psg->getGoodsImage($goods['img'], $section->parametrs->param206);
    else                  
        list($goods['image_prev'], ) = $psg->getGoodsImage($goods['img'], $section->parametrs->param289);
        
    $goods['img_alt'] = empty($goods['img_alt']) ? htmlspecialchars($goods['name']) : htmlspecialchars($goods['img_alt']);   
                 
    if (utf8_strlen($goods['name']) > $nchar) {
        $goods['name'] = se_LimitString($goods['name'], $nchar, ' ...');
    }
    if (($section->parametrs->param207 != '-1') && (utf8_strlen($goods['note']) > intval($section->parametrs->param207))) {
      $goods['note'] = se_limitstring($goods['note'], intval($section->parametrs->param207));
    }

    $goods['linkshow'] = seMultiDir() . "/{$virtualpage}/show/{$goods['code']}/";       
    
    $goods['rating'] = !empty($goods['rating']) ? round($goods['rating'], 1) : 0;
    $goods['rating_percent'] = round($goods['rating']/0.05);
    $__data->setItemList($section, 'objects', $goods);
    
}

?>