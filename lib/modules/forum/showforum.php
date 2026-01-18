<?php

Global $titlepage;

$ext_id=htmlspecialchars($_GET['id'], ENT_QUOTES);
if (isset($_GET['part']))
  $ext_part=htmlspecialchars($_GET['part'], ENT_QUOTES);
else
  $ext_part=1;

$rf = se_db_query("
  SELECT forum_forums.id AS id, forum_forums.name AS forum, forum_forums.img AS img,
         forum_forums.moderator AS moduid, forum_area.name AS area, forum_area.id AS aid, forum_users.nick AS moderator
  FROM forum_forums, forum_area, forum_users
  WHERE forum_forums.id='$ext_id' AND forum_area.id=forum_forums.id_area
        AND forum_forums.visible='Y' AND forum_users.id=forum_forums.moderator"
);

$forum=se_db_fetch_array($rf);
$moduid=$forum['moduid'];
$moderator=$forum['moderator'];
$forumName = htmlspecialchars($forum['forum']);
$forumId = $forum['id'];
$aid=$forum['aid'];

$rt = se_db_query("
  SELECT forum_topic.id, forum_topic.id_forums, forum_topic.name, forum_topic.views,
         forum_topic.date_time, forum_topic.id_users, forum_topic.date_time_new,
         forum_topic.id_user_new, forum_topic.visible, forum_users.nick AS autor, forum_topic.enable AS enable
  FROM forum_topic, forum_users
  WHERE forum_topic.id_forums='$ext_id' AND forum_topic.visible='Y'
        AND forum_topic.id_users=forum_users.id
  ORDER BY date_time_new desc;"
);


$forum_echo.= "<H3 class=forumTitle id=titleForumName>$forumName</H3>";

$forum_echo.= "<div id=showForumModerator>"."Модератор: "."<a href='?act=showuser&id=$moduid' id=showForumModeratorNick>$moderator</a>";

//Если модератор или супермодератор
if ($smod || ($moduid==$uid)) $forum_echo.=" <a id=showMdrLink href='?act=moderforum&id=$forumId'>Модерировать</a>";

$forum_echo.= "</div><div id=showForumTopics>Тем: ".se_db_num_rows($rt)."</div>";

$forum_echo.= "<div id=path><a href='?act=main' id=pathlink>".htmlspecialchars($nameForum)."</a><div id=path_strl>$btnPathStrl</div>";
$forum_echo.= "<a href='?act=showarea&id=$aid' id=pathlink>".htmlspecialchars($forum['area'])."</a></div>";

$titlepage=stripslashes(htmlspecialchars($forumName." - ".$forum['area']." - ".$nameForum));

$forum_echo.= "<a href='?act=newtopic&id=$forumId' id=showForumNewTopic>Новая тема</a>";

//Страницы
if ($msgOfPart<se_db_num_rows($rt)) {

  $n=ceil(se_db_num_rows($rt)/$msgOfPart);
  $forum_echo.= "<div id=steplist>|";
  for($i=1; $i<=$n; $i++)
    if ($i==$ext_part)
      $forum_echo.= "<b id=currentpart> $i </b>|";
    else
      $forum_echo.= "<a href='?act=showforum&id=$ext_id&part=$i' id=otherpart> $i </a>|";
  $forum_echo.= "</div>";
}
if (se_db_num_rows($rt)!=0) se_db_data_seek($rt, ($ext_part-1)*$msgOfPart);

$forum_echo.= "<table class=tableForum id=table_showForums><tbody class=tableBody>
<tr>
<td colspan=2 class=\"title\" id=title_ShowForumTopic><div id=show_ShowForumTopic>Тема</div></td>
<td class=\"title\" id=title_ShowForumMsgs><div id=show_ShowForumMsgs>Сообщений</div></td>
<td class=\"title\" id=title_ShowForumViews><div id=show_ShowForumViews>Просмотров</div></td>
<td class=\"title\" id=title_ShowForumCreate><div id=show_ShowForumCreate>Создана</div></td>
<td class=\"title\" id=title_ShowForumRefresh><div id=show_ShowForumRefresh>Обновление</div></td></tr>";

if (se_db_num_rows($rt)==0) {
$forum_echo.="<tr><td colspan=6 id=field_Error class=field><div id=show_Error>В данном форуме нет ни одной темы!</div></td></tr></tbody></table>";
return;
}

