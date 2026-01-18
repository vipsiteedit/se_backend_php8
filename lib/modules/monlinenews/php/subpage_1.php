<?php

// показать субстраницу для текущей новости
if (!empty($id)) {
    $news->select("id, title, short_txt, text, img, active, pub_date");
    $news->find($id);
    if ($news->active == 'N') {
        header("Location: ".seMultiDir()."/".$_page."/"); 
    }
    $__data->page->titlepage = htmlspecialchars($news->title);            
    $news_title = $news->title;
    if (strpos($news->text,'<') !== false && strpos($news->text,'>') !== false){
        $news_text = $news->text;
    } else {
        $news_text = nl2br($news->text);//str_replace("\n","<br>", $news->text);
    }
//    echo "[$news_text]";
    if ($news->img != '') {
        $news_img = '<img class="viewImage objectImage" alt="' . 
                        htmlspecialchars($news->title) . '" src="' . $IMAGE_DIR . strval($news->img) . '" border="0">';
    } else {
        $news_img = '';
    }
}

?>