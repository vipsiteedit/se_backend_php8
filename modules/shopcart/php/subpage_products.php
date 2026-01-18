<?php

unset($section->objects);
$shop_image = new plugin_ShopImages();               
foreach ($plugin_cart->getGoodsCart() as $key => $goods_list) {
    $goods_list['img'] = $shop_image->getPictFromImage($goods_list['img'], $section->parametrs->param15, 's');
    $goods_list['img_style'] = $shop_image->getSizeStyle($section->parametrs->param15);
    //$goods_list['price'];
    if (!floatval($goods_list['price']))  {
       $goods_list['newprice'] = strval($section->language->lang094);
       $goods_list['oldprice'] = strval($section->language->lang094);
       $goods_list['newsum'] = strval($section->language->lang094);
    }
   $__data->setItemList($section, 'objects', $goods_list);
}
unset($shop_image);

?>