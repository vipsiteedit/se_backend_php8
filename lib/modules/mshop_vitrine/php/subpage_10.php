<?php

// **************************
// Сопутствующие товары
$acc = new seTable('shop_accomp', 'sa');   
if ($field == 'price') {
    $addselect = ',(sp.price * (SELECT `kurs` 
                                    FROM money m1 
                                    WHERE 
                                        (m1.name = sp.curr) AND 
                                        (m1.date_replace = (SELECT MAX(m2.date_replace) 
                                                                FROM money m2 
                                                                WHERE 
                                                                    m2.name = sp.curr)) 
                                    LIMIT 1)) as tprice';
} else {
    $addselect = '';
}
$acc->select('sp.*' . $addselect . ', sg.name as `group`');
$acc->innerjoin('shop_price sp', 'sa.id_acc = sp.id');
$acc->innerjoin('shop_group sg', '`sg`.`id`=`sp`.`id_group`');
$acc->Where("sg.lang = '?'", $lang);
$acc->andWhere("sp.enabled = 'Y'");
$acc->andWhere("sa.id_price = '?'", $viewgoods);
  //Сотрировка по полям
if ($field=='price'){
    $acc->orderby('tprice', $ask);
} else {
    $acc->orderby($field, $ask);
}
$acc->groupby("sp.id");
$SE_NAVIGATOR_ACC = $acc->pageNavigator($countnav);
$acclist = $acc->getList();
$style = false;
$accompcount = count($acclist);
foreach ($acclist as $goods) {
    $goods['style'] = ($style = !$style) ? "tableRowOdd" : "tableRowEven"; // четные
    $goods['image_prev'] = $im->getPrevPriceImage($goods['id']);     
    if ($old_format)
        $goods['linkshow'] = seMultiDir() . "/{$_page}/viewgoods/{$goods['id']}/";
    else
        $goods['linkshow'] = seMultiDir() . "/{$_page}/show/{$goods['code']}/";


    $__data->setItemList($section, 'accomps', parseItemSamePrice($section, $goods));
}

?>