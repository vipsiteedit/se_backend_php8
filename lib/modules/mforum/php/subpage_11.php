<?php

//Ищем тему
$ext_code = getRequest('code',1);//], ENT_QUOTES);
$tbl = new seTable("forum_topic","ft");
$topic = $tbl->find($ext_id);
unset($tbl);
$fmsg = 1;
if (md5($topic['email'].$topic['id_users']."topic") == $ext_code) {
    $fmsg = 0;
    $topic_name = $topic['name'];
    $tbl = new seTable("forum_topic","ft");
    $tbl->find($ext_id);
    $tbl->email = '';
    $tbl->save();
    unset($tbl);
}
// 11

?>