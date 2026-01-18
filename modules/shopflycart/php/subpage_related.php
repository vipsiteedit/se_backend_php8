<?php

$related = $plugin_cart->getRelated($product['price_id'], false);

foreach ($related as $val) {
    $val['url'] = seMultiDir().'/'.$section->parametrs->param3.'/' . $val['url'];
    $__data->setItemList($section, 'related', $val);
}

?>