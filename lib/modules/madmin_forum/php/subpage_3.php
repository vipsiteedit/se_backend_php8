<?php

$forum_id = 0;
$forname  = '';
$descr    = '';
$area     = "";
if (isRequest('id')) {
    $forum_id = getRequest('id', 1);
}
$save_name = 0;
//Основная таблица для параметров
if (!isRequest('save_name')) {
    $save_name = 1;
    if ($forum_id) {
        $tbl = new seTable("forum_forums","ff");
        $res = $tbl->find($forum_id);
        unset($tbl);
        $forname = stripslashes($res['name']);
        $descr = stripslashes($res['description']);
        $visible = $res['visible'];
        $area_id = $res['id_area'];
    } else if (isRequest('area_place')) {
        $area_id = getRequest('area_place', 1);
    }
    $tbl = new seTable("forum_area","fa");
    $tbl->where("lang='?'" , $lang);   // выводим только нужны язык
    $tbl->orderby('id');
    $result = $tbl->getList();
    unset($tbl);
    foreach ($result as $res) {
        if ($res['id'] == $area_id) {
            $res['sel'] = 'selected';
        } else {
            $res['sel'] = '';
        }
        $res['name'] = htmlspecialchars($res['name']);
        
        $__data->setItemList($section, 'arealist', $res);
    }
} else {
    $forum_id   = getRequest('fid', 1);
    $forum_name = getRequest('forum_name', 3);
    $descr      = getRequest('descript', 3);
    $status     = getRequest('status', 3);
    $area_id    = getRequest('area_id', 1);
    if (!empty($forum_name)) {
        // Обновляем данные форума
        if ($forum_id != 0) {
            $tbl = new seTable("forum_forums","ff");
            $tbl->find($forum_id);
            $tbl->name = $forum_name;
            $tbl->description = $descr;
            $tbl->visible = $status;
            $tbl->id_area = $area_id;
            $tbl->lang = $lang;
            $tbl->save();
            unset($tbl);
            Header("Location:  ".seMultiDir()."/$_page/");
            exit();
        } else {
            $tbl = new seTable("forum_forums","ff");
            $tbl->select("max(id) AS id, max(order_id) AS oid");
            $tbl->fetchOne();
            $maxid  = $tbl->id + 1;
            $maxord = $tbl->oid + 1;
            unset($tbl);
            $tbl = new seTable("forum_forums","ff");
            $tbl->id = $maxid;
            $tbl->order_id = $maxord;
            $tbl->name = $forum_name;
            $tbl->description = $descr;
            $tbl->visible = $status;
            $tbl->id_area = $area_id;
            $tbl->moderator = 0;
            $tbl->lang = $lang;
            $tbl->save();
            unset($tbl);
            Header("Location:  ".seMultiDir()."/$_page/");
            exit();
        }
    }
}

?>