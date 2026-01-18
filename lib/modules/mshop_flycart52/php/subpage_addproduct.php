<?php

$getGoodsCart = $plugin_cart->getGoodsCart();

if(!empty($getGoodsCart)){
    $shop_image = new plugin_ShopImages();  
    $product = current($getGoodsCart);  
    $product_image = $shop_image->getPictFromImage($product['img'], '150x150', 's');  
    $product_link = seMultiDir().'/'.$section->parametrs->param3.'/show/' . $product['code'] . '/';
    $product_name = $product['name'];
    $product_params = $product['paramsname'];
    $product_article = $product['article'];
    $product_newprice = $product['newprice'];
    $product_oldprice = $product['oldprice'];
    $product_discount = $product['discount'];
    $product_count = $product['count'];
    $product_step = $product['step'];
    $product_amount = $product['newsum'];
    $product_key = $product['key'];
}


$total = $plugin_cart->getTotalCart();
 
$order_summ = $total['show_total'];
$count_goods = $total['count'];

?>