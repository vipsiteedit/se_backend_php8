<?php

$tbl = new seTable("forum_forums","ff");
$tbl->select("ff.id AS id, ff.name,  fa.lang, ff.description, ff.id_area, ff.moderator,
              fa.id AS aid, fa.name AS area, fu.id AS uid, fu.nick, ff.enable");
$tbl->innerjoin("forum_area fa","fa.id = ff.id_area");
$tbl->leftjoin("forum_users fu","fu.id = ff.moderator");
$tbl->where("ff.visible='Y'");
$tbl->andwhere("fa.lang='?'" , $lang);   // выводим только нужны язык
//$tbl->groupby("fa.order_id, ff.id");
$tbl->orderby("`fa`.`order_id`, fa.id, `ff`.`order_id`");
//echo $tbl->getSQL();
$rf = $tbl->getList(30);
unset($tbl);
$aid = 0;
foreach ($rf as $forum) {
    $data = array (
            'aid'         => 0,
            'id'          => $forum['id'],
            'uid'         => $forum['uid'],
            'name'        => $forum['name'],
            'description' => htmlspecialchars($forum['description'], ENT_QUOTES)
        );
    if ( $forum['aid'] != $aid ) {
        $data['aid'] = 1;
        $data['area'] = htmlspecialchars($forum['area'], ENT_QUOTES);
    }
  
    $aid = $forum['aid'];
    $tbl = new seTable("forum_topic","ft");
    $tbl->select("ft.id AS id, id_forums, date_time_new, id_user_new, name, nick");
    $tbl->innerjoin("forum_users fu","fu.id = ft.id_user_new");
    $tbl->where("ft.id_forums='?'",$forum['id']);
    $tbl->orderby("date_time_new",1);
    $rt = $tbl->getList();
    unset($tbl);
   
    if ( $forum['enable'] == "N" ) {
        $data['StatusID'] = "Closed";
    } else {
        $data['StatusID'] = "NoNewMess";
    }
    $data['count'] = count($rt);
    if (count($rt) != 0) {
        list($topic) = $rt;
        $data['topic_id'] = $topic['id'];
        $data['topic_name'] = stripslashes(htmlspecialchars($topic['name'], ENT_QUOTES));
        $data['topicDateNew'] = date("d", $topic['date_time_new'])." ".$month_R[date("m", $topic['date_time_new'])].date(" Y года в H:i", $topic['date_time_new']);
        $data['topic_id_user_new'] = $topic['id_user_new'];
        $data['topic_nick'] = htmlspecialchars($topic['nick'], ENT_QUOTES);      
        if ( ($topic['date_time_new']>@$lastVisit) && ($uid!=0) && ($forum['enable']=="Y") ) { 
            $data['StatusID'] = "NewMess";
        }
    }
    $__data->setItemList($section, 'forums', $data);
}
//$test2 = getmicrotime();
//echo $test2 - $test1;
?>