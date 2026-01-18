<?php

$tbl = new seTable("forum_users", "fu");
$all = $tbl->getList();
unset($tbl);
foreach ($all as $v) {
    $__data->setItemList($section, 'users', $v);
}
$tbl = new seTable("forum_forums","ff");
$rf = $tbl->getList();
unset($tbl);
foreach ($rf as $forum) {
    $__data->setItemList($section, 'forum_sel', 
        array(
            'id' => $forum['id'],
            'name' => $forum['name']
        )
    ); 
}
$date = getdate();
$time1 = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
$time7 = mktime(0, 0, 0, $date['mon'], $date['mday']-7, $date['year']);
$time30 = mktime(0, 0, 0, $date['mon']-1, $date['mday'], $date['year']);
$time60 = mktime(0, 0, 0, $date['mon']-2, $date['mday'], $date['year']);
$time90 = mktime(0, 0, 0, $date['mon']-3, $date['mday'], $date['year']);
$time180 = mktime(0, 0, 0, $date['mon']-6, $date['mday'], $date['year']);
$time365 = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']-1);

// 09

?>