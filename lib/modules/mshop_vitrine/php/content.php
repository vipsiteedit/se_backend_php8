<?php


$price->select('sp.*, sm.id AS `analogs`');
$price->innerjoin('shop_group sg', 'sp.id_group=sg.id');
$price->leftjoin('shop_price_param spp', 'spp.price_id=sp.id');
$price->leftjoin('shop_param spar', 'spar.id=spp.param_id');
$price->leftjoin('shop_sameprice sm', '(sm.id_price=sp.id) OR (sm.id_acc=sp.id)');
$price->leftjoin('shop_group_price sgp', 'sgp.price_id=sp.id');
$price->where("sg.lang = '?'", $lang);
$price->andWhere("sp.enabled = 'Y'");
$price->andWhere("sg.active = 'Y'");
if ($section->parametrs->param254 == 'Y') {// Залежавшийся товар
    $price->andWhere("sp.unsold = 'Y'");
}  

if ($section->parametrs->param279 == 'Y') {
    $scount = (int)$section->parametrs->param280; // Число выводимых объектов
    if ($scount < 1)
        $scount = 1;
    
    $price->innerjoin("shop_leader sl", "sp.id = sl.id_price");
}
else
{             
// Дополняем запрос в зависимости от поисковых данных
  // Придти может от mshop_searhlist или от mshop_groups
  $shopsearch = $_SESSION['CATALOGSRH']['searchname'];    
  $search_for = $_SESSION['CATALOGSRH']['search_for'];
  if (!empty($shopsearch)) { // Пришло от mshop_searchlist
                   
    if ($search_for == 'for_lot')
      $price->andWhere("(sp.name LIKE '%{$shopsearch}%')");
    elseif ($search_for == 'for_articul')
      $price->andWhere("(sp.article LIKE '%{$shopsearch}%')");  
    else
      $price->andWhere("((sp.note LIKE '%{$shopsearch}%') OR (sp.text LIKE '{$shopsearch}%'))");
  }
  elseif (!empty($_SESSION['SHOP_VITRINE']['shopsearch'])) { // Пришло от mshop_groups
    $shopsearch = $_SESSION['SHOP_VITRINE']['shopsearch'];
    $shopsearch = str_replace('"','\"',htmlspecialchars_decode($shopsearch,ENT_QUOTES));
    $shopsearch = str_replace("'","\'",htmlspecialchars_decode($shopsearch,ENT_QUOTES));
    $price->andWhere("((sp.article LIKE '{$shopsearch}%') OR (sp.name LIKE '{$shopsearch}%') OR 
                        (sp.note LIKE '{$shopsearch}%') OR (sp.text LIKE '{$shopsearch}%'))");
  }
             
  $manufacturer = $_SESSION['CATALOGSRH']['manufacturer'];
  if (!empty($manufacturer))
    $price->andWhere("(sp.manufacturer LIKE '%{$manufacturer}%')"); 

  // Поиск товара по параметрам  (строка запроса "Мощность=12.5..13.00|Производительность=5..6")
if (!empty($_SESSION['SHOP_VITRINE']['shopsearchparams'])) {        
    $shopsearchparams = $_SESSION['SHOP_VITRINE']['shopsearchparams']; 
    $shopsearchparams = str_replace(',', '.', $shopsearchparams);
    // Расшифровываем поисковую строку
    $paramarray = array();
    if (strpos($shopsearchparams, '|')) {
        $paramslist = explode('|', $shopsearchparams);
    }
    foreach($paramslist as $itemparam) {
        list($paramname, $paramarray) = explode('=', $itemparam);    
            $paramarray = explode('..', $paramarray);
        if (!empty($paramarray[0]) && !empty($paramarray[1])) {        
            $price->andWhere("(REPLACE(spp.value,',','.') BETWEEN {$paramarray[0]} AND {$paramarray[1]})");
        }
        elseif (!empty($paramarray[0]) && empty($paramarray[1])) {         
            $price->andWhere("(REPLACE(spp.value,',','.') >= {$paramarray[0]})");
        }
        elseif (empty($paramarray[0]) && !empty($paramarray[1])) {         
            $price->andWhere("(REPLACE(spp.value,',','.') <= {$paramarray[1]})");
        }
        if (!empty($paramarray)) {
            $price->andWhere("spar.nameparam='?'", $paramname);
        }
    }    
    unset($paramarray, $paramslist, $shopsearchparams);
}
                 
if ($isSearchlist) {   
    if ($searchingroup) {// Модуль расширенного поиска
        $price->andwhere("((sp.id_group IN (".$searchingroup.")) OR (sgp.group_id IN (".$searchingroup.")))");
    }
    elseif (($section->parametrs->param60 != 'Y') && empty($_SESSION['CATALOGSRH']['manufacturer'])) // при невыбранной группе запрещено показывать
        $price->where('false');                                                   // и не выбран производитель 
}    
else {   
    if ($shopcatgr) // Модуль каталога 
    { 
        $price->andwhere("((sp.id_group IN (".$shopcatgr.")) OR (sgp.group_id IN (".$shopcatgr.")))"); }    
    elseif ($section->parametrs->param60 != 'Y') // Не выбрана группа и запрещено показывать при невыбранной группе
        $price->where('false'); // Ничего не показываем
}


$price_from = $price_to = $otherPg = 0; 
if (isRequest('search_price_from')) {
    if (getRequest('search_price_from', 2) > 0) { 
        $price_from = getRequest('search_price_from', 2);//se_MoneyConvert(getRequest('search_price_from', 2), $pricemoney, se_baseCurrency());
        $otherPg = 1;
    }
    $_SESSION['SHOP_PARAMETRS']['search_price_from'] = $price_from;
} else if (!empty($_SESSION['SHOP_PARAMETRS']['search_price_from'])) {
    if (($price_from = $_SESSION['SHOP_PARAMETRS']['search_price_from'])) {
        $otherPg = 1;
    }
}
if (isRequest('search_price_to')) { 
    if (getRequest('search_price_to', 2) > 0) {
        $price_to = getRequest('search_price_to', 2);//se_MoneyConvert(getRequest('search_price_to', 2), $pricemoney, se_baseCurrency());
        $otherPg = 1;
    }
    $_SESSION['SHOP_PARAMETRS']['search_price_to'] = $price_to;
} else if (!empty($_SESSION['SHOP_PARAMETRS']['search_price_to'])) {
    if (($price_to = $_SESSION['SHOP_PARAMETRS']['search_price_to'])) {
        $otherPg = 1;
    }
}
} // конец if ($section->parametrs->param279 != 'Y')


