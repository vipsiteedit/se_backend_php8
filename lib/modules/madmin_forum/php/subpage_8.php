<?php

$firstp = $secndp = 0;
$tbl = new seTable("forum_users", "fu");
if (isRequest('id')) {
    $tbl->innerjoin("forum_forums ff", "ff.moderator != fu.id");
    $tbl->where("ff.id = '?'", getRequest('id', 1));
    $tbl->andwhere("ff.lang='?'" , $lang);   // выводим только нужны язык
} else {
    $tbl->where("`smoderator` = 'N'");
    //$tbl->andwhere("ff.lang='?'" , $lang);   // выводим только нужны язык
}
$tbl->andWhere("enabled = 'Y'");
$result = $tbl->getList();
unset($tbl);
foreach ($result as $res) {
    $__data->setItemList($section, 'users', $res);
}
$admindel = 0;
if (isRequest('id')) {
    $firstp = 1;
    $forum_id = getRequest('id', 1);
    $tbl = new seTable("forum_forums", "ff");
    $tbl->select("ff.name AS name, fu.nick AS nick");
    $tbl->leftjoin("forum_users fu", "fu.id = ff.moderator");
    $tbl->where("ff.id = '?'", $forum_id);
    $tbl->andwhere("ff.lang='?'" , $lang);   // выводим только нужны язык
    $forums = $tbl->fetchOne();
    unset($tbl);
    $forumName = $forums['name'];
    $forumNick = $forums['nick'];
    $fnexists = 1;
    if (empty($forumNick)) {
        $fnexists = 0;    
    }
    $save_moder = 0;
    if (isRequest('save_moder')) {
        $save_moder = 1;
        $iduser = getRequest('moder_name', 1);
        if (!empty($iduser)) {
            $tbl = new seTable("forum_users", "fu");
            $res = $tbl->find($iduser);
            unset($tbl);            
            if (!empty($res)) {
                $tbl = new seTable("forum_forums", "ff");
                $tbl->find($forum_id);
                $tbl->moderator = $res['id'];
                $tbl->save();
                unset($tbl);
                Header("Location:  ".seMultiDir()."/$_page/");
                exit();
            }
        }
    }
} else {
    $secndp = 1;
    $tbl = new seTable("forum_users", "fu");
    $tbl->where("`smoderator` = 'Y'");
    $tbl->andWhere("enabled = 'Y'");
    $result = $tbl->getList();
    unset($tbl);
    if (($smoderators = count($result))) {
        foreach ($result as $res) {
            $__data->setItemList($section, 'smoderators', $res);
        }
    }
    if (isRequest('delsu')) {
        if (isRequest('supusr')) {
            $id_su = getRequest('supusr', 1);
            $tbl = new seTable("forum_users", "fu");
            $tbl->find($id_su);
            if ($tbl->id_author != -1) {
                $tbl->smoderator = 'N';
                $tbl->save();
            } else {
                $admindel = 1;
            }
            unset($tbl);
            if (!$admindel) {
                Header("Location:  ".seMultiDir()."/$_page/$razdel/sub8/");
                exit();
            }
        }
    }
    if (isRequest('savesu')) {
        $iduser = getRequest('suname', 1);
        if (!empty($iduser)) {
            $tbl = new seTable("forum_users", "fu");
            $res = $tbl->find($iduser);
            if (!empty($res)) {
                $tbl->smoderator = 'Y';
                $tbl->save();
                unset($tbl);
                Header("Location:  ".seMultiDir()."/$_page/$razdel/sub8/");
            }
            unset($tbl);
        }
    }
}
if (isRequest('Back')) {
    Header("Location:  ".seMultiDir()."/$_page/");
    exit();
}

?>