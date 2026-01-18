<?php

// проверка от тех кто хочет взломать
if ($flagmoders != 1) {
    if ($user_group < $group_num) {
        header ("Location: $multidir/$_page/?" . time());
        exit();
    } 
}
// конец проверки
$posts = new seTable('se_blog_posts');
$id = getRequest('id', 1);
$posts->find($id);
$namepage = $posts->url; 
$bazamsg = new seTable('se_blog_comments');
$idmsg = getRequest('idmsg', 1);
$bazamsg->find($idmsg);
$text  = $bazamsg->message;
// Проверка не модер ли нас посетил который прописан в параметрах
$flag = 0; 
$moderi = $section->parametrs->param2;
$moders = explode(",", $moderi);
for ($j = 0; $j <= count($moders); $j++) {
    $userlogin = trim($moders[$j]); // очищаем от лиlних пробелов массив модераторов
    if (($userlogin == seUserLogin()) && (seUserLogin() != "")) {
        $flag = 1;
    }
}
// конец проверки на модера    
$author = $posts->author_id;
//!(($author != 0) && ($author == $user_id)  || ($user_group == 3))
if ((($author == 0) || ($author != $user_id))  && ($user_group != 3) && ($flag == 0)) {
    return;    // это проверочка не дает левым пользователям менять чужие обьявления
}
if (isRequest('delkom')) { //удаляем рисунок
    $bazamsg->delete($idmsg);
    header("Location: $multidir/$_page/post/$namepage/");
    exit();
}
if (isRequest('GoTo')) {
    $text = getRequest('text', 3);
    if ($text != "") {
    //$bazamsg->upid = "";
        $bazamsg->update("message", "'" . $text . "'");
        $bazamsg->where("id = '?'", $idmsg);
        $bazamsg->save();
// echo $bazamsg->getsql();
        header("Location: $multidir/$_page/post/$namepage/");
        exit();
    }
}

?>