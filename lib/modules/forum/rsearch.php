<?php

//require_once "/etc/dump.php";

if (isset($_GET['part']))
  $ext_part=htmlspecialchars($_GET['part'], ENT_QUOTES);
else
  $ext_part=1;

$text=htmlspecialchars($_GET['text'], ENT_QUOTES);
$user=htmlspecialchars($_GET['user'], ENT_QUOTES);

$time=htmlspecialchars($_GET['time'], ENT_QUOTES);
if (!is_numeric($time)) $time=0;

@$forums=$_GET['forums'];
$time=htmlspecialchars($_GET['time'], ENT_QUOTES);
$result_type=htmlspecialchars($_GET['result_type'], ENT_QUOTES);

//Составляем условие запроса
if (empty($text))
  $where="";
else
  $where="MATCH(text) AGAINST('$text' IN BOOLEAN MODE) AND ";

if (!empty($user)) $where.="nick='$user' AND ";

if (@array_search("all", $forums)===false)
  @$where.=htmlspecialchars("id_forums IN (".join(", ", $forums).") AND ", ENT_QUOTES);

$where.="forum_msg.date_time>'$time'";

//Если тип вывода - темы
if ($result_type=="topics") {

  $irm= se_db_query("
  SELECT DISTINCT id_topic FROM forum_msg, forum_users WHERE forum_msg.id_users=forum_users.id AND $where"
  );

  while ($idtopic=@se_db_fetch_array($irm)) $intopic[]=$idtopic['id_topic'];

  if (@se_db_num_rows($irm)!=0) $in="IN (".join(", ", $intopic).")"; else $in="IN (-1)";

  $rm = se_db_query("
  SELECT forum_topic.id, forum_topic.id_forums, forum_topic.name, forum_topic.views,
         forum_topic.date_time, forum_topic.id_users, forum_topic.date_time_new,
         forum_topic.id_user_new, forum_topic.visible, forum_users.nick AS author,
         forum_topic.id AS id_topic, forum_forums.name AS forumname
  FROM forum_topic, forum_users, forum_forums
  WHERE forum_topic.visible='Y' AND forum_topic.id_users=forum_users.id
        AND forum_forums.id=forum_topic.id_forums AND forum_topic.id $in
  ORDER BY date_time"
);
}
else {

////////////////////////////////////////////////////////////////////////////////

  $rm = se_db_query("
  SELECT forum_msg.id AS id_msg, id_topic, forum_msg.id_users, forum_msg.text, forum_msg.date_time,
   forum_msg.priority, date_time_edit, moderator_edit, date_time_moderator_edit,
   nick, location, origin, forum_status.name AS status, forum_users.img, forum_topic.name AS topic,
   forum_topic.id_forums
  FROM forum_msg, forum_users, forum_status, forum_topic, forum_forums
  WHERE $where AND forum_msg.id_users=forum_users.id
        AND forum_status.id=forum_users.id_status AND forum_topic.id=id_topic
       AND forum_forums.id=forum_topic.id_forums
  ORDER BY date_time desc"
  );
}

if ($rm===false) return;

//Если ничего не найдено

if (se_db_num_rows($rm)==0) {
  $forum_echo.= "<div id=message_warning>По Вашему запросу ничего не найдено</div>";
  $forum_echo.= "<div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
  return;
}

//Ссылки на части форума

$n=ceil(se_db_num_rows($rm)/$msgOfPart);
if ($ext_part>$n) $ext_part=$n;
if ($ext_part<1) $ext_part=1;

if ($msgOfPart<se_db_num_rows($rm)) {
  $forum_echo.= "<div id=steplist>|";
  for($i=1; $i<=$n; $i++) {
    $query=ereg_replace("&part=.+$", "", $_SERVER['REQUEST_URI']);
    if ($i==$ext_part)
      $forum_echo.= " <b id=currentpart> $i </b>|";
    else
      $forum_echo.= " <a id=otherpart href='$query&part=$i'>$i</a> |";
  }
  $forum_echo.= "</div>";
}
se_db_data_seek($rm, ($ext_part-1)*$msgOfPart);

