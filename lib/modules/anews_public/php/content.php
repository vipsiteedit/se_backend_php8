<?php

$newslist = $clnews->getItems(trim($section->parametrs->param20), 0, $limit);
$editobject = ($clnews->isModerator()) ? 'Y' : 'N';
$items = array();
unset($section->newss);

foreach($newslist as $item){
   $item['note'] = str_replace('&nbsp;', '', ($item['note']) ? $item['note'] :  se_LimitString(strip_tags($item['text']), $nchar, ' ..'));
   $item['shownews'] = seMultiDir(). '/show'.$section->parametrs->param20.'/'. $item['id'].'/';
   $item['news_date'] = date('d.m.Y', strtotime($item['news_date']));
   $__data->setItemList($section, 'newss', $item);
}

//удаление новости через панель редактрования
if ($delete_id = getRequest('delete', 1)){
    $result = $clnews->delete($delete_id);
    if ($result['status'] == 'success') {
        Header("Location: ".seMultiDir()."/" . $_page . '/?' . time());
        exit();
    }
}
?>