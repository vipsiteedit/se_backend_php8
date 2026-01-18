<?php

$user_group = seUserGroup();

// Список неоплаченных заказов
$ORDER_PAYEELIST = '<option value="">-</option>';
$order = new seShopOrder();
$order->select('id');
$order->where('id_author=?', $id_author);
$order->andWhere("`status` IN ('N','K')");
$order->orderby('id');

$orderlist = $order->getList();

$maxid = count($orderlist);
$i = 1;
foreach($orderlist as $line) {
    $selected = '';
    if ($ORDER_ID > 0) {
        if ($ORDER_ID == $line['id']) {
            $selected = 'selected';
        }
    } 
    else if ($i == $maxid) {
        $ORDER_ID = $line['id'];
        $selected = 'selected';
    }
    $str_order = sprintf('%06d', $line['id']);
    $ORDER_PAYEELIST .= '<option value="' . $line['id'] . '" ' . $selected.'>' . $str_order . '</option>';
    $i++;
}

// Список способов оплаты
$payees = new seUserAccount();
$SHOP_PAYEE_LIST = '';
$PAYEE_RES = 0;
$PAYEE_OUT = 0;
$PAYEE_IN = 0;          
$PAYEE_DATE = '';
$payees->select('`in_payee`, `out_payee`, `curr`, `operation`, `entbalanse`, date_payee');
$payees->where('user_id = ?', $id_author);
$payees->orderby('date_payee', 1);
$payeelist = $payees->getList();
unset($payees);           
foreach($payeelist as $line) {
    $k = se_Money_Convert(1, $line['curr'], $basecurr, $line['date_payee']);
    if ($PAYEE_DATE == '') {
        $PAYEE_DATE = date('d.m.Y', strtotime($line['date_payee']));
    }
    if ($line['operation'] == 0) {
        $PAYEE_RES = $line['entbalanse'] * $k;
        break;
    }
    $PAYEE_IN += $line['in_payee'] * $k;
    $PAYEE_OUT += $line['out_payee']* $k;
}

$PAYEE_RES = se_formatMoney($PAYEE_RES + $PAYEE_IN - $PAYEE_OUT, $basecurr);
$PAYEE_IN = se_formatMoney($PAYEE_IN, $basecurr);
$PAYEE_OUT = se_formatMoney($PAYEE_OUT, $basecurr);

$spay = new plugin_payment($ORDER_ID);
$paylist = $spay->getList(0, false, true);

if ($section->parametrs->param8 != 'No' && $user_group)
    array_unshift($paylist, array('id' => 0, 'startform' => $section->parametrs->param10, 'name_payment' => $section->language->lang011));
    
foreach ($paylist as $res) {  
    $res['linkblank'] = '/' . $_page . '/merchant/blank/';
    if (!empty($res['logoimg'])) { 
        $res['logoimg'] = '/images/' . $lang . '/shoppayment/' . $res['logoimg'];
    }
    $res['order_payee'] = $ORDER_ID;
    $hash_pay = md5($res['id'] . '_' . $ORDER_ID . '3dfgvj');
    $res['invnum'] = seMultiDir() . '/' . $_page . '/invnum/' . $res['id'] . '_' . $ORDER_ID . '/' . $hash_pay . '/';
    $__data->setItemList($section, 'objects', $res);
} 
?>
