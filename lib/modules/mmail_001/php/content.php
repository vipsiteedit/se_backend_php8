<?php


if (isRequest('name'))
    $ml001_name  = htmlspecialchars(stripslashes(getRequest('name', 3)),  ENT_QUOTES);    
if (isRequest('email'))
    $ml001_email = htmlspecialchars(stripslashes(getRequest('email')), ENT_QUOTES);
if (isRequest('note'))
    $ml001_note  = htmlspecialchars(stripslashes(getRequest('note', 3)),  ENT_QUOTES);

if (isRequest('GoTo'))
{
    $referer    = explode("/",urldecode($_SERVER['HTTP_REFERER']));
    $refer_page = explode('&',$referer[3]);
    if (preg_match("/^home.*/", $_page))
        $refer_page[0] = 'home';
   // if (!preg_match("/".$_page."/", $refer_page[0]) or ($referer[2] != $_SERVER['HTTP_HOST']))
    //     return;

    $_email = utf8_substr(getRequest('email'), 0, 40);
    $_name  = trim(getRequest('name', 3));
    $_email = trim(getRequest('email'));
    $_note  = utf8_substr(trim(getRequest('note', 3)), 0, $col);

    $flag = true;
    if ($emailAdmin == '')
    {
        $ml001_errtxt = $section->language->lang011;
        $flag = false;
    }

    if (empty($_name) && $flag)
    {
        $ml001_errtxt = $section->language->lang012." ".$section->language->lang005;
        $flag = false;
    }

    if (empty($_email) && $flag)
    {
        $ml001_errtxt = $section->language->lang012." ".$section->language->lang006;
        $flag = false;
    }

    if (!CheckMail($_email) && $flag)
    {
        $ml001_errtxt = $section->language->lang013." ".$section->language->lang006;
        $flag = false;
    }

    if (empty($_note) && $flag)
    {
        $ml001_errtxt = $section->language->lang012." ".$section->language->lang007;
        $flag = false;
    }

    $param11 = $section->parametrs->param11;
    if (($param11!="No") and ($flag))
    {
        @$pin = trim($_POST['pin']);
        require_once getcwd()."/lib/card.php";
        if (!checkcard($pin))
        {
            $ml001_errtxt = $section->language->lang014;
            $flag = false;
        }
    }

    if ($flag)
    {
      

        if (!empty($entertext))
            $mail_text = $entertext."\n\n";
        else
            $mail_text = '';

        $mail_text .= $section->language->lang017.": " . $_name."\n";
        $mail_text .= $section->language->lang018.": " . $_email."\n";
        $mail_text .= "\n".$section->language->lang015.": \n". $_note;

       if (!empty($closetext))
            $mail_text .= "\n\n".$closetext;

      
      
      $subj = $section->language->lang004." ".$_SERVER['HTTP_HOST'];
      if ($_email == $adminmail) $_email = 'noreply@' . $_SERVER['HTTP_HOST'];
      
      $str_mail = '@mail';  
      $find_mail = strpos($_email, $str_mail);
      if($find_mail == true){
        $_email = 'noreply@' . $_SERVER['HTTP_HOST'];  
      }
      
      $from = "=?utf-8?b?" . base64_encode($_name) . "?= <". $_email . ">";  
      $adminmail = $section->parametrs->param1; 
                    
      $mailsend = new plugin_mail($subj, $adminmail, $from, $mail_text);
      if ($mailsend->sendfile()) {
            Header('Location: ' . seMultiDir() . "/$_page/$razdel/sub1/");
            exit();
        }
        else
            $ml001_errtxt = $section->language->lang016;
    }

}
?>