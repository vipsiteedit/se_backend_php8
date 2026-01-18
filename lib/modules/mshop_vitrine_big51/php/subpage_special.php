<?php

unset($section->objects);
foreach($pricelist as $item) {
    if ($item['modifications']) {
        $plugin_modifications = new plugin_shopmodifications($item['id'], $section->parametrs->param318 == 'Y');
        $plugin_modifications->getModifications(true);
    }
    
    $selected = !empty($_SESSION['modifications'][$item['id']]) ? $_SESSION['modifications'][$item['id']] : '';
        
    $plugin_amount = new plugin_shopAmount(0, $item, $price_type, 1, $selected);    

    $item['maxcount'] = (int)$plugin_amount->getPresenceCount();    
    
    $item['newprice'] = $plugin_amount->showPrice(true, $rounded, $separator);
    
    $item['oldprice'] = '';
    if ($plugin_amount->getDiscount() > 0){
        $item['oldprice'] = $plugin_amount->showPrice(false, $rounded, $separator);
        $item['percent'] = 0 - $plugin_amount->getDiscountProc();    
    }
    
    unset($plugin_amount);
    
    list($item['image_prev'], ) = $psg->getGoodsImage($item['img'], $section->parametrs->param315);
    
    $item['img_alt'] = empty($item['img_alt']) ? htmlspecialchars($item['name']) : htmlspecialchars($item['img_alt']);  
    
    if (utf8_strlen($item['name']) > $nchar) {
        $item['name'] = se_LimitString($item['name'], $nchar, ' ...');
    }
    $item['name'] = htmlspecialchars($item['name']);
    
    $item['linkshow'] = seMultiDir() . "/{$_page}/show/{$item['code']}/";
    
    $item['rating'] = !empty($item['rating']) ? round($item['rating'], 1) : 0;
    $item['rating_percent'] = round($item['rating']/0.05);    
    
    $__data->setItemList($section, 'objects', $item);
}

?>