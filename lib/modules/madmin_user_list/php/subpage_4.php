<?php

$tbl = new seTable("se_group", "g");
foreach ($tbl->getList() as $v) {
    $v['name'] = trim($v['name']);
    $v['title'] = trim($v['title']);
    $v['sel'] = intval(in_array($v['id'], $ugrp));
    $__data->setItemList($section, 'usergroups', $v);
}
unset($tbl);

?>