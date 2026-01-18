<?php

$payment_list = getPaymentList($plugin_delivery->delivery_type_id, true);
$exist_payments = 0;       
if (!empty($payment_list)) {
    foreach($payment_list as $key => $val) {
        if (empty($val['delivery']))
            $exist_payments++;    
        $__data->setItemList($section, 'paymentlist', $val);
    }
}

?>
