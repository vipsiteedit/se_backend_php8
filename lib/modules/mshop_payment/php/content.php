<?php

// Список неоплаченных заказов
$ORDER_PAYEELIST = '';
$order = new seShopOrder();
$order->select('id');
$order->where('id_author=?', $id_author);
$order->andWhere("`status` IN ('N','K')");
$order->orderby('id');
$orderlist = $order->getList();
//$result=se_db_query("select id,status from `shop_order` where `id_author`='$id_author'
//AND (`status`='N' OR `status`='K') ORDER BY `id` ASC");
$maxid = count($orderlist);
$i = 1;
$this_id = 0;
$is_sel = false;
foreach($orderlist as $line) {
    $this_id = $line['id'];
    $selected = '';
    if ($ORDER_ID > 0) {
        if ($ORDER_ID == $line['id']) {
            $selected = "SELECTED";
            $is_sel = true;
        }
    } else if ($i == $maxid) {
        $ORDER_ID = $line['id'];
        $selected = "SELECTED";
        $is_sel = true;
    }
    $str_order = $line['id'];
    while (strlen($str_order) < 6) {
        $str_order = "0" . $str_order; 
    }
    $ORDER_PAYEELIST .= '<OPTION value="' . $line['id'] . '" ' . $selected.'>' . $str_order . '</OPTION>';
    $i++;
}
// Если номер заказа не найден через запрос
if (!$is_sel) {
    $ORDER_ID = $this_id;
}
// Список способов оплаты
$payees = new seUserAccount();
$SHOP_PAYEE_LIST = "";
$PAYEE_RES = 0;
$PAYEE_OUT = 0;
$PAYEE_IN = 0;
$PAYEE_DATE = '';
$payees->select('`in_payee`, `out_payee`, `curr`, `operation`, `entbalanse`, date_payee');
$payees->where('user_id IN (?)', seUserId());
$payees->orderby('date_payee', 1);
$payeelist = $payees->getList();          
foreach($payeelist as $line) {
    $k = se_Money_Convert(1, $line['curr'], $basecurr, $line['date_payee']);
    if ($PAYEE_DATE == '') {
        $PAYEE_DATE = date("d.m.Y", strtotime($line['date_payee']));
    }
    if ($line['operation'] == 0) {
        $PAYEE_RES = $line['entbalanse'] * $k;
        break;
    }
    $PAYEE_IN += $line['in_payee'] * $k;
    $PAYEE_OUT += $line['out_payee']* $k;
}
unset($payees); 
$PAYEE_RES = se_formatMoney($PAYEE_RES + $PAYEE_IN - $PAYEE_OUT, $basecurr);
$PAYEE_IN = se_formatMoney($PAYEE_IN, $basecurr);
$PAYEE_OUT = se_formatMoney($PAYEE_OUT, $basecurr);
$payment = new seTable('shop_payment');//seShopPayment();
$payment->select('logoimg, name_payment, startform, id, type, blank');
$payment->where("lang='?'", $lang);
$payment->andWhere("active='Y'");
$payment->orderby('sort');
$paylist = $payment->getlist();
unset($payment);
$dataarr = array();
foreach ($paylist as $res) {
    $res['note'] = se_macrocomands($res['startform'], $res['id'], $ORDER_ID, $basecurr, $lang);
    $res['linkblank'] = '/'.$_page.'/merchant/blank/';
    if (!empty($res['logoimg'])) { 
        $res['logoimg'] = '/images/' . $lang . '/shoppayment/' . $res['logoimg'];
    }
    $res['order_payee'] = $ORDER_ID;
    $dataarr[] = $res;
}
// Выводим в шаблон
$__data->setList($section, 'objects', $dataarr);
?>