<?php

//Проверка, что пользователь является модератором этого форума
$tbl = new seTable ("forum_msg", "fm");
$msg_info = $tbl->find ($ext_id);
unset ($tbl);
$condition = intval (($msg_info ['to_whom'] == $uid) || 
                     (($msg_info ['id_users'] == $uid) &&
                      ($msg_info ['to_whom']))); 
if (!$msg_info ['to_whom']) {
    $tbl = new seTable ("forum_forums","ff");
    $tbl->select ("moderator, id_topic, ft.id_forums");
    $tbl->innerjoin ("forum_topic ft","ff.id = ft.id_forums");
    $tbl->innerjoin ("forum_msg fm","ft.id = fm.id_topic");
    $tbl->where ("fm.id = '?'", $ext_id);
    $priv = $tbl->fetchOne ();
    unset ($tbl);
    $condition = intval (($priv ['moderator'] == $uid) || $smod);  
}          
if ($condition && isRequest ('delmsg') && $uid) {
    $way = 0;
    $tbl = new seTable("forum_msg","fm");
    $tbl->where("`id`='?'", $ext_id);
    $tbl->deletelist();   
    unset($tbl);
  //Если в теме нет сообщений, удаляем ее
    if (!$msg_info ['to_whom']) {
        $id_topic = $priv['id_topic'];
        $tbl = new seTable("forum_msg","fm");
        $tbl->where("id_topic = '?'",$id_topic);
        $rt = $tbl->getList();
        unset($tbl);
        if (!count($rt)) {
            $tbl = new seTable("forum_topic","ft");
            $tbl->find($id_topic);
            $tbl->deletelist();
            $way = 1;
            unset($tbl);                
        }
    }
  //Удаление приаттаченных файлов
    $tbl = new seTable("forum_attached","fa");
    $tbl->where("id_msg = '?'", $ext_id);
    $ra = $tbl->getList();
    unset($tbl);
    foreach ($ra as $file) {
        @unlink("modules/forum/upload/".$file['file']);
        @unlink("modules/forum/upload/".utf8_substr($file['file'], 0, utf8_strlen($file['file'])-4)."-1".substr($file['file'], -4));
    }
    $tbl = new seTable("forum_attached","fa");
    $tbl->where("id_msg = '?'", $ext_id);
    $tbl->deletelist();
    unset($tbl);
    if ($msg_info ['to_whom']) {
        if ($msg_info ['to_whom'] == $uid) {
            Header("Location: ".seMultiDir()."/$_page/$razdel/sub3/user/$uid/");
        } else {
            Header("Location: ".seMultiDir()."/$_page/$razdel/sub3/puser/$uid/");
        }
    } else {
        if ($way) {
            Header("Location: ".seMultiDir()."/$_page/$razdel/sub2/?id=".$priv['id_forums']);
        } else {
            Header("Location: ".seMultiDir()."/$_page/$razdel/sub3/?id=$id_topic&".time());        
        }
    }
    exit();
} else if (isRequest('cancel')) {
    if ($msg_info ['to_whom']) {
        if ($msg_info ['to_whom'] == $uid) {
            Header("Location: ".seMultiDir()."/$_page/$razdel/sub3/user/$uid/");
        } else {
            Header("Location: ".seMultiDir()."/$_page/$razdel/sub3/puser/$uid/");
        }
    } else {
        Header("Location: ".seMultiDir()."/$_page/$razdel/sub3/?id=".$priv['id_topic']);
    }
    exit();
}
// 08

?>