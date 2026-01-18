<?php

$result_type = getRequest('result_type',3);
if ($result_type != 'topics') {
    $npage = str_replace("sub10", "sub18", $_SERVER['REQUEST_URI']);
    header("Location: ".seMultiDir().$npage);
    exit();
}
if (isRequest('part')) {
    $ext_part = getRequest('part',1);
} else {
    $ext_part = 1;
}
$text = $_GET['text'];
$user = htmlspecialchars($_GET['user'], ENT_NOQUOTES);
$time = getRequest('time', 3);
if (!is_numeric($time)) {
    $time = 0;
}
@$forums = $_GET ['forums'];
$tbl = new seTable("forum_msg","fm");
$tbl->select("DISTINCT id_topic");
$tbl->innerjoin("forum_users fu","fm.id_users = fu.id");
$tbl->where ("((to_whom = '0') OR (to_whom IS NULL))");
if (!empty($text)) {
    $tbl->andWhere("fm.text LIKE '%$text%'");
}
if (!empty($user)) {
    $tbl->andWhere("fu.id = '?'", $user);
}
if (@array_search("all", $forums)===false) {
    $tbl->andWhere("ft.id_forums IN (".join(", ", $forums).")");
    $tbl->innerjoin("forum_topic ft", "fm.id_topic = ft.id");
}
$tbl->andWhere("fm.date_time > '$time'");
$tbl->orderby ("`fm`.`id_topic`");
//echo $tbl->getSQL ();
$irm = $tbl->getList();
unset($tbl);
$intopic = array ();
foreach($irm as $idtopic) {
    $intopic [] = $idtopic ['id_topic'];
}
//print_r ($intopic);
$tbl = new seTable ("forum_topic","ft");
$tbl->select ("ft.id AS id_topic, ft.id_forums, ft.name, ft.views, ft.date_time, 
                    ft.id_users, ft.date_time_new, ft.id_user_new, ft.visible, 
                    fu.nick AS author, ff.name AS forumname");
$tbl->innerjoin ("forum_users fu","ft.id_users = fu.id");
$tbl->innerjoin ("forum_forums ff","ff.id = ft.id_forums");
$tbl->where ("ft.visible = 'Y'");
$tbl->andWhere ("ft.enable = 'Y'");
if (count($irm)) {
    $tbl->andWhere ("ft.id IN (".join(", ", $intopic).")"); 
} else {
    $tbl->andWhere ("ft.id IN (-1)");
}
$tbl->orderby ("date_time_new", 1);
//echo $tbl->getSQL ();
$rm = $tbl->getList ();
unset($tbl);
//Если ничего не найдено
$find = count($rm);
if ($find) {
//Ссылки на части форума
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
//Если тип вывода - темы
    for ($i = $bpage; ($i < $msgOfPart + $bpage) && ($i<count($rm)); $i++) {
        $topic = $rm[$i];
        $data = array(
                'forumname' => stripslashes(htmlspecialchars($topic['forumname'])),
                'id_forums' => $topic['id_forums'],
                'views'     => $topic['views'],
                'date'      => date("d", $topic['date_time'])." ".
                                $month_R[date("m", $topic['date_time'])].
                                 date(" Y года в H:i", $topic['date_time']),
                'id_users'  => $topic['id_users'],
                'author'    => htmlspecialchars($topic['author']),
                'name'      => stripslashes(htmlspecialchars($topic['name'])),                
                'id_topic'  => $topic['id_topic']
        );
        $new = 0;
        if (isRequest('new')) {
            $new = 1;
        }   
        $tbl = new seTable ("forum_msg","fm");
        $tbl->select ("fm.id_users, fm.date_time, fu.id AS uid, fu.nick AS nick");
        $tbl->innerjoin ("forum_users fu","fu.id = fm.id_users");
        $tbl->where ("id_topic = '?'", $data['id_topic']);
        $tbl->orderby ("date_time",1);
        $rm1 = $tbl->getList ();
        unset($tbl);
        $data['count'] = count($rm1);
        list($msg) = $rm1;
        $data['id_usersNew'] = $msg['id_users'];
        $data['nick'] = $msg['nick'];
        $data['dateNew'] = date("d", $msg['date_time'])." ".$month_R[date("m", $msg['date_time'])].date(" Y года в H:i", $msg['date_time']);
        $__data->setItemList($section, 'frms',$data);
    }
}
// 10

?>