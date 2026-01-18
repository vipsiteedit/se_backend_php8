<?php

//добавление и редактирование новостей
if ($editobject == 'N') {
    return;
}
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
    $filename = '';
    $removeImage = (!empty($_POST['removeImage']));
    // если загружается картинка
    if (is_uploaded_file($_FILES['userfile']['tmp_name'])){                     
        $userfile = $_FILES['userfile']['tmp_name'];
        $userfile_size = $_FILES['userfile']['size'];
        $user = mb_strtolower(htmlspecialchars($_FILES['userfile']['name'], ENT_QUOTES), 'UTF-8');
//Проверяем, что загруженный файл - картинка
        $sz = GetImageSize($userfile);
        if (preg_match("/([^.]+)\.(gif|jpeg|jpg|png)$/u", $user, $m) && (($sz[2] == 1) || ($sz[2] == 2) || ($sz[2] == 3))) {
            $extendfile = $m[2];
        } else {
            $errortext = $section->language->lang009; 
            $flag = false;
        }                  
//Если размер файла больше заданного
        if ($userfile_size > 10240000) {
            $errortext = $section->language->lang010;
            $flag = false;
        }
        $file = true;  
    } //конец обработки картинки
    //  если была ошибка - нет одного из полей
    if (!$flag){
        $date = getRequest('date');
        $_title = getRequest('title', 4);
        $_text = trim($text);//getRequest('text', 3);
    } else {
        $date = getRequest('date');
        $time = strtotime($date);
        $title = getRequest('title', 4);
        $newstext = trim($text);//getRequest('text', 3); 
//$imgname  = 'news'.time();
        $imgname = 'news' . $time;
// если картинка есть
        if ($file) {  
            $uploadfile = getcwd() . $IMAGE_DIR . $imgname . ".".$extendfile;
            $filename = $imgname . '.' . $extendfile;
            move_uploaded_file($userfile, $uploadfile);
            ThumbCreate($uploadfile, $uploadfile, '', $width);
        }
// опубликовать сегодня
        $checkbox = ((getRequest('publics', 3)=='on')? 0:$time); 
        if (empty($id)) {
//при добавлении
            $cat_name = $section->parametrs->param20;
            $newscat = new seTable('news_category', 'nc');
            $newscat->where("nc.ident = '?'", $cat_name);
            $newscat->andWhere("nc.lang = '?'", $lang);
            $newscat->fetchOne();
            $id_cat = $newscat->id;
            if (!$id_cat) {
                $newscat->ident = $cat_name;
                $newscat->title = $cat_name;
                $newscat->lang = $lang;
                $id_cat = $newscat->save();
            }                
            $news->insert();
            $news->id_category = $id_cat;
        } else {
            $news->find($id);  //при редактировании
        }
        $news->news_date = $time;
        $news->pub_date = $checkbox;
        $news->title = $title;
        $news->text = $newstext;
        if ($file || $removeImage) {
            $news->img = $filename;
        }
        if ($news->save()) {
           header("Location: ".seMultiDir()."/" . $_page . '/?' . time());
           exit();
        }              
    } // конец if(!$flag)
} //конец сохранения


// редактирование новости
if ($id) {
    $news->select();
    $news-> find($id);
// сформировать дату            
    $date = date("Y-m-d H:i:s", $news->news_date); 
//получить содержание новости
    $_title = se_db_output($news->title);
    $_text = se_db_output($news->text);
    $filename = se_db_output($news->img);
    if ($filename)
       $news_img = $IMAGE_DIR . $filename;
} else {
// сформировать дату
    $date = date("Y-m-d H:i:s", time());
}
//разбиваем дату
//$_day = $_time[0];
//$_month = $_time[1];
//$_year = $_time[2];
// активность чекбокса
$checked = '';
if(intval($news->pub_date)==0) 
    $checked = 'checked';

?>
