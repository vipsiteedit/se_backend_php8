<?php

$delivery_list = $plugin_delivery->getDeliveryList();
$delivery_type_id = $plugin_delivery->delivery_type_id;

foreach($delivery_list as $delivery) {
    $delivery['sel'] = ($delivery_type_id == $delivery['id']) ? ' checked' : '';
    
    if ((float)$delivery['price'] > 0){
        $delivery['price'] = se_FormatMoney($delivery['price'], $options_cart['curr'], '&nbsp;', $options_cart['round']);
    }
    else $delivery['price'] = $section->language->lang021;
    
    $delivery['time'] = getTimeWord($delivery['time']);
    
    
    $__data->setItemList($section, 'deliverylist', $delivery);
}

?>