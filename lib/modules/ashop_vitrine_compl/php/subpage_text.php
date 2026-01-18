<?php

$product_text = $shop_variables->parseProductText(trim($price_fields['text']), $price_fields);
//вкладки  
$use_tabs = false;  
$pattern = '#<tab[^>]+title="([^>\"]+)"[^>]*>(.*?)</tab>#uis';
if (preg_match_all($pattern, $product_text, $arr)) {
    $use_tabs = true;
    $tabs = array();
    foreach($arr[1] as $key => $val) {
        $tabs['title'] = trim($val);
        $tabs['content'] = trim($arr[2][$key]);
        if (!empty($tabs['title']) && !empty($tabs['content'])) 
            $__data->setItemList($section, 'tabs', $tabs);       
    }
}


?>
