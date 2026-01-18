<?php


$allcount = 0;

list($head, $copmpares) = $plugin_compare->getCompareList($main_fields, $section->parametrs->param10 == 'Y');

if (!empty($head)) {
$main_compares = array();

$psg = new plugin_shopgoods($_page);

$main_fields = array(
    'article' => $section->parametrs->param4 == 'Y' ? $section->language->lang031 : '', 
    'rating' => $section->parametrs->param5 == 'Y' ? $section->language->lang032 : '', 
    'brand' => $section->parametrs->param6 == 'Y' ? $section->language->lang033 : '', 
    'price' => $section->parametrs->param7 == 'Y' ? $section->language->lang034 : '', 
    'count' => $section->parametrs->param8 == 'Y' ? $section->language->lang035 : ''
);
foreach($head as $line) {

    $line['link'] = seMultiDir() . '/' . $section->parametrs->param3 . '/show/' . $line['code']. '/';
    list($line['image'], ) = $psg->getGoodsImage($line['img'], $section->parametrs->param1);
    
    $selected = !empty($_SESSION['modifications'][$line['id']]) ? $_SESSION['modifications'][$line['id']] : '';
    $plugin_amount = new plugin_shopAmount($line['id'], '', $price_type, 1, $selected);
    
    foreach ($main_fields as $key => $val) {
        if (empty($val))
            continue;
        if (!isset($main_compares[$key])) {
            $main_compares[$key] = array(
                'name' => $val, 
                'values' => array(),
                'diff' => 'f-diff',
                'type' => 'main'
            );    
        }
        switch ($key){
            case 'article':
                $main_compares[$key]['values'][$line['id']] = $line['article'];
                break;
            case 'brand':
                $main_compares[$key]['values'][$line['id']] = array_shift(explode('||', $line['brand']));
                break;
            case 'rating':
                $main_compares[$key]['values'][$line['id']] = '<div class="blockRating"><span class="ratingOff" title=""> <span class="ratingOn" style="width:' . ($line['rating']*100/5) . '%;"></span></span> <span class="ratingValue">' . round($line['rating'], 1) . '</span>
<span class="marks">(<a class="marksLabel" href="' . $line['link'] . '#reviews">' . $section->language->lang036 . '</a> <span class="marksValue">' . $line['marks'] . '</span>)</span></div>';
                break;
            case 'price':
                $main_compares[$key]['values'][$line['id']] = $plugin_amount->showPrice(true, true, '&nbsp;');
                break;
            case 'count':
                $main_compares[$key]['values'][$line['id']] = $plugin_amount->showPresenceCount($section->language->lang037, $section->language->lang038);
                break;
        }
    }

    $__data->setItemList($section, 'product', $line);  
}

$copmpares = $main_compares + $copmpares;
$allcount = count($copmpares);

if (!empty($copmpares)) {
    foreach($copmpares as $key => $line) {
        $line['id'] = $key;
        if (!empty($line['values'])) {
            if ($line['type'] == 'main' && count(array_unique($line['values'])) == 1)
                $line['diff'] = 'f-same';   
            foreach($line['values'] as $val_id => $val){
                if ($line['type'] == 'bool') {
                    if ($val !== null)
                        $val = $val ? $section->language->lang029 : $section->language->lang030;
                }
                $__data->setItemList($section, 'features' . $key, array('id' => $val_id, 'val' => $val));    
            }
        }
        unset($line['values']);
       
        $__data->setItemList($section, 'compare_test', $line);
    
    }
}
}

$categories_compare = $plugin_compare->getTypesCompare();
if (!empty($categories_compare)) {
    foreach($categories_compare as $key => $val) {
        $val['id'] = $key;
        $__data->setItemList($section, 'categories', $val);    
    }
}


?>
