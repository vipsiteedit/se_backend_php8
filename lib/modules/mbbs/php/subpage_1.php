<?php

if (seUserGroup() < $group_num) {
    return;   
}   
if (isRequest('GoTo')) {
    require_once("lib/lib_images.php"); //Присоединяем графическую библиотеку
    $bbs = new seTable('bbs');
    $bbs->select('max(id) as obid');
    $bbs->fetchone();
    $resmax = $bbs->obid; 
    $maxid  = $resmax+1;
    $width_prew = $section->parametrs->param4; 
    if ($width_prew == 0) {
        $width_prew = 100;
    }
    $width = $section->parametrs->param5; 
    if ($width == 0) {
        $width = 350;
    }
    $img = se_set_image_prev($width_prew, $width, "bbs", $maxid);
//считование 
    $name = stripslashes($req['name']);
    $town = stripslashes($req['town']);
    $email = stripslashes($req['email']);
    $url = stripslashes($req['url']);
    $short = stripslashes($req['short']);
    $text = stripslashes($req['text']);
    $phone = trim(stripslashes($req['phone']));
//Предполагаем, что все введеные данные корректны
    $flag = true;
//обрабатываем name
    if (empty($name)) {
        $flag = false;
        $errortext = $section->language->lang032;
    }
//обрабатываем town
    if (empty($town) && $flag) {
        $flag = false;
        $errortext = $section->language->lang033;
    }
    if ($flag && !preg_match("/^\+?[\d\s\-]*(?:\([\d\s\-]+\)[\d\s\-]*)?$/i", $phone) && !empty($phone)) {
        $flag = false;
        $errortext = $section->language->lang038;
    }
//обрабатываем e-mail
    if ($flag && !preg_match("/[0-9a-zA-Z]([0-9a-zA-Z\-\_]+\.)*[0-9a-zA-Z]*@[a-zA-Z0-9]*([0-9a-zA-Z\-\_]+\.)*[0-9a-zA-Z]+\.[a-zA-Z]{2,6}$/iu", $email) && !empty($email)) {
        $flag = false;
        $errortext = $section->language->lang034;
    }
//обрабатываем url
    if ((utf8_substr($url, 0, 7) == "http://") && !empty($url)) {
        $url = utf8_substr($url, 7);
    }
//обрабатываем краткий текст
    if ($flag && empty($short)) {
        $flag = false;
        $errortext = $section->language->lang035;
    }
//обрабатываем текст
    if ($flag && empty($text)) {
        $flag = false;
        $errortext = $section->language->lang036;
    }
    if (!$flag) {  //если есть ошибки, то отправляем в космос
        $_razdel = $razdel;
        $_sub = 1;
    } else {
//добавляем запись
        $date=date("Y-m-d");
//$bbs = new seTable('bbs');
        $bbs->insert();
        $bbs->id_author = seUserId();
        $bbs->page = $_page;
        $bbs->date = date("Y-m-d");
        $bbs->name = utf8_substr($req['name'], 0, 50);
        $bbs->town = utf8_substr($req['town'], 0, 30);
        $bbs->email = utf8_substr($req['email'], 0, 40);
        $bbs->url = utf8_substr($req['url'], 0, 50);
        $bbs->short = utf8_substr($req['short'], 0, intval($section->parametrs->param37));
        $bbs->text = utf8_substr($req['text'], 0, intval($section->parametrs->param38));
        $bbs->img = $img;
        $bbs->phone = utf8_substr($req['phone'], 0, 15);
        if ($bbs->save()) {
            Header("Location: /" . $_page . "/?" . time());
        }
    }
}

?>
