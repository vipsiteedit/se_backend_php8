<?php


if (isRequest('name'))
    $ml001_name  = htmlspecialchars(stripslashes(getRequest('name', 3)),  ENT_QUOTES);
if (isRequest('phone'))
    $ml001_phone  = htmlspecialchars(stripslashes(getRequest('phone', 3)),  ENT_QUOTES);
if (isRequest('email'))
    $ml001_email = htmlspecialchars(stripslashes(getRequest('email')), ENT_QUOTES);
if (isRequest('note'))
    $ml001_note  = htmlspecialchars(stripslashes(getRequest('note', 3)),  ENT_QUOTES);
//if (isRequest('file'))
//    $ml001_file  = getRequest('file', 3);

$antispamon = $section->parametrs->param13 == "Yes";

if ($antispamon) {
    $capcha = new plugin_capcha();
}

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
    $_phone  = trim(getRequest('phone', 3));
    
    if($section->parametrs->param18=='Y'){
        if($section->parametrs->param17=='N'){
            if(empty($_email)){
                $_email = 'noreply@' . $_SERVER['HTTP_HOST'];
            }else{
                $_email = trim(getRequest('email'));
            }
        }else{
            $_email = trim(getRequest('email'));
        }
    }else{
        $_email = 'noreply@' . $_SERVER['HTTP_HOST'];
    }
    
    $_note  = utf8_substr(trim(getRequest('note', 3)), 0, $col);

    $flag = true;

    if ($section->parametrs->param24 == 'Y' || $section->parametrs->param26 == 'Y') {
        if ($section->parametrs->param24 == 'Y' && !isRequest('personal_accepted')) {
            $ml001_errtxt = $section->language->lang030;   
            $flag = false; 
        }
        
        if ($section->parametrs->param26 == 'Y' && !isRequest('additional_accepted')) {
            $ml001_errtxt = $section->language->lang030; 
            $flag = false;   
        }
    }
    
    $filename = array();
    //$filename = "";
    $upfile = &$_FILES["file"];
    //print_r($_FILES);
    //exit;
    if (is_uploaded_file($upfile['tmp_name'])) {
        move_uploaded_file($upfile['tmp_name'], $uploadfld . '/' . $upfile['name']);    
        $filename[] = $uploadfld . '/' . $upfile['name'];
    }
    
    
    if ($emailAdmin == '')
    {
        $ml001_errtxt = $section->language->lang018;
        $flag = false;
    }

    if (empty($_name) && $flag)
    {
        //$ml001_errtxt = $section->language->lang012." ".$section->language->lang005;
        $flag = false;
    }
    
    /*if (empty($_phone) && $flag)
    {
        //$ml001_errtxt = $section->language->lang012." ".$section->language->lang005;
        $flag = false;
    }*/

    if($section->parametrs->param18=='Y'){
    if (empty($_email) && $flag)
    {
        //$ml001_errtxt = $section->language->lang012." ".$section->language->lang006;
        $flag = false;
    }

    if (!CheckMail($_email) && $flag)
    {
        $ml001_errtxt = $section->language->lang019." ".$section->language->lang014;
        $flag = false;
    }
    }

    /*
    if (empty($_note) && $flag)
    {
        //$ml001_errtxt = $section->language->lang012." ".$section->language->lang007;
        $flag = false;
    }*/

    $param11 = $section->parametrs->param13;
   /* if (($param11!="No") and ($flag))
    {
        @$pin = trim($_POST['pin']);
        require_once getcwd()."/lib/card.php";
        if (!checkcard($pin))
        {
            $ml001_errtxt = $section->language->lang020;
            $flag = false;
        }
    } */
    
    //antispam
    if ($antispamon) {
        $check = $capcha->check();
        if ($check === -10){
            //$errstpin = "errorinp";
            $ml001_errtxt = $section->language->lang016;
            $flag = false;
        } elseif (!$check) {
            //$errstpin = "errorinp"; 
            $ml001_errtxt = $section->language->lang020;
            $flag = false;
        }
    }

    if ($flag)
    {
      

        if (!empty($entertext))
            $mail_text = $entertext."<br><br>";
        else
            $mail_text = '';

        $mail_text .= $section->language->lang021.": " . $_name."<br>";
        
        if($section->parametrs->param19=='Y' || !empty($_phone)){
            $mail_text .= $section->language->lang028.": " . $_phone."<br>";
        }
        
        $mail_text .= $section->language->lang022.": " . $_email."<br>";
        
        if(!empty($_note)){
            $mail_text .= "<br>".$section->language->lang023.": <br>". $_note;
        }

       if (!empty($closetext))
            $mail_text .= "<br>".$closetext;

      
      
      $subj = $section->language->lang003." ".$_SERVER['HTTP_HOST'];
      if ($_email == $adminmail) $_email = 'noreply@' . $_SERVER['HTTP_HOST'];
      $from = "=?utf-8?b?" . base64_encode($_name) . "?= <". $_email . ">";  
            
      $mailsend = new plugin_mail($subj, $emailAdmin, $from, $mail_text, 'text/html',  join(';', $filename));
      if ($mailsend->sendfile()) {
            //Header('Location: ' . seMultiDir() . "/$_page/$razdel/submessage/");
            
            $__data->goSubName($section, 'message');
            //exit();
        }
        else
            $ml001_errtxt = $section->language->lang024;
    }

}

if ($antispamon) {
    $anti_spam = $capcha->getCapcha($section->language->lang016, $section->language->lang020);
}

?>