<?php

$payment_list = getPaymentList($plugin_delivery->delivery_type_id, true);
$exist_payments = 0;       
if (!empty($payment_list)) {
    $plugin_macros = new plugin_macros();
    foreach($payment_list as $key => $val) {
        if (empty($val['delivery']))
            $exist_payments++; 
        $val['startform'] = $plugin_macros->execute($val['startform']);      
        $__data->setItemList($section, 'paymentlist', $val);
    }
}

?>