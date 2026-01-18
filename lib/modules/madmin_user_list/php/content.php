<?php

$and = 0;
if (isRequest('sorttype')) {
    $orderby = getRequest('sorttype', 3);
    $sortway = intval(!getRequest('sortway', 1));
}
if (isRequest('usergroup')) {
    $ugrp = array(getRequest('usergroup', 1));
    $search = $searchtype = $where = '';
}
$person->select("p.*, CONCAT_WS(' ', p.last_name, p.first_name, p.sec_name) AS fullname, u.username, u.is_active, u.last_login");
$person->innerjoin('se_user u', 'u.id = p.id');
$person->innerjoin("se_user_group ug", "ug.user_id = p.id");
$person->innerjoin("se_group g", "ug.group_id = g.id");
if (seUserGroup() != 3) {
    $person->where('id_up = ?', seUserId());
    $and = 1;
}
if (intval($ugrp[0])) {
    if ($and) {
        $person->andWhere('ug.group_id = ?', $ugrp[0]);
    } else {
        $person->where('ug.group_id = ?', $ugrp[0]);    
    }
    $and = 1;
}
$selecttype = array();
if (isRequest('searchRun')) {
    $searchtype = getRequest('searchtype');
    $search = addslashes(trim(getRequest('search', 3)));
    switch ($searchtype) {
        case 'login':
            $where = 'u.username'; 
            break;
        case 'name':
            $where = "CONCAT_WS(' ', p.last_name, p.first_name, p.sec_name)";
            break;
    }
}
if (!empty($where)) {
    if ($and) {
        $person->andWhere("$where LIKE '%$search%'");
    } else {
        $person->where("$where LIKE '%$search%'");
    }
}
if (!empty($orderby)) {
    $person->orderby($orderby, $sortway);
} else { 
    $person->orderby('reg_date', 1);
}
$person->groupby("p.id");
//echo $person->getSQL();
$userCount = 0;//$person->getListCount();//count($personlist);
//$SE_NAVIGATOR = $person->pageNavigator(intval($section->parametrs->param115));  
$personlist = $person->getlist();
$fl = false;
if ($searchtype != '') {
    $selecttype[$searchtype] = 'selected'; 
}
$pg = 1;
if (!empty($personlist)) {
    if (isRequest('pg')) {
        $pg = getRequest('pg', 1);
    }
    if ($pg < 1) {
        $pg = 1;
    }
    $begp = ($pg - 1) * intval($section->parametrs->param115);
    $endp = $begp + intval($section->parametrs->param115);  
    foreach ($personlist as $line) {
        ++$userCount;
        if (($userCount - 1 < $begp) || ($userCount - 1 >= $endp)) {
            continue;
        }
        $tbl = new seTable("se_group", "g");
        $tbl->innerjoin("se_user_group ug", "ug.group_id = g.id");
        $tbl->where("ug.user_id = '?'", $line['id']);
        foreach ($tbl->getList() as $v) {
            if (empty($v['name'])) {
                $v['name'] = $v['title'];
            }
            $__data->setItemList($section, 'usergroup' . $line['id'], $v);
        }
        unset($tbl);
        $line['style'] = intval($fl = !$fl);
        if (empty($line['fullname'])) {
            $line['fullname'] = '-- -- --';
        }
        $time = strtotime($line['reg_date']);
        $line['reg_date'] = date("j", $time) . ' ' . $monthLstnm[date("n", $time) - 1] . ' ' . date("Y", $time) . ' года'; 
        if ($line['last_login']) {
            $time = strtotime($line['last_login']);
            $line['last_login'] = date("j", $time) . ' ' . $monthLstnm[date("n", $time) - 1] . ' ' . date("Y", $time) . ' года';
        } else {
            $line['last_login'] = '-';
        } 
        $__data->setItemList($section, 'objects', $line);
    }
}
$printPages[] = array('COUNT' => $userCount, 'PAGE' => intval($section->parametrs->param115), 'NUM' => $pg, 'NAME' => 'pg');
?>