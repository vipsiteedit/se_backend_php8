<?php

$userfile = $_FILES['userfile']['tmp_name'];
$userfile_size = $_FILES['userfile']['size'];
$userfname = strtolower(htmlspecialchars($_FILES['userfile']['name'], ENT_QUOTES));
//Определяем, допустим ли такой формат
$error = '';
if (!preg_match("/^[^\.]+\.(gif|jpg|jpeg|png|rar|zip|arj|gz)$/u", $userfname)) {
    $error = "{$section->language->lang099} gif, jpg, jpeg, png, rar, zip, arj, gz.";
} else {
    @$forum_attached = $_SESSION['forum_attached'];
// проверка на количество файлов
    if (count($forum_attached)>=$maxFilesAttached) {
        $error = $section->language->lang100;
    } else {
//Проверка на размер файлов для сообщения
        $fsize = 0;
        if (count($forum_attached) > 0) {
            foreach ($forum_attached as $af) {
                $fsize+=$af['size'];
            }
        }
        if ($fsize+$userfile_size > $maxFilesAttachedSize) {
            $error = "{$section->language->lang101} (".round((($fsize+$userfile_size)/1024), 2)." {$section->language->lang102}) {$section->language->lang103}";
        } else {
            $tbl = new seTable("forum_attached","fa");
            $tbl->select("SUM(size) AS size");
            $tbl->where("id_user = '?'", $uid);  
            $ms = $tbl->fetchOne();//se_db_fetch_array($rms);
            unset($tbl);
            if (($ms['size']+$userfile_size)>$maxFilesAttachedUser) {
                $error = "{$section->language->lang104} (".round((($ms['size']+$userfile_size)/1024), 2)." {$section->language->lang102}) {$section->language->lang103}";
            } else {
                $filename = $uid."-".time().substr($userfname, -4);
                $uploadfile = "modules/forum/upload/".$filename;
                if (move_uploaded_file($userfile, $uploadfile)) {
                    $tbl = new seTable("forum_attached","fa");
                    $tbl->file = $filename;
                    $tbl->realname = $userfname;
                    $tbl->id_user = $uid;
                    $tbl->size = $userfile_size;
                    $tbl->save();
                    unset($tbl);
                    $forum_attached[$filename]['name'] = $userfname;
                    $forum_attached[$filename]['size'] = $userfile_size;
                    $_SESSION['forum_attached'] = $forum_attached;
  // Если загруженный файл - изображение
                    if (preg_match ("/^.+\.(gif|jpg|jpeg|png)$/u", $uploadfile)) {
    //Определяем размер картинки
                        $sz = GetImageSize($uploadfile);
    //Если размер больше 400х300, делаем уменьшенную копию
                        if ($sz[0]>400 || $sz[1]>300) {
      //Имя нового файла
                            $fnameNewImage = 
                                utf8_substr($uploadfile, 0, utf8_strlen($uploadfile)-4)."-1".substr($userfname, -4);
      //Определяем ширину и высоту новой картинки
      // Если ширина/высоту > 4/3
                            if (($sz[0]/$sz[1]) > (4/3)) {
                                $wimr = 400;
                                $himr = round($sz[1]*(400/$sz[0]));
                            } else { 
                                $himr = 300;
                                $wimr = round($sz[0]*(300/$sz[1]));
                            }
                            $imr = imagecreatetruecolor($wimr, $himr);
      //Преобразуем в зависимости от формата
                            if ($sz[2] == 1) {
                                $im = imagecreatefromgif($uploadfile);
                                imagecopyresized ($imr, $im, 0, 0, 0, 0, $wimr, $himr, $sz[0], $sz[1]);
                                imagegif($imr, $fnameNewImage);
                            } else if ($sz[2]==2) {
                                $im = imagecreatefromjpeg($uploadfile);
                                imagecopyresized($imr, $im, 0, 0, 0, 0, $wimr, $himr, $sz[0], $sz[1]);
                                imagejpeg($imr, $fnameNewImage);
                            } else if ($sz[2]==3) {
                                $im = imagecreatefrompng($uploadfile);
                                imagecopyresized($imr, $im, 0, 0, 0, 0, $wimr, $himr, $sz[0], $sz[1]);
                                imagepng($imr, $fnameNewImage);
                            }
                            imagedestroy($im);
                            imagedestroy($imr);
                        }
                        $_SESSION['forum_msgtext'] .= "\n[attimg src=$userfname]";
                    } else {
                        $_SESSION['forum_msgtext'] .= "\n[attfile src=$userfname]";
                    }
                }
                Header("Location: ".$_SERVER['HTTP_REFERER']."#edit");
                exit();
            }
        }
    }
}
// 13

?>