<?php

Global $titlepage;

if (!isset($_GET['id'])) return;

$ext_id=htmlspecialchars($_GET['id'], ENT_QUOTES);

//Проверяем, является ли пользователь модератором этого форума или супермодератором

if (!$smod) {
  $rm=se_db_query(
    "SELECT name
    FROM forum_forums
    WHERE moderator='$uid' AND id='$ext_id'"
  );
  if (se_db_num_rows($rm)==0) return;
}

if (isset($_POST['doOpen'])) {
  if (isset($_POST['checked'])) {
    $idlist=join(", ", $_POST['checked']);
    se_db_query(
      "UPDATE forum_topic
      SET enable='Y'
      WHERE id IN ($idlist)"
    );
  }
  Header("Location: ".$_SERVER['HTTP_REFERER']);
  exit();
}
elseif (isset($_POST['doClose'])) {
  if (isset($_POST['checked'])) {
    $idlist=join(", ", $_POST['checked']);
    se_db_query(
      "UPDATE forum_topic
      SET enable='N'
      WHERE id IN ($idlist)"
    );
  }
  Header("Location: ".$_SERVER['HTTP_REFERER']);
  exit();
}
elseif (isset($_POST['doOn'])) {
  if (isset($_POST['checked'])) {
    $idlist=join(", ", $_POST['checked']);
    se_db_query(
      "UPDATE forum_topic
      SET visible='Y'
      WHERE id IN ($idlist)"
    );
  }
  Header("Location: ".$_SERVER['HTTP_REFERER']);
  exit();
}
elseif (isset($_POST['doOff'])) {
  if (isset($_POST['checked'])) {
    $idlist=join(", ", $_POST['checked']);
    se_db_query(
      "UPDATE forum_topic
      SET visible='N'
      WHERE id IN ($idlist)"
    );
  }
  Header("Location: ".$_SERVER['HTTP_REFERER']);
  exit();
}
elseif (isset($_POST['doDel'])) {
  if (isset($_POST['checked'])) {
    $idlist=join(", ", $_POST['checked']);

    //Определяем список файлов для удаления
    $rf=se_db_query(
    "SELECT file, id_msg
    FROM forum_attached, forum_msg
    WHERE forum_attached.id_msg=forum_msg.id AND forum_msg.id_topic IN ($idlist)");

    while ($file=se_db_fetch_array($rf)) {
      $msglist[]=$file['id_msg'];
      @unlink("modules/forum/upload/".$file['file']);
      @unlink("modules/forum/upload/".substr($file['file'], 0, strlen($file['file'])-4)."-1".substr($file['file'], -4));
    }

   //Удаляем записи из таблицы файлов
   $msglist=join(", ", $msglist);
    se_db_query(
      "DELETE
      FROM forum_attached
      WHERE forum_attached.id_msg IN ($msglist)"
    );

   //Удаляем сообщения из базы
    se_db_query(
      "DELETE
      FROM forum_msg
      WHERE id_topic IN ($idlist)"
    );

    //Удаляем темы
    se_db_query(
      "DELETE
      FROM forum_topic
      WHERE id IN ($idlist)"
    );
  }
  Header("Location: ".$_SERVER['HTTP_REFERER']);
  exit();
}


else {
  //Выводим список

  $rf=se_db_query(
    "SELECT forum_forums.name AS name, id_area, forum_area.name AS area
    FROM forum_forums, forum_area
    WHERE forum_area.id=forum_forums.id_area AND forum_forums.id='$ext_id'"
  );
  $forum=se_db_fetch_array($rf);
  $forumName=$forum['name'];
  $aid=$forum['id_area'];
  $forum_echo.= "<h3 class=forumTitle id=mdr_Title>Модерирование форума &quot;$forumName&quot</h3>";
  $titlepage=stripslashes(htmlspecialchars($forumName." - Модерирование"));

  $rt = se_db_query("
    SELECT id, name, date_time, date_time_new, visible, enable
    FROM forum_topic
    WHERE forum_topic.id_forums='$ext_id'
    ORDER BY date_time_new desc;"
  );

  $allTopic=se_db_num_rows($rt);
  if ($allTopic==0) {
    $forum_echo.="<div id=message_warning>В данном форуме нет ни одной темы!</div>";
    return;
  }

  $forum_echo.= "<div id=mdr_ThNum>Количество тем: $allTopic</div>";

  $forum_echo.= "<div id=path><a href='?act=main' id=pathlink>".htmlspecialchars($nameForum)."</a><div id=path_strl>$btnPathStrl</div>";
  $forum_echo.= "<a href='?act=showarea&id=$aid' id=pathlink>".htmlspecialchars($forum['area'])."</a></div>";

  //Страницы
  if (isset($_GET['part']))
    $ext_part=htmlspecialchars($_GET['part'], ENT_QUOTES);
  else
    $ext_part=1;

  if ($msgOfPart<se_db_num_rows($rt)) {
    $n=ceil(se_db_num_rows($rt)/$msgOfPart);
    $forum_echo.= "<div id=steplist>|";
    for($i=1; $i<=$n; $i++)
      if ($i==$ext_part)
        $forum_echo.= "<b id=currentpart> $i </b>|";
      else
        $forum_echo.= "<a href='?act=moderforum&id=$ext_id&part=$i' id=otherpart> $i </a>|";
    $forum_echo.= "</div>";
  }
  if ($allTopic!=0) se_db_data_seek($rt, ($ext_part-1)*$msgOfPart);

  $forum_echo.= "<form action='' method=POST><table class=tableForum id=mdr_Table><tbody class=tableBody>
  <tr>
    <td class=\"title\" id=mdr_titleCl>&nbsp;</td>
    <td class=\"title\" id=mdr_titleTh><div id=mdr_tTh>Тема</div></td>
    <td class=\"title\" id=mdr_titleSt><div id=mdr_tSt>Статус</div></td>
    <td class=\"title\" id=mdr_titleVis><div id=mdr_tVis>Видимость</div></td>
    <td class=\"title\" id=mdr_titleCrt><div id=mdr_tCrt>Создана</div></td>
    <td class=\"title\" id=mdr_titleUpd><div id=mdr_tUpd>Обновление</div></td>
  </tr>";

  for ($i=1; ($i<=$msgOfPart) && ($topic=se_db_fetch_array($rt)); $i++) {
    $name=stripslashes(htmlspecialchars($topic['name'], ENT_QUOTES));
    $date = date("d", $topic['date_time'])." ".$month_R[date("m", $topic['date_time'])].date(" Y года в H:i", $topic['date_time']);
    $dateNew = date("d", $topic['date_time_new'])." ".$month_R[date("m", $topic['date_time_new'])].date(" Y года в H:i", $topic['date_time_new']);

    $id=$topic['id'];
    if ($topic['enable']=="Y") $enable="<div id=mdr_fStOn>Открыта</div>"; else $enable="<div id=mdr_fStOff>Закрыта</div>";
    if ($topic['visible']=="Y") $visible="div id=mdr_fVisOn>Включена</div>"; else $visible="div id=mdr_fVisOff>Выключена</div>";

    $forum_echo.= "
    <tr>
      <td class=\"field\" id=mdr_fieldCl><div id=mdr_fCl><input type=checkbox name='checked[]' value='$id' id=mdr_chbSel></div></td>
      <td class=\"field\" id=mdr_fieldTh><div id=mdr_fTh>$name</div></td>
      <td class=\"field\" id=mdr_fieldSt>$enable</td>
      <td class=\"field\" id=mdr_fieldVis><$visible</td>
      <td class=\"field\" id=mdr_fieldCrt><div id=main_date>$date</div></td>
      <td class=\"field\" id=mdr_fieldUpd><div id=main_date>$dateNew</div></td>
    </tr>";
  }


  $forum_echo.= "
  <TR><TD colspan=6>&nbsp;</TD></TR>
  <TR><TD colspan=6>
  <div id=mdr_SrvBtns>
  <input type=submit class=buttonForum id=mdr_bOp name='doOpen' value='Открыть'>
  <input type=submit class=buttonForum id=mdr_bCls name='doClose' value='Закрыть'>
  <input type=submit class=buttonForum id=mdr_bOn name='doOn' value='Включить'>
  <input type=submit class=buttonForum id=mdr_bOff name='doOff' value='Выключить'>
  <input type=submit class=buttonForum id=mdr_bDel name='doDel' value='Удалить' onclick='return confirmDel();'></div></form>
  <script>
    function confirmDel() {
      var is_confirmed = confirm('Вы уверены, что хотите удалить выбранные темы?');
      return is_confirmed;
    }
  </script>
  </TD></TR><TR><TD colspan=6>&nbsp;</TD></TR>";

  $forum_echo.= "</tbody></table>";

    if ($msgOfPart<se_db_num_rows($rt)) {
    $n=ceil(se_db_num_rows($rt)/$msgOfPart);
    $forum_echo.= "<div id=steplist>|";
    for($i=1; $i<=$n; $i++)
      if ($i==$ext_part)
        $forum_echo.= "<b id=currentpart> $i </b>|";
      else
        $forum_echo.= "<a href='?act=moderforum&id=$ext_id&part=$i' id=otherpart> $i </a>|";
    $forum_echo.= "</div>";
  }
}

return;

?>