<?php

// показать субстраницу для текущей новости
if (!empty($id)) {
    $val = $clnews->getItem($id);
    if ($val['active'] == 'N') {
        header("Location: ".seMultiDir()."/".$_page."/"); 
    }
    $__data->page->title = $val['title'];
    $__data->page->titlepage = htmlspecialchars(strip_tags(($val['seotitle']) ? $val['seotitle'] : $val['title']));
    
    $val['note'] = ($val['note']) ? $val['note'] :  se_LimitString(strip_tags($val['text']), $nchar, ' ..');
    $__data->page->description = htmlspecialchars(($val['description']) ? $val['description'] : se_LimitString(strip_tags($val['note']), 500, ''));
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
    if (empty($val['imagelist']) && $val['img']) $val['imagelist'][] = array('id'=>0, 'picture'=>$val['img'], 'picture_alt'=>htmlspecialchars($val['title'])); 
    $imagelistcount = count($val['imagelist']);
    foreach($val['imagelist'] as $new) {
      if ($new['picture']) {
          $new['image'] = se_getDImage('/images/rus/newsimg/' . $new['picture'], $section->parametrs->param4, 'm');
          $new['image_prev'] = se_getDImage('/images/rus/newsimg/' . $new['picture'], $section->parametrs->param39, 'm');
      }
      $new['image_alt'] = htmlspecialchars($new['picture_alt']);
      $new['title'] = $new['picture_alt'];
      
      $__data->setItemList($section, 'imagelist',$new);
    }
}

?>