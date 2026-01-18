<?php

if (seUserGroup() < $group_num) {
    return;
}     
if (!isRequest('id')) {
    return; //это проверочка отсылает в небеса тех кто прописывает ссылку на вторую субстраницу ручками
}

$filecatalog = new seTable('filecatalog');
$id = getRequest('id', 1);
$filecatalog->find($id);
$_page = $filecatalog->page; 
$filecatalogmsg = new seTable('filecatalogmsg');
$idmsg = getRequest('idmsg', 1);
$filecatalogmsg->find($idmsg);
$text  = $filecatalogmsg->message;
// Проверка не модер ли нас посетил который прописан в параметрах
$flag = 0; 
$moderi = $section->parametrs->param47;
$moders = explode(",", $moderi);
for ($j = 0; $j <= count($moders); $j++) {
    $userlogin = trim($moders[$j]); // очищаем от лишних пробелов массив модераторов
    if (($userlogin == seUserLogin()) && (seUserLogin() != "")){
        $flag = 1;
    }
}
// конец проверки на модера    
$author = $filecatalog->user_id;
if ((!(($author != 0) && ($author == seUserId())  || (seUserGroup() == 3))) && ($flag == 0)) {
    return;    // это проверочка не дает левым пользователям менять чужие обьявления
}
if (isRequest('GoTo')) {
    if (isRequest('delkom') && getRequest('delkom')) { //удаляем рисунок
        $filecatalogmsg->delete($idmsg);
        header("Location: /" . $_page . "/" . $razdel . "/sub3/object/" . $id . "/");
        exit();
    }
    $text=(getRequest('text', 3));
    if ($text != "") {
        $filecatalogmsg->message = $text;
        $filecatalogmsg->save();
        header("Location: /" . $_page . "/" . $razdel . "/sub3/object/" . $id . "/");
        exit();
    }
}

?>