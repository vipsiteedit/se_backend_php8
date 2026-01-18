<?php

$shoppage  = (string)$section->parametrs->param10 ? (string)$section->parametrs->param10 : $__data->getVirtualPage('shop_vitrine');
$options = array(
    'field_name' => $section->parametrs->param24 == 'Y',
    'field_article' => $section->parametrs->param25 == 'Y',
    'field_note' => $section->parametrs->param27 == 'Y',
    'field_image' => $section->parametrs->param22 == 'Y',
    'field_rating' => $section->parametrs->param21 == 'Y',
    'field_group_name' => $section->parametrs->param23 == 'Y',
    'field_price' => $section->parametrs->param26 == 'Y',
    'count' => (intval($section->parametrs->param5)) ? intval($section->parametrs->param5) : 8,
    'sort' => array((string)$section->parametrs->param6, (string)$section->parametrs->param20),
    'start_group' => (string)$section->parametrs->param14,
    'group_type' => (string)$section->parametrs->param13,
    'image_size' => (string)$section->parametrs->param48,
    'round' => $section->parametrs->param29 == 'Y',
    'page_shop' => $shoppage
);

$plugin_special = new plugin_shopspecial((string)$record->text1);
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
        $line['altname'] = htmlspecialchars($line['name']);
        if (intval($section->parametrs->param50) > 0) { 
            if (utf8_strlen($line['name']) > $section->parametrs->param50){
                $line['name'] = se_LimitString($line['name'], $section->parametrs->param50);
            }
        }
        $__data->setItemList($section, 'specobjects'.$record->id, $line);
    }
}

?>
