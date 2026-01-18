<?php

      /*foreach ($plugin_compare->getGoodsCompare() as $line){
    $empty_list[$line] = null;
    $tl = new seTable('shop_price');
    $tl->select('*')->find($line);
    $product = $tl->fetchOne();
    $product['link'] = seMultiDir() . '/' . $section->parametrs->param3 . '/show/' . $product['code']. '/';
    list($product['image'], ) = $psg->getGoodsImage($product['img'], $section->parametrs->param1);
    
    $selected = !empty($_SESSION['modifications'][$product['id']]) ? $_SESSION['modifications'][$product['id']] : '';
    $plugin_amount = new plugin_shopAmount($product['id'], '', $price_type, 1, $selected);
    
    if (!isset($main_compares['article'])) {
        $main_compares['article'] = array(
            'name' => 'Артикул', 
            'values' => array(),
            'diff' => 'r',
            'type' => 'main',
            'count' => 0
        );    
    }
    $main_compares['article']['values'][$line] = $product['article']; 
    
    if (!isset($main_compares['rating'])) {
        $main_compares['rating'] = array(
            'name' => 'Рейтинг', 
            'values' => array(),
            'diff' => 'r',
            'type' => 'main',
            'count' => 0
        );    
    }
    $main_compares['rating']['values'][$line] = $product['rating'];
    
    if (!isset($main_compares['brand'])) {
        $main_compares['brand'] = array(
            'name' => 'Бренд', 
            'values' => array(),
            'diff' => 'r',
            'type' => 'main',
            'count' => 0
        );    
    }
    $main_compares['brand']['values'][$line] = $product['brand']; 
    
    if (!isset($main_compares['price'])) {
        $main_compares['price'] = array(
            'name' => 'Цена', 
            'values' => array(),
            'diff' => 'r',
            'type' => 'main',
            'count' => 0
        );    
    }
    $main_compares['price']['values'][$line] = $plugin_amount->showPrice(true, true, '&nbsp;'); 
    
    if (!isset($main_compares['count'])) {
        $main_compares['count'] = array(
            'name' => 'Наличие', 
            'values' => array(),
            'diff' => 'r',
            'type' => 'main',
            'count' => 0
        );    
    }
    $main_compares['count']['values'][$line] = $plugin_amount->showPresenceCount('нет в наличии', 'в наличии');       
    
    $__data->setItemList($section, 'product', $product);  
}
*/
?>