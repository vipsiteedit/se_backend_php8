<?php

$area_id = 0;
if (isRequest('id')) {
    $area_id = getRequest('id',1);//htmlspecialchars($_GET['id'], ENT_QUOTES);
}
//Основная таблица для параметров
$save_name = 0;
if (!isRequest('save_name')) {
    $save_name = 1;
    $tbl = new seTable("forum_area","fa");
    $res = $tbl->find($area_id);
    unset($tbl);
    if (!empty($res)) {
        $res = htmlspecialchars($res['name'], ENT_QUOTES);
    }
} else {
    $area_id   = getRequest('aid',1);//], ENT_QUOTES);
    $area_name = getRequest('area_name', 3);//];
    $area_name = (empty($area_name)) ? '' : $area_name;
    //если не указано имя раздела
    if (!empty($area_name)) {
        if ($area_id == 0) {
            $tbl = new seTable("forum_area","fa");
            $tbl->select("max(id) AS id, max(order_id) AS oid");
            $max    = $tbl->fetchOne();
            unset($tbl);
            $maxid  = $max['id']+1;
            $maxord = $max['oid']+1;
            $tbl = new seTable("forum_area","fa");
            $tbl->id = $maxid;
            $tbl->order_id = $maxord;
            $tbl->name = $area_name;
            $tbl->lang=$lang;
            $tbl->save();
            unset($tbl);
            Header("Location:  ".seMultiDir()."/$_page/");
            exit();
        } else {
            $tbl = new seTable("forum_area","fa");
            $tbl->find($area_id);
            $tbl->name = $area_name;
            $tbl->lang=$lang;
            $tbl->save();
            Header("Location:  ".seMultiDir()."/$_page/");
            exit();
        }
    }
}

?>