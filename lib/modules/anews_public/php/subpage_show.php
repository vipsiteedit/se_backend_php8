<?php

// показать субстраницу для текущей новости
if (!empty($id)) {
    $val = $clnews->getItem($id);
    if ($val['active'] == 'N') {
        header("Location: ".seMultiDir()."/".$_page."/"); 
    }
    $__data->page->titlepage = htmlspecialchars(strip_tags($val['title']));
    $val['note'] = ($val['note']) ? $val['note'] :  se_LimitString(strip_tags($val['text']), $nchar, ' ..');
    $__data->page->description = htmlspecialchars(se_LimitString(strip_tags($val['note']), 500, ''));
    $news_title = $val['title'];
    if (strpos($val['text'],'<') !== false && strpos($val['text'],'>') !== false){
        $news_text = $val['text'];
    } else {
        $news_text = nl2br($val['text']);//str_replace("\n","<br>", $news->text);
    }
    $news_date = $val['pub_date'];
//    echo "[$news_text]";
    if ($val['image'] != '') {
        $news_img = '<img class="viewImage objectImage" alt="' . 
                        htmlspecialchars($news->title) . '" src="' . $val['image'] . '" border="0">';
    } else {
        $news_img = '';
    }
    if (empty($val['imagelist'])) $val['imagelist'][] = array('id'=>0, 'picture'=>$val['image'], 'picture_alt'=>htmlspecialchars($val['title'])); 
    foreach($val['imagelist'] as $new) {
      $new['picture_alt'] = htmlspecialchars($new['picture_alt']);
      $__data->setItemList($section, 'imagelist',$val['imagelist']);
    }
}

?>