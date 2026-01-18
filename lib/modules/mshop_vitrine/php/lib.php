<?php

//error_reporting(E_ALL); 
if (!function_exists('createtables')){
function createtables($path) {
    $tables = array('shop_param','shop_price_param');
    foreach($tables as $table){
        if (se_db_num_rows(se_db_query("SHOW COLUMNS FROM `{$table}`")) == 0){
        //se_yaml_to_sql($ymlfile, $outpath = '', $foreign_keys = true, $add_values = false)
           $sqllist = explode("###", se_yaml_to_sql($path.'/'.$table.'.yml', '', true));
           foreach($sqllist as $sql){
                se_db_query($sql); echo se_db_error();
           }
        }
    }
}}


if (!function_exists('getTreeShopGroup')){
// Выбирает id всех вложенных подгрупп группы $shopcatgr
// ** $shopcatgr - значение id группы, из которой выбираем подгруппы
// ** возвращает массив id-шников всех подгрупп
function getTreeShopGroup($shopcatgr){
    $list = array();
    $shgroup = new seShopGroup();
    $shgroup->select('id');
    $shgroup->where('upid=?', $shopcatgr);
    $glist = $shgroup->getList();    
    foreach($glist as $item){
       if ($item['id'])
          $list = array_merge($list, getTreeShopGroup($item['id']));
                                  
       $list[] = $item['id'];     
        // Перебираем деревья до встречи с группой
    }
    return $list;
}}


/*
if (!function_exists('getRootPrc')){
function getRootPrc($this_id, $price = '', $count = ''){
    $spp = new seTable('shop_price_param', 'spp');
    $spp->select('parent_id, price, count'); 
    $line = $spp->Find($this_id);
    if ($spp->price != '-1' && $spp->price != '' && $price == '') 
        $price = $spp->price; 
    if ($spp->count != '-1' && $spp->count != '' && $count == '') 
        $count = $spp->count;
    if (!empty($line['parent_id'])) {
        list($price, $count) = getRootPrc($line['parent_id'], $price, $count);
    }
    return array($price, $count);
}}
*/

