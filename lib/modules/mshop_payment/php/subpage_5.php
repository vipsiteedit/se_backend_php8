<?php

$se_payment = $_SESSION['SE_PAYMENT'];
$payment->find($se_payment['fp']);

 if (preg_match("/(\<ACCESS\>(.+?)\<\/ACCESS\>)/ium", $payment->fail, $m)){
    $payment->fail = str_replace($m[1], '' , $payment->fail);
 }

// Заполняю переменными
 $payment->fail = se_macrocomands($payment->fail, 
        $se_payment['fp'], 
        $se_payment['order_id']);

?>