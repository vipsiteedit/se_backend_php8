<?php

$lang= substr(SE_DIR, 0, (strlen(SE_DIR)-1)); // заносим в переменную подсайт многосайтового




$comments = new seTable('se_blog_posts', 'p');
$comments->select('p.id pid, p.lang, c.id cid, c.upid, c.idfiles, c.message, c.author');
$comments->leftjoin('se_blog_comments c', 'p.id=c.idfiles');
                  



//$comments = new seTable('se_blog_comments', 't1');
//$comments->select('id, upid, idfiles, message, author');
$comments->where("p.lang='?'", $lang); 
$comments->andwhere('(c.id) in (
         select max(id)
           from se_blog_comments
         group by idfiles
       )'); 
//echo $comments->getSQL();       
$comments->orderby("cid",1);

$commentslist = $comments->getList(0,$section->parametrs->param1);

//$commentslist = $comments->getList(); 
foreach($commentslist as $onecomm){
 
 
 $posts = new seTable('se_blog_posts');
 //$posts->where("lang='?'", $lang);   // теперь запрос выборка языка

 $posts->find($onecomm['idfiles']);
  $onecomm['title']=$posts->title;
  $onecomm['url']=$posts->url;
  $__data->setItemList($section, 'blogsposlcomm', $onecomm);
}
?>