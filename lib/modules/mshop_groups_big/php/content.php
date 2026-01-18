<?php

$srch = plugin_shopsearch40::getInstance();
$srch_price_min = $srch->get('price','min');
$srch_price_max = $srch->get('price','max');
//var_dump($srch_price_min,$srch_price_max);
$shopcatgr = get('cat',0);
$shop = new plugin_shopgoods40($_page);
list($minprice,$maxprice) = $shop->getPriceRange();
if (!$minprice) $minprice = '0';
if (!$maxprice) $maxprice = '0';
$maxpriceset = round((!$srch_price_max || $srch_price_max>$maxprice) ? $maxprice : $srch_price_max);
$minpriceset = round((!$srch_price_min || $srch_price_min<$minprice) ? $minprice : $srch_price_min);
?>