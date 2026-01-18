<?php

if (isRequest('part')) {
    $ext_part = getRequest('part',1);
} else {
    $ext_part = 1;
}
$text = $_GET['text'];//getRequest('text',3);
$user = htmlspecialchars($_GET['user'], ENT_NOQUOTES);
$time = getRequest('time',3);
if (!is_numeric($time)) {
    $time = 0;
}
@$forums = $_GET['forums'];
//Составляем условие запроса
//Если тип вывода - темы
  ////////////////////////////////////////////////////////////////////////////////
$tbl = new seTable("forum_msg","fm");
$tbl->select("fm.id AS id_msg, id_topic, fm.id_users, fm.text, fm.date_time,
                fm.priority, date_time_edit, moderator_edit, 
                date_time_moderator_edit, fu.nick, location, origin, 
                fs.name AS status, fu.img, ft.name AS topic, ft.id_forums");
$tbl->innerjoin("forum_users fu","fm.id_users = fu.id");
$tbl->innerjoin("forum_topic ft","ft.id = fm.id_topic");
$tbl->innerjoin("forum_forums ff","ff.id = ft.id_forums");
$tbl->leftjoin("forum_status fs","fs.id = fu.id_status");
$tbl->where ("((fm.to_whom = '0') OR (fm.to_whom IS NULL))");
if (!empty($text)) {
    $tbl->andWhere("fm.text LIKE '%$text%'");
}
if (!empty($user)) {
    $tbl->andWhere("fu.id = '?'", $user);
}
if (@array_search("all", $forums) === false) {
    $tbl->andWhere("ft.id_forums IN (".join(", ", $forums).")");
}
$tbl->andWhere("fm.date_time > '$time'");
//echo $tbl->getSQL ();
$rm = $tbl->getList();
unset($tbl);
$find = count($rm);
if ($find) {
    $n = ceil($find/$msgOfPart);
    if ($ext_part>$n) {
        $ext_part=$n;
    }
    if ($ext_part<1) {
        $ext_part = 1;
    }
    $ipages = 0;
    if ($msgOfPart < $find) { 
        $ipages = 1;
        $query = preg_replace("/&part=.+$/u", "", $_SERVER['REQUEST_URI']);
        for($i=1; $i<=$n; $i++) {
            $__data->setItemList($section, 'ipages', 
                array(
                    'ipage'   => $i,
                    'status' => (($i==$ext_part)?1:0)
                )
            );
        }
    }
    $bpage = ($ext_part-1)*$msgOfPart;
    for ($i = $bpage; ($i < $bpage + $msgOfPart) && 
                            ($i<count($rm)); $i++) {
        $msg = $rm[$i];
        $data = array(
            'i' => $i,
            'status' => $msg['status'],
            'location' => $msg['location'],
            'id_users' => $msg['id_users'],
            'id_msg'    => $msg['id_msg']
        );
        $text = stripslashes(htmlspecialchars($msg['text'], ENT_QUOTES));
        $text .= "<div class='origin'>" . originResult(stripslashes(htmlspecialchars($msg['origin'], ENT_QUOTES))) . "</div>";
        $text = textedit ($text, $msg['id_users'], $msg['id_msg'], 
                                $iconssmiles, $_page, $razdel);
        $data['time_edit'] = 0;
        if (!empty($msg['date_time_edit'])) {
            $data['time_edit'] = 1;
            $data['date_time_edit'] = date("d", $msg['date_time_edit'])." ".$month_R[date("m", $msg['date_time_edit'])].date(" Y года, H:i", $msg['date_time_edit']); 
        }
        $data['moderator_edit'] = 0;
        if ($msg['moderator_edit']=='Y') {
            $data['moderator_edit'] = 1;
            $data['date_time_moderator_edit'] = date("d", $msg['date_time_moderator_edit'])
                        ." ".$month_R[date("m", $msg['date_time_moderator_edit'])].date(" Y года, H:i", $msg['date_time_moderator_edit']); 
        }
        $text = str_replace("\n", "<br>", $text);
        $data['topic'] = stripslashes($msg['topic']);
        $data['text'] = $text;
        $data['user'] = htmlspecialchars($msg['nick'], ENT_QUOTES);
        $data['id_topic'] = $msg['id_topic'];
        $data['goTo'] = "<a id=mess_linkUserMessage href='/$_page/$razdel/sub3/?id=".$msg['id_topic']."'>Перейти к теме</a>";
        $data['date'] = date("d", $msg['date_time'])." ".$month_R[date("m", $msg['date_time'])].date(" Y года, H:i", $msg['date_time']);
        $data['img'] = 0;
        if (!empty($msg['img'])) {
            $data['img'] = $msg['img'];
        }
        $__data->setItemList($section, 'somethings',$data);
    }
}
// 18

?>