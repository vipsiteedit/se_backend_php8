<?php

Global $titlepage;

$ext_id=htmlspecialchars($_GET['id'], ENT_QUOTES);

//Если был запрос на новые сообщения
if (isset($_GET['new'])) {
  $rm = se_db_query("
    SELECT id, date_time
    FROM forum_msg
    WHERE id_topic='$ext_id'
    ORDER BY date_time"
  );
  if (isset($_GET['last']))
    $i=se_db_num_rows($rm);
  else
    for ($i=0; $msg=se_db_fetch_array($rm); $i++) if ($msg['date_time']>$lastVisit) break;

  if ($i<=0) $i=1;
  $p=ceil($i/$msgOfPart);
  $n=$i-($p-1)*$msgOfPart;
  $request=str_replace("&new", "", $_SERVER['REQUEST_URI']);
  Header("Location: http://".$_SERVER['HTTP_HOST']."$request&part=$p#t$n");
  exit();
}


//Инкременируем просмотры
if (!isset($_GET['part'])) {
  $ext_part=1;
  $ru = se_db_query("
    UPDATE forum_topic
    SET views = views+1
    WHERE forum_topic.id='$ext_id'"
  );
}
else
  $ext_part=htmlspecialchars($_GET['part'], ENT_QUOTES);

$rf = se_db_query("
  SELECT forum_forums.id AS fid, forum_forums.name AS forum, forum_area.name AS area,
         forum_area.id AS aid, forum_topic.name AS topic, forum_forums.moderator
  FROM forum_forums, forum_area, forum_topic
  WHERE forum_topic.id='$ext_id' AND forum_forums.id=forum_topic.id_forums
        AND forum_area.id=forum_forums.id_area AND forum_topic.visible='Y';"
);


if (se_db_num_rows($rf)==0) {
  $forum_echo.= "<div id=message_warning>Тема не найдена. Возможно, она была удалена модератором.</div>";
  $forum_echo.= "<div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
  return;
}


$title=se_db_fetch_array($rf);

$aid=$title['aid'];
$fid=$title['fid'];

$forum_echo.= "<H3 id=titleTopic>".stripslashes(htmlspecialchars($title['topic']))."</H3>";

$titlepage=stripslashes(htmlspecialchars($title['topic']." - ".$title['forum']." - ".$title['area']." - ".$nameForum));

$forum_echo.= "<div id=path><a href='?act=main' id=pathlink>".htmlspecialchars($nameForum)."</a><div id=path_strl>$btnPathStrl</div>";
$forum_echo.= "<a href='?act=showarea&id=$aid' id=pathlink>".htmlspecialchars($title['area'])."</a><div id=path_strl>$btnPathStrl</div>";
$forum_echo.= "<a href='?act=showforum&id=$fid' id=pathlink>".htmlspecialchars($title['forum'])."</a></div>";

$rm = se_db_query(
  "SELECT forum_msg.id, id_topic, id_users, forum_msg.text, date_time, priority,
    date_time_edit, moderator_edit, date_time_moderator_edit, icq,
    nick, location, origin, forum_status.name AS status, img
  FROM forum_msg, forum_users, forum_status
  WHERE forum_msg.id_topic='$ext_id' AND forum_msg.id_users=forum_users.id AND
    forum_status.id=forum_users.id_status
  ORDER BY date_time;"
);

//$msg=se_db_fetch_array($rm);

//$new = "<a href='?act=newmsg&id=$ext_id&area=$ext_area&forum=$ext_forum'>Новое сообщение</a>";

$new = "<a class=shTmenitem href='?act=newmsg&id=$ext_id' id=showTopicMsgMenuNew>$btnNew</a>";

//Ссылки на части форума

$n=ceil(se_db_num_rows($rm)/$msgOfPart);
if ($ext_part>$n) $ext_part=$n;
if ($ext_part<1) $ext_part=1;

if ($msgOfPart<se_db_num_rows($rm)) {
  $forum_echo.= "<div id=steplist>|";
  for($i=1; $i<=$n; $i++)
    if ($i==$ext_part)
      $forum_echo.= " <b id=currentpart> $i </b>|";
    else
      $forum_echo.= " <a href='?act=showtopic&id=$ext_id&part=$i' id=otherpart>$i</a> |";
  $forum_echo.= "</div>";
}
se_db_data_seek($rm, ($ext_part-1)*$msgOfPart);

