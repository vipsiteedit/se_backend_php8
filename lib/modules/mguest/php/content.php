<?php

$admin = (seUserGroup() == 3);
$sid = session_id();
$addcomment = false;
$usrmail_inform = stripslashes(htmlspecialchars($__request['usrmail']));
$usrname_inform = stripslashes($__request['usrname']);
$usrnote_inform = stripslashes($__request['note']); 

$antispamon = $section->parametrs->param2 == "Yes";

if ($antispamon) {
    $capcha = new plugin_capcha();
}

//обработка кнопки
if (isset($_POST['SaveGuest'.$section->id])) {
/*
    if (!file_exists($filename)) {
        $file = fopen($filename, "w");
        fclose ($file);
    }
    if (!file_exists($filenameip)) {
        $file = fopen($filenameip, "w");
        fclose ($file);
    }
    if (!file_exists($sessfile)) {
        $file = fopen($sessfile, "w");  
        fclose ($file);
    }
*/    
    //проверяем сесии
    $fileses = file($sessfile);
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
    $block = file($filenameip);  
    if (in_array($remaddr . "\n", $block)) {
        if (seUserGroup() != 3) {
            $usrblock = $section->language->lang031;
            $usrflag = true;
        } 
    }
    if (!$usrblock) {  
        if (($blockflag) && (seUserGroup() != 3)) {
            $usrblock = $section->language->lang032;
        } else {
            //имя
            
            if ($section->parametrs->param44 == 'Y' || $section->parametrs->param46 == 'Y') {
                if ($section->parametrs->param44 == 'Y' && !isRequest('personal_accepted')) {
                    $errorlicense = $section->language->lang043; 
                    $errorflag = false;   
                }
        
                if ($section->parametrs->param46 == 'Y' && !isRequest('additional_accepted')) {
                    $errorlicense = $section->language->lang043; 
                    $errorflag = false;   
                }
            }
            
            if (empty($_POST['usrname'])) {
                $errstname = "errorinp";
                $errorname = $section->language->lang033;
                $errorflag = false;
            }
            //E-mail
            if (!empty($_POST['usrmail'])) {
                if (!se_CheckMail($_POST['usrmail'])) {
                    $errstmail = "errorinp";
                    $errormail = $section->language->lang034;
                    $errorflag = false; 
                }  
            } else {
                $errstmail = "errorinp";
                $errormail = $section->language->lang035;
                $errorflag = false;  
            }
             //Сообщение
            if (empty($_POST['note'])) {
                $errstnote = "errorinp";
                $errornote = $section->language->lang036;
                $errorflag = false;
            } else {
                $numlinls = $section->parametrs->param36;
                $links = preg_match_all('/(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]/i', $_POST['note'], $ochen);
                if ($links > $numlinls) {
                    $errstnote = "errorinp";
                    $errornote = $section->language->lang017;
                    $errorflag = false;  
                }
            }
            if ($_SESSION['guest_nospan'.$section->id] != $_POST['usrcode']) {
                        $errstpin = "errorinp";
                        $errorpin = $section->language->lang040;
                        $errorflag = false;
            }
            
            //Капча
            if ($antispamon) { 
                $check = $capcha->check();
                if ($check === -10){
                    $errstpin = "errorinp";
                    $errorpin = $section->language->lang037;
                    $errorflag = false;
                } elseif (!$check) {
                    $errstpin = "errorinp"; 
                    $errorpin = $section->language->lang038;
                    $errorflag = false;
                }
            }
            if ($errorflag) {
                $usrnote = utf8_substr($__request['note'], 0, intval($section->parametrs->param35));
                $gueststr['usrname'] = utf8_substr($__request['usrname'], 0, 50); 
                $gueststr['usrmail'] = utf8_substr($__request['usrmail'], 0, 50);
                $gueststr['usrnote'] = base64_encode($usrnote);
                $gueststr['date'] = time();//strtotime(date("d F Y"));
                $gueststr['ip'] = $remaddr;
                $gueststr['active'] = 'N';
                $addcomment = ($section->parametrs->param41 == 'Yes');
                
                //пишем в файл
                $file = fopen($filename,"a+");
                flock($file,LOCK_SH);
                fwrite($file, serialize($gueststr) . "\n");
                flock($file,LOCK_UN);
                fclose($file);
                $sesarr['sid'] = $sid;
                $sesarr['time'] = time() + $section->parametrs->param13;
                $serstr = serialize($sesarr);
                //пишем сессию со временем в файл
                $session = fopen($sessfile, "a+");
                flock($session,LOCK_SH);
                fwrite($session, $serstr . "\n");
                flock($session,LOCK_UN);
                fclose($session);
                //отправляем email админу что написали что то
                $adminmail = utf8_substr($adminmail,0 , 100);
                $mail_adm = $section->parametrs->param39;
                if (($mail_adm == "Yes") && se_CheckMail($adminmail)) {   
                    $subject = $section->language->lang029;
                    $mail_text = 
                                "{$section->language->lang027}: " . $gueststr['usrname'] . "\n" .
                                "E-mail: " . $gueststr['usrmail'] . "\n" .
                                "{$section->language->lang011}:\n" .
                                $usrnote . "\n" .
                                "\n\n" .
                                "{$section->language->lang016}: " . _HOST_ . "/$_page/\n";
                    $email = $gueststr['usrmail'];
                    if ($email == $adminmail) { 
                        $email = 'noreply@' . $_SERVER['HTTP_HOST'];
                    }
                    $from = "=?utf-8?b?" . base64_encode($section->language->lang015) . "?= ". $_SERVER['HTTP_HOST'] ." <$email>"; 
                    $mailsend = new plugin_mail($subject, $adminmail, $from, $mail_text);
                    $mailsend->sendfile();            
                }
                $usrmail_inform = "";
                $usrname_inform = "";
                $usrnote_inform = "";
                //header("Location: ?".time());
                //exit; 
            }
        }
    }
}

