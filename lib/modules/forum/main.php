<?php


$nameForum=htmlspecialchars($nameForum); //Название форума

$forum_echo.= "<H3 class=forumTitle id=forumtitle>$nameForum</H3>
			   <table class=tableForum id=table_main><TBODY class=tableBody>";
$forum_echo.= "<tr>
    <td colspan=2 class=\"title\" id=title_MainForum><div id=main_MainForum>Форум</div></td>
    <td class=\"title\" id=title_MainTopic><div id=main_MainTopic>Тем</div></td>
    <td class=\"title\" id=title_MainUpdate><div id=main_MainUpdate>Обновление</div></td></tr>";

//Форумы

$rf = mysql_query("
  SELECT forum_forums.id AS id, forum_forums.name, forum_forums.description, forum_forums.id_area, forum_forums.moderator,
         forum_area.id AS aid, forum_area.name AS area, forum_users.id AS uid, forum_users.nick, forum_forums.enable
  FROM forum_forums, forum_area, forum_users
  WHERE forum_forums.id_area=forum_area.id AND forum_users.id=forum_forums.moderator AND forum_forums.visible='Y'
  GROUP BY forum_area.order_id, forum_forums.id;  
"
);
//echo mysql_error();

$aid=0;
while ($forum=mysql_fetch_array($rf)) {
// $forum_echo.= htmlspecialchars($forum['area']);
  if ($forum['aid']!=$aid) {
    //if ($aid!=0) $forum_echo.= "</TBODY></table><br>";
    $forum_echo.= "<tr><td colspan=4 id=field_Topic class=field><div id=forumrazdel>".htmlspecialchars($forum['area'], ENT_QUOTES)."</div></td></tr>";
  }
  $id_forum=$forum['id'];
  $id_user=$forum['uid'];
  $aid=$forum['aid'];
  $name="<a id=main_linkForum href='?act=showforum&id=$id_forum'>".$forum['name']."</a>";
  $moderator="<a id=main_linkModer href='?act=showuser&id=$id_user'>".$forum['nick']."</a>";
  $description=htmlspecialchars($forum['description'], ENT_QUOTES);

  $rt = mysql_query("
    SELECT forum_topic.id AS id, id_forums, date_time_new, id_user_new, name, nick
    FROM forum_topic, forum_users
    WHERE forum_topic.id_forums='$id_forum' AND forum_topic.id_user_new=forum_users.id
    ORDER BY date_time_new desc"
  );

  if ($forum['enable']=="N")
    $forumStatusID="Closed";
  else {
    $forumStatusID="NoNewMess";
  }

  $count=mysql_num_rows($rt);

  if ($count!=0) {

    $topic=mysql_fetch_array($rt);
    $topicName="<a href='?act=showtopic&id=".$topic['id']."&new&last' id=main_LinkTopic>".stripslashes(htmlspecialchars($topic['name'], ENT_QUOTES))."</a>";
    $topicDateNew = date("d", $topic['date_time_new'])." ".$month_R[date("m", $topic['date_time_new'])].date(" Y года в H:i", $topic['date_time_new']);
    $topicNick="<a href='?act=showuser&id=".$topic['id_user_new']."' id=main_cellAuthorNickCr>".htmlspecialchars($topic['nick'], ENT_QUOTES)."</a>";
    //<a href='[@subpage6]' id=main_cellAuthorNickCr>Пользователь</a>

    $topicUpdate="<div id=main_Update>$topicName<div id=main_date>$topicDateNew<div id=main_autUpdate>Автор: $topicNick</div></div></div>";
    if (($topic['date_time_new']>@$lastVisit) && ($uid!=0) && ($forum['enable']=="Y")) $forumStatusID="NewMess";
  }
  else
    $topicUpdate="Нет";

  $forum_echo.= "<tr><td class='field' id=statustd><div id=main_$forumStatusID></div></td>
  					 <td class=\"field\" id=field_MainForum>
   					       <div id=main_ForumName>$name<div id=main_ShDescr>$description</div></div></td>
                     <td class=\"field\" id=field_MainTopic><div id=main_MessMount>$count</div></td>
                     <TD class=\"field\" id=field_MainUpdate>$topicUpdate</TD></tr>";
}
$forum_echo.= "</TBODY></table>";

?>