<?php

if (isRequest('edit'))
{
    $id = getRequest('edit', 1);
    $_SESSION['edit_message'] = $id;
    $file = se_file($filename);
    usort($file, "cmpar");
    $gueststr = unserialize($file[$id]);
    $postdate = date("m/d/Y",$gueststr['date']);
    $name = stripslashes($gueststr['usrname']);
    $mail = htmlspecialchars(stripslashes($gueststr['usrmail']));
    $note = stripslashes(base64_decode($gueststr['usrnote']));
    $adm_text  = stripslashes(base64_decode($gueststr['admtext']));    

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
       header("Location: /$_page/");
       exit();  
    }

if (isRequest('save'))
{
        //удаление записи
      if (isRequest('del'))
      {
        $data = se_file($filename);
        usort($data, "cmpar");
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
        header("Location: /$_page/$razdel/");     
      }
      $id = getRequest('edit', 1);
      $date = getRequest('date', 3);
      $err = false;
      if (empty($date))
      {
        $dateerror = $section->parametrs->param34;
        $err = true;
      }
      else
      {
        $gueststr['date'] = strtotime($date);
      }
      
      if (empty($__request['usrname']))
      {
        $nameerror = $section->parametrs->param7;
        $err = true;
        $name = $__request['usrname'];
      }
      else
      {
        $gueststr['usrname'] = htmlspecialchars(utf8_substr($__request['usrname'], 0, 50));
      } 
      if (!se_CheckMail($__request['usrmail']))
      {
        $mailerror = $section->parametrs->param8;
        $err = true;
        $mail = $__request['usrmail']; 
      }
      else
      { 
        $gueststr['usrmail'] = htmlspecialchars(utf8_substr($__request['usrmail'], 0, 50));
      } 
      if (empty($__request['note']))
      {
        $noteerror = $section->parametrs->param10;
        $err = true;
        $note = $__request['note'];
      }
      else
      {
      $gueststr['usrnote'] = base64_encode(utf8_substr($__request['note'], 0, intval($section->parametrs->param35)));

      }
      $admtext = getRequest('admtxt', 3);
      $gueststr['admtext'] = base64_encode($admtext);      
      
      //отправляем письмо юзеру если админ чота написал
      $mail_usr = $section->parametrs->param40;
      if (base64_decode($gueststr['admtext']) != $adm_text && !$err && $mail_usr == "Yes" && se_CheckMail($adminmail))
      {
 
        $subject = $section->parametrs->param4;
        $adminmail = $section->parametrs->param1;
        $mail_text = "{$section->parametrs->param4}:\n";
        $mail_text .= $admtext."\n";
        $mail_text .= "{$section->parametrs->param38}: ". $_SERVER['CHARSET_HTTP_METHOD']  . $_SERVER['HTTP_HOST'] . "/$_page";


        $email = $gueststr['usrmail'];
        if ($email == $adminmail) $adminmail = 'noreply@' . $_SERVER['HTTP_HOST'];
        $from = "=?utf-8?b?" . base64_encode($section->parametrs->param43) . "?= ". $_SERVER['HTTP_HOST'] ." <".$adminmail.'>'; 
        $mailsend = new plugin_mail($subject, $email, $from, $mail_text);
        $mailsend->sendfile();            
      }
     
      //берем из файла йп(иначе не пересохранить его)
      if (!$err)
      {
     
        $file = se_file($filename);
        usort($file, "cmpar");
        $tempstr = unserialize($file[$id]);
        $gueststr['ip'] = $tempstr['ip'];

        if (!isRequest('del'))
        {
            //если не удаляем текст то перезаписываем его
            $serstr = serialize($gueststr) . "\n";
            $data = se_file($filename);
            usort($data, "cmpar");
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

        //блокировка по ip
        if (isRequest('block'))
        {
            $file = se_file($filename);
            usort($file, "cmpar");
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
            header("Location: /$_page/$razdel/sub2/");
            exit();  
        }
        else
        { 
            if ($_SESSION['curpage']>1) {                                                                                               
                header("Location: /$_page/$razdel/?p=" . $_SESSION['curpage'] . "#record$id");
            } else {
                header("Location: /$_page/$razdel/#record$id");
            }
            exit();
        }      
    }
}

?>