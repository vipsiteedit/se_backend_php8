<?php

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

if(!function_exists('recalculatGroup')){
function recalculatGroup($groupId)
{
    $price_count = new seTable('shop_price');
    $price_count->select('count(*) as `cnt`');
    $price_count->where('id_group=?', $groupId);
    $price_count->fetchOne();
    $count = $price_count->cnt;
    
    $group_count = new seTable('shop_group');
    $group_count->update('scount',"'$count'");
    $group_count->where('id=?', $groupId);
    $group_count->save();
}
}
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

function translitIt($str) 
{
    $tr = array(
        "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g","Д"=>"d","Е"=>"e","Ж"=>"zh","З"=>"z","И"=>"i","Й"=>"j","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n","О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t","У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch","Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"y","Ь"=>"","Э"=>"e","Ю"=>"yu","Я"=>"ya",
        "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh","з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"","ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"," "=>"-"
    );
    return strtr($str,$tr);
}

?>