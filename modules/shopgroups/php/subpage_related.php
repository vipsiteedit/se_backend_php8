<?php

if (!empty($related)) {
    foreach ($related as $val) {
        $val['link'] = seMultiDir() . '/' . $shoppath . '/cat/' . urlencode($val['code']) . '/';
        
        if (empty($val['picture_alt'])) {
            $val['image_alt'] = se_db_output($val['name']);
        } 
        else { 
            $val['image_alt'] = htmlspecialchars($val['picture_alt']);
        }
            
        if (!empty($val['image'])) {
            $val['image'] = $psi->getPictFromImage($val['image'], $section->parametrs->param29);
        }
        
        $__data->setItemList($section, 'realted_groups', $val);
    }
}

?>