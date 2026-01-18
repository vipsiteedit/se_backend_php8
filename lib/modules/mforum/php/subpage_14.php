<?php

$fname = getRequest('file', 3);
$fname = str_replace("/", "", $fname);
$tbl = new seTable("forum_attached", "fa");
$tbl->where("md5(file) = '?'", $fname);
$file = $tbl->fetchOne();
unset ($tbl);
if (!empty($file)) {
    $fname = $file['file'];
    if (file_exists("modules/forum/upload/$fname")) {
//Удаляем старые ссылки
        if (file_exists("modules/forum/download/")) {
            $d = opendir(getcwd() . "/modules/forum/download/");
            while(($f = readdir($d))) {
                if (($f != '.') && ($f != '..') && is_file($f)) {
                    if (se_filemtime($f) < (time() - 3600)) {
                        unlink($f);
                    }
                }
            }
            closedir($d);
            if (file_exists(getcwd() . "/modules/forum/download/" . $file['realname'])) {
                unlink(getcwd() . "/modules/forum/download/" . $file['realname']);
            }
            se_symlink(getcwd() . "/modules/forum/upload/$fname", getcwd() . "/modules/forum/download/" . $file['realname']);
            $flink = $_SERVER["HTTP_HOST"] . "/modules/forum/download/" . $file['realname'] . '?' . time();
//Увеличиваем счетчик скачиваний
/*
// не работает из-за того что класс seTable ничего не может при отсутствии поля id в таблице
            $tbl = new seTable("forum_attached");
            $tbl->where("file = '?'", $fname);
            $tbl->fetchOne();
            $tbl->counter = $file['counter'] + 1;
            $tbl->save();
            unset($tbl);
/*/
            se_db_query("UPDATE forum_attached
                            SET
                                counter = counter + 1
                            WHERE
                                file = '$fname'");            
//*/
            Header("Location: ". $flink);
            exit();
        }
    }
}
// 14

?>