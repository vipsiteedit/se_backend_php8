<?php

$tbl = new seTable("forum_status","fs");
$tbl->orderby('id');
$result = $tbl->getList();
unset($tbl);
foreach ($result as $status) {
    $__data->setItemList($section, 'statuslist', $status);
}
$tbl = new seTable("forum_users","fu");
$tbl->where("enabled = 'N'");
$result = $tbl->getList();
unset($tbl);
foreach ($result as $ban) {
    $__data->setItemList($section, 'banlist', $ban);
}
$tbl = new seTable("forum_users","fu");
$tbl->where("enabled = 'Y'");
$result = $tbl->getList();
unset($tbl);
foreach ($result as $user) {
    $__data->setItemList($section, 'users', $user);
}

?>