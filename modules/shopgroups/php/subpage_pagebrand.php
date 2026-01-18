<?php

if (!$brand) {
    $brands = $psb->getList();
    
    foreach($brands as $val) {
        if (!empty($val['image']))
            $val['image'] = '/images/rus/shopbrand/' . $val['image'];
        $val['title'] = se_db_output($val['name']);
        $val['link'] = seMultiDir() . '/' . $shoppath . '/brand/' . urlencode($val['code']) . URL_END;
        $__data->setItemList($section, 'brands', $val);
     }
}

?>