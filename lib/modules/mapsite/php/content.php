<?php

$sid = session_id();
$antispamon = $section->parametrs->param3;
if(isset($_POST['Send']))
{
    $name  = stripslashes(utf8_substr($_POST['name'], 0, 20));
    $email = trim(stripslashes(utf8_substr($_POST['mail'], 0, 30)));
    $note = stripslashes(utf8_substr($_POST['note'],0, $section->parametrs->param5));
    $err = false;
    //name
    if (empty($name))
    {
        $errstname = "errorinp";
        $err = true;
        $nameerr = $section->parametrs->param6;   
    }
    //email
    if (empty($email))
    {
      $errstmail = "errorinp";
      $err = true;
      $emailerr = $section->parametrs->param7;  
    }
    else
    {
        if (!se_CheckMail($email))
        {
            $errstmail = "errorinp";
            $err = true;
            $emailerr = $section->parametrs->param8;
        }
    }
    //note
    if (empty($note))
    {
        $errstnote = "errorinp";
        $err = true;
        $noteerr = $section->parametrs->param9;   
    }
    //antispam
    if ($antispamon == "Yes")
    {
        if (isset($_POST['pin']))
        {
            if (empty($_POST['pin']))
            {
                $errstpin = "errorinp";
                $errorpin = $section->parametrs->param11;
                $err = true;
            }
            else
            {
                require_once getcwd()."/lib/card.php";
                if(!checkcard($_POST['pin']))
                {
                    $errstpin = "errorinp"; 
                    $errorpin = $section->parametrs->param10;
                    $err = true;
                }
            }
        }   
    }   
    if (!$err)
    {
        if (se_CheckMail($section->parametrs->param1))
        {
           $mail_text = $section->parametrs->param20 . $name . "\n";
           $mail_text .= "E-mail: " . $email . "\n";
           $mail_text .= $section->parametrs->param21."\n";
           $mail_text .= $note;

           if ($email == $adminmail) $email = 'noreply@' . $_SERVER['HTTP_HOST'];
           $from = "=?utf-8?b?" . base64_encode($name) . "?= <".$email.'>'; 
           $subject =  $section->parametrs->param12 . $_SERVER['HTTP_HOST'];
           $adminmail = $section->parametrs->param1;
            
            $mailsend = new plugin_mail($subject, $adminmail, $from, $mail_text);
            if ($mailsend->sendfile()){
                header("Location: /$_page/$razdel/sub1/");
            }
            else {
               $globalerr = $section->parametrs->param14;
            }     
        }
        else
        {
            $globalerr = $section->parametrs->param13;
        }
    }
}
//антиспам
if ($antispamon == "Yes")
{
    $anti_spam = "
                  <tr>
                    <td colspan=\"2\" class=\"tablrow\"><img id=\"pin_img\" src=\"/lib/cardimage.php?session=" . $sid . "\">
                    <div class=\"titlepin\">".$section->parametrs->param4."</div>

                        <input class=\"inp inppin " . $errstpin ."\"" . $glob_err_stryle . " name=\"pin\" maxlength=\"5\" value=\"\" autocomplete=\"off\">
                        <div class=\"err\">".$errorpin."</div>
                    </td> 
                  </tr>
                 ";
}
?>
