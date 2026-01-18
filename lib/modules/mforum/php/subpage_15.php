<?php

$thispage = 1;                         
$_message = '';
$_message_exists = 0;
if ($uid==0) { //Если гость
    $_message = $section->language->lang115;
    $_message_exists = 1;
} else {
    if (isRequest('doGo')) {
        $topic = getRequest('topicid', 1);
        $message = htmlspecialchars(utf8_substr(getRequest('message', 3), 0, 10000));
        $message = str_replace("\n\r", "\n", $message);
        $message = str_replace("\r\n", "\n", $message);
//        $nick = substr(getRequest('userfrom', 3), 0, 50);
        $mailfrom = utf8_substr(getRequest('mailfrom', 3), 0, 100);
        $subject = '=?utf-8?b?'.base64_encode(utf8_substr(getRequest('subject', 3), 0, 100)).'?=';
//Определяем получателя
        $ext_id = getRequest('idto',1);
        $tbl = new seTable("forum_users","fu");
        $to = $tbl->find($ext_id);
        unset($tbl);
        if (empty($to)) {
            $thispage = 0;            
        } else {
            if (empty($mailfrom)) {
                $mailfrom = "From: =?utf-8?b?" . base64_encode($nick) . "?= <noreply@" . $_SERVER['HTTP_HOST'].">\n";
            } else {
                $mailfrom = "From: =?utf-8?b?" . base64_encode($nick) . "?= <$mailfrom>\n";
            }
            $mailfrom .= 'Content-type: text/html; charset=utf-8';
            mail($to['email'], $subject, $message, $mailfrom);
//Отправляем сообщение
            $_message = $section->language->lang116;
            $_message_exists = 1;
        }
    } else {  
        if (!isRequest('id') || !isRequest('topic')) {
            $thispage = 0;
        } else {
            $ext_topic = getRequest('topic', 1);
//Определяем e-mail отправителя
            $tbl = new seTable("person","p");
            $tbl->innerjoin("forum_users fu","fu.id_author = p.id ");
            $tbl->where("fu.id = '?'", $uid);
            $rf = $tbl->getList();
            unset($tbl);
            if (!count($rf)&&seUserGroup()!=3) {
                $thispage = 0;
            } else {
                list($from) = $rf;
                $mailfrom = $from['email'];
//Определяем получателя
                $tbl = new seTable("forum_users","fu");
                $to = $tbl->find($ext_id);  
                unset($tbl);              
                if (empty($to)) {
                    $thispage = 0;
                } else {
                    $mailto = $to['email'];
                    $userto = $to['nick'];
//Определяем тему сообщения
                    $tbl = new seTable("forum_topic","ft");
                    $subject = $tbl->find($ext_topic);
                    unset($tbl);
                    if (empty($subject)) {
                        $thispage = 0;
                    } else {
                        $subject = $subject['name'];
//Выводим форму
                    }
                }
            }
        }
    }
}
// 15

?>