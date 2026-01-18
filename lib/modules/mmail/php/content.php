<?php

$antispamon = $section->parametrs->param3 == "Yes";

if ($antispamon) {
    $capcha = new plugin_capcha();
}

if(isset($_POST['Send'])) {
    $name  = stripslashes(utf8_substr($_POST['name'], 0, 20));
    $email = trim(stripslashes(utf8_substr($_POST['mail'], 0, 30)));
    $note = stripslashes(utf8_substr($_POST['note'],0, $section->parametrs->param5));
    $err = false;
    //name
    if (empty($name)) {
        $errstname = "errorinp";
        $err = true;
        $nameerr = $section->parametrs->param6;   
    }
    //email
    if (empty($email)) {
      $errstmail = "errorinp";
      $err = true;
      $emailerr = $section->parametrs->param7;  
    }
    else {
        if (!se_CheckMail($email)) {
            $errstmail = "errorinp";
            $err = true;
            $emailerr = $section->parametrs->param8;
        }
    }
    //note
    if (empty($note)) {
        $errstnote = "errorinp";
        $err = true;
        $noteerr = $section->parametrs->param9;   
    }
    
    if ($section->parametrs->param24 == 'Y' || $section->parametrs->param26 == 'Y') {
        if ($section->parametrs->param24 == 'Y' && !isRequest('personal_accepted')) {
            $errorlicense = $section->language->lang003; 
            $err = true;   
        }
        
        if ($section->parametrs->param26 == 'Y' && !isRequest('additional_accepted')) {
            $errorlicense = $section->language->lang003; 
            $err = true;   
        }
    }
    
    //antispam
    if ($antispamon) {
        $check = $capcha->check();
        if ($check === -10){
            $errstpin = "errorinp";
            $noteerr = $section->parametrs->param11;
            $err = true;
        } elseif (!$check) {
            $errstpin = "errorinp"; 
            $noteerr = $section->parametrs->param10;
            $err = true;
        }
    }   
    if (!$err) {
        if (!empty($admins)) {
           $mail_text = $section->parametrs->param20 . ' '. $name . "\n";
           $mail_text .= "E-mail: " . $email . "\n";
           $mail_text .= $section->parametrs->param21."\n";
           $mail_text .= $note;

           if ($email == $adminmail) $email = 'noreply@' . $_SERVER['HTTP_HOST'];
           $from = "=?utf-8?b?" . base64_encode($name) . "?= <".$email.'>'; 
           $subject =  $section->parametrs->param12 . ' '.  $_SERVER['HTTP_HOST'];
           $adminmail = implode(",", $admins);
            
            $mailsend = new plugin_mail($subject, $adminmail, $from, $mail_text);
            if ($mailsend->sendfile()){
                header("Location: /$_page/$razdel/sub1/");
            }
            else {
               $globalerr = $section->parametrs->param14;
            }     
        }
        else {
            $globalerr = $section->parametrs->param13;
        }
    }
}

if ($antispamon) {
    $anti_spam = $capcha->getCapcha($section->parametrs->param4, $section->parametrs->param10);
}
?>