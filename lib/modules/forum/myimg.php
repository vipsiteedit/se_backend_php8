<?php

Global $_page;
//
// Проверка сессии
@$ext_sid=htmlspecialchars($_GET['sid']);

$rs=se_db_query(
  "SELECT id_users
  FROM forum_session
  WHERE sid='$ext_sid'");

$s=se_db_fetch_array($rs);

if ($s['id_users']!=$uid) return;

if (isset($_GET['delete'])) {
  //Удаляем картинку из базы
  $ru = se_db_query("
  UPDATE forum_users
  SET `img`=''
  WHERE id='$uid'"
  );
  //Удаляем файлы картинки
  @unlink("modules/forum/images/$uid.gif");
  @unlink("modules/forum/images/$uid.jpg");
  @unlink("modules/forum/images/$uid.png");

  Header("Location: http://".$_SERVER['HTTP_HOST']."/".$_page."?act=my&".time());
  exit();
}

$userfile=$_FILES['userfile']['tmp_name']; // Временный автосозданный файл
$userfile_size=$_FILES['userfile']['size'];
$origfilename=strtolower(htmlspecialchars($_FILES['userfile']['name'], ENT_QUOTES));

if (!empty($userfile)) {
	$extimg = explode('.',$origfilename);
	$fnam = $extimg[0]; // имя файла до расширения
	$ext = $extimg[1]; // расширение
	$previmg = @$fnam.'_prev.'.@$ext; // имя файла создаваемой превьюшки
}
else
	return;

//Проверяем, что загруженный файл - картинка
list($width, $height, $mimetype) = GetImageSize($userfile);
if (!(ereg("^[^\.]+\.(gif|jpg|png)$", $origfilename) && ($mimetype==1 || $mimetype==2 || $mimetype==3))) {
  $forum_echo.= "<div id=message_warning>Выбранный файл не является картинкой в формате GIF/JPG/PNG!</div>
  <div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
  return;
}

if ($width<=100 && $height<=100) {// Если размеры картинки не превышают 100х100// ### Помещаем ту самую картинку, которую загрузил пользователь
	move_uploaded_file($userfile, getcwd().'/modules/forum/images/'.$previmg);
	//if file_exists(getcwd().'/modules/forum/images/'.$previmg)
}else { // Если размеры загруженной картинки превышают допустимые
// ### Создаем маленькую картинку
 	// Вычисляем необходимую ширину и высоту
 	if ($width > $height) {
 		// если ширина больше длины
 		$wdth = 100;
 		$hght = $height * 100 / $width;
 	}
 	else {
		// если длина больше ширины
		$hght = 100;
		$wdth = $width * 100 / $height;
	}

    switch ($ext) {
       	case "gif": $img = imagecreatefromgif($userfile);break;
       	case "png": $img = imagecreatefrompng($userfile);break;
       	default:    $img = imagecreatefromjpeg($userfile);break;
	}

    @$d_im   = imagecreatetruecolor($wdth, $hght);

	@imagecopyresampled($d_im,$img,0,0,0,0,$wdth,$hght,$width,$height);

    switch ($ext){
       	case "gif": @imagegif($d_im, getcwd().'/modules/forum/images/'.$previmg);break;
       	case "png": @imagepng($d_im, getcwd().'/modules/forum/images/'.$previmg);break;
       	default:    @imagejpeg($d_im, getcwd().'/modules/forum/images/'.$previmg, 75);break;
    }

    @imagedestroy($d_im);
    @imagedestroy($img);
}


//Если размер файла больше заданного
//if ($userfile_size > 50024) {
//  $forum_echo.= "<div id=message_warning>Выбранный файл превышает размер 50 Кб!</div>
//  <div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
//  return;
//}

//Определяем размер картинки

//Если размер картинки больше 100x100
//if ($sz[0]>100 || $sz[1]>100) {
//  $forum_echo.= "<div id=message_warning>Размер картинки превышает 100x100px!</div>
//  <div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
//  return;
//}

$uploadfile=getcwd()."/modules/forum/images/$uid.".@$ext; // Полное имя окончательного файла
$filename="$uid.".@$ext; // Базовое имя окончательного файла

if (se_rename(getcwd()."/modules/forum/images/".$previmg, $uploadfile)) {
  //Обновляем данные картинки в базе
  $ru = se_db_query("
  UPDATE forum_users
  SET `img`='$filename'
  WHERE id='$uid'"
  );
}

Header("Location: http://".$_SERVER['HTTP_HOST']."/".$_page."?act=my&".time());
exit();

?>