//while ($topic=se_db_fetch_array($rt)) {
for ($i=1; ($i<=$msgOfPart) && ($topic=se_db_fetch_array($rt)); $i++) {

$id_topic=$topic['id'];
$views=$topic['views'];
$id_users=$topic['id_users'];
$date = date("d", $topic['date_time'])." ".$month_R[date("m", $topic['date_time'])].date(" Y года в H:i", $topic['date_time']);
$autor="<a href='?act=showuser&id=$id_users' id=main_cellAuthorNickCr>".htmlspecialchars($topic['autor'])."</a>";

$name="<a href='?act=showtopic&id=$id_topic' id=show_LinkTopic>".stripslashes(htmlspecialchars($topic['name'], ENT_QUOTES))."</a>";

$rm = se_db_query("
  SELECT forum_msg.id_users, forum_msg.date_time,
         forum_users.id AS uid, forum_users.nick AS nick
  FROM forum_msg, forum_users
  WHERE id_topic='$id_topic' AND forum_users.id=forum_msg.id_users
  ORDER BY date_time desc;"
);

$count=se_db_num_rows($rm);
$msg=se_db_fetch_array($rm);
$id_usersNew=$msg['id_users'];
$dateNew = date("d", $msg['date_time'])." ".$month_R[date("m", $msg['date_time'])].date(" Y года в H:i", $msg['date_time']);
$nick="<a href='?act=showuser&id=$id_usersNew' id=main_cellAuthorNickCr>".htmlspecialchars($msg['nick'], ENT_QUOTES)."</a>";


//Вывод страниц для перехода в темах

$nCount=ceil($count/$msgOfPart); //количество страниц
$nPart=NULL;
$sPart="";
if (($nCount>1) && ($nCount<=4)) {
  for($j=1; $j<=$nCount; $j++) {
    $nPart[]="<a id=show_NextNum href='?act=showtopic&id=$id_topic&part=$j'>$j</a>";
  }
  $sPart="<div id=show_MsgNum>Страницы: ".join(", ", $nPart)."</div>";
}
elseif ($nCount>4) {
  for($j=$nCount-2; $j<=$nCount; $j++) {
    $nPart[]="<a id=show_NextNum href='?act=showtopic&id=$id_topic&part=$j'>$j</a>";
  }
  $sPart="<div id=show_MsgNum>Страницы: <a id=show_NextNum href='?act=showtopic&id=$id_topic&part=1'>1</a>, ... ".join(", ", $nPart)."</div>";
}


//Картинки статусов тем
if ($topic['enable']=="N")
  $topicStatusID="Closed";
else {
  $topicStatusID="NoNewMess";
}

if (($msg['date_time']>@$lastVisit) && ($uid!=0) && $topic['enable']=="Y") $topicStatusID="NewMess";

$forum_echo.= "
<tr>
  <td class=\"field\" id=statustd><div id=main_$topicStatusID></div></td>
  <td class=\"field\" id=field_ShowForumTopic><div id=show_Theme>$name</div>$sPart</td>
  <td class=\"field\" id=field_ShowForumMsgs><div id=show_ThemMount>$count</div></td>
  <td class=\"field\" id=field_ShowForumViews><div id=show_ThemShow>$views</div></td>
  <td class=\"field\" id=field_ShowForumCreate><div id=main_date>$date,<div id=main_autUpdate>Автор: $autor</div></div></td>
  <td class=\"field\" id=field_ShowForumRefresh><div id=main_date>$dateNew,<div id=main_autUpdate>Автор: $nick</div></div></td></tr>";
}
$forum_echo.= "</tbody></table>";

//Страницы
if ($msgOfPart<se_db_num_rows($rt)) {

  $n=ceil(se_db_num_rows($rt)/$msgOfPart);
  $forum_echo.= "<div id=steplist>|";
  for($i=1; $i<=$n; $i++)
    if ($i==$ext_part)
      $forum_echo.= "<b id=currentpart> $i </b>|";
    else
      $forum_echo.= "<a href='?act=showforum&id=$ext_id&part=$i' id=otherpart> $i </a>|";
  $forum_echo.= "</div>";
}
//se_db_data_seek($rt, ($ext_part-1)*$msgOfPart);

$forum_echo.= "<div id=path><a href='?act=main' id=pathlink>".htmlspecialchars($nameForum)."</a><div id=path_strl>$btnPathStrl</div>";
$forum_echo.= "<a href='?act=showarea&id=$aid' id=pathlink>".htmlspecialchars($forum['area'])."</a></div>";


//  "<tr><td>".$area['name']."</td></tr>";

//  if (@$title!="") $title1=@$title."&gt;".$row['name']; else $title1=$row['name'];
  //$str.="<a href=\"select.php?obj=".$row['id']."&title=".$title1."\">".$row['name']."</a><br>";



?>