//Если тип вывода - темы
if ($result_type=="topics") {
$forum_echo.= "<table class=tableForum id=found_tableFound><TBODY class=tableBody>
<tr><td class=title id=found_titleTheme><div id=found_Theme>Тема</div></td>
<td class=title id=found_titleForum><div id=found_Forum>Форум</div></td>
<td class=title id=found_titleMessages><div id=found_Messages>Сообщений</div></td>
<td class=title id=found_titleShowed><div id=found_Showed>Просмотров</div></td>
<td class=title id=found_titleCreated><div id=found_Created>Создана</div></td>
<td class=title id=found_titleRefreshed><div id=found_Refreshed>Обновление</div></td></tr>";
//while ($topic=se_db_fetch_array($rt)) {
for ($i=1; ($i<=$msgOfPart) && ($topic=se_db_fetch_array($rm)); $i++) {
$id_topic=$topic['id_topic'];
$id_forums=$topic['id_forums'];
$views=$topic['views'];
$id_users=$topic['id_users'];
$date = date("d", $topic['date_time'])." ".$month_R[date("m", $topic['date_time'])].date(" Y года в H:i", $topic['date_time']);
$author="<a id=main_cellAuthorNickCr href='?act=showuser&id=$id_users'>".htmlspecialchars($topic['author'])."</a>";

$name="<a id=found_linkTheme href='?act=showtopic&id=$id_topic'>".stripslashes(htmlspecialchars($topic['name']))."</a>";
$forumname="<a id=found_linkForum href='?act=showforum&id=$id_forums'>".stripslashes(htmlspecialchars($topic['forumname']))."</a>";

if (isset($_GET['new']))
  $new="<a href='?act=showtopic&id=$id_topic&new' id=found_linkNew>$btnNewMsg</a>";
else
  $new="";

$rm1 = se_db_query("
  SELECT forum_msg.id_users, forum_msg.date_time,
         forum_users.id AS uid, forum_users.nick AS nick
  FROM forum_msg, forum_users
  WHERE id_topic='$id_topic' AND forum_users.id=forum_msg.id_users
  ORDER BY date_time desc;"
);

$count=se_db_num_rows($rm1);
$msg=se_db_fetch_array($rm1);
$id_usersNew=$msg['id_users'];
$dateNew = date("d", $msg['date_time'])." ".$month_R[date("m", $msg['date_time'])].date(" Y года в H:i", $msg['date_time']);
$nick="<a id=main_cellAuthorNickCr href='?act=showuser&id=$id_usersNew'>".htmlspecialchars($msg['nick'])."</a>";


$forum_echo.= "
<tr>
  <td class=field id=found_fieldTheme><div id=found_nameTheme>$name<br>$new</div></td>
  <td class=field id=found_fieldForum><div id=found_nameForum>$forumname</div></td>
  <td class=field id=found_fieldMessages><div id=found_MessagesMount>$count</div></td>
  <td class=field id=found_fieldShowed><div id=found_ShowedMount>$views</div></td>
  <td class=field id=found_fieldCreated><div id=main_date>$date,<div id=main_autUpdate>Автор: $author</div></div></td>
  <td class=field id=found_fieldRefreshed><div id=main_date>$dateNew,<div id=main_autUpdate>Автор: $nick</div></div></td></tr>";
}
$forum_echo.= "</tbody></table>";

}


////////////////////////////////////////////////////////////////////////////////
else {

$forum_echo.= "<table class=tableUserMessage>";

for ($i=1; ($i<=$msgOfPart) && ($msg=se_db_fetch_array($rm)); $i++) {

$status=$msg['status'];
$location="<div id=topic_showTopicLoc>".$msg['location']."</div>";
$id_users = $msg['id_users'];
$id_msg = $msg['id_msg'];

$text=stripslashes(htmlspecialchars($msg['text'], ENT_QUOTES));
$text.="<div id=origin>".stripslashes(htmlspecialchars($msg['origin'], ENT_QUOTES))."</div>";

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

if (!empty($msg['date_time_edit']))
  $text.="\n<div id=edit>Сообщение было отредактировано ".date("d", $msg['date_time_edit'])." ".$month_R[date("m", $msg['date_time_edit'])].date(" Y года в H:i", $msg['date_time_edit'])."</div>";

if ($msg['moderator_edit']=='Y')
  $text.="\n<div id=moder>Сообщение было отредактировано модератором ".date("d", $msg['date_time_moderator_edit'])." ".$month_R[date("m", $msg['date_time_moderator_edit'])].date(" Y года в H:i", $msg['date_time_moderator_edit'])."</div>";

//$text=nl2br($text);
$text=str_replace("\n", "<br>", $text);

$topic=stripslashes($msg['topic']);
$user = htmlspecialchars($msg['nick'], ENT_QUOTES);

$id_topic=$msg['id_topic'];
$goTo = "<a id=mess_linkUserMessage href='?act=showtopic&id=$id_topic'>Перейти к теме</a>";

$date = date("d", $msg['date_time'])." ".$month_R[date("m", $msg['date_time'])].date(" Y года, H:i", $msg['date_time']);

if (empty($msg['img'])) $img=""; else $img="<a href='?act=showuser&id=$id_users'><img border=0 id=mess_showTopicAuthorImg src=/modules/forum/images/".$msg['img']."></a>";

$forum_echo.= "
<tr><td colspan=2 id=mess_MessTheme><div id=mess_ThemeName>$topic</div></td></tr>
  <tr>
  <td class=title id=title_ShowUserMess><div id=mess_ShowUserMess><a name='t$i'></a><a id=mess_showTopicAuthorNick href='?act=showuser&id=$id_users'>$user</a>$img<div id=mess_showTopicAuthorStatus>$status</div>$location</div></td>
  <td class=field id=field_ShowUserMess><div id=mess_MessageText><div id=mess_showTopicMsgDate>$date</div><div id=mess_GoToTheme>$goTo</div>";

  $forum_echo.= "<div id=mess_showTopicMsgText>$text</div></div></td></tr>";
}
$forum_echo.= "</table>";
}

if ($msgOfPart<se_db_num_rows($rm)) {

  $n=ceil(se_db_num_rows($rm)/$msgOfPart);
  $forum_echo.= "<div id=steplist>|";
  for($i=1; $i<=$n; $i++) {
    $query=ereg_replace("&part=.+$", "", $_SERVER['REQUEST_URI']);
    if ($i==$ext_part)
      $forum_echo.= " <b id=currentpart> $i </b>|";
    else
      $forum_echo.= " <a id=otherpart href='$query&part=$i'>$i</a> |";
  }
  $forum_echo.= "</div>";
}

?>