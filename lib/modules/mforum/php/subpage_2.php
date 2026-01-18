<?php

if (isRequest('part')) {
    $ext_part = getRequest('part',1);
} else {
    $ext_part = 1;
} 
$tbl = new seTable("forum_forums","ff");
$tbl->select("ff.id AS id, ff.name AS forum, fa.lang, ff.img AS img,ff.moderator AS moduid, 
              fa.name AS area, fa.id AS aid, fu.nick AS moderator");
$tbl->innerjoin("forum_area fa","fa.id = ff.id_area");
$tbl->leftjoin("forum_users fu","fu.id = ff.moderator");
$tbl->where("ff.id='?'",$ext_id);
$tbl->andwhere("fa.lang='?'" , $lang);   // выводим только нужны язык
$tbl->andWhere("ff.visible = 'Y'");
$forum = $tbl->fetchOne();
unset($tbl);
$moduid = $forum['moduid'];      
$moderator_exists = 0;
$moderator = $forum['moderator'];
if (!empty($moderator)) {
    $moderator_exists = 1;    
}
$forumName = htmlspecialchars($forum['forum']);
$forumId = $forum['id'];
$aid = $forum['aid'];
$forumArea = $forum['area'];
$tbl = new seTable("forum_topic","ft");
$tbl->select("ft.id, ft.id_forums, ft.name, ft.views, ft.date_time, ft.id_users, 
              ft.date_time_new, ft.id_user_new, ft.visible, fu.nick AS autor, 
              ft.enable AS enable");
$tbl->leftjoin("forum_users fu","ft.id_users = fu.id");
$tbl->where("ft.id_forums='?'", $ext_id);
$tbl->andWhere("ft.visible = 'Y'");
$tbl->orderby("`ft`.`priority` DESC, `ft`.`date_time_new`", 1);
$rt = $tbl->getList();
unset($tbl);
//Если модератор или супермодератор
$ismoderator = ($uid && ($smod || ($moduid==$uid)))?1:0; //$forum_echo.=" <a id=showMdrLink href='?act=moderforum&id=$forumId'>Модерировать</a>";
$themes = count($rt);
//Страницы
$ipages = 0;
if ($thmOfPart<count($rt)) {
    $ipages = 1;
    $n = ceil(count($rt)/$thmOfPart);
    for($i=1; $i<=$n; $i++) {
        $__data->setItemList($section, 'ipages', 
            array(
                'ipage'   => $i,
                'status' => (($i==$ext_part)?1:0)
            )
        );
    }
}
$begin_page = 0;
if (count($rt)) {
    $begin_page = ($ext_part-1)*$thmOfPart;
}
for ($i = $begin_page; ($i < $thmOfPart+$begin_page) && ($i<$themes); ++$i) { 
    $topic = $rt[$i];
    $data = array(
                'id' => $topic['id'],
                'views' => $topic['views'],
                'id_users' => $topic['id_users'],
                'date' => date("d", $topic['date_time'])." ".$month_R[date("m", $topic['date_time'])].date(" Y года в H:i", $topic['date_time']),
                'author' => htmlspecialchars($topic['autor']),
                'name' => stripslashes(htmlspecialchars($topic['name'], ENT_QUOTES))  
            ); 
    $tbl = new seTable("forum_msg","fm");
    $tbl->select("fm.id_users, fm.date_time, fu.id AS uid, fu.nick AS nick");
    $tbl->innerjoin("forum_users fu","fu.id = fm.id_users");
    $tbl->where("id_topic = '?'",$topic['id']);
    $tbl->orderby("date_time",1);
    $rm = $tbl->getList();
    unset($tbl);
    $data['count'] = count($rm);
    list($msg) = $rm;
    $data['nick'] = htmlspecialchars($msg['nick'], ENT_QUOTES);
    $data['dateNew'] = date("d", $msg['date_time'])." ".$month_R[date("m", $msg['date_time'])].date(" Y года в H:i", $msg['date_time']);
    $data['id_usersNew'] = $msg['id_users'];   
//Вывод страниц для перехода в темах
    $nCount = ceil($count/$thmOfPart); //количество страниц
    $sPart = array();
    $data['parts'] = 0;
    if ( ($nCount>1) && ($nCount<=4) ) {
        $data['parts'] = 1;
        for($j=1; $j<=$nCount; $j++) {
            $nPart['part'] = $j;
            $nPart['next'] = (($j<$nCount) ? ',' : '');    
            $__data->setItemList($section, 'parts'.$data['id'],$nPart);        
        }
    } else if ($nCount>4) {
        $data['parts'] = 2;
        for($j=$nCount-2; $j<=$nCount; $j++) {
            $nPart['part'] = $j;
            $nPart['next'] = (($j<$nCount) ? ',' : '');
            $__data->setItemList($section, 'parts'.$data['id'],$nPart);
        }
    }
//Картинки статусов тем
    if ($topic['enable']=="N") {
        $data['StatusID'] = "Closed";
    } else {
        $data['StatusID'] = "NoNewMess";
    }
    if (($msg['date_time']>@$lastVisit) && ($uid!=0) && $topic['enable']=="Y") {
        $data['StatusID'] = "NewMess";
    }
    $__data->setItemList($section, 'themes',$data);
}
// 02

?>