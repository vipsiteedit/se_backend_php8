<?php

// добавление статусов
$add = 0;
if (isRequest('add')) {
    $add = 1;
    $status_name = getRequest('status_name', 3);
    if (!empty($status_name)) {
        $tbl = new seTable("forum_status", "fs");
        $tbl->select("max(id) AS id");
        $tbl->fetchOne();        
        $maxid  = $tbl->id + 1;
        unset($tbl);
        $tbl = new seTable("forum_status", "fs");
        $tbl->id = $maxid;
        $tbl->name = $status_name;
        $tbl->save();
        unset($tbl);
        Header("Location:  ".seMultiDir()."/$_page/$razdel/sub1/");
        exit();
    }
}
// удаляем статус
$del = 0;
if (isRequest('del')) {
    $del = 1;
    $status_id = getRequest('status_list', 1);
    if (($status_id != 1)) {
        $tbl = new seTable("forum_users", "fu");
        $tbl->where("id_status = '?'", $status_id);
        $userid = $tbl->getList();
        unset ($tbl);
        foreach ($userid as $v) {
            $tbl = new seTable("forum_users", "fu");
            $tbl->find($v['id']);
            $tbl->id_status = 1;
            $tbl->save();
            unset($tbl);
        }
        $tbl = new seTable("forum_status", "fs");
        $tbl->find($status_id);
        $tbl->deletelist();
        unset($tbl);
        Header("Location:  ".seMultiDir()."/$_page/$razdel/sub1/");
        exit();
    }
}
$edit = 0;
if (isset($_POST['edit'])) {
    $edit = 1;
    $status_id = $_POST['status_list'];
    $status_name = getRequest('status_name', 3);
    if (!empty($status_name)) {
        $tbl = new seTable("forum_status", "fs");
        $tbl->find($status_id);
        $tbl->name = $status_name;
        $tbl->save();
        unset($tbl);
        Header("Location:  ".seMultiDir()."/$_page/$razdel/sub1/");
        exit();
    }
}
$save_status = 0;
if (isRequest('save_status')){
    $save_status = 1;
    $suser_name = getRequest('user_name', 1);
    if (!empty($suser_name)) {
        $status = getRequest('status_list', 1);
        $tbl = new seTable("forum_users", "fu");
        $user = $tbl->find($suser_name);
//        unset($tbl);
        if (!empty($user)) {
//            $tbl = new seTable("forum_users", "fu");
//            $tbl->find($user['id']);
            $tbl->id_status = $status;
            $tbl->save();
            unset($tbl);
            Header("Location:  ".seMultiDir()."/$_page/$razdel/sub1/");
            exit();
        }
        unset($tbl);
    }
}
$del_status = 0;
if (isRequest('del_status')){
    $del_status = 1;
    $duser_name = getRequest('user_name', 3);
    if (!empty($duser_name)) {
        $tbl = new seTable("forum_users", "fu");
        $tbl->where("nick = '?'", $duser_name);
        $user = $tbl->fetchOne();
        unset($tbl);
        if (!empty($user)) {
            $user_id = $user['id'];
            $tbl = new seTable("forum_users", "fu");
            $tbl->find($user_id);
            $tbl->id_status = 1;
            $tbl->save();
            unset($tbl);
            Header("Location:  ".seMultiDir()."/$_page/$razdel/sub1/");
            exit();
        }
    }
}

?>