<?php

$related = $plugin_cart->getRelated();

foreach ($related as $val) {
    $__data->setItemList($section, 'related', $val);
}

?>