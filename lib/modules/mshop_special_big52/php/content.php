<?php

if (!empty($pricelist)) {
    $plugin_image = new plugin_ShopImages();   
    foreach($pricelist as $line) {    
        if ($section->parametrs->param21 == 'Y'){
            $line['ratio'] = round(100 / 5 * $line['rating']);
            $line['rating'] = round($line['rating'], 2);
        }
        
        if ($section->parametrs->param22 == 'Y') {
            $line['image_prev'] = $plugin_image->getPictFromImage($line['img'], $section->parametrs->param48, 's');
        }
        
        /*if ($line['modifications']) {
            $plugin_modifications = new plugin_shopmodifications($line['id'], true);
            $plugin_modifications->getModifications(true);
        }*/
        $line['modifications'] = ($line['modifications']) ? showSpecParamList($__data, $section, $__MDL_ROOT, $line['id']) : '';
    
        $selected = !empty($_SESSION['modifications'][$line['id']]) ? $_SESSION['modifications'][$line['id']] : '';
        
        $plugin_amount = new plugin_shopAmount(0, $line, $price_type, 1, $selected);
        
        $count_goods = (int)$plugin_amount->getPresenceCount();
        
        if (empty($count_goods)) {
            
            $line['disabled'] = 'disabled';
            $line['empty_class'] = ' emptyGoods';
            $line['btn_title'] = $section->language->lang008;
        }
        else{
            $line['btn_title'] = $section->language->lang007;
        }
        if ($section->parametrs->param26 == 'Y') {
            $round = ($section->parametrs->param29 == 'Y');  
            $line['new_price'] = $plugin_amount->showPrice(true, $round, ' ');
            
            $discount = $plugin_amount->getDiscount();
            
            if ($discount > 0) {
                $line['old_price'] = $plugin_amount->showPrice(false, $round, ' '); 
                $line['percent'] = 0 - $plugin_amount->getDiscountProc();      
            }                                    
        }
      
        $line['name'] = htmlspecialchars($line['name']);
        $line['code'] = urlencode($line['code']);
        $line['code_gr'] = urlencode($line['code_gr']);
        $line['view_goods'] = seMultiDir() . "/$vitrine_page/show/{$line['code']}/";
        $line['view_group'] = seMultiDir() . "/$vitrine_page/cat/{$line['code_gr']}/";
        $__data->setItemList($section, 'objects', $line);
    }
}
?>