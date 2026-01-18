<?php

if (empty($filters)) {
    $filtercount = 0;
    return;
} 
foreach ($filters as $fkey => $fval) {
    if (empty($fval)) continue;
    $fval['id'] = $fkey;
    if ($fval['type'] == 'list' || ($section->parametrs->param8!='Y' && $fval['type'] == 'colorlist')) {    
        $fval['type'] = 'list';
        foreach($fval['values'] as $vkey => $vval) {
            $vval['id'] = $vkey;
            if (!$vval['id']) continue;
            $__data->setItemList($section, 'filter_values' . $fkey, $vval);
        }
        unset($fval['values']);    
    } else
    if ($fval['type'] == 'colorlist') {    
        foreach($fval['values'] as $vkey => $vval) {
            $vval['id'] = $vkey;
            if (!$vval['id']) continue;
            $__data->setItemList($section, 'filter_values' . $fkey, $vval);
        }
        unset($fval['values']);    
    }
    elseif ($fval['type'] == 'range') {
        $fval['min'] = floor($fval['min']);
        if (!isset($fval['from']) || $fval['from'] < $fval['min'])
            $fval['from'] = $fval['min'];
        if (!isset($fval['to']) || $fval['to'] > $fval['max'])
            $fval['to'] = $fval['max'];
    }
    switch($fkey) {
         case 'price': $fval['name'] = $section->language->lang009;
            break;
         case 'brand': $fval['name'] = $section->language->lang010;
            break;
         case 'hit': $fval['name'] = $section->language->lang011;
            break;
         case 'new': $fval['name'] = $section->language->lang012;
            break;
         case 'discount': $fval['name'] = $section->language->lang013;
            break;
    }
    $__data->setItemList($section, 'filters', $fval);
} 

?>