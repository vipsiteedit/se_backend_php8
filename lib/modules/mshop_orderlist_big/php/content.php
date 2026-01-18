<?php

$pagen = intval($section->parametrs->param35);
$orders = new seTable('shop_order', 'so');
$orders->select("so.id as `idorder`, so.id_author, so.date_order, so.date_payee, so.discount, so.curr, 
                    so.status, so.delivery_payee, so.delivery_type, so.delivery_status, so.delivery_date, 
                    (SELECT SUM((st.price-st.discount)*st.count) 
                        FROM shop_tovarorder st 
                        WHERE 
                            st.id_order = `idorder`) AS `price_tovar`, sc.date, sc.number");
$orders->leftjoin('shop_contract sc', 'sc.id_order = so.id');
$orders->where('so.id_author = ?', $id_author);
$orders->andWhere("so.is_delete <> '?'", 'Y');
$orders->orderby('idorder', 1);
$orders->groupby('so.id');
$MANYPAGE = $orders->pageNavigator($pagen);        
$orderlist = $orders->getlist();

foreach ($orderlist as $line) {
    
    switch ($line['status']) {
        case 'Y': 
            $line['status'] = '<span class="fontstatus_y">' . $section->language->lang001 . 
                      ' <span class="fontstatus_yd">(' . date("d.m.Y", @strtotime($line['date_payee'])) . ')</span></span>'; 
            break;
        case 'N': 
            $line['status'] = '<span class="fontstatus_n">' . $section->language->lang003 . '</span>'; 
            break;
        case 'K': 
            $line['status'] = '<span class="fontstatus_k">' . $section->language->lang002 . '</span>'; 
            break;
        case 'P': 
            $line['status'] = '<span class="fontstatus_p">' . $section->language->lang005 . '</span>'; 
            break;
        default: 
            $line['status'] = '<span class="fontstatus_n">' . $section->language->lang003 . '</span>';
    }
    
    $line['delivery'] = "-";

    if (!empty($line['delivery_type'])) {
        switch ($line['delivery_status']) {
            case 'N':
                $line['delivery'] = '<span class="fontdeliv_n">' . $section->language->lang004 . '</span>'; 
                break;
            case 'Y':
                $line['delivery'] = '<span class="fontdeliv_y">' . $section->language->lang006 . ' <span class="fontdeliv_yd">('.date("d.m.Y", @strtotime($line['delivery_date'])).')</span></span>'; 
                break;
            case 'P':
                $line['delivery'] = '<span class="fontdeliv_p">' . $section->language->lang013 . '</span>'; 
                break;
            default:
                $line['delivery'] = '<span class="fontdeliv_n">' . $section->language->lang004 . '</span>';
        }
    } 
    
    $line['contract'] = '000000/'.$line['number'];
    $kdate = explode("-", $line['date']);
    if (!empty($kdate[0]) && !empty($kdate[1]) && !empty($kdate[2])) {
        $line['contract'] = $kdate[2] . $kdate[1] . substr($kdate[0], 2, 2) . '/' . $line['number'];
    }
    
    $line['date'] = date("d.m.Y", strtotime($line['date_order']));
    
    $line['id_order'] = sprintf("%06u", $line['idorder']); 
    
    $summ = se_MoneyConvert(($line['price_tovar'] + $line['delivery_payee'] - $line['discount']), $line['curr'], $showcurr, $line['date_order']);
    $line['summ'] = se_formatMoney($summ, $showcurr, '&nbsp;', $round_price);
    
    $__data->setItemList($section, 'objects', $line);
}
?>