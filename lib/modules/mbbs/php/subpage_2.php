<?php

if (seUserGroup() < $group_num) {
    return;
}   
if (!isRequest('id')) {
    return; //это проверочка отсылает в небеса тех кто прописывает ссылку на вторую субстраницу ручками
}
$bbs = new seTable('bbs');
$id = getRequest('id', 1);
$bbs->find($id);
if (isRequest('GoTo')) { //изменяем запись
    require_once("lib/lib_images.php"); //Присоединяем графическую библиотеку
//print_r($_FILES['userfile']);
    $width_prew = intval($section->parametrs->param4); 
    if ($width_prew == 0) {
        $width_prew = 100;
    }
    $width = intval($section->parametrs->param5); 
    if ($width == 0) {
        $width = 350;
    }       
    if (empty($req['img']) || is_uploaded_file($_FILES['userfile']['tmp_name'][0])) { 
        $img = se_set_image_prev($width_prew, $width, "bbs", $id);
    } else {
        $img = $req['img'];
    }
    if (isRequest('del') && getRequest('del')) { //удаляем запись
//se_db_query("DELETE QUICK FROM bbs WHERE id='$id';");
        $bbs->delete($id);
        Header("Location: /".$_page."/?".time());
        exit();
    }
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
        $flag=false;
        $errortext = $section->language->lang034;
    }
//обрабатываем url
    if ((utf8_substr($url, 0, 7 )== "http://") && !empty($url)) {
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
        $date = date("Y-m-d");
//$bbs = new seTable('bbs');
//        $bbs->find($id);
        $bbs->id_author = seUserId();
        $bbs->page = $_page;
        $bbs->name = utf8_substr($req['name'], 0, 50);
        $bbs->town = utf8_substr($req['town'], 0, 30);
        $bbs->email = utf8_substr($req['email'], 0, 40);
        $bbs->url = utf8_substr($req['url'], 0, 50);
        $bbs->short = utf8_substr($req['short'], 0, intval($section->parametrs->param37));
        $bbs->text = utf8_substr($req['text'], 0, intval($section->parametrs->param38));
        $bbs->img = $img;
        $bbs->phone = utf8_substr($req['phone'], 0, 15);
        $bbs->save();
        Header("Location: /" . $_page . "/?" . time());
    }
//    Header("Location: /" . $_page . "/?" . time());
} else {
//проверка на инъекции
    $name = $bbs->name;
    $town = $bbs->town;
    $phone = $bbs->phone;
    $email = $bbs->email;
    $url = $bbs->url;
    $short = $bbs->short;
    $text = $bbs->text;
}
$id = $bbs->id;
$_img = $bbs->img;
$author = $bbs->id_author;
if (!($author != 0 && $author == seUserId())  || (seUserGroup() == 3)) {
    return;    // это проверочка не дает левым пользователям менять чужие обьявления
}

?>
