<?php

//добавление и редактирование новостей
if (!$clnews->isModerator()) {
    return;
}
// редактирование новости
if ($id) {
    $val = $clnews->getItem($id);

// сформировать дату            
    $date = $val['news_date']; 
//получить содержание новости
    $_title = $val['title'];
    $_text = $val['text'];
    $filename = $val['img'];
} else {
// сформировать дату
    $date = date("Y-m-d", time());
}

// активность чекбокса
$checked = '';
if(intval($val['pub_date'])==0) 
    $checked = 'checked';
 
//сохранение 
if (isRequest('Save')) {
    $flag = true;   //нет ошибки
    $file = false;
    $text = preg_replace("/(<!--[\w\W]*?-->)/i", "", $_POST['text']);
    if (empty($_POST['date']) && $flag) {
        $flag = false;
        $errortext = $section->language->lang008;
    } 
    if (empty($_POST['title']) && $flag) {
        $flag = false;
        $errortext = $section->language->lang004;
    }
    if (empty($text) && $flag) {
        $flag = false;
        $errortext = $section->language->lang003;
    }
    $date = getRequest('date');
    $_title = getRequest('title', 4);
    $_text = trim($text);//getRequest('text', 3);

    $result = $clnews->edit($id);
    if ($result['status'] == 'success') {
        Header("Location: ".seMultiDir()."/" . $_page . '/?' . time());
        exit();
    } else {
        $errortext = $result['errortext'];
    }
    $checkbox = ((getRequest('publics', 3)=='on')? 0:$time); 
} //конец сохранения

?>