if (!function_exists('getTreeParam')) { //  getTreeParam($section, 0, $goods['id'], 0, $goods['presence_count']);
    function getTreeParam($section, $parent_id =0, $price_id, $count, $i_param = 0, $setblock = true) {                                               
    // * Если $parent_id = 0, значит в базе его значение = null
    // Берем только первый тип параметра из данного уровня дерева
    // (а должен быть и так только один, если пользователь корректно задавал параметры)
        $sellist = '';
        $spp = new seTable('shop_price_param', 'spp');
        $spp->select('spp.param_id, sp.nameparam'); 
        $spp->innerjoin('shop_param sp', 'spp.param_id = sp.id');
        $spp->where("price_id = '?'", $price_id);
    
        if ($parent_id == 0) {
            $spp->andwhere('spp.parent_id IS NULL');
        } else {
            $spp->andwhere("spp.parent_id = '?'", $parent_id);
        }
        $spp->groupby('param_id');
        $sel_id = 1;
        $paramline = $spp->getlist();
        if (!empty($paramline)) {
            if (($parent_id == 0) && ($i_param == 0)) {
                unset($_SESSION['SHOP_VITRINE']['paramcount'][$price_id]);
                if ($setblock) {
                    $sellist .= '<div id="prm_' . $section->id . '_' . $price_id . '">';
                }
            }
            foreach ($paramline as $line) {
                $param_id = $line['param_id'];    
                $nameparam = $line['nameparam'];  
                if ($parent_id == 0) { 
                    $sellist .= '<div class="paramsLineBlock">';
                }
                // Список значений для заданного уровня иерархии
                $spp = new seTable('shop_price_param', 'spp');
                $spp->select('id, value, (SELECT COUNT(id) 
                                            FROM `shop_price_param` 
                                            WHERE 
                                                `parent_id` = spp.id) AS `cnt`'); 
                $spp->where("price_id = '?'", $price_id);
                if ($parent_id == 0) {
                    $spp->andwhere('parent_id IS NULL');
                } else {
                    $spp->andwhere("parent_id = '?'", $parent_id);
                }
                $spp->andwhere("param_id = '?'", $param_id);
                $spp->orderby('value');
                $spplist = $spp->getList();
                if (!empty($spplist)) {
                    $_SESSION['SHOP_VITRINE']['paramcount'][$price_id][$sel_id]++;
                    if ((intval($parent_id) == 0) && ($i_param == 0)) {
                        $ni_param = $sel_id * 100;
                    } else {
                        $ni_param = $i_param;
                        $sel_id = floor($ni_param / 100);
                    }    
                    $prm = new plugin_shopparams40(0);
                    $selected = ($_SESSION['SHOP_VITRINE']['selected'][$price_id][$sel_id]); // выбранный в предыдущий раз пункт данного селекта
                    $selected = $prm->getPreviousParamId($selected, $ni_param - ($sel_id * 100));
                    $first_next = false;
                    if (count($spplist) > 1) {
                        foreach ($spplist as $item) {
                            if (($item['cnt'] > 0) && ($selected == $item['id'])) {
                                $first_next = true;
                            }
                        }
                        $nameid = ($first_next) ? 'p' . $i_param . '_' . $price_id : 'addcartparam[]';
                        $sellist .= '<span class="goodsParam"><span class="goodsParamTitle">' . $nameparam . '</span>
                                    <select class="goodsParamSelect" name="' . $nameid . '" id="p' . $ni_param . '_' . $price_id . '"';
                        $sellist .= ' onChange="loadParam(\'' . $section->id . '\',' . ($ni_param + 1) . ',' . $price_id . ',this.value);';
                        $sellist .= '">';
                        foreach ($spplist as $item) {
                            if ($selected == '') { // Если не выбран никакой,
                                $selected = $item['id']; // тогда выбираем первый 
                            }
                            if ($selected != $item['id']) {
                                $sellist .= '<option value="' . $item['id'] . '">' . trim($item['value']) . '</option>';
                            } else {
                                $sellist .= '<option selected value="' . $item['id'] . '">' . trim($item['value']) . '</option>';
                                if (!$first_next) {
                                    $_SESSION['SHOP_VITRINE']['selected'][$price_id][$sel_id] = $item['id'];
                                }
                            }   
                        }
                        $sellist .= '</select><span id="p' . $ni_param . '_' . $price_id . 'dots" style="display: none">
                                    <img src="[module_url]preloader_foto.gif" width="14" height="14"></span></span>';
                        list($sel) = getTreeParam($section, $selected, $price_id, $count, $ni_param + 1); 
                        // Субселект на первый выбор селекта
                        $sellist .= $sel;
                    } else {
                        $sellist .= '<span class="goodsParam"><span class="goodsParamTitle">' . $nameparam . '</span>';
                        foreach ($spplist as $item) {
                            $sellist .= '<span class="goodsParamValue"> ' . trim($item['value']) . '</span>';
                            $sellist .= '<input type="hidden" name="addcartparam[]" value="' . $item['id'] . '">';
                            //$selected = $_SESSION['SHOP_VITRINE']['selected'][$price_id][$sel_id] = $item['id'];           
                            $selected = $item['id'];
                            break;
                        }
                        $sellist .='</span>';
                        list($sel) = getTreeParam($section, $selected, $price_id, $count, $ni_param + 1); 
                        $sellist .= $sel;
                    }   
                    unset($spp);
                }
                $sel_id++;
                if ($parent_id == 0) { 
                    $sellist .= '</div>';
                }
            }   
            if (($parent_id == 0) && ($i_param == 0) && $setblock) {
                $sellist .= '</div>';
            }
        }
        if ((intval($parent_id) == 0) && !empty($_SESSION['SHOP_VITRINE']['selected'][$price_id])) {
            $paramlist = 'param:' . join(',', $_SESSION['SHOP_VITRINE']['selected'][$price_id]);
            $prm = new plugin_shopparams40($paramlist);
            list($price, $count) = $prm->getRootPrice();
        }                                               
        return array($sellist, $price, $count);       
    }
}

