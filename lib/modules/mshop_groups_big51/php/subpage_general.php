<?php

$psi = new plugin_ShopImages('group');

$group = $psg->getGroup($shopcatgr);

$thisgroup_name = $group['name'];
    
if (trim($group['title']) == '') {
    $group['title'] = $thisgroup_name;
}
$__data->page->titlepage = $group['title'];
    
$__data->page->keywords = $group['keywords'];

$thisgroup_commentary = $group['commentary'];
    
$description = $group['description'];

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
    $sgrouplist = $psg->getChilds($group['id']);
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