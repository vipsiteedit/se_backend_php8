<?php

$regions = $plugin_shopgeo->getCities($search, 10);

if (!empty($regions)) {
    foreach ($regions as $val) {
        $__data->setItemList($section, 'regions', $val);
    }
}

?>
