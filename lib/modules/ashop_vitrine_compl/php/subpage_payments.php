<?php

$lang = se_getLang();
$p_payment = new plugin_payment();
$pay_list = $p_payment->getList();

foreach($pay_list as $key=>$val){
    if($val['logoimg']){
        $val['logoimg'] = '/images/'.$lang.'/shoppayment/'.$val['logoimg'];
    }
    $__data->setItemList($section, 'paymentlist', $val);
}

//print_r($pay_list);

?>
