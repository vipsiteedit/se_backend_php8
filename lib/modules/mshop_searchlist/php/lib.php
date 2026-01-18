<?php

if (!function_exists('ShowCatalog_array_search_key')){
function ShowCatalog_array_search_key($val, $arr) {
    $res = array();
    if (is_array($arr))
        foreach ($arr as $key => $value) if ($value == $val) $res[] = $key;
    return $res;
}}

if (!function_exists('shop_catalog_group_cn')){
function shop_catalog_group_cn($ParentID, $arr_cat, $countgr) {
    $arr_k = ShowCatalog_array_search_key($arr_cat['id'][$ParentID], $arr_cat['upid']);
    if (!empty($arr_k))
        foreach ($arr_k as $k => $val)
            $countgr += shop_catalog_group_cn($val, $arr_cat, $arr_cat['cn'][$val]);
    return $countgr;
}}

if (!function_exists('get_groups_with_goods')){
// #### Функция возаращает массив групп и всей иерархии вложенных подгрупп, имеющих товары
// Параметры:
//     $node_id - номер id данной группы;
//     $arrGroups - указатель на массив, в который складываются
//                  уникальные значения id подгрупп иерархии.
function get_groups_with_goods($node_id, &$arrGroups) {
  $arrSgr = array(); // Массив групп, в которых есть товары (`typegroup`='s')
  $row_sgr = se_db_fields_item('shop_group', "id='$node_id'", 'typegroup');
  if ($row_sgr['typegroup'] == 's')
    if (!in_array($node_id, $arrSgr))  // если id группы нет в массиве,
        $arrSgr[] = $node_id;          // то помещаем ее туда
  // Выбираем слой подгрупп, непосредственно подчиняющихся данной группе
  $ssql = "SELECT `id` FROM `shop_group` WHERE `upid`='".$node_id."'";
  $sql_res = se_db_query($ssql);
  if (se_db_num_rows($sql_res) > 0)
    // работаем с каждой выбранной подгруппой слоя
    while ($row_group = se_db_fetch_array($sql_res)) {
      if (!in_array($row_group['id'], $arrGroups)) { // если id группы нет в массиве,
        $arrGroups[] = $row_group['id'];          // то помещаем ее туда,
        $arrSgr = array_merge($arrSgr, get_groups_with_goods($row_group['id'], $arrGroups));
                                        // затем находим массив подгрупп, имеющих товары
                                        // и объединяем с массивом $arrSgr
      } // end if
    } // end while
  return $arrSgr;
}
}

if (!function_exists('category_list_sub')) {
    function category_list_sub(&$groups, $startGr = 0, $selCtgr = 0, $all = 0, $blank = '') {
//        $groups = array();
        $tbl = new seTable("shop_group");
        $tbl->select("id, name");
        if ($startGr) {
            $tbl->where("upid = '$startGr'");
        } else {
            $tbl->where("((upid = '0') OR (upid IS NULL))");
        }
        $tbl->andWhere("lang = '?'", se_getLang());
        $res = $tbl->getList();
        unset($tbl);
        if (count($res)) {
            foreach ($res as $v) {
                if (!in_array($v['id'], $groups)) {
                    $groups[] = $v['id'];
                    if ($selCtgr == $v['id']) {
                        $groups[] = '<option selected value="' . $v['id'] . '">' . $blank . $v['name'] . '</option>';
                    } else {
                        $groups[] = '<option value="' . $v['id'] . '">' . $blank . $v['name'] . '</option>';
                    }
                    $tbl = new seTable("shop_group", "sg");
                    $tbl->where("upid = '?'", $v['id']);
                    if ($tbl->fetchOne() && $all) {
                        $groups = category_list_sub($groups, $v['id'], $selCtgr, $all, '&nbsp;&nbsp;&nbsp;&nbsp;');
                    }
                    unset($tbl);
                }
            }
        }
        return $groups;
    }
}
?>