if (!function_exists('getSelects')){
function getSelects($section, $param_id, $price_id, $price, $count, $i_param = 0)
{
// * Если $parent_id = 0, значит в базе его значение = null

    // Список значений для заданного уровня иерархии
    $spp = new seTable('shop_price_param', 'spp');
    $spp->select('id, value, price, count'); 
    $spp->where("price_id='?'", $price_id);
    if ($param_id == 0)
        $spp->andwhere('parent_id IS NULL');
    else
        $spp->andwhere("parent_id='?'", $param_id);
    //$spp->andwhere("param_id='?'", $param_id);
    $spp->orderby('value');

    $spplist = $spp->getList();
    $sellist = '';
    if (!empty($spplist))
    {
        $first = '';
        foreach($spplist as $item)
        {
            if ($first == '') { 
                if ($item['price'] != '' && $item['price'] != '-1')
                    $price = $item['price'];  
                if ($item['count'] != '' && $item['count'] != '-1')
                    $count = $item['count']; 
                $first = $item['id']; 
            }
            $sellist .= '<option value="'.$item['id'].'">'.trim($item['value']).'</option>';
        }
    
    }
    return array($sellist, $price, $count, $first);
}}
  
if (!function_exists('getPreviousParamsState')) {
function getPreviousParamsState($price_id) {               
    // Определяем все бывшие состояния параметров товара на основе последнего сохраненного параметра
    // и записывает их в сессию
    $nlist = $_SESSION['SHOP_VITRINE']['selected'][$price_id];
   // $n='65';
    if (!empty($nlist)) { 
        // Обнулим значение количества параметров
      $i = 1;  
      foreach($nlist as $n){  
        $_SESSION['SHOP_VITRINE']['paramcount'][$price_id][$i] = 0;
        // Очищаем предыдущие значения
       // unset($_SESSION['SHOP_VITRINE']['selected'][$price_id][$i]);
        // Восстановим состояние всех параметров данного товара
        $arr = array();

        $spp = new seTable('shop_price_param');
        $j = 100;
        while ($n) {
            $arr[$j++] = $n;
            $spp->select('parent_id');
            $spp->Find($n);
            $n = $spp->parent_id;
        } 
        // Развернем массив
        $arr = array_reverse($arr);
        //$_SESSION['SHOP_VITRINE']['selected'][$price_id][$i] = $arr;
       // print_r($arr);
        unset($spp);
        $i++;
      }     
    }
}}


if (!function_exists('getShopTextCount')) {
    function getShopTextCount($section, $goods, $pcount) {
/*
        if ($goods['presence_count'] && ($goods['presence_count'] > 0)) {
            return $goods['presence_count'];
        } else if (($goods['presence_count'] < 0) || ($goods['presence_count'] == '')) {
            return (empty($goods['presence'])) ? $section->parametrs->param69 : $goods['presence'];
        } else if ($goods['presence']) {
            return $goods['presence'];
        } else {
            return '--'; // прячем кнопку
        }
//*/
        $goodStyle = 1;
        if (($goods['presence_count'] != '') && ($goods['presence_count'] != -1)) {
            if (($pcount == '') || ($pcount == -1) || ($goods['presence_count'] < $pcount)) {
                $pcount = $goods['presence_count'];
            }
            if (!$pcount) {
                if (!empty($goods['presence'])) {
                    $pcount = $goods['presence'];
                } else {
                    $pcount = '--';
                }
                $goodStyle = 0;
            } else {
                $pcount .= '&nbsp;' . $goods['measure'];            
            }
        } else if (($pcount == '') || ($pcount == -1)) {
            if (!empty($goods['presence'])) {
                $pcount = $goods['presence'];
            } else {
                $pcount = $section->parametrs->param69;
            }
        } else {
            $pcount .= '&nbsp;' . $goods['measure'];  
        }
        return array($pcount, $goodStyle);
    }
}

