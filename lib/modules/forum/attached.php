<?php

Global $_page;
//


$userfile=$_FILES['userfile']['tmp_name'];
$userfile_size=$_FILES['userfile']['size'];
$userfname=strtolower(htmlspecialchars($_FILES['userfile']['name'], ENT_QUOTES));

//Определяем, допустим ли такой формат
if (!(ereg("^[^\.]+\.(gif|jpg|png|rar|zip|arj|gz)$", $userfname))) {
  $forum_echo.= "<div id=message_warning>Недопустимый формат! Разрешается прикреплять файлы следующих форматов: gif, jpg, png, rar, zip, arj, gz.</div>
  <div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
  return;
}

@$forum_attached=$_SESSION['forum_attached'];

// проверка на количество файлов
if (count($forum_attached)>=$maxFilesAttached) {
  $forum_echo.= "<div id=message_warning>Количество прикрепленных файлов превышает максимально допустимое!</div>
  <div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
  return;
}

//Проверка на размер файлов для сообщения
$fsize=0;
if (count($forum_attached)>0)
  foreach ($forum_attached as $af) $fsize+=$af['size'];
if ($fsize+$userfile_size>$maxFilesAttachedSize) {
  $forum_echo.= "<div id=message_warning>Суммарный размер файлов для сообщения (".round((($fsize+$userfile_size)/1024), 2)." кБ) превышает максимально допустимый!</div>
  <div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
  return;
}

//Проверка на размер файлов для пользователя
$rms=se_db_query(
  "SELECT SUM(size) AS size
  FROM forum_attached
  WHERE id_user='$uid'");
  
$ms=se_db_fetch_array($rms);
if (($ms['size']+$userfile_size)>$maxFilesAttachedUser) {
  $forum_echo.= "<div id=message_warning>Суммарный размер файлов для Вас (".round((($ms['size']+$userfile_size)/1024), 2)." кБ) превышает максимально допустимый!</div>
  <div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
  return;
}
;


$filename=$uid."-".time().substr($userfname, -4);

  //$uploadfile=getcwd()."/modules/forum/upload/".$filename;
$uploadfile="modules/forum/upload/".$filename;

if (move_uploaded_file($userfile, $uploadfile)) {
  se_db_query(
    "INSERT INTO `forum_attached` (file, realname, id_user, size)
    VALUES('$filename', '$userfname', '$uid', '$userfile_size')"
  );
  //$forum_attached[]=$filename;
  $forum_attached[$filename]['name']=$userfname;
  $forum_attached[$filename]['size']=$userfile_size;
  $_SESSION['forum_attached']=$forum_attached;

  // Если загруженный файл - изображение
  if (ereg("^.+\.(gif|jpg|png)$", $uploadfile)) {
    //Определяем размер картинки
    $sz=GetImageSize($uploadfile);
    //Если размер больше 400х300, делаем уменьшенную копию
    if ($sz[0]>400 || $sz[1]>300) {
      //Имя нового файла
      $fnameNewImage=substr($uploadfile, 0, strlen($uploadfile)-4)."-1".substr($userfname, -4);
      //Определяем ширину и высоту новой картинки
      // Если ширина/высоту > 4/3
      if (($sz[0]/$sz[1]) > (4/3)) {
        $wimr=400;
        $himr=round($sz[1]*(400/$sz[0]));
      }
      else {
        $himr=300;
        $wimr=round($sz[0]*(300/$sz[1]));
      }
      $imr=imagecreatetruecolor($wimr, $himr);
      //Преобразуем в зависимости от формата
      if ($sz[2]==1) {
        $im=imagecreatefromgif($uploadfile);
        imagecopyresized ($imr, $im, 0, 0, 0, 0, $wimr, $himr, $sz[0], $sz[1]);
        imagegif($imr, $fnameNewImage);
      }
      elseif ($sz[2]==2) {
        $im=imagecreatefromjpeg($uploadfile);
        imagecopyresized($imr, $im, 0, 0, 0, 0, $wimr, $himr, $sz[0], $sz[1]);
        imagejpeg($imr, $fnameNewImage);
      }
      elseif ($sz[2]==3) {
        $im=imagecreatefrompng($uploadfile);
        imagecopyresized($imr, $im, 0, 0, 0, 0, $wimr, $himr, $sz[0], $sz[1]);
        imagepng($imr, $fnameNewImage);
      }
      imagedestroy($im);
      imagedestroy($imr);
    }

  $_SESSION['forum_msgtext'].="\n[attimg src=$userfname]";
  }
  else
    $_SESSION['forum_msgtext'].="\n[attfile src=$userfname]";
}


Header("Location: ".$_SERVER['HTTP_REFERER']."#edit");
exit();

?>