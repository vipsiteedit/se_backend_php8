<?php

// ############################################ 
// #### Обработка кнопки "Отправить"
// ####
// echo "lksfdnh";
//print_r($section->objects);
//echo '<br>';
if (isRequest('GoTo')) {
    $referer_arr = explode("/", urldecode($_SERVER['HTTP_REFERER']));
    $refer_page[0] = 'home';
    $emailAdmin = str_replace(';', ',', $emailAdmin);
    $emailAdmin = trim(htmlspecialchars($emailAdmin, ENT_QUOTES));
    list($email_head) = explode(',', $emailAdmin);
    $flag = true;
    // ##################################
    // ### Проверки ввода
    // ###
    // === проверка объектов на наличие там записей!!
    $i = 1;
    $n = 0;
    foreach ($section->objects as $item) {
        if (isRequest("formtitle" . $item->id)) {
            if (isRequest("formcheck" . $item->id)) {
                if (empty($_POST["formobj" . $item->id])) {
                    $flag = false;
                    $ank_err_text = $section->parametrs->param44;
                    break;
                }
            } else if (isRequest('isfile' . $item->id)) {
                $upfile = $_FILES["formobj" . $item->id];
                if (is_uploaded_file($upfile['tmp_name']) && (round($upfile['size'] / 1024) > intval($section->parametrs->param51))) {
                    $flag = false;
                    $ank_err_text = "Один из загружаемых файлов превышает размер:" . $section->parametrs->param51 . 'Кб';
                    break;
                } 
            } 
        }
    }
    // == конец проверки
    // Проверяем e-mail администратора (указан в параметрах) - один или список   
    if ($emailAdmin == '') { // если пусто - сообщение об ошибке
        $ank_err_text = $section->parametrs->param15;
        $flag = false;
    } else { // если не пусто - проверяем на валидность e-mail адресов, перечисленных в параметре
        $emailAdmin_arr = explode(',', $emailAdmin);
        foreach($emailAdmin_arr as $v) {
            $v = trim($v);
            if (!se_CheckMail($v)) {
                $ank_err_text = $section->parametrs->param40 . ": $v"; // выводим сообщение об ошибке конкретного адреса
                $flag = false;
                break;
            }        
        }
    }
   // Проверяем ввод поля "Имя"
    if (($section->parametrs->param21 == 'Yes') && $flag && isset($_POST['name'])) { // если показывать поле имя
        $_POST['name'] = stripslashes(trim($_POST['name']));
        if (($section->parametrs->param23 == 'Yes') && empty($_POST['name'])) { // если имя - обязательное поле, и оно пусто
            $ank_err_text = $section->parametrs->param16 . " " . $section->parametrs->param22;
            $flag = false;
        }
        if (($section->parametrs->param23 == 'No') && empty($_POST['name'])) { // если имя - НЕобязательное поле, и оно пусто
            $_POST['name'] = ' - ';
        }
    }
    // Проверяем ввод поля "Адрес"
    if (($section->parametrs->param24 == 'Yes') && $flag && isset($_POST['address'])) { // если показывать поле адрес
        $_POST['address'] = stripslashes(trim($_POST['address']));
        if (($section->parametrs->param26 == 'Yes') && empty($_POST['address'])) { // если адрес - обязательное поле, и оно пусто
            $ank_err_text = $section->parametrs->param16 . " " . $section->parametrs->param25;
            $flag = false;
        }
        if (($section->parametrs->param26 == 'No') && empty($_POST['address'])) { // если адрес - НЕобязательное поле, и оно пусто
            $_POST['address'] = ' - ';
        }
    }
    // Проверяем ввод поля "Телефон"
    if (($section->parametrs->param27 == 'Yes') && $flag && isset($_POST['phone'])) { // если показывать поле телефон
        $_POST['phone'] = stripslashes(trim($_POST['phone']));
        if (($section->parametrs->param29 == 'Yes') && empty($_POST['phone'])) { // если телефон - обязательное поле, и оно пусто
            $ank_err_text = "{$section->parametrs->param16} ".$section->parametrs->param28;
            $flag = false;               
        }
        if (!preg_match("/^\+?[\d\s\-]*(?:\([\d\s\-]+\)[\d\s\-]*)?$/i", $_POST['phone']) && !empty($_POST['phone'])) {
            $ank_err_text = $section->parametrs->param46;
            $flag = false;
        }
        if (($section->parametrs->param29 == 'No') && empty($_POST['phone'])) { // если телефон - НЕобязательное поле, и оно пусто
            $_POST['phone'] = ' - ';
        }
    }
    // Проверяем ввод поля "E-mail"
    if (($section->parametrs->param38 == 'Yes') && $flag && isset($_POST['email'])) { // если показывать поле e-mail
        $_POST['email'] = stripslashes(trim($_POST['email']));
        if (($section->parametrs->param39 == 'Yes') && empty($_POST['email'])) { // если обязательное поле, и оно пусто
            $ank_err_text = "{$section->parametrs->param16}" . " " . $section->parametrs->param10;
            $flag = false;
        }
        if (($section->parametrs->param39 != 'Yes') && empty($_POST['email'])) { // если НЕобязательное поле, и оно пусто
            $_POST['email'] = ' - ';
        } 
    }
    if (($section->parametrs->param14 == "Yes") && $flag) { 
        if (isset($_POST['pin'])) {
            require_once getcwd()."/lib/card.php";
            if(!checkcard($_POST['pin'])) {
                $ank_err_text = $section->parametrs->param18;
                $flag = false;
            }
        } else {
            $ank_err_text = $section->parametrs->param18;
            $flag = false;
        }
    }
    // ###
    // ### Конец проверок ввода
    // #################################################
    $subj = $section->parametrs->param2; // Тема письма
    if (!empty($entertext)) {
        $mail_text = str_replace('\r\n', "<br>", $entertext)."<br><br>";
    } else {
        $mail_text = '';
    }
    $filename = array();
    foreach ($section->objects as $item) {
        if (isset($_POST["formtitle" . $item->id])) {
            $formt = $item->title;// htmlspecialchars(Anketa_SpecChars(stripslashes($_POST['formtitle' . $item->id])), ENT_QUOTES);
            if (!isset($_POST['isfile' . $item->id])) { 
                $formx = isset($_POST['formobj' . $item->id]) ? strval($_POST['formobj' . $item->id]) : '';
                if (empty($formx)) {
                    if (isset($_POST['ischeckbox' . $item->id])) {
                        $formx = $section->parametrs->param45; // "Нет"
                    } else {
                        $formx = '-';
                    }
                } 
                $mail_text .= '<b>' . $formt . '</b>';//str_replace('<br>', "\n", $formt); 
                $text = stripslashes($formx) . '<br>';//str_replace('<br>', "\n", stripslashes($formx)) . "\n";
            } else {
                $upfile = &$_FILES["formobj" . $item->id];
                if (is_uploaded_file($upfile['tmp_name'])) {
                    move_uploaded_file($upfile['tmp_name'], $uploadfld . '/' . $upfile['name']);    
                    $filename[] = $uploadfld . '/' . $upfile['name'];
                }
            }
            list($type) = explode('|', $item->text1);
            switch ($type) {
                case 'title':
                    $mail_text .= "<br>";
                    break;
                case '*string':
                    $mail_text .= ": " . $text;
                    break;
                case '*email':
                    $mail_text .= ": " . $text;
                    break;
                case 'email':
                    $mail_text .= ": " . $text;
                    break;
                case 'string':
                    $mail_text .= ": " . $text;
                    break;        
                case '*list':
                    $mail_text .= ": " . $text;
                    break;
                case 'list':
                    $mail_text .= ": " . $text;
                    break;
                case 'field':
                    $mail_text .= ": " . $text;
                    break;
                case 'chbox':
                    $mail_text .= ": " . $text;
                    break;
                case 'radio':
                    $mail_text .= ": " . utf8_substr($text, utf8_strpos($text, ' ') + 1, utf8_strlen($text) - utf8_strpos($text, ' '));
                    break;
                case 'file':
                    break;
            }
        }
    }
    $mail_text .= "<br><br>";
    // --- Блок заявителя ----
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $mail_text .= "<b>{$section->parametrs->param22}</b>:".' '.stripslashes($_POST['name'])."<br>";
    }
    if (isset($_POST['address']) && !empty($_POST['address'])) {
        $mail_text .= "<b>{$section->parametrs->param25}</b>:".' '.stripslashes($_POST['address'])."<br>";
    }
    if (isset($_POST['phone']) && !empty($_POST['phone'])) {
        $mail_text .= "<b>{$section->parametrs->param28}</b>:".' '.stripslashes($_POST['phone'])."<br>";
    }
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $mail_text .= "<b>{$section->parametrs->param10}</b>:".' '.stripslashes($_POST['email'])."<br>";
    }
    // -----------------------            
    if ($closetext != '') {
        $mail_text .= "<br>".str_replace('\r\n', "<br>", $closetext);
    }
    //$from = "From: =?utf-8?b?" . base64_encode($name) . "?=<".$email.'>'; 
    if (!empty($_POST['email']) && $flag) {
        $ret_path = $_POST['email'];
    } else {
        $ret_path = $email_head;
    }
    if ($flag) { //echo 'Отправляем почту!';
        if ($ret_path == $emailAdmin) {
            $ret_path = 'noreply@' . $_SERVER['HTTP_HOST'];
        }
        $from = "=?utf-8?b?" . base64_encode($section->parametrs->param2) . "?= " . $_SERVER['HTTP_HOST'] ." <" . $ret_path . '>';
        //$filename = 'c:\text.txt;c:\about.dat';
        $mailsend = new plugin_mail($subj, $emailAdmin, $from, $mail_text, 'text/html', join(';', $filename));
        if ($mailsend->sendfile()) {
//*
            foreach ($filename as $v) {
                @unlink($v);
            }
//*/
            header("Location: $PAGE/$razdel/sub1/");
            exit();
//echo $PAGE;
        } else {
            $ank_err_text = $section->parametrs->param19;//*/
        }
    }
}
?>