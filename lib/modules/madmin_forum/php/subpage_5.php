<?php

$order_id = getRequest('order', 1);
$area_id  = getRequest('id', 1);
$action   = getRequest('do', 1);

if ($action == 1){
    $tbl = new seTable("forum_area", "fa");
    $tbl->select("min(order_id) AS oid");
    list($res) = $tbl->getList();
    unset($tbl);
    if ($res['oid'] != $order_id) {
        $tbl = new seTable("forum_area", "fa");
        $tbl->where("order_id < '$order_id'");
        $tbl->orderby("order_id", 1);
        list($res) = $tbl->getList();
        unset($tbl);
        $area_ord_prev = $res['order_id'];
        $area_id_prev  = $res['id'];
        $tbl = new seTable("forum_area", "fa");
        $tbl->find($area_id);
        $tbl->order_id = $area_ord_prev;
        $tbl->save();
        unset($tbl);
        $tbl = new seTable("forum_area", "fa");
        $tbl->find($area_id_prev);
        $tbl->order_id = $order_id;
        $tbl->save();
        unset($tbl);
        Header("Location:  ".seMultiDir()."/$_page/");
        exit();
    }

}

if ($action == 2){
    $tbl = new seTable("forum_area", "fa");
    $tbl->select("max(order_id) AS oid");
    list($res) = $tbl->getList();
    unset($tbl);
    if ($res['oid'] != $order_id) {
        $tbl = new seTable("forum_area", "fa");
        $tbl->where("order_id > '$order_id'");
        $tbl->orderby('order_id');
        list($res) = $tbl->getList();
        unset($tbl);
        $area_ord_next = $res['order_id'];
        $area_id_next  = $res['id'];
        $tbl = new seTable("forum_area", "fa");
        $tbl->find($area_id);
        $tbl->order_id = $area_ord_next;
        $tbl->save();
        unset($tbl);
        $tbl = new seTable("forum_area", "fa");
        $tbl->find($area_id_next);
        $tbl->order_id = $order_id;
        $tbl->save();
        unset($tbl);
        Header("Location:  ".seMultiDir()."/$_page/");
        exit();
    }
}

?>