if ($section->parametrs->param279 == 'Y') { // Показывать только спецпредложения
    if ($section->parametrs->param281 == 'A') { //Сорировка по артикулу
        $price->orderby("article", $ask);
    }
}
else
    $price->orderby($field, $ask);    

$price->groupby("sp.id");

// при выборе конкретной группы не выводились страницы
// echo $price->getSQL();
if ($otherPg) {
    $curunit = 0;
    $beg = ($curpg - 1) * $strlimit + 1;
    $end = $beg + $strlimit;
    $pricelist = $price->getList();
} else {
    $SE_NAVIGATOR = $price->pageNavigator($strlimit);
    $pricelist = $price->getList();
}
$goodscount = count($pricelist);

$datalist = array();
unset($section->objects);
$style = true;
$nchar = intval($section->parametrs->param237);
if (!$nchar) {
    $nchar = 60;
}

// ==== Выборка только (10) значений в случае режима спецпредложения и сортировка их в случайном порядке, если надо. 
if ($section->parametrs->param279 == 'Y') { // Режим спецпредложения

    $dataarr = array();
    
    if (!empty($pricelist)) {
        // $ncount - сколько записей выводить
        if ($scount > $goodscount) { // если число выводимых объектов больше общего числа записей
            $ncount = $goodscount;  // $ncount = числу всех записей
        } else {
            $ncount = $scount; // $ncount = числу выводимых объектов
        }  
        
        // Заполнение массива $rand_keys значениями массива $rarr (вперемежку или нет).
        $rand_keys = range(0, $goodscount - 1);

        if ($section->parametrs->param281 == "R") { // Сортировка в случайном порядке   
            srand((float)microtime() * 10000);
            shuffle($rand_keys);
        }
    
        for ($i = 0; $i < $ncount; $i++) {
            $lin = $pricelist[intval($rand_keys[$i])]; 
            $dataarr[$i] = $lin;
        }
        
        unset($pricelist);
        $pricelist = $dataarr;
    }
}
// ==================================
           
