<?php

Global $titlepage;

$ext_id=htmlspecialchars($_GET['id'], ENT_QUOTES);

if (!is_numeric($ext_id)) require "hack.php";

$rf = se_db_query("
  SELECT forum_area.name AS area, forum_area.id AS aid
  FROM forum_area
  WHERE forum_area.id='$ext_id' AND forum_area.visible='Y';"
);

$title=se_db_fetch_array($rf);
$aid=$title['aid'];

$forum_echo.= "<H3 id=area_AreaName>".htmlspecialchars($title['area'])."</H3>";

$titlepage=stripslashes(htmlspecialchars($title['area']." - ".$nameForum));

$forum_echo.= "<div id=path><a href='?act=main' id=pathlink>".htmlspecialchars($nameForum)."</a></div>";

//Форумы

$rf = se_db_query("
  SELECT forum_forums.id AS id, forum_forums.name, forum_forums.description, forum_forums.id_area, forum_forums.moderator,
         forum_area.id AS aid, forum_users.id AS uid, forum_users.nick, forum_forums.enable AS enable
  FROM forum_forums, forum_area, forum_users
  WHERE forum_forums.id_area='$ext_id' AND forum_forums.id_area=forum_area.id AND forum_users.id=forum_forums.moderator;"
);

$forum_echo.= "<table class=tableForum id=table_showAreas><tbody class=tableBody><tr><TD colspan=2 class=\"title\" id=title_ShowForumName><div id=area_ShowForumName>Форум</div></TD>
<TD class=\"title\" id=title_ShowThemMount><div id=area_ShowThemMount>Тем</div></TD>
<TD class=\"title\" id=title_ShowModerName><div id=area_ShowModerName>Модератор</div></TD></tr>";
while ($forum=se_db_fetch_array($rf)) {
  $id_forum=$forum['id'];
  $id_user=$forum['uid'];

  $name="<a href='?act=showforum&id=$id_forum' id=area_LinkForum>".$forum['name']."</a>";
  $moderator="<a href='?act=showuser&id=$id_user' id=area_LinkModer>".$forum['nick']."</a>";
  $description=htmlspecialchars($forum['description'], ENT_QUOTES);

  $rt = se_db_query("
    SELECT id_forums, date_time_new
    FROM forum_topic
    WHERE forum_topic.id_forums='$id_forum'
    ORDER BY date_time_new desc"
  );

  $count=se_db_num_rows($rt);

  if ($forum['enable']=="N")
    $forumStatusID="Closed";
  else {
    $forumStatusID="NoNewMess";
  }

  $topic=se_db_fetch_array($rt);

  if (($topic['date_time_new']>@$lastVisit) && ($uid!=0) && ($forum['enable']=="Y")) $forumStatusID="NewMess";

  $forum_echo.= "<tr>
                      <td class=\"field\" id=statustd><div id=main_$forumStatusID></div></td>
                      <td class=\"field\" id=field_ShowForumName><div id=area_ForumName>$name<div id=area_ShDescr>$description</div></div></td>
                      <td class=\"field\" id=field_ShowThemMount><div id=area_ThemMount>$count</div></td>
                      <td class=\"field\" id=field_ShowModerName><div id=area_ModerName>$moderator</div></td></tr>";
}
$forum_echo.= "</tbody></table>";

$forum_echo.= "<div id=path><a href='?act=main' id=pathlink>".htmlspecialchars($nameForum)."</a></div>";

?>