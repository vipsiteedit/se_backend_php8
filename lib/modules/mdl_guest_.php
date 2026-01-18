<?php
//BeginLib
//EndLib
function module_guest($razdel, $section = null)
{
   $__module_subpage = array();
   $__data = seData::getInstance();
   $_page = $__data->req->page;
   $_razdel = $__data->req->razdel;
   $_sub = $__data->req->sub;
   unset($SE);
   if ($section == null) return;
if (empty($section->params[0]->value)) $section->params[0]->value = "[%adminmail%]";
if (empty($section->params[1]->value)) $section->params[1]->value = "Yes";
if (empty($section->params[2]->value)) $section->params[2]->value = "На вашем сайте в гостевой добавлено новое сообщение";
if (empty($section->params[3]->value)) $section->params[3]->value = "На ваше сообщение ответил администратор";
if (empty($section->params[4]->value)) $section->params[4]->value = "Вам запрещено добавлять комментарии!";
if (empty($section->params[5]->value)) $section->params[5]->value = "Вы слишком часто отправляете комментарии";
if (empty($section->params[6]->value)) $section->params[6]->value = "Не корректно введено имя";
if (empty($section->params[7]->value)) $section->params[7]->value = "Вы ввели некорректный E-mail";
if (empty($section->params[8]->value)) $section->params[8]->value = "Вы не ввели E-mail";
if (empty($section->params[9]->value)) $section->params[9]->value = "Вы не ввели текст.";
if (empty($section->params[10]->value)) $section->params[10]->value = "Не верно введено число";
if (empty($section->params[11]->value)) $section->params[11]->value = "Не введено число";
if (empty($section->params[12]->value)) $section->params[12]->value = "30";
if (empty($section->params[13]->value)) $section->params[13]->value = "Ответ Администратора:";
if (empty($section->params[14]->value)) $section->params[14]->value = "Введите цифры с картинки";
if (empty($section->params[15]->value)) $section->params[15]->value = "Имя";
if (empty($section->params[16]->value)) $section->params[16]->value = "E-mail";
if (empty($section->params[17]->value)) $section->params[17]->value = "Ваше сообщение";
if (empty($section->params[18]->value)) $section->params[18]->value = "Отправить";
if (empty($section->params[19]->value)) $section->params[19]->value = "Логин Администратора";
if (empty($section->params[20]->value)) $section->params[20]->value = "Пароль администратора";
if (empty($section->params[21]->value)) $section->params[21]->value = "Логин";
if (empty($section->params[22]->value)) $section->params[22]->value = "Дата";
if (empty($section->params[23]->value)) $section->params[23]->value = "Текст записи";
if (empty($section->params[24]->value)) $section->params[24]->value = "Ответ администратора";
if (empty($section->params[25]->value)) $section->params[25]->value = "Удалить запись";
if (empty($section->params[26]->value)) $section->params[26]->value = "Заблокировать пользователя";
if (empty($section->params[27]->value)) $section->params[27]->value = "Сохранить";
if (empty($section->params[28]->value)) $section->params[28]->value = "Заблокированные адреса";
if (empty($section->params[29]->value)) $section->params[29]->value = "Удалить";
if (empty($section->params[30]->value)) $section->params[30]->value = "Нет заблокированных адресов";
if (empty($section->params[31]->value)) $section->params[31]->value = "Вернутся назад";
if (empty($section->params[32]->value)) $section->params[32]->value = "8";
if (empty($section->params[33]->value)) $section->params[33]->value = "Не верно введена дата";
if (empty($section->params[34]->value)) $section->params[34]->value = "3000";
if (empty($section->params[35]->value)) $section->params[35]->value = "2";
if (empty($section->params[36]->value)) $section->params[36]->value = "В вашем сообщении слишком много ссылок";
if (empty($section->params[37]->value)) $section->params[37]->value = "Ссылка на страницу";
if (empty($section->params[38]->value)) $section->params[38]->value = "Yes";
if (empty($section->params[39]->value)) $section->params[39]->value = "Yes";
if (empty($section->params[40]->value)) $section->params[40]->value = "Адрес";
if (empty($section->params[41]->value)) $section->params[41]->value = "На ваше сообщение ответил администратор:";
$dateerror = "";
getRequestList(&$__request, 'usrmail,usrname,note', VAR_NOTAGS); 
$month_name = array (" ", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$adminmail = $section->params[0]->value;
//coздаем тут всё
if (!file_exists("data/"))
{
    mkdir("data",0740);
}
$filename = "data/m".$_page."_".$razdel.".dat";
$filenameip = "data/m".$_page."_".$razdel.".ip.dat";
$sessfile = "data/m".$_page."_".$razdel.".sess.dat";
if (!file_exists($filename))
{
    $file = se_fopen($filename, "w");
    fclose ($file);
}
if (!file_exists($filenameip))
{
    $file = se_fopen($filenameip, "w");
    fclose ($file);
}
if (!file_exists($sessfile))
{
    $file = se_fopen($sessfile, "w");
    fclose ($file);
}
//перекодируем старую гостевую в новую
if (file_exists($filename))
{
    $file = se_file($filename);
    krsort($file);
    if ($file[0][0] == chr(8))
    {
        $f = se_fopen($filename, "w");
        flock($f,LOCK_SH); 
        foreach($file as $line)
        {
            $oldgst = explode(chr(8),$line);
            $gueststr['usrname'] = iconv('CP1251', 'UTF8', substr($oldgst[2], 0, 20)); 
            $gueststr['usrmail'] = iconv('CP1251', 'UTF8', substr($oldgst[6], 0, 20));
            $gueststr['usrnote'] = base64_encode(iconv('CP1251', 'UTF8', str_replace('<br>',"\n", html_entity_decode(substr($oldgst[4], 0, $section->params[34]->value)))));
            $gueststr['date'] = strtotime(substr($oldgst[1], 0, 2) . " " . $month_name[intval(substr($oldgst[1], 3, 2))] . " " . substr($oldgst[1], 6, 4));
            $gueststr['ip'] = trim($oldgst[7]);
            if (!empty($oldgst[5]))
            {
                $gueststr['admtext'] = base64_encode(iconv('CP1251', 'UTF8', str_replace('<br>',"\n", $oldgst[5])));
            }
            else $gueststr['admtext'] = base64_encode('');
            fwrite($f,serialize($gueststr) . "\n");   
        }
        flock($f,LOCK_UN);
        fclose($f);       
    }
}
//BeginSubPages
if (($razdel != $__data->req->razdel) || empty($__data->req->sub)){
//BeginRazdel
$antispamon = $section->params[1]->value;
$sid = session_id();
$usrmail_inform = stripslashes(htmlspecialchars($__request['usrmail']));
$usrname_inform = stripslashes(htmlspecialchars($__request['usrname']));
$usrnote_inform = stripslashes(htmlspecialchars($__request['note'])); 
//обработка кнопки
if (isset($_POST['Save']))
{
  //проверяем сесии
  $fileses = se_file($sessfile);
  foreach ($fileses as $id => $str)
  {
    $unstr = unserialize($str);
    if (time() > $unstr['time'])
    {
        unset ($fileses[$id]);                         
    }
  }
  $fileses = array_values($fileses);
  foreach ($fileses as $str)
  { 
    $unstr = unserialize($str);
    if (in_array($sid, $unstr))
    {
        $blockflag = true;
    }
  }
  //проверка полей
  $errorflag = true;
  //проверяем есть ли ip адрес в заблокированных.
  $block = se_file($filenameip);  
  if (in_array($_SERVER['REMOTE_ADDR'] . "\n", $block))
  {
    if ($SESSION_VARS['GROUPUSER'] != 3)
    {
        $usrblock = $section->params[4]->value;
        $usrflag = true;
    } 
  }
  if (!$usrblock)
  {  
   if ($blockflag)
   {
    $usrblock = $section->params[5]->value;
   }
   else
   {
    //имя
    if (empty($_POST['usrname']))
    {
        $errstname = "errorinp";
        $errorname = $section->params[6]->value;
        $errorflag = false;
    }
    //E-mail
    if (!empty($_POST['usrmail']))
    {
        if (!se_CheckMail($_POST['usrmail']))
        {
           $errstmail = "errorinp";
           $errormail = $section->params[7]->value;
           $errorflag = false; 
        }  
    }
    else
    {
      $errstmail = "errorinp";
      $errormail = $section->params[8]->value;
      $errorflag = false;  
    }
    //Сообщение
    if (empty($_POST['note']))
    {
        $errstnote = "errorinp";
        $errornote = $section->params[9]->value;
        $errorflag = false;
    }
    else
    {
     $numlinls = $section->params[35]->value;
     $links = preg_match_all('/(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]/i', $_POST['note'], $ochen);
     if ($links > $numlinls)
     {
        $errstnote = "errorinp";
        $errornote = $section->params[36]->value;
        $errorflag = false;  
     }
    }
    //Капча
    if ($antispamon == "Yes")
    { 
        if (isset($_POST['pin']))
        {
            require_once getcwd()."/lib/card.php";
            if(!checkcard($_POST['pin']))
            {
                $errstpin = "errorinp";
                $errorpin = $section->params[10]->value;
                $errorflag = false;
            }
        }
        else
        {
            $errstpin = "errorinp";
            $errorpin = $section->params[11]->value;
            $errorflag = false;
        }
    }
    if ($errorflag)
    {
        $gueststr['usrname'] = utf8_substr($__request['usrname'], 0, 50); 
        $gueststr['usrmail'] = utf8_substr($__request['usrmail'], 0, 50);
        $gueststr['usrnote'] = base64_encode(utf8_substr($__request['note'], 0, intval($section->params[34]->value)));
        $gueststr['date'] = strtotime(date("d F Y"));
        $gueststr['ip'] = $_SERVER['REMOTE_ADDR'];
        //пишем в файл
        $file = se_fopen($filename,"a");
        flock($file,LOCK_SH);
        fwrite($file, serialize($gueststr) . "\n");
        flock($file,LOCK_UN);
        fclose($file);
        //защита от частого написания сообщений
        //пишем сессию и время в файл 
        $sesarr['sid'] = $sid;
        $sesarr['time'] = time() + $section->params[12]->value;
        $serstr = serialize($sesarr);
        //пишем сессию со временем в файл
        $session = se_fopen($sessfile, "a");
        flock($session,LOCK_SH);
        fwrite ($session, $serstr . "\n");
        flock($session,LOCK_UN);
        fclose ($session);
        //отправляем email админу что написали что то
        $adminmail = substr($adminmail,0 , 100);
        $mail_adm = $section->params[38]->value;
        if (($mail_adm == "Yes") && (se_CheckMail($adminmail)))
        {
            $mail_text = "{$section->params[15]->value}: " . $gueststr['usrname'] . "<br>";
            $mail_text .= "E-mail: " . $gueststr['usrmail'] . "<br>";
            $mail_text .= "{$section->params[23]->value}:<br>";
            $mail_text .= base64_decode($gueststr['usrnote']);
            $mail_text .= "<hr>";
            $mail_text .= "<a href=\"http://" . $_SERVER['HTTP_HOST'] . "/$_page\">{$section->params[37]->value}</a>";
            $headers = "From: Guest book " . $_SERVER['HTTP_HOST'] . " <" . $gueststr['usrmail'] . ">\n";
            $headers .= "X-Sender: <" . $gueststr['usrmail'] . ">\n";
            $headers .= "X-Mailer: PHP\n";
            $headers .= "X-Priority: 3\n";
            $headers .= "Content-Type: text/html; charset=utf-8\n";
            $headers .= "Content-Transfer-Encoding: 8bit\n";
            $headers .= "Return-Part: <" . $gueststr['usrname'] . ">\n";
            mail($adminmail, "{$section->params[2]->value}", $mail_text, $headers);
        }
        $usrmail_inform = "";
        $usrname_inform = "";
        $usrnote_inform = ""; 
    }
   }
  }
}
// комментарии
//read file
$cont_comm = "";
$file = se_file($filename); 
krsort($file);
//постраничная навигация
if (isset($_GET['p']))
{
    $pagen = intval($_GET['p']);
}
else
{
    $pagen = 1;
}
$rowcont = intval("{$section->params[32]->value}");
 if ((count($file) % $rowcont) == 0)
 {
    $countpage = intval(count($file) / $rowcont);
 }
 else
 {
    $countpage = intval(count($file) / $rowcont) + 1;  
 }
 if ($pagen == 1)
 {
  $limpagen = count($file);    
 }
 else
 {
  $limpagen = count($file) - ($pagen - 1) * $rowcont;
 }
 $limpagek = $limpagen - $rowcont;
//постраничная навигация отрисовка
$pageselector = "<div class=\"pageselector\">";
if ($countpage > 1)
{
        // первая страница  и стрелочка в лево
        if ($pagen == 1)
        {
            $pageselector .=  "<b class=\"activpg\">&larr;</b>";
            $pageselector .=  "<b class=\"activpg\">1</b>";   
        }
        else 
        {
            $prevpage = $pagen - 1;
            $pageselector .=  "<a class=\"pagesel\" href=\"?p=" . $prevpage . "\">&larr;</a>";
            $pageselector .=  "<a class=\"pagesel\" href=\"?p=1\">1</a>";     
        }
        //точки если есть "роазрыв страниц"
        if ($pagen > 5 )
        {
            $pageselector .=  " ... ";
        }
        //все остальные страницы
        for ($i = 2 ; $i <= $countpage - 1; $i++)
        {   
            if ( ($i >= $pagen - 3) && ($i <= $pagen + 3))
            {
                if ($pagen == $i)
                {
                    $pageselector .=  "<b class=\"activpg\">" . $i . "</b>";
                }
                else
                {
                    $pageselector .=  "<a class=\"pagesel\" href=\"?p=" . $i . "\">" . $i . "</a>";   
                }
             }
        }
        //послендняя страница и стрелочка в право
        if ($pagen <= $countpage - 5)
        {
            $pageselector .=  " ... ";
        }       
        if ($pagen == $countpage)
        {
            $pageselector .=  "<b class=\"activpg\">" . $pagen . "</a>";
            $pageselector .=  "<b class=\"activpg\">&rarr;</a>";
        }
        else
        {
            $nextpage = $pagen + 1;
            $pageselector .=  "<a class=\"pagesel\" href=\"?p=" . $countpage . "\">" . $countpage . "</a>";
            $pageselector .=  "<a class=\"pagesel\" href=\"?p=" . $nextpage . "\"> &rarr; </a>";          
        } 
}
$pageselector .= "</div>";
//ссылка на забаненых пользователей
if(seUserGroup() == 3)
{
    $blocked = "<a href=\"[@subpage2]\" id=\"blockip\">{$section->params[28]->value}</a>";
}
//генерируем html
foreach ($file as $id => $comm_ser)
{
  $adm_ent = '';
  if (($limpagen >= $id) && ($id >= $limpagek))
  {
   if($comm_ser != "\n")
   {
    $comm = unserialize($comm_ser); 
    $date = date("d.m.Y",$comm['date']);
    $usrname = htmlspecialchars(stripslashes($comm['usrname']));
    $usrmail = htmlspecialchars(stripslashes($comm['usrmail']));
    $usrnote = nl2br(stripslashes(htmlspecialchars(base64_decode($comm['usrnote']))));
    $adm_text = "";
    if (!empty($comm['admtext']))
    {
        $adm_text =  " 
                        <div class=\"adm_txt\">
                            <label class=\"admin_label\">{$section->params[13]->value}</label>
                            <div class=\"admtext\">
                                ".nl2br(stripslashes(htmlspecialchars(base64_decode($comm['admtext']))))."
                            </div>
                        </div>
                    ";
    }
    //рисуем стрелочки 
    //if(seUserGroup() == 3)
    //{
        $adm_ent = "
                <a class=\"adm_lnk\" href=\"/$_page/$razdel/sub1/?edit=".$id."\"> »</a>
                ";
    //}
    //пишем блок комментариев
    $cont_comm .= " 
                    <div class=\"comm\">
                        <div class=\"userdat\">
                            ".$adm_ent."
                            <label class=\"date\">".$date."</label> 
                            <a class=\"name\" href=\"mailto:".$usrmail."\">".$usrname."</a>
                        </div>
                        <div class=\"com_txt\">
                            ".$usrnote."   
                        </div>
                        ".$adm_text."
                    </div>
                 ";
  }
 }
}
//антиспам
if ($antispamon == "Yes")
{
    $anti_spam = "<tr> 
<td colspan=\"2\" class=\"tablrow\"><img id=\"pin_img\" src=\"/lib/cardimage.php?session=" . $sid .'&'.time(). "\">
<div class=\"titlepin\">{$section->params[14]->value}</div>
 <input class=\"inp inppin\" name=\"pin\" maxlength=\"5\" value=\"\">
</td> 
</tr> "
                /* "
                  <tr>
                    <td>&nbsp;</td>
                    <td class=\"tablrow\"><img id=\"pin_img\" src=\"/lib/cardimage.php?session=" . $sid . "\"></td> 
                  </tr>
                  <tr>
                    <td class=\"tablrow tabltext\">{$section->params[14]->value}</td>
                    <td class=\"tablrow\">
                        <INPUT class=\"inp " . $errstpin . "\" name=\"pin\" maxlength=\"5\" id=\"idpin\" value=\"\">
                        <div class=\"error\">".$errorpin."</div>
                    </td> 
                  </tr>
                 "//*/;
}
//EndRazdel
}
else{
if(($razdel == $__data->req->razdel) && !empty($__data->req->sub) && ($__data->req->sub==1)){
//BeginSubPage1
if (isRequest('edit'))
{
    $id = getRequest('edit', 1);
    $file = se_file($filename);
    $gueststr = unserialize($file[$id]);
    $postdate = date("m/d/Y",$gueststr['date']);
    $name = htmlspecialchars(stripslashes($gueststr['usrname']));
    $mail = htmlspecialchars(stripslashes($gueststr['usrmail']));
    $note = stripslashes(htmlspecialchars(base64_decode($gueststr['usrnote'])));
    $adm_text  = stripslashes(htmlspecialchars(base64_decode($gueststr['admtext'])));    
}  
if (isRequest('enter'))
    {
        $edit = intval($_GET['edit']);
        $login = htmlspecialchars($_POST['usrlogin']);
        $pass = htmlspecialchars($_POST['usrpass']);                                 
        $pass_md5 = strtoupper(md5($pass));
        if (($adminpassw == $pass_md5) && ($adminlogin == $login))
        {
            $_SESSION['admin'] = true;  
        }
       header("Location: /$_page/$razdel/sub1/edit/$edit/");
       exit();  
    }
if (isRequest('save'))
{
      $id = getRequest('edit', 1);
      $date = getRequest('date', 3);
      $err = false;
      if (empty($date))
      {
        $dateerror = "{$section->params[33]->value}";
        $err = true;
      }
      else
      {
        $gueststr['date'] = strtotime($date);
      }
      if (empty($__request['usrname']))
      {
        $nameerror = "{$section->params[6]->value}";
        $err = true;
        $name = $__request['usrname'];
      }
      else
      {
        $gueststr['usrname'] = htmlspecialchars(substr($__request['usrname'], 0, 50));
      } 
      if (!se_CheckMail($__request['usrmail']))
      {
        $mailerror = "{$section->params[7]->value}";
        $err = true;
        $mail = $__request['usrmail']; 
      }
      else
      { 
        $gueststr['usrmail'] = htmlspecialchars(substr($__request['usrmail'], 0, 50));
      } 
      if (empty($__request['note']))
      {
        $noteerror = "{$section->params[9]->value}";
        $err = true;
        $note = $__request['note'];
      }
      else
      {
      $gueststr['usrnote'] = base64_encode(substr($__request['note'], 0, intval("{$section->params[34]->value}")));
      }
      $gueststr['admtext'] = base64_encode(getRequest('admtxt', 3));      
      //отправляем письмо юзеру если админ чота написал
      $mail_usr = "{$section->params[39]->value}";
      if (base64_decode($gueststr['admtext']) != $adm_text && !$err && $mail_usr == "Yes" && se_CheckMail($adminmail))
      {
        $mail_text = "{$section->params[41]->value}<br>";
        $mail_text .= base64_decode($gueststr['admtext']);
        $mail_text .= "<hr>";
        $mail_text .= "<a href=\"http://" . $_SERVER['HTTP_HOST'] . "/$_page\">{$section->params[37]->value}</a>";
        $headers = "From: Guest Book " . $_SERVER['HTTP_HOST'] ."  <" . "{$section->params[0]->value}" . ">\n";
        $headers .= "X-Sender: <Guest Book>\n";
        $headers .= "X-Mailer: PHP\n";
        $headers .= "X-Priority: 3\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit\n";
        $headers .= "Return-Part: <Guest Book>\n";
        mail($gueststr['usrmail'], "{$section->params[3]->value}", $mail_text, $headers);
      }
      //берем из файла йп(иначе не пересохранить его)
      if (!$err)
      {
        $file = se_file($filename);
        $tempstr = unserialize($file[$id]);
        $gueststr['ip'] = $tempstr['ip'];
        //блокировка по ip
        if (isRequest('block'))
        {
            $file = se_file($filename);
            $gueststr = unserialize($file[$id]);
            $ip = $gueststr['ip'];
            //проверяем есть ли уже такой йп
            $ipfile = se_file($filenameip);
            if (!in_array($ip . "\n",$ipfile))
            { 
                //пишем заблокированный йп
                $file = se_fopen($filenameip,"a");
                flock($ffile,LOCK_SH);
                fwrite($file, $ip . "\n");
                flock($file,LOCK_UN);
                fclose($file);
            } 
        }
        //удаление записи
        if (isRequest('del'))
        {
            $data = se_file($filename);
            unset($data[$id]);
            $data = array_values($data);
            $file = se_fopen($filename,"w");
            flock($file,LOCK_SH);
            foreach ($data as  $str)
            {
                $str = str_replace("\n\n","\n",$str);
                fwrite($file, $str);
            }
            flock($file,LOCK_UN);
            fclose($file);
        }
        else
        {
            //если не удаляем текст то перезаписываем его
            $serstr = serialize($gueststr) . "\n";
            $data = se_file($filename);
            $data[$id] = $serstr;
            $file = se_fopen($filename,"w");
            flock($file,LOCK_SH);
            foreach ($data as $str)
            {
                $str = str_replace("\n\n","\n",$str);
                fwrite($file, $str); 
            }
            flock($file,LOCK_UN);
            fclose($file);
        }
        if (isRequest('block'))
        {
            header("Location: /$_page/$razdel/sub2/");
            exit();  
        }
        else
        {                                                                                                 
            Header("Location: /".$_page);
            exit();
        }      
    }
}
//EndSubPage1
} else
if(($razdel == $__data->req->razdel) && !empty($__data->req->sub) && ($__data->req->sub==2)){
//BeginSubPage2
    $ipfile = se_file($filenameip);
    //eсли удаляем
    if(isRequest('del'))
    {
        $delid = getRequest('del');
        unset($ipfile[$delid]);
        $ipfile = array_values($ipfile);
        $file = se_fopen($filenameip,"w");
        flock($file,LOCK_SH);
        foreach($ipfile as $str)
        {
            $str = str_replace("\n\n","\n",$str);
            fwrite($file, $str);   
        }
        flock($file,LOCK_UN);
        fclose($file); 
    }
    //генерируем хтмл раздела
    $cont_subpage2 = "";
    foreach($ipfile as $id => $ipstr)
    {
        $cont_subpage2 .= "
                <tr>
                    <td class=\"tablerow\"><p class=\"blocktext\">".$ipstr."</p></td>
                    <td class=\"tablerow\"><a class=\"link\" href=\"[@subpage2]?del=".$id."\">{$section->params[29]->value}</td>
                </tr>
                 ";  
    }
    if (empty($ipfile))
    {
        $cont_subpage2 = "
                        <tr>
                            <td class=\"tablerow\" colspan=\"2\">{$section->params[30]->value}</td>
                        </tr>
                        ";
    }
//EndSubPage2
}
}
//EndSubPages
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<div class=\"content\" id=\"cont_guest\" [part.style]>
<noempty:part.title><h3 class=\"contentTitle\"[part.style_title]><span class=\"contentTitleTxt\">[part.title]</span> </h3> </noempty>
<noempty:part.image><img border=\"0\" class=\"contentImage\"[part.style_image] src=\"[part.image]\" alt=\"[part.image_alt]\"></noempty>
<noempty:part.text><div class=\"contentText\"[part.style_text]>[part.text]</div> </noempty>
$blocked 
<form method=\"post\" action=\"\" enctype=\"multipart/form-data\">
<div id=\"guest\">
  <div id=\"comments\">
    $cont_comm
   $pageselector      
  </div>
    <table class=\"tableTable\" id=\"guesttab\" width=\"400\">
    <div class=\"error\">
    $usrblock
    </div> 
      <tbody>
        <tr>
            <td width=\"40%\" class=\"tablrow tabltext\"><span class=\"titleTab\">{$section->params[15]->value}</span></td>               
            <td class=\"tablrow\">
                <input type=\"text\" maxlength=\"50\" class=\"inp $errstname\" value=\"$usrname_inform\" name=\"usrname\">
                <div class=\"error\">$errorname</div>
            </td>  
        </tr>
        <tr>
            <td class=\"tablrow tabltext\"><span class=\"titleTab\">{$section->params[16]->value}</span></td>                
            <td class=\"tablrow\">
                <input type=\"text\" maxlength=\"50\" class=\"inp $errstmail\" value=\"$usrmail_inform\" name=\"usrmail\">
                <div class=\"error\">$errormail</div>
            </td>  
        </tr>
        <tr>
            <td class=\"tablrow\" colspan=\"2\">
                <label class=\"titleTab\">{$section->params[17]->value}</label>
                <br>
                <textarea id=\"textar\" class=\"inp $errstnote\" name=\"note\" rows=\"7\" maxlength=\"{$section->params[34]->value}\" cols=\"36\">$usrnote_inform</textarea>
                <div class=\"error\">$errornote</div> 
            </td>                  
        </tr>
        $anti_spam
        <tr>
            <td class=\"tablrow\" colspan=\"2\">
            <input type=\"submit\" value=\"{$section->params[18]->value}\" name=\"Save\" id=\"but\" class=\"buttonSend\">
            </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<!-- =============== END CONTENT ============= -->";
$__module_subpage[1]['group'] = "3";
$__module_subpage[1]['form'] = "
<script src=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js\" type=\"text/javascript\" ></script>
<script type=\"text/javascript\" >
    $(function(){              
        $(\"#date\").datepicker();                                                                                                                         
    });                                                                                                                             
</script>                                                                                                                             
<style type=\"text/css\">                                                                                                                
/* Component containers
----------------------------------*/
.ui-widget { font-family: Lucida Grande, Lucida Sans, Arial, sans-serif; font-size: 1.1em; }
.ui-widget input, .ui-widget select, .ui-widget textarea, .ui-widget button { font-family: Lucida Grande, Lucida Sans, Arial, sans-serif; font-size: 1em; }
.ui-widget-content { border: 1px solid #dddddd; background: #f2f5f7 url(/skin/ui-bg_highlight-hard_100_f2f5f7_1x100.png) 50% top repeat-x; color: #362b36; }
.ui-widget-content a { color: #362b36; }
.ui-widget-header { border: 1px solid #aed0ea; background: #deedf7 url(/skin/ui-bg_highlight-soft_100_deedf7_1x100.png) 50% 50% repeat-x; color: #222222; font-weight: bold; }
.ui-widget-header a { color: #222222; }
/* Interaction states
----------------------------------*/
.ui-state-default, .ui-widget-content .ui-state-default { border: 1px solid #aed0ea; background: #d7ebf9 url(/skin/ui-bg_glass_80_d7ebf9_1x400.png) 50% 50% repeat-x; font-weight: bold; color: #2779aa; outline: none; }
.ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited { color: #2779aa; text-decoration: none; outline: none; }
.ui-state-hover, .ui-widget-content .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus { border: 1px solid #74b2e2; background: #e4f1fb url(/skin/ui-bg_glass_100_e4f1fb_1x400.png) 50% 50% repeat-x; font-weight: bold; color: #0070a3; outline: none; }
.ui-state-hover a, .ui-state-hover a:hover { color: #0070a3; text-decoration: none; outline: none; }
.ui-state-active, .ui-widget-content .ui-state-active { border: 1px solid #2694e8; background: #3baae3 url(/skin/ui-bg_glass_50_3baae3_1x400.png) 50% 50% repeat-x; font-weight: bold; color: #ffffff; outline: none; }
.ui-state-active a, .ui-state-active a:link, .ui-state-active a:visited { color: #ffffff; outline: none; text-decoration: none; }
/* Interaction Cues
----------------------------------*/
.ui-state-highlight, .ui-widget-content .ui-state-highlight {border: 1px solid #f9dd34; background: #ffef8f url(/skin/ui-bg_highlight-soft_25_ffef8f_1x100.png) 50% top repeat-x; color: #363636; }
.ui-state-highlight a, .ui-widget-content .ui-state-highlight a { color: #363636; }
.ui-state-error, .ui-widget-content .ui-state-error {border: 1px solid #cd0a0a; background: #cd0a0a url(/skin/ui-bg_flat_15_cd0a0a_40x100.png) 50% 50% repeat-x; color: #ffffff; }
.ui-state-error a, .ui-widget-content .ui-state-error a { color: #ffffff; }
.ui-state-error-text, .ui-widget-content .ui-state-error-text { color: #ffffff; }
.ui-state-disabled, .ui-widget-content .ui-state-disabled { opacity: .35; filter:Alpha(Opacity=35); background-image: none; }
.ui-priority-primary, .ui-widget-content .ui-priority-primary { font-weight: bold; }
.ui-priority-secondary, .ui-widget-content .ui-priority-secondary { opacity: .7; filter:Alpha(Opacity=70); font-weight: normal; }
.ui-datepicker { width: 17em; padding: .2em .2em 0;  }
.ui-datepicker .ui-datepicker-header { position:relative; padding:.2em 0; }
.ui-datepicker .ui-datepicker-prev, .ui-datepicker .ui-datepicker-next { position:absolute; top: 2px; width: 2.5em; height: 1.8em; }
.ui-datepicker .ui-datepicker-prev-hover, .ui-datepicker .ui-datepicker-next-hover { top: 1px; }
.ui-datepicker .ui-datepicker-prev { left:2px; }
.ui-datepicker .ui-datepicker-next { right:2px; }
.ui-datepicker .ui-datepicker-prev-hover { left:1px; }
.ui-datepicker .ui-datepicker-next-hover { right:1px; }
.ui-datepicker .ui-datepicker-prev span, .ui-datepicker .ui-datepicker-next span { display: block; position: absolute; left: 50%; margin-left: -12px; top: 50%; margin-top: -8px;  }
.ui-datepicker .ui-datepicker-title { margin: 0 2.3em; line-height: 1.8em; text-align: center; }
.ui-datepicker .ui-datepicker-title select { float:left; font-size:1em; margin:1px 0; }
.ui-datepicker select.ui-datepicker-month-year {width: 100%;}
.ui-datepicker select.ui-datepicker-month, 
.ui-datepicker select.ui-datepicker-year { width: 49%;}
.ui-datepicker .ui-datepicker-title select.ui-datepicker-year { float: right; }
.ui-datepicker table {width: 100%; font-size: .9em; border-collapse: collapse; margin:0 0 .4em; }
.ui-datepicker th { padding: .7em .3em; text-align: center; font-weight: bold; border: 0;  }
.ui-datepicker td { border: 0; padding: 1px; }
.ui-datepicker td span, .ui-datepicker td a { display: block; padding: .2em; text-align: right; text-decoration: none; }
.ui-datepicker .ui-datepicker-buttonpane { background-image: none; margin: .7em 0 0 0; padding:0 .2em; border-left: 0; border-right: 0; border-bottom: 0; }
.ui-datepicker .ui-datepicker-buttonpane button { float: right; margin: .5em .2em .4em; cursor: pointer; padding: .2em .6em .3em .6em; width:auto; overflow:visible; }
.ui-datepicker .ui-datepicker-buttonpane button.ui-datepicker-current { float:left; }
/* with multiple calendars */
.ui-datepicker.ui-datepicker-multi { width:auto; }
.ui-datepicker-multi .ui-datepicker-group { float:left; }
.ui-datepicker-multi .ui-datepicker-group table { width:95%; margin:0 auto .4em; }
.ui-datepicker-multi-2 .ui-datepicker-group { width:50%; }
.ui-datepicker-multi-3 .ui-datepicker-group { width:33.3%; }
.ui-datepicker-multi-4 .ui-datepicker-group { width:25%; }
.ui-datepicker-multi .ui-datepicker-group-last .ui-datepicker-header { border-left-width:0; }
.ui-datepicker-multi .ui-datepicker-group-middle .ui-datepicker-header { border-left-width:0; }
.ui-datepicker-multi .ui-datepicker-buttonpane { clear:left; }
.ui-datepicker-row-break { clear:both; width:100%; }
/* RTL support */
.ui-datepicker-rtl { direction: rtl; }
.ui-datepicker-rtl .ui-datepicker-prev { right: 2px; left: auto; }
.ui-datepicker-rtl .ui-datepicker-next { left: 2px; right: auto; }
.ui-datepicker-rtl .ui-datepicker-prev:hover { right: 1px; left: auto; }
.ui-datepicker-rtl .ui-datepicker-next:hover { left: 1px; right: auto; }
.ui-datepicker-rtl .ui-datepicker-buttonpane { clear:right; }
.ui-datepicker-rtl .ui-datepicker-buttonpane button { float: left; }
.ui-datepicker-rtl .ui-datepicker-buttonpane button.ui-datepicker-current { float:right; }
.ui-datepicker-rtl .ui-datepicker-group { float:right; }
.ui-datepicker-rtl .ui-datepicker-group-last .ui-datepicker-header { border-right-width:0; border-left-width:1px; }
.ui-datepicker-rtl .ui-datepicker-group-middle .ui-datepicker-header { border-right-width:0; border-left-width:1px; }
/* IE6 IFRAME FIX (taken from datepicker 1.5.3 */
.ui-datepicker-cover {
    display: none; /*sorry for IE5*/
    display/**/: block; /*sorry for IE5*/
    position: absolute; /*must have*/
    z-index: -1; /*must have*/
    filter: mask(); /*must have*/
    top: -4px; /*must have*/
    left: -4px; /*must have*/
    width: 200px; /*must have*/
    height: 200px; /*must have*/
}/* Dialog
</style>
<DIV class=\"content\" id=\"cont_guest\">
  <div id=\"subpage1\">
    <form action=\"\" enctype=\"multipart/form-data\" method=\"post\">
        
 <table class=\"tableTable\" border=\"0\">
    <tbody>
        <tr>
            <td class=\"tablerow\"><label class=\"text\">{$section->params[22]->value}</label></td>
            <td class=\"tablerow\"> 
                <input class=\"inp\" id=\"date\" type=\"text\" maxlength=\"10\" name=\"date\" value=\"{$postdate}\">
                <div class=\"error\">{$dateerror}</div> 
            </td> 
        </tr> 
        <tr> 
            <td class=\"tablerow\"><label class=\"text\">{$section->params[15]->value}</label></td> 
            <td class=\"tablerow\">
                <input class=\"inp\" id=\"name\" type=\"text\" name=\"usrname\" value=\"{$name}\">
                <div class=\"error\">{$nameerror}</div>
            </td> 
        </tr> 
        <tr> 
            <td class=\"tablerow\"><label class=\"text\">{$section->params[16]->value}</label></td> 
            <td class=\"tablerow\">
                <input class=\"inp\" id=\"usrmail\" type=\"text\" name=\"usrmail\" value=\"{$mail}\">
                <div class=\"error\">{$mailerror}</div>
            </td> 
        </tr> 
        <tr> 
            <td class=\"tablerow\" colspan=\"2\"> 
                <label class=\"text\">{$section->params[23]->value}</label><br> 
                <div><textarea class=\"inp\" id=\"note\" name=\"note\" rows=\"7\" maxlength=\"255\" cols=\"30\">{$note}</textarea></div>
                <div class=\"error\">{$noteerror}</div> 
                </td> 
        </tr> 
        <tr> 
            <td class=\"tablerow\" colspan=\"2\"> 
                <label class=\"text\">{$section->params[24]->value}</label><br> 
                <div><textarea class=\"inp\" id=\"admtxt\" name=\"admtxt\" rows=\"7\" maxlength=\"255\" cols=\"30\">{$adm_text}</textarea><div>
            </td> 
        </tr> 
        <tr> 
            <td class=\"tablerow\" colspan=\"2\">
                <label class=\"text\"><input class=\"cbox\" id=\"cbox1\" type=\"checkbox\" name=\"del\">{$section->params[25]->value}</label>
            </td> 
        </tr> 
        <tr> 
            <td class=\"tablerow\" colspan=\"2\">
                <label class=\"text\"><input class=\"cbox\" id=\"cbox2\" type=\"checkbox\" name=\"block\">{$section->params[26]->value}</label>
            </td> 
        </tr> 
        <tr> 
            <td colspan=\"2\" class=\"buttonBlock\"> 
                <input class=\"buttonSend saveedit\" type=\"submit\" name=\"save\" value=\"{$section->params[27]->value}\"> 
                <input class=\"buttonSend blockedit\" type=\"button\" onclick=\"document.location = '[@subpage2]'\" name=\"save\" value=\"{$section->params[28]->value}\"> 
            </td> 
        </tr> 
    </tbody> 
</table> 
    </form>
  </div>
</DIV>";
$__module_subpage[2]['group'] = "3";
$__module_subpage[2]['form'] = "<DIV class=\"content\" id=\"cont_guest\">
  <div id=\"subpage2\">
    <table id=\"blocked\">
        <tbody>
            <tr>
                <td class=\"tablerow\"><span id=\"blockcaption\">{$section->params[40]->value}</span></td>
                <td class=\"tablerow\">&nbsp;</td>
            </tr>
            $cont_subpage2
                        
            <tr>
                <td class=\"tablerow\" colspan=\"2\">
                    <input class=\"buttonSend\" type=\"button\" onclick=\"document.location = '".$_page.".html'\" value=\"{$section->params[31]->value}\">
                </td>
            </tr>
        </tbody>
    </table>
  </div>
</DIV>";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};