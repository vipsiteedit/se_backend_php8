<?php

unset($section->objects);
$shop_image = new plugin_ShopImages();               
foreach ($plugin_cart->getGoodsCart() as $key => $goods_list) {
    $goods_list['link'] = $goods_list['link'];
    $goods_list['img'] = $shop_image->getPictFromImage($goods_list['img'], $section->parametrs->param15, 's');
    $goods_list['img_style'] = $shop_image->getSizeStyle($section->parametrs->param15);
    $__data->setItemList($section, 'objects', $goods_list);
}
unset($shop_image);

?>
