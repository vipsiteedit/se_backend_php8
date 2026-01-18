<?php

if (isset($_POST['doGo'])) {

$ext_id=htmlspecialchars($_POST['id'], ENT_QUOTES);

$rp = se_db_query(
  "SELECT moderator, id_topic
  FROM forum_forums, forum_topic, forum_msg
  WHERE forum_msg.id='$ext_id' AND forum_topic.id=forum_msg.id_topic AND
        forum_forums.id=forum_topic.id_forums"
);

$priv=se_db_fetch_array($rp);
if ($priv['moderator']!=$uid && !$smod) return;

$ext_text=$_POST['text'];
$ext_topic=$_POST['topic'];

if ($ext_text=="") {
  $forum_echo.= "<div id=message_warning>Поле сообщения пустое!</div>";
  $forum_echo.= "<div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
  return;
}

//$date = date("U");


//Добавляем сообщение
$text = substr($ext_text, 0, $msgMaxLength);
$text = AddSlashes($text);
$time=time();
$id_topic=$priv['id_topic'];

se_db_query("
  UPDATE forum_msg
  SET text='$text', moderator_edit='Y', date_time_moderator_edit='$time'
  WHERE id='$ext_id'"
);

se_db_query("
  UPDATE forum_topic
  SET name='$ext_topic'
  WHERE id='$id_topic'"
);

Header("Location: http://".$_SERVER['HTTP_HOST']."/".$page."?act=showtopic&id=".$priv['id_topic']."&".time());
exit();

}

else

{

$ext_id=htmlspecialchars($_GET['id'], ENT_QUOTES);

if (!is_numeric($ext_id)) return;

$rp = se_db_query(
  "SELECT moderator, text, forum_topic.name
  FROM forum_forums, forum_topic, forum_msg
  WHERE forum_msg.id='$ext_id' AND forum_topic.id=forum_msg.id_topic AND
        forum_forums.id=forum_topic.id_forums"
);

$priv=se_db_fetch_array($rp);

if (se_db_num_rows($rp)==0 || ($priv['moderator']!=$uid && !$smod)) return;

$text=($priv['text']);
$topic=stripslashes($priv['name']);
$forum_echo.= "<form action='?act=moder' method='post' id='form' name='form'>";
$forum_echo.= "<div id=erm_ThemeName>Название темы: <input id=erm_ThemeText type='text' maxlength='$msgMaxLengthTopic' name='topic' value='$topic'></div>";
require_once "msgform.php";
$forum_echo.= "<tr><td colspan=2>&nbsp</td></tr>
			   <tr><td colspan=2><input type='hidden' name='id' value='$ext_id'>";

$forum_echo.= "<div id=pvt_ServicesButtons><input class=buttonForum id=pvt_btnSave type='submit' name='doGo' value='Сохранить'>";
$forum_echo.= "<input class=buttonForum id=pvt_btnUndo type='reset' value='Отмена'></div></td><tr></tbody></table>";
$forum_echo.= "</form>";

}
?>