if ($antispamon) {
    $anti_spam = $capcha->getCapcha($section->language->lang028, $section->language->lang037);
}

// комментарии
$file = array();
if(file_exists($filename)) {
    $file = file($filename);
    usort($file, "cmpar");
}

//постраничная навигация
$rowcont = intval($section->parametrs->param33);
if (!$rowcont) {
    $rowcont = 10;
}
$lists = array();
foreach($file as $item) {
    $it = unserialize($item);
    if ($section->parametrs->param41 == 'Yes' && $it['active']=='N' && seUserGroup() < 3) continue;
    $lists[] = $item;
}



$countdata = count($lists);
$maxpage = intval($countdata / $rowcont);
$maxpage += intval($maxpage * $rowcont < $countdata);
$countpage = ($maxpage) ? $maxpage - 1 : 0;

//постраничная навигация
$pagen = 1;
if (isRequest('p')) {
    $pagen = getRequest('p', 1);
    if (($pagen < 1) || ($pagen > $maxpage)) {
        $pagen = 1;
    }
} else if (isset($_SESSION['curpage'])) {
    $pagen = $_SESSION['curpage'];
}
$_SESSION['curpage'] = $pagen;

$limpagen = $countdata - ($pagen - 1) * $rowcont;
$limpagek = $limpagen - $rowcont;

//постраничная навигация отрисовка
if ($countpage) {
    $prevpage = $pagen - 1;
    $pgbegin = ($pagen > 5) ? $pagen - 3 : 2;
    $pgpoint_end = 0;
    if ($pagen <= $maxpage - 5) {
        $pgend = $pagen + 3;
        $pgpoint_end = 1;
    } else {
        $pgend = $maxpage - 1;
    }
    $nextpage = 0;
    if ($pagen != $maxpage) {
        $nextpage = $pagen + 1;
    } 

    for ($i = $pgbegin; $i <= $pgend; ++$i) {
        $__data->setItemList($section, 'pages', array(
                                                    'pg' => $i,
                                                    'sel' => intval($i == $pagen)
                                                ));
    }
}

$comments = array();
foreach ($lists as $id => $comm_ser) {
    $adm_ent = '';
    if (($limpagen > $id) && ($id >= $limpagek) && ($comm_ser != "\n")) {

        $comm = unserialize($comm_ser);       
        $comment = array(
                        'id' => $id,
                        'date' => date("d.m.Y", $comm['date']),
                        'usrname' => stripslashes($comm['usrname']),
                        'usrmail' => htmlspecialchars(stripslashes($comm['usrmail'])),
                        'usrnote' => nl2br(stripslashes(base64_decode($comm['usrnote']))),
                        'admtext' => nl2br(stripslashes(base64_decode(trim($comm['admtext'])))),
                        'active' => trim($comm['active'])
                    ); 
                                            
        array_unshift($comments, $comment);
    }
}
$__data->setList($section, 'comments', $comments);

$usrcode = md5(time());
$_SESSION['guest_nospan'.$section->id] = $usrcode;
?>
