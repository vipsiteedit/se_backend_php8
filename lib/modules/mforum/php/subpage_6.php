<?php

// Проверка сессии
$ext_sid = getRequest('sid',3);
$tbl = new seTable("forum_session","fs");
$tbl->where("sid = '?'",$ext_sid);
$s = $tbl->fetchOne();
unset($tbl);
$showpage = 1;
$error = 0;
$error_exists = 0;
if ($s['id_users'] != $uid) {
    $showpage = 0;
} else {
    if (isRequest('delete')) {
  //Удаляем картинку из базы
        $tbl = new seTable("forum_users","fu");
        $tbl->find($uid);
        $tbl->img = '';
        $tbl->save();
        unset($tbl);
  //Удаляем файлы картинки
        @unlink("modules/forum/images/$uid.gif");
        @unlink("modules/forum/images/$uid.jpg");
        @unlink("modules/forum/images/$uid.jpeg");
        @unlink("modules/forum/images/$uid.png");
        Header("Location: ".seMultiDir()."/$_page/$razdel/sub5/?".time());
        exit();
    }
}
// 06

?>