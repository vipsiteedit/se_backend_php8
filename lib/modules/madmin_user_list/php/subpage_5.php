<?php

if (seUserGroup() != 3) {
    return;
}
if (isRequest('group')) {
    $ugrp = array(getRequest('group', 1));
}
$currid = &$ugrp[0];
$curpg = $othpg = 1;
$ingroup = array();
$othertype = 0;
$title = $name = '';
$level = 1;
if (isRequest('otherGroup')) {
    $otherGroup = getRequest('otherGroup', 1);
    $othertype = getRequest('othertype', 1);
}
if (isset($_GET['ingroup'])) {
    $ingroup = $_GET['ingroup'];
}
if (isRequest('editGroup')) {
    $name = addslashes(htmlspecialchars(trim($_GET['grpnm'])));
    $title = addslashes(htmlspecialchars(trim($_GET['grpttl'])));
    $level = getRequest('levelAccess', 1);
    if (empty($name) || empty($title)) {
        $errorRes = $section->language->lang103;
    } else if (!preg_match("/^[a-zA-Z][a-zA-Z\d_]*$/i", $name)) {
        $errorRes = $section->language->lang104;
    } else {
        $tbl = new seTable('se_group', 'g');
        $tbl->where("g.name = '$name'");
        $tbl->andWhere("g.id != '$currid'");
        $gr = $tbl->fetchOne();
        unset($tbl);
        if (!empty($gr)) {
            $errorRes = $section->language->lang105;
        } else {
            $tbl = new seTable('se_group');
            if ($currid) {
                $tbl->find($currid);
            }
            $tbl->name = $name;
            $tbl->title = $title;
            $tbl->level = $level;
            $res = $tbl->save();
            unset($tbl);
            if (!$currid) {
                $currid = $res;
                $_SESSION['ADMINUSRCH']['errorRes'] .= $section->language->lang106;
            } else {
                $_SESSION['ADMINUSRCH']['errorRes'] .= $section->language->lang107;
            }
            header("Location: /$_page/$razdel/sub5");
            exit();
        }
    }
    if (($errorRes != '') && (isRequest('oldgroup'))) {
        $currid = getRequest('oldgroup', 1);
    }
}
if (isRequest('add') && $currid) {
    se_db_query("INSERT INTO se_user_group(user_id, group_id)
                    SELECT DISTINCT g.user_id, '$otherGroup' 
                        FROM se_user_group g
                        WHERE
                            (g.group_id = '$currid') AND
                            (g.user_id IN ('" . join("','", $ingroup) . "')) AND
                            (NOT g.user_id IN (SELECT g2.user_id
                                                FROM se_user_group g2
                                                WHERE
                                                    g2.group_id = '$otherGroup'))");
    if ($othertype == 1) {
        se_db_query("DELETE FROM se_user_group
                        WHERE 
                            (group_id = '$currid') AND 
                            (user_id IN ('" . join("','", $ingroup) . "'))");
        $_SESSION['ADMINUSRCH']['errorRes'] .= $section->language->lang108; 
    } else {
        $_SESSION['ADMINUSRCH']['errorRes'] .= $section->language->lang109;
    }
    $ingroup = array();
    header("Location: /$_page/$razdel/sub5");
    exit();
}
if (getRequest('delUser', 1) && $currid) {
    $tbl = new seTable('se_user_group', 'ug');
    $tbl->select("DISTINCT ug.user_id AS id");  //?
    $tbl->where("ug.group_id = '$currid'");
    $tbl->andWhere("ug.user_id IN 
                        (SELECT ug2.user_id
                            FROM se_user_group ug2
                            WHERE
                                (ug2.group_id != ug.group_id) AND
                                (ug.user_id IN ('" . join("','", $ingroup) . "')))");
    $badUsers = array();
    foreach ($tbl->getList() as $v) {
        $badUsers[] = $v['id'];
    }
    unset($tbl);
    $tbl = new seTable('se_user_group');
    $tbl->where("user_id IN ('" . join("','", $badUsers) . "')");
    $tbl->andWhere("group_id = '$currid'");
    $tbl->deletelist();
    unset($tbl);
    if (count($ingroup) != count($badUsers)) {
        $_SESSION['ADMINUSRCH']['errorRes'] .= $section->language->lang110;
    }
    unset($tbl);
    header("Location: /$_page/$razdel/sub5");
    exit();
}
if (isRequest('delGroup') && $currid) {
    $tbl = new seTable('se_user_group', 'ug');
    $tbl->select("COUNT(ug.user_id) AS myUsers");
    $tbl->where("ug.group_id = '$currid'");
    $tbl->andWhere("NOT ug.user_id IN 
                        (SELECT ug2.user_id
                            FROM se_user_group ug2
                            WHERE
                                ug2.group_id != ug.group_id)");
    $tbl->fetchOne();
    if ($tbl->myUsers) {
        $errorRes = $section->language->lang110;
    } else {
        $tbl = new seTable('se_user_group');
        $tbl->where("group_id = '$currid'");
        $tbl->deletelist();
        unset($tbl);
        $tbl = new seTable('se_group'); 
        $tbl->find($currid);
        $tbl->deletelist();
        unset($tbl);
        $ugrp = array();
        header("Location: /$_page/$razdel/sub5");
        exit();
    }
}
$gUsers = 0; 
$tbl = new seTable('se_group', 'g');
if ($currid) {
    $tbl->find($currid);
} else {
    $tbl->where("g.id = (SELECT MIN(g2.id) FROM se_group g2)");
    $tbl->fetchOne();
    $currid = $tbl->id;
}
$grpnm = $tbl->name;
$grpttl = $tbl->title;
$grpnm2 = addslashes($tbl->name);
$grpttl2 = addslashes($tbl->title);
$glevel = intval($tbl->level);
unset($tbl);

if (isRequest("mypg")) {
    $curpg = getRequest("mypg", 1);
    if ($curpg < 1) {
        $curpg = 1;
    }
}
if (isRequest('searchmyb')) {
    $searchmy = trim($_GET['searchmy']);
}
$tbl = new seTable('se_user_group', 'ug');
$tbl->select("COUNT(ug.user_id) AS users");
if ($searchmy != '') {
    $tbl->innerjoin("person p", "(p.id = ug.user_id) AND (CONCAT_WS(' ', p.last_name, p.first_name) LIKE '%" . addslashes($searchmy) . "%')");
}
$tbl->where("ug.group_id = '$currid'");
$tbl->fetchOne();
$printPages[] = array('COUNT' => $tbl->users, 'PAGE' => intval($section->parametrs->param111), 'NUM' => $curpg, 'NAME' => 'mypg');
unset($tbl);

$sep_odin = true;
$tbl = new seTable('person', 'p');
$tbl->select("p.id, CONCAT_WS(' ', p.last_name, p.first_name) AS username");
$tbl->innerjoin('se_user_group ug', 'ug.user_id = p.id');
$tbl->where("ug.group_id = '$currid'");
if ($searchmy != '') {
    $tbl->andWhere("CONCAT_WS(' ', p.last_name, p.first_name) LIKE '%" . addslashes($searchmy) . "%'");
}
$tbl->orderby("username");
$begpg = ($curpg - 1) * intval($section->parametrs->param111);         
foreach ($tbl->getList($begpg, intval($section->parametrs->param111)) as $v) {
    ++$gUsers;
    if($sep_odin){
        $v['style'] = "even";
        $sep_odin = false;    
    } else {
        $v['style'] = "odd";
        $sep_odin = true;
    }
    $__data->setItemList($section, 'ingroup', $v);
}
unset($tbl);
$tbl = new seTable('se_group', 'g');
$tbl->where("g.id != '$currid'");
foreach ($tbl->getList() as $v) {
    $v['name'] = trim($v['name']);
    $v['title'] = trim($v['title']);
    $v['sel'] = intval($v['id'] == $otherGroup);
    $__data->setItemList($section, 'othergroup', $v);
}
unset($tbl);

?>