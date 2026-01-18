<?php

$tbl = new seTable("forum_area","fa");
$tbl->select("fa.name AS area, fa.id AS aid");
$tbl->where("fa.id = '?'",$ext_id);
$tbl->andWhere("fa.visible = 'Y'");
$title = $tbl->fetchOne();
unset ($tbl);
$aid = $title['aid'];
$area = $title['area'];
$tbl = new seTable ("forum_forums","ff");
$tbl->select ("ff.id AS id, ff.name, fa.lang, ff.description, ff.id_area, ff.moderator,
              fa.id AS aid, fu.id AS uid, fu.nick, ff.enable AS enable");
$tbl->innerjoin ("forum_area fa","ff.id_area = fa.id");
$tbl->leftjoin ("forum_users fu","fu.id = ff.moderator");
$tbl->where ("ff.id_area = '?'", $ext_id);
$tbl->andwhere("fa.lang='?'" , $lang);   // выводим только нужны язык
$rf = $tbl->getList ();
unset ($tbl);
foreach($rf as $forum) {
    $data = array(
                'id'          => $forum['id'],
                'uid'         => $forum['uid'],
                'name'        => $forum['name'],
                'description' => $forum['description'],
                'nick'        => $forum['nick']
            );
    $tbl = new seTable("forum_topic","ft");
    $tbl->where("ft.id_forums = '?'",$forum['id']);
    $tbl->orderby("date_time_new",1);
    $rt = $tbl->getList();
    unset($tbl);
    $data['count'] = count($rt);
    if ($forum['enable'] == "N") {
        $data['StatusID'] = "Closed";
    } else {
        $data['StatusID'] = "NoNewMess";
    }
    list($topic) = $rt;
    if (($topic['date_time_new']>@$lastVisit) && ($uid!=0) && ($forum['enable']=="Y")) {
        $data['StatusID'] = "NewMess";
    }
    $__data->setItemList($section, 'forums', $data);
//    add_simplexml_from_array(&$section, 'forums', $data);
}
//01

?>