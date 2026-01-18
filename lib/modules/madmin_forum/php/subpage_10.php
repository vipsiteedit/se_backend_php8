<?php

$add_banan = 0;
if (isRequest('add_banan')) {
    $add_banan = 1;
    $user_name = getRequest('banan_to', 1);
    if (!empty($user_name)) {
        $tbl = new seTable("forum_users", "fu");
        $user = $tbl->find($user_name);
        if (!empty($user)){
            $tbl->enabled = 'N';
            $tbl->save();
            unset($tbl);
            Header("Location:  ".seMultiDir()."/$_page/$razdel/sub1/");
            exit();
        }
        unset($tbl);
    }
}

if (isRequest('del_banan')){
    $ban_list = getRequest("ban_list", 1);
    if (!empty($ban_list)) {
        $tbl = new seTable("forum_users", "fu");
        $tbl->find($ban_list);
        $tbl->enabled = 'Y';
        $tbl->save();
        unset($tbl);
    }    
    Header("Location:  ".seMultiDir()."/$_page/$razdel/sub1/");
    exit();
}

?>