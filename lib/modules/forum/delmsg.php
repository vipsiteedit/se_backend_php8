<?php

Global $_page;

$ext_id=@htmlspecialchars($_GET['id'], ENT_QUOTES);

//Проверка, что пользователь является модератором этого форума
if (!is_numeric($ext_id)) return;

$rp = se_db_query(
  "SELECT moderator, id_topic
  FROM forum_forums, forum_topic, forum_msg
  WHERE forum_msg.id='$ext_id' AND forum_topic.id=forum_msg.id_topic AND
        forum_forums.id=forum_topic.id_forums"
);

$priv=se_db_fetch_array($rp);

if ($priv['moderator']==$uid || $smod) {

  $rm = se_db_query(
    "DELETE
    FROM forum_msg
    WHERE id='$ext_id'"
  );

  //Если в теме нет сообщений, удаляем ее
  $id_topic=$priv['id_topic'];
  $rt = se_db_query(
    "SELECT id
    FROM forum_msg
    WHERE forum_msg.id_topic='$id_topic'"
  );

  if (se_db_num_rows($rt)==0)
    $rm = se_db_query(
      "DELETE
      FROM forum_topic
      WHERE id='$id_topic'"
    );

  //Удаление приаттаченных файлов

  $ra=se_db_query(
    "SELECT file
    FROM forum_attached
    WHERE id_msg='$ext_id'"
  );

  while ($file=se_db_fetch_array($ra)) {
    @unlink("modules/forum/upload/".$file['file']);
    @unlink("modules/forum/upload/".substr($file['file'], 0, strlen($file['file'])-4)."-1".substr($file['file'], -4));
  }

  se_db_query(
    "DELETE
    FROM forum_attached
    WHERE id_msg='$ext_id'"
  );

  Header("Location: http://".$_SERVER['HTTP_HOST']."/".$_page."?act=showtopic&id=$id_topic&".time());
  exit();
}
else
  return;

?>