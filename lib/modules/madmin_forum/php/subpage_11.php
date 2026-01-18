<?php

if (isRequest('id')) {
    $forum_id = getRequest('id', 1);
    $tbl = new seTable("forum_forums", "ff");
    $tbl->where("lang='?'" , $lang);   // выводим только нужны язык
    $tbl->orderby('id');
    $result = $tbl->getList();
    unset($tbl);
    foreach ($result as $forums) {
        if ($forum_id == $forums['id']) {
            $forums['sel'] = 'selected';
        } else {
            $forums['sel'] = '';
        }  
        $__data->setItemList($section, 'forums', $forums);
    }
    $tbl = new seTable("forum_topic", "ft");
    $tbl->where("id_forums = '?'", $forum_id);
    $result = $tbl->getList();
    unset($tbl);
    foreach ($result as $topics) {
        $__data->setItemList($section, 'topics', $topics);
    }                   
}

if (isRequest('checks')) {
    $forum_id = getRequest('forum_list', 1);
    foreach ($_POST['checks'] as $v) { 
        $tbl = new seTable("forum_topic", "ft");
        $tbl->find($v);
        $tbl->id_forums = $forum_id;
        $tbl->save();
        unset($tbl);
    }
    Header("Location:  ".seMultiDir()."/$_page/");
    exit();
}

?>