$forum_echo.= "<table id=tableTopic class=tableForum><tbody class=tableBody>";

for ($i=1; ($i<=$msgOfPart) && ($msg=se_db_fetch_array($rm)); $i++) {

$id_msg=$msg['id'];
//$views=$topic['views'];
//$text=$msg['text'];
$status=$msg['status'];
$location="<div id=topic_showTopicLoc>".$msg['location']."</div>";
$id_users = $msg['id_users'];

$mail="<a id=topic_Email href='?act=mail&id=$id_users&topic=$ext_id'>E-mail</a>";

//echo $msg['icq'];
if (!empty($msg['icq'])) {
  $icq = "<a id=topic_ICQ href='http://wwp.icq.com/".$msg['icq']."#pager' target=_blank>ICQ <img border=0 src='http://web.icq.com/whitepages/online?icq=".$msg['icq']."&img=26 width=13 height=13'></a>";
  $icq=str_replace("-", "", $icq);
}
else
 $icq="";


$text=htmlspecialchars(stripslashes($msg['text']), ENT_QUOTES);
$text="<div id=topic_showTopicMsgText>$text</div><div id=origin>".stripslashes(htmlspecialchars($msg['origin'], ENT_QUOTES))."</div>";

//Заменяем тэги
$trans = array("[b]" => "<b>", "[/b]" => "</b>",
               "[em]" => "<em>", "[/em]" => "</em>",
               "[u]" => "<u>", "[/u]" => "</u>",
               "[ul]" => "<ul>", "[/ul]" => "</ul>",
               "[ol]" => "<ol>", "[/ol]" => "</ol>",
               "[center]" => "<center>", "[/center]" => "</center>",
               "[sup]" => "<sup>", "[/sup]" => "</sup>",
               "[sub]" => "<sub>", "[/sub]" => "</sub>",
               "[code]" => "<pre id=code>", "[/code]" => "</pre>",
               "[quote]" => "<div id=quote>", "[/quote]" => "</div>");

$text=strtr($text, $trans);

//Заменяем url
preg_match_all("/\[a +href=([^]]+)\]([^]]+)\[\/a\]/i", $text, $match, PREG_PATTERN_ORDER);

for($j=0; $j<count($match[1]); $j++)
  $text=str_replace($match[0][$j], '<a id=outlink href="?act=forward&link='.urlencode(str_replace("&amp;", "&", $match[1][$j])).'"target=_blank>'.$match[2][$j].'</a>', $text);

//Заменяем mailto
$text=eregi_replace("\[mailto=([^]]+)\]([^]]+)\[/mailto\]", '<a href="mailto:\\1">\\2</a>', $text);

//Заменяем img
$text=eregi_replace("\[img +src=([^]]+)\]", '<img src="\\1">', $text);

//Заменяем смайлики
$text=eregi_replace("\[smile([[:digit:]]+)\]", "<img src='$smilesURL/smile\\1.gif'>", $text);

//Заменяем цвет
$text=preg_replace("/\[color *= *(#[\d|A-F|a-f]+)\]([^\]]+)\[\/color\]/i", "<font color='$1'>$2</font>", $text);


//Заменяем прикрепленные картинки
$raf=se_db_query(
  "SELECT *
  FROM forum_attached
  WHERE id_user='$id_users' AND id_msg='$id_msg'");

while ($afile=se_db_fetch_array($raf)) {
  $fileP=substr($afile['file'], 0, strlen($afile['file'])-4)."-1".substr($afile['file'], -4);
  if (file_exists("modules/forum/upload/$fileP"))
    $replace="<a href='/modules/forum/upload/".$afile['file']."' title='Увеличить' target=_blank ><img id=forumimg src='/modules/forum/upload/$fileP' border=0></a>";
  else
    $replace="<img id=forumimg src='/modules/forum/upload/".$afile['file']."'>";

  $text=preg_replace("/\[attimg +src=(".$afile['realname'].")\]/", $replace, $text);
  $replace="<div id=topic_attach><a id=topic_linkat href=?act=download&file=".$afile['file']." target=_blank>".$afile['realname']." (".round($afile['size']/1024, 2)." кБ)</a> Количество скачиваний: <b id=topic_dnlnumb>".$afile['counter']."</b></div>";
  $text=preg_replace("/\[attfile +src=(".$afile['realname'].")\]/", $replace, $text);
}

//Считаем количество сообщений пользователя
$ra = se_db_query("
  SELECT id
  FROM forum_msg
  WHERE id_users='$id_users'"
);

$user = htmlspecialchars($msg['nick']);
$allmsg="<a id=topic_lnkShowTopicMsg href='?act=rsearch&user=$user&forums[]=all&text=&result_type=messages&time=0'>Сообщений: ".se_db_num_rows($ra)."</a>";


if (!empty($msg['date_time_edit']))
  $text.="\n<div id=edit>Сообщение было отредактировано ".date("d", $msg['date_time_edit'])." ".$month_R[date("m", $msg['date_time_edit'])].date(" Y года в H:i", $msg['date_time_edit'])."</div>";

if ($msg['moderator_edit']=='Y')
  $text.="\n<div id=moder>Сообщение было отредактировано модератором ".date("d", $msg['date_time_moderator_edit'])." ".$month_R[date("m", $msg['date_time_moderator_edit'])].date(" Y года в H:i", $msg['date_time_moderator_edit'])."</div>";

$text.="<div id=user_menu>$mail $icq</div>";

$text=str_replace("\n", "<br>", $text);

//$reply = "<a href='?act=newmsg&quote=$id_msg&id=$ext_id&area=$ext_area&forum=$ext_forum'>Ответить</a>";
$reply = "<a class=shTmenitem href='?act=newmsg&quote=$id_msg&id=$ext_id#edit' id=showTopicMsgMenuReply>$btnReply</a>";
$edit = "<a class=shTmenitem href='?act=editmsg&id=$id_msg' id=showTopicMsgMenuEdit>$btnEdit</a>";
$del = "<a class=shTmenitem href='?act=delmsg&id=$id_msg' id=showTopicMsgMenuDel>$btnDel</a>";
$moder = "<a class=shTmenitem href='?act=moder&id=$id_msg' id=showTopicMsgMenuModer>$btnModer</a>";

$date = date("d", $msg['date_time'])." ".$month_R[date("m", $msg['date_time'])].date(" Y года, H:i", $msg['date_time']);

if (empty($msg['img'])) $img=""; else $img="<a href='?act=showuser&id=$id_users' title='Личные данные'><img id=topic_showTopicAuthorImg src=/modules/forum/images/".$msg['img']." border=0></a>";

$forum_echo.= "
<tr>
  <td class=title id=title_ShowTopicAuthor><div id=topic_ShowTopicAuthor><a name='t$i'></a><a href='?act=showuser&id=$id_users' id=topic_showTopicAuthorNick>$user</a>$img<div id=topic_showTopicAuthorStatus>$status</div>$location$allmsg</td>
  <td class=field id=field_ShowTopicMsg><div id=topic_MessageText><div id=topic_showTopicMsgDate>$date</div>
  <div id=topic_showTopicMsgMenu> $new $reply";

// Если сообщение пользователя
  if ($id_users==$uid) $forum_echo.= " ".$edit;

//Если модератор
  if ($title['moderator']==$uid || $smod) $forum_echo.= " $moder $del";
//
  $forum_echo.= "</div>$text</div></td></tr>";
}
$forum_echo.= "</table>";

if ($msgOfPart<se_db_num_rows($rm)) {

  $n=ceil(se_db_num_rows($rm)/$msgOfPart);
  $forum_echo.= "<div id=steplist>|";
  for($i=1; $i<=$n; $i++)
    if ($i==$ext_part)
      $forum_echo.= " <b id=currentpart> $i </b>|";
    else
      $forum_echo.= " <a href='?act=showtopic&id=$ext_id&part=$i' id=otherpart>$i</a> |";
  $forum_echo.= "</div>";
}

$forum_echo.= "<div id=path><a href='?act=main' id=pathlink>".htmlspecialchars($nameForum)."</a><div id=path_strl>$btnPathStrl</div>";
$forum_echo.= "<a href='?act=showarea&id=$aid' id=pathlink>".htmlspecialchars($title['area'])."</a><div id=path_strl>$btnPathStrl</div>";
$forum_echo.= "<a href='?act=showforum&id=$fid' id=pathlink>".htmlspecialchars($title['forum'])."</a></div>";

?>