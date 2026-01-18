<?php

if (isRequest('sheet') && ($page_num = getRequest('sheet', 1)) > 1){
    $add_title = $section->language->lang005 . $page_num;
}

$shop_variables = plugin_shopvariables::getInstance(); 
$psi = new plugin_ShopImages('group');

$group = $psg->getGroup($shopcatgr);

$thisgroup_name = $shop_variables->parseGroupText($group['name'], $group) . $add_title;
    
if (trim($group['title']) == '') {
    $group['title'] = $thisgroup_name;
}

$thisgroup_name = $shop_variables->parseGroupText($group['name'], $group) . $add_title;

$__data->page->titlepage = $shop_variables->parseGroupText($group['title'], $group);
    
$__data->page->keywords = htmlspecialchars($shop_variables->parseGroupText($group['keywords'], $group));

$thisgroup_commentary = $shop_variables->parseGroupText($group['commentary'], $group);
    
$description = $shop_variables->parseGroupText($group['description'], $group);

if (!empty($description))
    $__data->page->description = htmlspecialchars($description);
else
    $__data->page->description = htmlspecialchars($thisgroup_commentary);
    
if (empty($group['picture_alt'])) {
    $thisgroup_image_alt = se_db_output($group['name']);       
}   
    // иначе выводим картинку
if (trim($group['image'])!='') {
    $thisgroup_image = $psi->getPictFromImage($group['image'], $section->parametrs->param30);
} 
else {
    $thisgroup_image = '';
} 
    
if (!empty($group['children'])) {
    $sgrouplist = $psg->getChilds((int)$group['id']);
    foreach ($sgrouplist as $val) {
        $sub_group = array();
        $sub_group['name'] = $val['name'];
        $sub_group['title'] = se_db_output($val['name']);
        $sub_group['link'] = seMultiDir() . '/' . $shoppath . '/cat/' . urlencode($val['code']) . '/';
        if (empty($val['picture_alt'])) {
            $sub_group['image_alt'] = se_db_output($val['name']);
        } 
        else { 
            $sub_group['image_alt'] = htmlspecialchars($val['picture_alt']);
        }
            
        if (!empty($val['image'])) {
            $sub_group['image'] = $psi->getPictFromImage($val['image'], $section->parametrs->param29);
        } 
        
        $sub_group['scount'] = $val['count'];
        $__data->setItemList($section, 'subgroups', $sub_group);
    }
}

?>
