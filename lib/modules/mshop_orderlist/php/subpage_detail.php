<?php

$order = new seTable('shop_order', 'so');
$order->select('so.*, sa.`account` AS accounts,sm.name_payment');
$order->leftjoin('shop_payment sm', 'sm.id = so.payment_type'); 
$order->leftjoin('shop_account sa', 'sa.id_order = so.id'); 
$order->where('so.id=?', $order_id);
$order->andwhere('so.id_author=?', $id_author);
$order->fetchOne();

$show_button = (!in_array($order->status, array('Y', 'K', 'P')));

$curr_order = $order->curr;
$order_num = sprintf("%06u", $order_id);
$order_paid = ($order->status == 'Y');
$delivery_type = $order->delivery_type;
$discount = se_MoneyConvert($order->discount, $curr_order, $showcurr);

### Список товаров в заказе ###
$torder = new seTable('shop_tovarorder', 'st');
$torder->select('sp.measure, sp.id, sp.article, sp.name, st.nameitem, st.price, st.discount, st.count, st.license, st.commentary, so.date_order');
$torder->leftjoin('shop_price sp', 'sp.id = st.id_price');
$torder->innerjoin('shop_order so', 'so.id = st.id_order');
$torder->where("st.id_order = ?", $order_id);
$torder->andWhere("so.id_author = ?", $id_author);
$goods = $torder->getList();
unset($torder);

$amount = 0; 
foreach ($goods as $key => $line) {
    $price = se_MoneyConvert($line['price'] - $line['discount'], $curr_order, $showcurr);
    $line['price'] = se_formatMoney($price, $showcurr, '&nbsp;', $round_price);
    $sum = $price * $line['count'];
    $line['sum'] =  se_formatMoney($sum, $showcurr, '&nbsp;', $round_price);
    $line['key'] = $key + 1;      
    // Отображаем лицензию, серийный номер, ссылку для скачивания (при оплаченом заказе)
    if (!empty($line['license'])) {
        $ser = explode("\r\n", $line['license']);
        foreach ($ser as $lin) { 
            if (!empty($lin)) {
                $getserial = getlicense_order($lin);
                $license = '<div class="serialHead"><span class="license">' . $section->language->lang026."</span>".$lin.'</div>
            <div class="serialKey">'.$getserial.'</div>';
            } 
        }
    }
        
    $amount += $sum; 
    
    $__data->setItemList($section, 'tovarorder', $line);
}
$amount = se_formatMoney($amount - $discount, $showcurr, '&nbsp;', $round_price);
$discount = se_formatMoney($discount, $showcurr, '&nbsp;', $round_price);

### Информация о доставке ###
if ($delivery_type){
    $delivery = new seTable('shop_deliverytype');
    $delivery->find($delivery_type);
    $delivery_name = $delivery->name;
    $delivery_sum = se_formatMoney(se_MoneyConvert($order->delivery_payee, $curr_order, $showcurr), $showcurr, '&nbsp;', $round_price);
        
    switch ($order->delivery_status) {
        case 'N': 
            $delivery_status = '<span class="fontdeliv_n">'.$section->language->lang004.'</span>'; 
            break;
        case 'Y': 
            $delivery_status = '<span class="fontdeliv_y">'.$section->language->lang006.'<span class="fontdeliv_yd">(' . 
                                    date("d.m.Y", strtotime($order->delivery_date)).')</span></span>'; 
            break;
        case 'P': 
            $delivery_status = '<span class="fontdeliv_p">'.$section->language->lang013.'</span>'; 
            break;
        default: 
            $delivery_status = '<span class="fontdeliv_n">'.$section->language->lang004.'</span>';
    }
    unset($delivery);
}


// ### Информация об оплате ###
if ($order_paid) {
    $payment_name = ($order->name_payment != '') ? $order->name_payment : $section->language->lang038;
    $payment_sum = se_formatMoney($order->transact_amount, $order->transact_curr);
    $transact_id = $order->accounts;                   
}      

?>