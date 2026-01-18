<?php

//Ищем тему
$ext_id=htmlspecialchars($_GET['id'], ENT_QUOTES);
$ext_code=htmlspecialchars($_GET['code'], ENT_QUOTES);

$rm=se_db_query("
  SELECT id_users, email, name
  FROM forum_topic
  WHERE id='$ext_id'"
);

$topic=se_db_fetch_array($rm);

if (md5($topic['email'].$topic['id_users']."topic")!=$ext_code) {
  $forum_echo.= "<div id=message_warning>Вы не подписаны на уведомления!</div>";
  return;
}

se_db_query("
  UPDATE forum_topic
  SET `email`=''
  WHERE id='$ext_id'"
);

$forum_echo.= "<div id=message_warning>Вы отписаны от уведомлений о добавлении сообщений в теме \"".$topic['name']."\"</div>";

?>