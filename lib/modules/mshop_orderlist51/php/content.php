<?php

list($orderlist, $MANYPAGE) = $plugin_order->getUserOrders((int)$section->parametrs->param35);     

foreach ($orderlist as $line) {
    
    switch ($line['status']) {
        case 'Y': 
            $line['status'] = '<span class="fontstatus_y">' . $section->language->lang001 . 
                      ' <span class="fontstatus_yd">(' . date("d.m.Y", @strtotime($line['date_payee'])) . ')</span></span>'; 
            break;
        case 'K': 
            $line['status'] = '<span class="fontstatus_k">' . $section->language->lang002 . '</span>'; 
            break;
        case 'P': 
            $line['status'] = '<span class="fontstatus_p">' . $section->language->lang005 . '</span>'; 
            break;
        case 'N':
        default: 
            $line['status'] = '<span class="fontstatus_n">' . $section->language->lang003 . '</span>';
    }
    
    $line['delivery'] = "-";

    if (!empty($line['delivery_type'])) {
        switch ($line['delivery_status']) {
            case 'Y':
                $line['delivery'] = '<span class="fontdeliv_y">' . $section->language->lang006 . ' <span class="fontdeliv_yd">('.date("d.m.Y", @strtotime($line['delivery_date'])).')</span></span>'; 
                break;
            case 'P':
                $line['delivery'] = '<span class="fontdeliv_p">' . $section->language->lang013 . '</span>'; 
                break;
            case 'N':
            default:
                $line['delivery'] = '<span class="fontdeliv_n">' . $section->language->lang004 . '</span>';
        }
    } 
    
    $line['contract'] = '000000';
    if (!empty($line['date']))
        $line['contract'] = date('dmy', strtotime($line['date']));
    $line['contract'] .= '/' . $line['number'];
    
    $line['date'] = date("d.m.Y", strtotime($line['date_order']));
    
    $line['id_order'] = sprintf("%06u", $line['idorder']); 
    
    $summ = se_MoneyConvert(($line['price_tovar'] + $line['delivery_payee'] - $line['discount']), $line['curr'], $showcurr, $line['date_order']);
    $line['summ'] = se_formatMoney($summ, $showcurr, '&nbsp;', $round_price);
    
    $__data->setItemList($section, 'objects', $line);
}
?>