<?php

 //вывод списка новостей
$wdth = (intval($section->parametrs->param5) > 0) ? intval($section->parametrs->param5) : 100;         
          
// заполнить массив раздела объектами (новостями)
$thisdate = strtotime(gmdate('Y-m-d', time() + 86400));
$news->select("`n`.`id`, LEFT(`n`.`text`,600) as `text`, `n`.img, `n`.title, `n`.`news_date`");
$news->innerjoin("news_category nc", "`n`.id_category = `nc`.id");
$news->Where("nc.lang = '?'", $lang);
if ($editobject == 'N'){
    $news->AndWhere("n.pub_date <= '$thisdate'");
}
$news->andWhere("n.active != 'N'");
$news->orderBy('news_date', 1); //сортировка: 0 от большей даты к меньшей , 1 - наоборот
$news->addOrderBy('id');
if (trim($newskod) != '') {
    $news->andWhere("`nc`.ident = '?'", $newskod);
}
//echo $news->getSQL();
$MANYPAGE = $news->pageNavigator($pagen);
$newslist = $news->getList();
unset($section->objects);
foreach ($newslist as $line) {
    $line['edit'] = " ";
    $notetext = strip_tags($line['text']);
    $id = $line['id'];
    if ($section->parametrs->param35 == 'Y') {
        $line['date'] = date("d.m.y", $line['news_date']);
    } else {
        $line['date'] = date("d.m.y H:i", $line['news_date']);    
    }

    $line['title'] = htmlspecialchars($line['title']);
    if (!empty($line['img'])) {
        $source = $IMAGE_DIR .$line['img'];
        $line['image_prev'] = se_getDImage($source, $wdth);
    }
//ссылка на субстраницу, если есть подробный текст для новости
//$line['link'] = "<br><a class=\"newsLink\" href=\"/{$_page}/{$razdel}/sub1/id/{$id}/\">$section->language->lang001</a>";
    if ((utf8_strlen($notetext) > $nchar) && ($nchar > 0)) {    
        $notetext = str_replace("\n", ' ', $notetext);
        $line['text'] = se_LimitString($notetext, $nchar, ' ...');
    } else {
        $line['text'] = $notetext;
    }
    // Корректировка ссылок
    //if ($razdel < 100000) {
        $line['shownews'] = seMultiDir().'/show'.$section->parametrs->param20.'/'.$line['id'].'/';
    //}
    $__data->setItemList($section, 'newss', $line);
}
//удаление новости через панель редактрования
$delete_id = getRequest('delete', 1);
if (($editobject != 'N') && (!empty($delete_id))) {
    $news->find($delete_id); 
    $filename = $news->img;
    if (!empty($filename)) { 
        $temp = explode(".", $filename);
        $filename = getcwd() . $IMAGE_DIR . $filename;
        if (file_exists($filename)) {
            @unlink($filename); 
        }
    }
    $news->delete($delete_id);
    Header("Location: ".seMultiDir()."/$_page/?" . time());
    exit();
}  
?>
