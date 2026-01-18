<?php

$plugin_delivery = new plugin_shopdelivery();
$delivery_list = $plugin_delivery->getDeliveryList($viewgoods);
//$delivery_type_id = $plugin_delivery->delivery_type_id;

$num_d = 1;

$count_d = count($delivery_list);

foreach($delivery_list as $delivery) {
    if($count_d > 1){
        $delivery['num'] = $num_d++.'. ';
    }
    $__data->setItemList($section, 'deliverylist', $delivery);
}

?>
