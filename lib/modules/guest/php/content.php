<?php

$antispamon = $section->parametrs->param2;
$sid = session_id();
$usrmail_inform = stripslashes(htmlspecialchars($__request['usrmail']));
$usrname_inform = stripslashes($__request['usrname']);
$usrnote_inform = stripslashes($__request['note']); 

/*
$script = '';

if (isset($_SESSION['edit_message'])) {
    $script = 
"<script type='text/javascript'>
    document.location='#record".$_SESSION['edit_message']."';
</script>";
    unset($_SESSION['edit_message']);
}
//*/
if (isRequest('p')) {
    $_SESSION['curpage'] = getRequest('p', 1);
} else {
    $_SESSION['curpage'] = 1;
}

//обработка кнопки
if (isset($_POST['Save'])) {
    if (!file_exists($filename)) {
        $file = se_fopen($filename, "w");
        fclose ($file);
    }
    if (!file_exists($filenameip)) {
        $file = se_fopen($filenameip, "w");
        fclose ($file);
    }
    if (!file_exists($sessfile)) {
        $file = se_fopen($sessfile, "w");
        fclose ($file);
    }

  //проверяем сесии
    $fileses = se_file($sessfile);
    foreach ($fileses as $id => $str) {
        $unstr = unserialize($str);
        if (time() > $unstr['time']) {
            unset($fileses[$id]);                         
        }
    }
    $fileses = array_values($fileses);
    foreach ($fileses as $str) { 
        $unstr = unserialize($str);
        if (in_array($sid, $unstr)) {
            $blockflag = true;
        }
    }
  //проверка полей
    $errorflag = true;
  //проверяем есть ли ip адрес в заблокированных.
    $block = se_file($filenameip);  
    if (in_array($_SERVER['REMOTE_ADDR'] . "\n", $block)) {
        if (seUserGroup() != 3) {
            $usrblock = $section->parametrs->param5;
            $usrflag = true;
        } 
    }
    if (!$usrblock) {  
        if (($blockflag) && (seUserGroup() != 3)) {
            $usrblock = $section->parametrs->param6;
        } else {
        //имя
            if (empty($_POST['usrname'])) {
                $errstname = "errorinp";
                $errorname = $section->parametrs->param7;
                $errorflag = false;
            }
            //E-mail
            if (!empty($_POST['usrmail'])) {
                if (!se_CheckMail($_POST['usrmail'])) {
                    $errstmail = "errorinp";
                    $errormail = $section->parametrs->param8;
                    $errorflag = false; 
                }  
            } else {
                $errstmail = "errorinp";
                $errormail = $section->parametrs->param9;
      $errorflag = false;  
    }
    
    //Сообщение
    if (empty($_POST['note']))
    {
        $errstnote = "errorinp";
        $errornote = $section->parametrs->param10;
        $errorflag = false;
    }
    else
    {
     $numlinls = $section->parametrs->param36;
     $links = preg_match_all('/(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]/i', $_POST['note'], $ochen);
     if ($links > $numlinls)
     {
        $errstnote = "errorinp";
        $errornote = $section->parametrs->param37;
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
                $errorpin = $section->parametrs->param11;
                $errorflag = false;
            }
        }
        else
        {
            $errstpin = "errorinp";
            $errorpin = $section->parametrs->param12;
            $errorflag = false;
        }
    }
    
    if ($errorflag)
    {
        
        $usrnote = utf8_substr($__request['note'], 0, intval($section->parametrs->param35));
        $gueststr['usrname'] = utf8_substr($__request['usrname'], 0, 50); 
        $gueststr['usrmail'] = utf8_substr($__request['usrmail'], 0, 50);
        $gueststr['usrnote'] = base64_encode($usrnote);
        $gueststr['date'] = time();//strtotime(date("d F Y"));
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
        $sesarr['time'] = time() + $section->parametrs->param13;
        $serstr = serialize($sesarr);
        //пишем сессию со временем в файл
        $session = se_fopen($sessfile, "a");
        flock($session,LOCK_SH);
        fwrite ($session, $serstr . "\n");
        flock($session,LOCK_UN);
        fclose ($session);
        //отправляем email админу что написали что то
        $adminmail = utf8_substr($adminmail,0 , 100);
        $mail_adm = $section->parametrs->param39;
        if (($mail_adm == "Yes") && (se_CheckMail($adminmail)))
        {   
            $subject = $section->parametrs->param3;
            $mail_text = "{$section->parametrs->param16}: " . $gueststr['usrname'] . "\n";
            $mail_text .= "E-mail: " . $gueststr['usrmail'] . "\n";
            $mail_text .= "{$section->parametrs->param24}:\n";
            $mail_text .= $usrnote."\n";
            $mail_text .= "\n\n";
            $mail_text .= "{$section->parametrs->param38}: http://" . $_SERVER['HTTP_HOST'] . "/$_page/\n";
            $email = $gueststr['usrmail'];
            if ($email == $adminmail) $email = 'noreply@' . $_SERVER['HTTP_HOST'];
            $from = "=?utf-8?b?" . base64_encode($section->parametrs->param43) . "?= ". $_SERVER['HTTP_HOST'] ." <".$email.'>'; 
            $mailsend = new plugin_mail($subject, $adminmail, $from, $mail_text);
            $mailsend->sendfile();            
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
//krsort($file);
usort($file, "cmpar");
//постраничная навигация
if (isset($_GET['p']))
{
    $pagen = intval($_GET['p']);
}
else
{
    $pagen = 1;
}
$rowcont = intval($section->parametrs->param33);

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
            $pageselector .=  "<b class=\"activpg arr\">&larr;</b>";
            $pageselector .=  "<b class=\"activpg\">1</b>";   
        }
        else 
        {
            $prevpage = $pagen - 1;
            $pageselector .=  "<a class=\"pagesel arr\" href=\"?p=" . $prevpage . "\">&larr;</a>";
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
            $pageselector .=  "<b class=\"activpg\">" . $pagen . "</b>";
            $pageselector .=  "<b class=\"activpg arr\">&rarr;</b>";
        }
        else
        {
            $nextpage = $pagen + 1;
            $pageselector .=  "<a class=\"pagesel\" href=\"?p=" . $countpage . "\">" . $countpage . "</a>";
            $pageselector .=  "<a class=\"pagesel arr\" href=\"?p=" . $nextpage . "\"> &rarr; </a>";          
        } 
}
$pageselector .= "</div>";
//ссылка на забаненых пользователей
if(seUserGroup() == 3)
{
    $blocked = "<a href=\"[@subpage2]\" id=\"blockip\">{$section->parametrs->param29}</a>";
}
//генерируем html
foreach ($file as $id => $comm_ser)
{
  $adm_ent = '';
  if (($limpagen > $id) && ($id >= $limpagek))
  {
   if($comm_ser != "\n")
   {
    $comm = unserialize($comm_ser); 
    $date = date("d.m.Y",$comm['date']);
    $usrname = stripslashes($comm['usrname']);
    $usrmail = htmlspecialchars(stripslashes($comm['usrmail']));
    $usrnote = nl2br(stripslashes(base64_decode($comm['usrnote'])));
//    echo "[$usrname]-[$usrmail]-[$usrnote]-[" . mb_detect_encoding ($usrnote, 'cp1251,cp1252,utf-8') . "]<br>";
    $adm_text = "";
    if (!empty($comm['admtext']))
    {
        $adm_text =  " 
                        <div class=\"adm_txt\">
                            <label class=\"admin_label\">" . $section->parametrs->param14 . "</label>
                            <div class=\"admtext\">
                                ".nl2br(stripslashes(base64_decode($comm['admtext'])))."
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
    $cont_comm = " 
                    <div class=\"comm\">
                        <div class=\"userdat\">
                            ".$adm_ent."
                            <label class=\"date\">".$date."</label>" . 
                            (($userAccess) ? "<a class=\"name\" href=\"mailto:$usrmail\" name=record$id>$usrname</a>" :
                                             "<span class=\"name\" >$usrname</span>") .      
                        "</div>
                        <div class=\"com_txt\">
                            ".$usrnote."   
                        </div>
                        ".$adm_text."
                    </div>
                 ".$cont_comm;
  }
 }
}

//антиспам
if ($antispamon == "Yes")
{
    $anti_spam = "<tr> 
<td colspan=\"2\" class=\"tablrow\"><img id=\"pin_img\" src=\"/lib/cardimage.php?session=" . $sid .'&'.time(). "\">
<div class=\"titlepin titleTab\">" . $section->parametrs->param15 . "</div>
 <input class=\"inp inppin\" name=\"pin\" maxlength=\"5\" value=\"\">
</td> 
</tr> ";
}
?>