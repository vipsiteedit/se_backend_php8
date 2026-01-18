<?php

$options = array(
    'field_name' => $section->parametrs->param24 == 'Y',
    'field_article' => $section->parametrs->param25 == 'Y',
    'field_note' => $section->parametrs->param27 == 'Y',
    'field_image' => $section->parametrs->param22 == 'Y',
    'field_rating' => $section->parametrs->param21 == 'Y',
    'field_group_name' => $section->parametrs->param23 == 'Y',
    'field_price' => $section->parametrs->param26 == 'Y',
    'field_label' => $section->parametrs->param51 == 'Y',
    'count' => (int)$section->parametrs->param5 > 0 ? (int)$section->parametrs->param5 : 10,
    'sort' => array((string)$section->parametrs->param6, (string)$section->parametrs->param20),
    'start_group' => (string)$section->parametrs->param14,
    'group_type' => (string)$section->parametrs->param13,
    'image_size' => (string)$section->parametrs->param48,
    'round' => $section->parametrs->param29 == 'Y',
    'page_shop' => !(string)$section->parametrs->param10 ? (string)$section->parametrs->param10 : $__data->getVirtualPage('shop_vitrine'),
    'price_label' => (string)$section->language->lang002,
    'label' => (string)$section->parametrs->param50,
);

$plugin_special = new plugin_shopspecial((string)$section->parametrs->param18);
$pricelist = $plugin_special->getSpecial($options);

if (!empty($pricelist)) { 
    foreach($pricelist as $line) {    
        if ($line['modifications']) {
            if ($section->parametrs->param49 == 'radio' || $section->parametrs->param49 == 'list') {
                $line['param_block'] = showModifications($__data, $__MDL_ROOT, $section->parametrs->param49, $line['id'], 's' . $razdel);    
            }
            else {
                $plugin_modifications = new plugin_shopmodifications($line['id'], true);
                $plugin_modifications->getModifications(true);
            }   
        }
        
        if (!empty($line['labels'])) {
            foreach($line['labels'] as $val) {
                $__data->setItemList($section, 'labels' . $line['id'], $val);
            }
        }
        
        $__data->setItemList($section, 'objects', $line);
    }
}
?>