foreach ($pricelist as $goods) {
   // list($goods['params'], $addprice, $maxcount) = getTreeParam($section, 0, $goods['id'], $goods['presence_count']);
    //$goods['price'] += $addprice;
 //   list($goods['newprice'], $goods['priceheader'], $realprice) = getShopActualPrice($section, $goods, $addprice);
    
    list($goods['params']) = getTreeParam($section, 0, $goods['id'], $goods['presence_count']);
                                
    // --- Округление и сепараторы ---
    $rounded = ($section->parametrs->param243 == 'Y'); // округление

    if ($section->parametrs->param276 == 'Y') // сепаратор между 1 000 000
        $separator = ' ';
    else
        $separator = '';
    // -------------------------------
                                   
    if ($section->parametrs->param225 == seUserGroupName()) { // optcorp
        $ptype = 1;
        $goods['priceheader'] = $section->parametrs->param227;
    } else if ($section->parametrs->param224 == seUserGroupName()) { // optovik  
        $ptype = 2;
        $goods['priceheader'] = $section->parametrs->param122;
    } else {                                    // розничный покупатель
        $ptype = 0;
        $goods['priceheader'] = $section->parametrs->param121;
    }                        
                   
    $price_id = $goods['id'];    
    $plugin_amount = new plugin_shopAmount40($goods['id'], '', $ptype, 1, 
                                           'param:' . join(',', $_SESSION['SHOP_VITRINE']['selected'][$price_id]), $pricemoney);
    
    $maxcount = $plugin_amount->getPresenceCount();
    
    $realprice =  $plugin_amount->getPrice(true);
    
    $price_disc = $plugin_amount->showPrice(true, // discounted
                                           $rounded, // округлять ли цену
                                           $separator); // разделять ли пробелами 000 000 
    $pr_without_disc = $plugin_amount->showPrice(false, // discounted
                                          $rounded, // округлять ли цену
                                          $separator); // разделять ли пробелами 000 000

    $discount = $plugin_amount->getDiscount();
                  
    $goods['newprice'] = '';       
    if (($discount > 0) && ($section->parametrs->param113 == 'Y')) // отображать поле "Старая цена"
        $goods['newprice'] .= '
          <span style="text-decoration:line-through;" class="old_price" id="old_price_'.$section->id.'_'.$goods['id'].'">'.
            $pr_without_disc.'</span>';
    $goods['newprice'] .= '<span class="new_price" id="price_'.$section->id.'_'.$goods['id'].'">'.$price_disc.'</span>';                         

    unset($plugin_amount);
    
    
    if ($otherPg) {
        if (($price_from > $realprice) || (($price_to < $realprice) && $price_to)) {
            continue;
        }
        ++$curunit;
        if (($curunit < $beg) || ($curunit >= $end)) {
            continue;
        }
    }
    $goods['compare'] = '';
    foreach($_SESSION['SHOPVITRINE']['COMPARE'] as $id=>$line){
        if ($line == $goods['id']){
            $goods['compare'] = 'checked';   break;
        }
    }
    $goods['style'] = ($style = !$style) ? "tableRowOdd" : "tableRowEven"; // четные

    $goods['img_alt'] = htmlspecialchars($goods['img_alt']);
    if (empty($goods['img_alt']))
        $goods['img_alt'] = htmlspecialchars($goods['name']);
            
    if (utf8_strlen($goods['name']) > $nchar) {
        $goods['name'] = se_LimitString($goods['name'], $nchar, ' ...');
    }
    if (($section->parametrs->param207 != '-1') && (utf8_strlen($goods['note']) > intval($section->parametrs->param207))) {
      $goods['note'] = se_limitstring($goods['note'], intval($section->parametrs->param207));
    }
    $im = new plugin_ShopImages40();
    $goods['image_prev'] = $im->getPrevPriceImage($goods['id']);

    if ($old_format)
        $goods['linkshow'] = seMultiDir() . "/{$_page}/viewgoods/{$goods['id']}/";
    else
        $goods['linkshow'] = seMultiDir() . "/{$_page}/show/{$goods['code']}/";

    // === Строим прорисовку параметров товара ===
    getPreviousParamsState($goods['id']); // Для восстановления прежних состояний селектов
/*
    if (($maxcount != '') && ($maxcount != -1) && ($goods['presence_count'] > $maxcount)) {
        $goods['presence_count'] = $maxcount;
    }
//*/
    list($goods['count'], $goodStyle) = getShopTextCount($section, $goods, $maxcount);

    if (($goods['analogs'] && ($section->parametrs->param80 != 'Y')) || (!empty($goods['id_analog']) && ($section->parametrs->param80 == 'Y'))) {
        $goods['analogs'] = $section->parametrs->param236;
    } else {
        $goods['analogs'] = '';
    }        
    // Блокировка кнопки "Заказать"
    if ($section->parametrs->param233 == 'Y') {
//        $goods['show_addcart'] = ($goods['count'] != '--') ? 'display: inline' : 'display: none';
        $goods['show_addcart'] = ($goodStyle) ? 'display: inline' : 'display: none';
    } else {
        $goods['show_addcart'] = 'display: inline';
    }
    
    $__data->setItemList($section, 'objects', $goods);
} 
?>