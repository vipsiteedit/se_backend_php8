<?php

if (!$uid) {
    header("Location: ".seMultiDir()."/$_page/");
    exit();
}
$ext_id_numeric = 1;
if (!is_numeric($ext_id)) {
    $ext_id_numeric = 0;
}
$tbl = new seTable("forum_users","fu");
$tbl->select("fu.id, fu.id_status, fu.nick, fu.realname, fu.location,
                    fu.email, fu.icq, fu.url, fu.img, fu.jobtitle,
                    fu.enabled, fu.registered, fu.last, fu.origin, fu.interests,
                    fs.id as sid, fs.name as status");
$tbl->leftjoin("forum_status fs","fu.id_status = fs.id");
$tbl->where("fu.id = '?'", $ext_id);
//echo $tbl->getSQL();
$user = $tbl->fetchOne();
unset($tbl);
$_nick = stripslashes(htmlspecialchars($user['nick']));
$realname = stripslashes(htmlspecialchars($user['realname']));
$location = stripslashes(htmlspecialchars($user['location']));
$jobtitle = stripslashes(htmlspecialchars($user['jobtitle']));
$interests = stripslashes(htmlspecialchars($user['interests']));
$status = stripslashes(htmlspecialchars($user['status']));
$email = stripslashes(htmlspecialchars($user['email']));
$icq = stripslashes(htmlspecialchars($user['icq']));
$url = stripslashes(htmlspecialchars($user['url']));
$registered = date("d", $user['registered'])." ".$month_R[date("m", $user['registered'])].date(" Y года в H:i", $user['registered']);
$last = date("d", $user['last'])." ".$month_R[date("m", $user['last'])].date(" Y года в H:i", $user['last']);
$img_exists = 0;    
if ($user['img'] != "") {
    $img_exists = 1;
    $img = $user['img'];
}
// '[' . $user['origin'] . ']';
$origin = stripslashes(htmlspecialchars($user['origin'], ENT_QUOTES));
$origin = originResult($origin);
/*
$origin = str_replace("\n", "<br>", $origin);
    //Заменяем тэги
$trans = array("[b]" => "<b>", "[/b]" => "</b>",
                    "[em]" => "<em>", "[/em]" => "</em>",
                    "[u]" => "<u>", "[/u]" => "</u>",
                    "[ul]" => "<ul>", "[/ul]" => "</ul>",
                    "[ol]" => "<ol>", "[/ol]" => "</ol>",
                    "[quote]" => "<div id=quote>", "[/quote]" => "</div>");
$origin = strtr($origin, $trans);
  //Заменяем url
$origin = preg_replace("/\[a +href=([^\]]+)\]([^\[]+)\[\/a\]/i", "<a id='user_linkSite' href='$1'>$2</a>", $origin);
  //Заменяем mailto
$origin = preg_replace("/\[mailto=([^\]]+)\]([^\[]+)\[\/mailto\]/i", "<a id='user_linkEmail' href=\"mailto:$1\">$2</a>", $origin);
  //Заменяем img
$origin = preg_replace("/\[img +src=([^\]]+)\]/i", '<img src="\\1">', $origin);
  //Заменяем смайлики
$origin = preg_replace("/\[smile([[:digit:]]+)\]/i", "<img src='$iconssmiles/smile\\1.gif'>", $origin);
  //Заменяем цвет
$origin = preg_replace("/\[color *= *(#[\d|A-F|a-f]+)\]([^\[]+)\[\/color\]/i", "<font color='$1'>$2</font>", $origin);
//*/
$tbl = new seTable("forum_msg", "fm");
$tbl->where("id_users = '?'",$ext_id);
$tbl->andWhere("((to_whom = '0') OR (to_whom IS NULL))");
$allmsg = count($tbl->getList());
unset($tbl);
// 17

?>