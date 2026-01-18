<?php

//Если был запрос на новые сообщения
$_SESSION['forum_msgtext'] = '';
$_SESSION['forum_attached'] = array();
if (isRequest('new')) {
    $tbl = new seTable("forum_msg","fm");
    if (isRequest('puser')) {
        $tbl->where("id_users = '?'", getRequest('puser', 1));
        $tbl->andWhere("to_whom != '?'", 0);
    } else {
        $tbl->where("id_topic = '?'", $ext_id);
    }
    $tbl->orderby("date_time");
    $rm = $tbl->getList();
    unset($tbl);
    if (isRequest('last')) {
        $i = count($rm) - 1;
    } else {
        $i = 0;
        foreach ($rm as $msg) {
            if ($msg['date_time'] > $lastVisit) {
                break;
            }
            ++$i;
        }
    }        
    if ($i <= 0) {
        $i = 1;
    }
    $p = ceil($i / $msgOfPart);
    $n = $i - ($p - 1) * $msgOfPart;
    $request = str_replace("&new", "", $_SERVER['REQUEST_URI']);
    header("Location: $request&part=$p#t$n");
    exit();
}
//Инкременируем просмотры
if (!isRequest('part') && !isRequest('user') && !isRequest('puser') && $ext_id) {
    $ext_part = 1;
    $tbl = new seTable("forum_topic","ft");
    $tbl->find($ext_id);
    $tbl->views++;
    $tbl->save();
    unset($tbl);
} else {
    $ext_part = getRequest('part', 1);
}
$user_p = $puser_p = 0;
if (isRequest('user')) {
    $user_p = getRequest("user", 1);
    $theme_exists = intval(($user_p == $uid) && $uid);
} else if (isRequest('puser')) {
    $puser_p = getRequest("puser", 1);
    $theme_exists = intval(($puser_p == $uid) && $uid);
} else {
    if ($ext_id) {
        $_SESSION['forum_ext_id'] = $ext_id; 
    } else if ($_SESSION['forum_ext_id']) {
        $ext_id = $_SESSION['forum_ext_id'];
    }
    $tbl = new seTable("forum_forums","ff");
    $tbl->select("ff.id AS fid, ff.name AS forum, fa.lang, fa.name AS area,
                  fa.id AS aid, ft.name AS topic, ff.moderator");
    $tbl->innerjoin("forum_area fa","fa.id = ff.id_area");
    $tbl->innerjoin("forum_topic ft","ff.id = ft.id_forums");
    $tbl->where("ft.id = '?'", $ext_id);
    $tbl->andwhere("fa.lang='?'" , $lang);   // выводим только нужны язык
    $tbl->andWhere("ft.visible = 'Y'");
    $rf = $tbl->getList();
    unset($tbl);
    $theme_exists = count($rf);
    list($title) = $rf;
    $aid = $title['aid'];
    $fid = $title['fid'];
    $titleTopic = stripslashes($title['topic']);
    $Area = $title['area'];
    $Forum = $title['forum'];
}
$tbl = new seTable("forum_msg","fm");
$tbl->select("fm.id, fm.id_topic, fm.id_users, fm.text, fm.date_time, fm.priority,
              fm.date_time_edit, fm.moderator_edit, fm.date_time_moderator_edit, fu.icq,
              fu.nick, fu.location, fu.origin, fs.name AS status, 
              fu.img,
              fu.email, fm.msg_id, pfm.id_users AS pid_users, pfm.text AS ptext, 
              pfm.date_time AS pdate, pfu.nick AS pnick,
              fu2.id AS id_whom, fu2.nick AS nick_whom");
$tbl->innerjoin("forum_users fu","fm.id_users = fu.id");
$tbl->leftjoin("forum_users fu2", "fu2.id = fm.to_whom");
$tbl->leftjoin("forum_status fs","fs.id=fu.id_status");
$tbl->leftjoin("forum_msg pfm", "pfm.id = fm.msg_id");
$tbl->leftjoin("forum_users pfu", "pfu.id = pfm.id_users");
if ($user_p) {
    $tbl->where("fm.to_whom = '?'", $user_p);
} else if ($puser_p) {
    $tbl->where("fm.id_users = '?'", $puser_p);
    $tbl->andWhere ("fm.to_whom != '0'");
} else {
    $tbl->where("fm.id_topic = '?'",$ext_id);
}
$tbl->orderby("date_time");
//echo $tbl->getSQL();
$rm = $tbl->getList();
unset($tbl);
//Ссылки на части форума
$n = ceil(count($rm)/$msgOfPart);
if ($ext_part > $n) {
    $ext_part = $n;
}
if ($ext_part < 1) {
    $ext_part = 1;
}
$ipages = 0;
$all_msg = count($rm); 
if ($msgOfPart < $all_msg) {
    $ipages = 1;
    $n = ceil($all_msg/$msgOfPart);
    for($i=1; $i<=$n; $i++) {
        $__data->setItemList($section, 'ipages', 
            array(
                'ipage'   => $i,
                'status' => (($i==$ext_part)?1:0)
            )
        );
    }
}
$begin = ($ext_part - 1) * $msgOfPart;
$new_msg = $user_p + $puser_p;
for ($i = $begin; ($i < $begin + $msgOfPart) && ($i < $all_msg); $i++) {
    $msg = $rm[$i];
    $msg ['id_whom'] = intval ($msg ['id_whom']);
    $msg['cur'] = $i;
    $msg['icq'] = trim(str_replace("-", "", $msg['icq']));
    $msg['icq_exists'] = 1;
    if (empty($msg['icq']) || ($msg['id_author'] == -1)) {
        $msg['icq_exists'] = 0;
    }
    $msg['msg_id'] = intval($msg['msg_id']);
    $msg['email_exists'] = 1;
    $msg['email'] = trim($msg['email']);
    if (empty($msg['email']) || ($msg['id_author'] == -1)) {
        $msg['email_exists'] = 0;
    }
    $msg['origin'] = originResult(stripslashes(htmlspecialchars($msg['origin'], ENT_QUOTES)));
    $msg['text'] = stripslashes($msg['text']);
//Заменяем тэги
    $msg['text'] = textedit($msg['text'], $msg['id_users'], $msg['id'], 
                                $iconssmiles, $_page, $razdel);
    $msg['ptext'] = stripslashes($msg['ptext']); 
    $msg['ptext'] = textedit($msg['ptext'], $msg['pid_users'], $msg['msg_id'], 
                                $iconssmiles, $_page, $razdel);                           
//Считаем количество сообщений пользователя
    $tbl = new seTable("forum_msg","fm");
    $tbl->where("id_users = '?'", $msg['id_users']);
    $tbl->andWhere("((to_whom = '0') OR (to_whom IS NULL))");
    $msg['count_msg'] = count($tbl->getList());
    unset($tbl);
    $msg['nick'] = htmlspecialchars($msg['nick']);
    $msg['pnick'] = htmlspecialchars($msg['pnick']);
    $msg['date_time_edit_show'] = 0;
    if (!empty($msg['date_time_edit'])) {
        $msg['date_time_edit_show'] = 1;
        $msg['date_time_edit_date'] = date("d", $msg['date_time_edit'])." ".$month_R[date("m", $msg['date_time_edit'])].date(" Y года в H:i", $msg['date_time_edit']);
    }
    if ($msg['moderator_edit'] == 'Y') {
        $msg['moderator_edit_date'] = date("d", $msg['date_time_moderator_edit'])." ".$month_R[date("m", $msg['date_time_moderator_edit'])].date(" Y года в H:i", $msg['date_time_moderator_edit']);
    }
    $msg['pdate'] = date("d", $msg['pdate']) . " " . $month_R[date("m", $msg['pdate'])] . date(" Y года, H:i", $msg['pdate']); 
    $msg['date'] = date("d", $msg['date_time']) . " " . $month_R[date("m", $msg['date_time'])] . date(" Y года, H:i", $msg['date_time']);
    $msg['img_exists'] = 0;   
    if (!empty($msg['img'])) {
        $msg['img_exists'] = 1;
    }
// Если сообщение пользователя
    $msg['moderator'] = $msg['edit'] = 0;
    $msg['todel'] = $new_msg;
    $msg['edit_p'] = "id/{$ext_id}/";
    if (($msg['id_users'] == $uid) && $uid) {
        $msg['edit'] = 1;
        if ($puser_p) {
            $msg['edit_p'] = "personal/" . $msg['id_whom'] ."/";
        }
    }
//Если модератор
    if ((($title['moderator'] == $uid) || $smod) && $uid && $msg['id_topic']) {
        $msg['todel'] = $msg['moderator'] = 1;
    }
    $__data->setItemList($section, 'messages', $msg);
}
if (!empty($_SESSION['USER_ADD_MSG'])) {
    unset($_SESSION['USER_ADD_MSG']);
}
// 03
                     

?>