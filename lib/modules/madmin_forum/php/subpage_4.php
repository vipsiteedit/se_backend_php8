<?php

$order_id = getRequest('order',1);
$forum_id = getRequest('id', 1);
$action   = getRequest('do', 1);
$tbl = new seTable("forum_forums", "ff");
$area_id = $tbl->find($forum_id);
unset($tbl);
$area_id = $area_id['id_area'];

// находит минимальное (если $do=1) либо максимальное ($do=2) значение порядка для форума в текущем разделе
function firstlast($id, $do) {
    $tbl = new seTable("forum_forums","ff");
    $res = $tbl->find($id);
    unset($tbl);
    $area_id = $res['id_area'];
    if ($do == 1) {
        $tbl = new seTable("forum_forums","ff");
        $tbl->select("min(order_id) AS oid");
        $tbl->where("id_area = '?'", $area_id);
        $tbl->andwhere("lang='?'" , $lang);   // выводим только нужны язык
        $res = $tbl->fetchOne();
        unset($tbl);
        return $res['oid'];
    }
    if ($do == 2) {
        $tbl = new seTable("forum_forums","ff");
        $tbl->select("max(order_id) AS oid");
        $tbl->where("id_area = '?'", $area_id);
        $tbl->andwhere("lang='?'" , $lang);   // выводим только нужны язык
        $res = $tbl->fetchOne();
        unset($tbl);
        return $res['oid'];
    }
}

// поднять
if ($action == 1) {
    if ($order_id != firstlast($forum_id, 1)) {
        $tbl = new seTable("forum_forums","ff");
        $tbl->where("order_id < '$order_id'");
        $tbl->andWhere("id_area = '?'", $area_id);
        $tbl->andwhere("lang='?'" , $lang);   // выводим только нужны язык
        $tbl->orderby("order_id", 1);
        list($res) = $tbl->getList();
        unset($tbl);
        $prev_forum_id = $res['id'];
        $prev_ord_id   = $res['order_id'];
        $tbl = new seTable("forum_forums", "ff");
        $tbl->find($forum_id);
        $tbl->order_id = $prev_ord_id;
        $tbl->save();
        unset($tbl);
        $tbl = new seTable("forum_forums", "ff");
        $tbl->find($prev_forum_id);
        $tbl->order_id = $order_id;
        $tbl->save();
        unset($tbl);
        Header("Location:  ".seMultiDir()."/$_page/");
        exit();
    }
}


// опустить
if ($action == 2) {
    if ($order_id != firstlast($forum_id, 2)) {
        $tbl = new seTable("forum_forums", "ff");
        $tbl->where("order_id > '$order_id'");
        $tbl->andWhere("id_area = '?'", $area_id);
        $tbl->andwhere("lang='?'" , $lang);   // выводим только нужны язык
        $tbl->orderby("order_id");
        list($res) = $tbl->getList();
        unset($tbl);
        $next_forum_id = $res['id'];
        $next_ord_id   = $res['order_id'];
        $tbl = new seTable("forum_forums", "ff");
        $tbl->find($forum_id);
        $tbl->order_id = $next_ord_id;
        $tbl->save();
        unset($tbl);
        $tbl = new seTable("forum_forums", "ff");
        $tbl->find($next_forum_id);
        $tbl->order_id = $order_id;
        $tbl->save();
        unset($tbl);        
        Header("Location:  ".seMultiDir()."/$_page/");
        exit();
    }
}

?>