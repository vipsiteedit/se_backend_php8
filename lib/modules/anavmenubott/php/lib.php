<?php

if (!function_exists('getTreesCatalog')){
function getTreesCatalog($d, $s, $items, $objname, $level = 1, $section){
    $col = intval($section->parametrs->param15);
    $k = 1;
    $groupimages = new plugin_ShopImages('group');
    foreach($items as $item){
        
        if (!empty($item['image']))
            $item['image'] = $groupimages->getPictFromImage($item['image']);
            
        $item['is_row'] = (fmod($k, $col) == 0 && $level == 1) ? 1 : 0;
        $k++;  
        $d->setItemList($s, strval($objname), $item);
        if (!empty($item['menu']) && $level < 3) {
            getTreesCatalog($d, $s, $item['menu'], strval('s'.$objname.$item['id']), $level + 1, $section);
        }
    }
}}

?>