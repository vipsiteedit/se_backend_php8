<?php

// ################# ВЫВОД РЕЗУЛЬТАТА ПОИСКА ##################
//if ((isset($_POST['search'])) || ($searchflag == 'quicksearch'))
//if (($category1 != '-1') || ($word != '')) {// Если пустая строка, и все категории, то не выводить таблицу;
                                     // Если пустая строка и выбрана категория, то выводим товары этой категории;
                                          // Если не пустая строка, то выводим товары;
                                          // Если не пустая строка и выбрана категория, то выводим товары.

// ########## ОБРАБОТКА ЗАПРОСОВ ТАБЛИЦЫ SHOP_PRICE    
// ### Выводим список товаров для выбранной группы
$search = 0;
if (isRequest('search') || isRequest('shopcatgr') || ($searchflag == 'quicksearch') || (count($_SESSION['CATALOGSRH']) > 1)) {
    $search = 1;
   // xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    $class = "tableRowEven";

    $prices = new seTable('shop_price', 'sp');
    $prices->select('sp.*, sg.name as ggroup, ss.id as analog, ssp.id_group AS spGroup, ssp.id_price AS spPrice, ssp.newproc');
    $prices->innerjoin('shop_group sg', 'sg.id = sp.id_group');
    $prices->innerjoin("money m", "(m.name = sp.curr) AND (m.date_replace = (SELECT MAX(m2.date_replace)
                                                                                FROM money m2
                                                                                WHERE
                                                                                    m2.name = m.name))");
    $prices->leftjoin('shop_sameprice ss', 'sp.id = ss.id_price');
    $prices->leftjoin("shop_special ssp", "(ssp.id_price = sp.id) OR 
                                            ((ssp.id_group = sp.id_group) AND 
                                             ((SELECT COUNT(ssp2.id)
                                                FROM shop_special ssp2
                                                WHERE
                                                    ssp2.id_price = sp.id) = '0'))");
    $prices->where("sg.lang = '$lang'");
    $prices->andWhere("sp.enabled = 'Y'" . $searchby);
    $prices->orderby($field, $ask);
    $prices->groupby("sp.id");
//echo $prices->getSQL();
    $goods_count = $prices->getListCount();
    $MANYPAGE = $prices->pageNavigator(30);
    $goodslist = $prices->getList();
    if (!empty($goodslist)) {
        foreach ($goodslist as $goods) {
            if ($class != "tableRowOdd") {
                $class = "tableRowOdd";
            } else {
                $class = "tableRowEven";
            }
            $goods['itemstyle'] = $class;
            $goods['buyshow'] = $goods['presence_count'];
            if (($goods['presence_count'] == '') || ($goods['presence_count'] == -1)) {
                $goods['presence_count'] = $section->parametrs->param90;
                $goods['buyshow'] = 1;
            }
            $goodsprice = se_MoneyConvert($goods['price'], $goods['curr'], $pricemoney);
            $goods['newprice'] = '';
            if (!empty($goods['analog'])) {
                $goods['analog'] = $section->language->lang044;
            } else {
                $goods['analog'] = "--";
            }
            $discountproc = 0;
            if ($goods['special_price'] == 'Y' || $goods['discount'] == 'Y') {
                $shopdiscount = new plugin_shopDiscount40($goods['id']);
                $discountproc = $shopdiscount->execute();
                unset($shopdiscount);
            }
            $goods['link'] = $page_vitrine . 'show/'. $goods['code'].'/';
            if ($discountproc > 0) {
                $discount = $goodsprice * ($discountproc / 100);
                $goods['newprice'] = '<span class="new_price">' . se_formatMoney($goodsprice - $discount , $pricemoney) . '</span>';
                $goods['oldprice'] = '<span style="text-decoration:line-through;" class="old_price">' . se_formatMoney($goodsprice, $pricemoney) . '</span>';
            } else { 
                $goods['oldprice'] = '<span class="new_price">' . se_formatMoney($goodsprice, $pricemoney) . '</span>';
            }
            $__data->setItemList($section, 'objects', $goods);
        }
    }
// ################### ВЫВОДИМ РЕЗУЛЬТАТ ПОИСКА ####################
}
$orderb['group'] =  (($orderby=='ga')?se_sqs("orderby","gd"):se_sqs("orderby","ga")).'#productlst';
$orderb['article'] =  (($orderby=='aa')?se_sqs("orderby","ad"):se_sqs("orderby","aa")).'#productlst';
$orderb['name'] =  (($orderby=='na')?se_sqs("orderby","nd"):se_sqs("orderby","na")).'#productlst';
$orderb['manuf'] =  (($orderby=='ma')?se_sqs("orderby","md"):se_sqs("orderby","ma")).'#productlst';
$orderb['price'] =  (($orderby=='pa')?se_sqs("orderby","pd"):se_sqs("orderby","pa")).'#productlst';
?>