if (!function_exists('myFormatMoney')) {
    function myFormatMoney($section, $money, $name) {
        $tbl = new seTable('money_title', 'mt');
        $tbl->where("mt.name = '$name'");
        $tbl->fetchOne();
        $submoney = $money - intval($money);
        if ($section->parametrs->param242 == 'N') {
            if ($submoney > 0) {
                return $tbl->name_front . ' ' . number_format($money, 2, '.', ' ') . ' ' . $tbl->name_flang;
            } else {
                return $tbl->name_front . ' ' . number_format($money, 0, '', ' ') . ' ' . $tbl->name_flang;
            }
        } else if ($submoney > 0) {
            return $tbl->name_front . ' ' . number_format($money, 2, '.', '') . ' ' . $tbl->name_flang;
        } else {
            return $tbl->name_front . ' ' . number_format($money, 0, '', '') . ' ' . $tbl->name_flang;
        }     
//        return se_formatMoney($money, $name);
    }
}
if (!function_exists('getShopActualPrice')) {
    function getShopActualPrice($section, $goods, $paramprice = 0) {
        $pricemoney = $_SESSION['pricemoney'];
        if ($section->parametrs->param225 == seUserGroupName()) { 
            $header = $section->parametrs->param227;
            $goodsprice = se_MoneyConvert($goods['price_opt_corp'], $goods['curr'], $pricemoney);
        } else if ($section->parametrs->param224 == seUserGroupName()) {   
            $header = $section->parametrs->param122;     
            $goodsprice = se_MoneyConvert($goods['price_opt'], $goods['curr'], $pricemoney);
        } else {
            $header = $section->parametrs->param121;
            $goodsprice = se_MoneyConvert($goods['price'], $goods['curr'], $pricemoney);
        }                                          //  echo $goodsprice;
        $paramprice = se_MoneyConvert($paramprice, $goods['curr'], $pricemoney);
        $discountproc = 0;
        if (($goods['special_price'] == 'Y') || ($goods['discount'] == 'Y')) {
            $shopdiscount = new plugin_shopDiscount40($goods['id']);
            $discountproc = $shopdiscount->execute();
            $discountproc = $goodsprice * ($discountproc / 100);
        }
        if ($section->parametrs->param243 == 'Y') {
            $goodsprice = ceil($goodsprice);
            $paramprice = ceil($paramprice);
            $discountproc = floor($discountproc);
        }                    
        $goodsprice += $paramprice;
        $realprice = $goodsprice - $discountproc;
        $price = ''; 
        if (($discountproc > 0) && ($section->parametrs->param113 == 'Y')) {
          $price .= '
          <span style="text-decoration:line-through;" class="old_price" id="old_price_'.$section->id.'_'.$goods['id'].'">'.
            myFormatMoney($section, $goodsprice, $pricemoney).
          '</span>';
        }           
                                     
        $price .= '<span class="new_price" id="price_'.$section->id.'_'.$goods['id'].'">'.
                         myFormatMoney($section, $realprice, $pricemoney).
                  '</span>';
                
        return array($price, $header, $realprice, $goodsprice);
    }
}

if (!function_exists('parseItemSamePrice')) {
function parseItemSamePrice($section, $goods){
    // === Строим прорисовку параметров товара ===
    getPreviousParamsState($goods['id']); // Для восстановления прежних состояний селектов
    list($goods['params'], $addprice, $maxcount) = 
        getTreeParam($section, 0, $goods['id'], $goods['presence_count']);
    if ($maxcount != '' && $maxcount != -1 && $goods['presence_count'] > $maxcount)
        $goods['presence_count'] = $maxcount;
        
    $goods['count'] = getShopTextCount($section, $goods);
    // ==== конец прорисовки параметров товара ===
    $goods['special_price']  = '';
    //$goods['price'] += $addprice;
    list($goods['price'],) = getShopActualPrice($section, $goods, $addprice);
    //$goods['price'] += $addprice;
    return $goods;
}} 
?>