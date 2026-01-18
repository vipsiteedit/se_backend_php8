<?php

// **********************************    
// Аналоги
$countnav = (intval($section->parametrs->param235) > 0) ? intval($section->parametrs->param235) : 30;
if ($section->parametrs->param80 != 'Y') { // обычные аналоги 
    //Сотрировка по полям
    $same = new seTable('shop_sameprice', 'ss');   
    if ($field=='price') {
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
    $same->select('sp.*' . $addselect . ', sg.name as `group`');
    $same->innerjoin('shop_price sp', 'ss.id_acc = sp.id');
    $same->innerjoin('shop_group sg', 'sg.id = sp.id_group');
    $same->Where("sg.lang = '?'", $lang)->andWhere("sp.enabled = 'Y'");
    $same->andWhere("ss.id_price='?'", $viewgoods);
            //Сотрировка по полям
    if ($field == 'price') {
        $same->orderby('tprice', $ask);
    } else {
        $same->orderby($field, $ask);
    }
    $same->groupby("sp.id");
    $SE_NAVIGATOR_SAME = $same->pageNavigator($countnav);
    $samelist = $same->getList();
    if ($same->getListCount() == 0) {
        $same->select('sp.*' . $addselect . ', sg.name as `group`');
        $same->innerjoin('shop_price sp', 'ss.id_price=sp.id');
        $same->innerjoin('shop_group sg', 'sg.id=sp.id_group');
        $same->Where("sg.lang = '?'", $lang);
        $same->andWhere("sp.enabled = 'Y'");
        $same->andWhere("ss.id_acc = '?'", $viewgoods);
            //Сотрировка по полям
        if ($field=='price'){
            $same->orderby('tprice', $ask);
        } else {
            $same->orderby($field, $ask);
        }
        $same->groupby("sp.id");
        $SE_NAVIGATOR_SAME = $same->pageNavigator($countnav);
        $samelist = $same->getList();
    }         
} else { // Аналоги по полю id_analog
    if ((isset($id_analog)) && (!empty($id_analog))) {
        $price->select('sp.*, sg.name as `group`');
        $price->Where("sp.lang = '?'", $lang);
        $price->andWhere("sp.enabled = 'Y'");
        $price->leftjoin('shop_group sg', 'sg.id = sp.id_group');
        $price->andWhere("sp.id_analog = '?'", $id_analog);
        $price->andWhere("sp.id  <> ?", $viewgoods);
        $SE_NAVIGATOR_SAME = $price->pageNavigator($countnav);
        $samelist = $price->getList();
    }
}
$style = false;
$analogscount = count($samelist);
foreach ($samelist as $goods) { 
     // Выводим результаты
    $goods['style'] = ($style = !$style) ? "tableRowOdd" : "tableRowEven"; // четные
    $goods['image_prev'] = $im->getPrevPriceImage($goods['id']);     
    if ($old_format)
        $goods['linkshow'] = seMultiDir() . "/{$_page}/viewgoods/{$goods['id']}/";
    else
        $goods['linkshow'] = seMultiDir() . "/{$_page}/show/{$goods['code']}/";
    $__data->setItemList($section, 'analogs', parseItemSamePrice($section, $goods));
}          

?>