<?php

if (isset($_POST['doGo'])) {

$ext_id=htmlspecialchars($_POST['id'], ENT_QUOTES);

$rp = se_db_query(
  "SELECT id_users, moderator_edit, id_topic
  FROM forum_msg
  WHERE forum_msg.id='$ext_id'"
);

$priv=se_db_fetch_array($rp);
if ($priv['id_users']!=$uid) return;

if ($priv['moderator_edit']=='Y') {
  $forum_echo.= "<div id=message_warning>Вы не можете редактировать данное сообщение!</div>";
  $forum_echo.= "<div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
  return;
}

$ext_text=$_POST['text'];

if ($ext_text=="") {
  $forum_echo.= "<div id=message_warning>Поле сообщения пустое!</div>";
  $forum_echo.= "<div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
  return;
}

$date = date("U");


//Добавляем сообщение
$text = substr($ext_text, 0, $msgMaxLength);
$text = AddSlashes($text);
$time=time();

se_db_query("
  UPDATE forum_msg
  SET text='$text', date_time_edit='$time'
  WHERE id='$ext_id'"
);

$forum_echo.= "<div id=message_warning>Ваше сообщение было изменено.</div>";
$forum_echo.= "<div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:location.href=\"?act=showtopic&id=".$priv['id_topic']."\"' value=\"Перейти к теме\"></div>";

}

else

{

$ext_id=htmlspecialchars($_GET['id'], ENT_QUOTES);

if (!is_numeric($ext_id)) return;

$rp = se_db_query(
  "SELECT id_users, moderator_edit
  FROM forum_msg
  WHERE forum_msg.id='$ext_id'"
);

$priv=se_db_fetch_array($rp);
if ($priv['id_users']!=$uid) return;

if ($priv['moderator_edit']=='Y') {
  $forum_echo.= "<div id=message_warning>Вы не можете редактировать данное сообщение!</div>";
  $forum_echo.= "<div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
  return;
}

$rm = se_db_query(
  "SELECT id, id_users, text, priority, moderator_edit
  FROM forum_msg
  WHERE forum_msg.id='$ext_id';"
);

$msg=se_db_fetch_array($rm);
if (se_db_num_rows($rm)==0 || $msg['id_users']!=$uid) require "hack.php";

$text=stripslashes($msg['text']);
$forum_echo.= "<form action='?act=editmsg' method='post' id='form' name='form'>";
require_once "msgform.php";
$forum_echo.= "<tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2><input type='hidden' name='id' value='$ext_id'>";

$forum_echo.= "<div id=erm_ServicesButtons><input class=buttonForum id=erm_Add type='submit' name='doGo' value='Сохранить'>";
$forum_echo.= "<input class=buttonForum id=erm_Clear type='reset' value='Отмена'></div></td></tr></tbody></table>";
$forum_echo.= "</form>";

}

?>