<?php

if (!function_exists('isPostPayment')){
function isPostPayment($id_order) {
    $shop_order = new seTable('shop_order', 'so');
    $shop_order->select('so.id');
    $shop_order->innerJoin('shop_payment sp', 'so.payment_type=sp.id');
    $shop_order->where('so.id=?', $id_order);
    $shop_order->andWhere('sp.way_payment="a"');
    $shop_order->fetchOne();
    return $shop_order->isFind();
}
}
?>