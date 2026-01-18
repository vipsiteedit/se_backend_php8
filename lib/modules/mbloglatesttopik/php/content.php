<?php

$lang= substr(SE_DIR, 0, (strlen(SE_DIR)-1)); // заносим в переменную подсайт многосайтового
// стиль который не попал в хтмл я его пока убрал
// <link type="text/css" href="http://jquery.page2page.ru/ui/jqueryui.custom.css" rel="stylesheet" /> 


$posts = new seTable('se_blog_posts');

$posts->select('id, lang, author_id, url, title, short, full, hits, rating, tags, commenting, comment_presence, label, keywords_post, description_post, date_add');
$posts->where("lang='?'", $lang);   // теперь запрос выборка языка
$posts->orderby("id",1);

$postslist = $posts->getList(0,$section->parametrs->param1);
//$commentslist = $comments->getList(); 
//print_r($postslist);
foreach($postslist as $oneposts){

    $comments = new seTable('se_blog_comments');
    $comments->select('id, upid, idfiles, message, author');
    $comments->where("idfiles='?'", $oneposts[id]);
    $commentslist = $comments->getList();
    $oneposts[kolvomsg] = count($commentslist); 
          // делаем из даты русскую дату :) 
       $date=explode("-", $oneposts[date_add]);
       switch ($date[1]){
            case 1: $m=$section->language->lang009; break;
            case 2: $m=$section->language->lang010; break;
            case 3: $m=$section->language->lang011; break;
            case 4: $m=$section->language->lang012; break;
            case 5: $m=$section->language->lang013; break;
            case 6: $m=$section->language->lang014; break;
            case 7: $m=$section->language->lang015; break;
            case 8: $m=$section->language->lang016; break;
            case 9: $m=$section->language->lang017; break;
            case 10: $m=$section->language->lang018; break;
            case 11: $m=$section->language->lang019; break;
            case 12: $m=$section->language->lang020; break;
       }
       $oneposts[date_add_rus]= $date[2].'&nbsp;'.$m.'&nbsp;'.$date[0];
       
       // конец руссификации даты 
  $__data->setItemList($section, 'blogsposlcomm', $oneposts);
}

$posts = new seTable('se_blog_posts');
$posts->select('id,lang, author_id, url, title, short, full, hits, rating, tags, commenting, comment_presence, label, keywords_post, description_post, date_add');
$posts->where("lang='?'", $lang);   // теперь запрос выборка языка
$posts->orderby("hits",1);

$postslist = $posts->getList(0,$section->parametrs->param1);
//$commentslist = $comments->getList(); 
//print_r($postslist);
foreach($postslist as $oneposts){
    $comments = new seTable('se_blog_comments');
    $comments->select('id, upid, idfiles, message, author');
    $comments->where("idfiles='?'", $oneposts[id]);
    $commentslist = $comments->getList();
    $oneposts[kolvomsg] = count($commentslist); 
       

   
   
    
  $__data->setItemList($section, 'blogspopularposts', $oneposts);
}
?>