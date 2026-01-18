<?php

// если кнопочка "Да" не нажата и "Да, удалить вместе с темами" не нажата
$yes = 0;
if ( (!isRequest('yes')) && (!isRequest('yes_all')) ) {
    $yes = 1;
    $forum_id = getRequest('id', 1);
    $tbl = new seTable("forum_forums", "ff");
    $res = $tbl->find($forum_id);
    unset($tbl);
    $res = $res['name'];
} else {
    if (!isRequest('yes_all')) { // Если не нажата кнопка "Удалить вместе с темами"
        $forum_id = getRequest('forum_id', 1);
        $tbl = new seTable("forum_topic", "ft");
        $tbl->where("id_forums = '?'", $forum_id);
        $res = $tbl->fetchOne();
        unset($tbl);
        if (empty($res)) { // Если в форуме есть темы, т.е. он не пустой
        // Выводим вопрос "Удалить форум вместе со всеми темами"
            $tbl = new seTable("forum_forums", "ff");
            $tbl->find($forum_id);
            $tbl->delete();
            unset($tbl);
            Header("Location:  ".seMultiDir()."/$_page/");
            exit();
        }
    } else { // Удаляем форум вместе со всеми темами
        $forum_id = getRequest('forum_id', 1);
     // Удаляем темы, принадлежащие данному форуму
        $tbl = new seTable("forum_topic", "ft");
        $tbl->where("id_forums = '?'", $forum_id);
        $result = $tbl->getList();
        unset($tbl);
        if (!empty($result)) {
            foreach ($result as $res) {
   // Удаляем сообщения из данной темы
                $tbl = new seTable("forum_msg", "fm");
                $tbl->where("id_topic = '?'", $res['id']);
                $tbl->deletelist();
//                $result_msg = $tbl->fetchOne();
                unset($tbl);
//                if (!empty($result_msg)) {
//                    $tbl = new seTable("forum_msg", "fm");
//                    $tbl->where("id_topic = '?'", $res['id']);
//                    $tbl->deletelist();
//                    unset($tbl);
//                }
   // Удаляем тему
                $tbl = new seTable("forum_topic", "ft");
                $tbl->find("id = '?'", $res['id']);
                $tbl->delete();
                unset($tbl);      
            }     
        }
     // Наконец удаляем форум, он пустой
        $tbl = new seTable("forum_forums", "ff");
        $tbl->find($forum_id);
        $tbl->delete();
        unset($tbl);
        Header("Location:  ".seMultiDir()."/$_page/");
        exit();  
    }
}

?>