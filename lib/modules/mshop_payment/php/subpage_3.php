<?php

$se_payment = $_SESSION['SE_PAYMENT'];
$payment->find($se_payment['fp']);

 if (preg_match("/(\<ACCESS\>(.+?)\<\/ACCESS\>)/ium", $payment->success, $m)){
    $payment->success = str_replace($m[1], '' , $payment->success);
 }
// Заполняю переменными
 $payment->success = se_macrocomands($payment->success, 
        $se_payment['fp'], 
        $se_payment['order_id']);

?>