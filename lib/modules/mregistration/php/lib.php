<?php

if (!function_exists('Mail_CheckMail')){
function Mail_CheckMail($name)
{
        if (preg_match("/[0-9a-z_\-]+@([0-9a-z_\-^\.]+\.[a-z]{2,4})$/i", $name, $matches))
 //           if (getmxrr($matches[1], $arr))
            return true;
   return false;
}}   

if (!function_exists('Mail_UserRegistration')){
function Mail_UserRegistration($section, $req)
{   
    $_login = getRequest('username');
    $_last_name = getRequest('last_name');
    $_first_name = getRequest('first_name');
    $_email = getRequest('email');
    $phone = getRequest('phone');
    $_passw = getRequest('passw');
      
      $adminmail = $section->parametrs->param1;
      
      $mail_text =$section->parametrs->param20;
      if ($section->parametrs->param4=='Y') {
        $mail_text .= $section->language->lang017.": ";
        $authkod=substr(md5($_login."USymlQpSK"),0,16);
        $mail_text .= '<a href="http://'.$_SERVER['HTTP_HOST'];
        $mail_text .= "/?activate_code=".$authkod."&login=".$_login."\">{$section->language->lang018}</a> ";
      }
      $mail_text.=$section->parametrs->param23;
      $mail_text = str_replace("\\r\\n", "<br>", $mail_text);
      
      $mail_text = str_replace("[USERNAME]", "$_last_name $_first_name", $mail_text);
      $mail_text = str_replace("[SITENAME]", $_SERVER['HTTP_HOST'], $mail_text);
      $mail_text = str_replace("[USERLOGIN]", se_db_output($_login), $mail_text);
      $mail_text = str_replace("[USERPASSWORD]", se_db_output($_passw), $mail_text);
      $mail_text = str_replace("[USEREMAIL]", se_db_output($_email), $mail_text);

      $from = "=?utf-8?b?" . base64_encode($section->language->lang027)."?= ".$_SERVER['HTTP_HOST']." <$adminmail>";
      $mailsend = new plugin_mail($section->language->lang027, $_email, $from, $mail_text, 'text/html');
      $mailsend->sendfile();
      
     // mail($_email, "USER REGISTRATION", $mail_text,$headers);


      if (!empty($adminmail)){
        $mail_text = $section->parametrs->param24;
        $mail_text = str_replace("\\r\\n", "\r\n", $mail_text);
        $mail_text = str_replace("[USERNAME]", "$_last_name $_first_name", $mail_text);
        $mail_text = str_replace("[SITENAME]", $_SERVER['HTTP_HOST'], $mail_text);
        $mail_text = str_replace("[USERLOGIN]", se_db_output($_login), $mail_text);
        $mail_text = str_replace("[USERPASSWORD]", se_db_output($_passw), $mail_text);
        $mail_text = str_replace("[USEREMAIL]", se_db_output($_email), $mail_text);
        $mail_text = str_replace("\\r\\n", "\r\n", $mail_text);

        $from =  "=?utf-8?b?" . base64_encode($section->language->lang027)."?= ".$_SERVER['HTTP_HOST']." <noreply@".$_SERVER['HTTP_HOST'].">";
        $mailsend = new plugin_mail($section->language->lang027, $adminmail, $from, $mail_text, 'text/html');
        $mailsend->sendfile();

      }
}}
?>