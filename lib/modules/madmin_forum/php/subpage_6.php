<?php

// если кнопочка "Да" не нажата
$yes = 0;
if (!isRequest('yes')) {
    $yes = 1;
    $area_id = getRequest('id', 1);
    $tbl = new seTable("forum_area", "fa");
    $res = $tbl->find($area_id);
    unset($tbl);
    $res = $res['name'];
} else {
    $area_id = getRequest('area', 1);
    $tbl = new seTable("forum_forums", "ff");
    $tbl->where("id_area = '?'", $area_id);
    $tbl->andwhere("lang='?'" , $lang);   // выводим только нужны язык
    $res = $tbl->fetchOne();
    unset($tbl);
    if (empty($res)) {
        $tbl = new seTable("forum_area", "fa");
        $tbl->find($area_id);
        $tbl->delete();
        unset($tbl);
        Header("Location:  ".seMultiDir()."/$_page/");
        exit();
    }
}

?>