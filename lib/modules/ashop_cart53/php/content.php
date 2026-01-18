<?php

$region_city = $_SESSION['user_region']['city']; 

$_SESSION['check_cart'] = false;

$plugin_shopstat->saveEvent('view shopcart');
$addr = $select_delivery['addr'];
?>
