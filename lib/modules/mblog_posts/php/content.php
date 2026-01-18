<?php

if (isRequest('blog')) {
    $cat = new seTable('blogcategories');
    $cat->where("url='?'", getRequest('blog'));
    $cat->fetchOne();
    $id_cat = $cat->id;
    $__data->page->keywords = $cat->keywords;
    $__data->page->description = $cat->description;
    $__data->page->titlepage = $cat->name; 
} else {
    $id_cat = 0;
}
$newbbs_add_link = intval($user_group >= $group_num);
/*
if (($user_group >= $group_num) ) {
    $newbbs_add_link = '<a class="newob" title="'.$section->language->lang032.'" href="[link.subpage=edit]'.'">' . $section->language->lang032 . "</a>";
} 
*/
$catsql = "(SELECT GROUP_CONCAT(CONCAT_WS('|',`name`,`url`)) " .
            "FROM blogcategories " .
            "WHERE " .
                "id IN (SELECT id_category " .
                        "FROM se_blog_category_post " .
                        "WHERE " .
                            "id_post=sbp.id)) AS categors";
//  Число коментариев в блоге
$blogcount = "(SELECT COUNT(*) FROM se_blog_comments WHERE idfiles = sbp.id) AS coment";
$posts = new seTable('se_blog_posts', 'sbp');
$posts->select("sbp.id, sbp.lang, sbp.url, sbp.commenting, sbp.author_id, sbp.beginning, sbp.rating, sbp.hits,
                sbp.comment_presence, sbp.title, sbp.short, sbp.tags, {$catsql}, {$blogcount}"); 
if (!isRequest('tag') && $id_cat) {
    $posts->innerjoin('se_blog_category_post sbcp', 'sbp.id = sbcp.id_post');
}
$posts->where("sbp.lang = '?'", $lang);
if (isRequest('tag')) {
// Если ищем по тегам
    list($bid, $tid) = explode('-', getRequest('tag', 3));
    $tbl = new seTable('se_blog_posts');
    $tbl->find($bid);
    if ($tbl->id) {
        $tarr = explode(',', $tbl->tags);
        $posts->andWhere("sbp.tags LIKE '%?%'", $tarr[$tid - 1]);
    }
} else if ($id_cat) {
    $posts->andWhere("sbcp.id_category = ?", $id_cat);
}
if (!($user_group >= $group_num)) {
    $posts->andWhere("sbp.comment_presence = 'no'"); // отображаем только НЕ СКРЫТЫЕ
}
$posts->orderby('id', 1);
$posts->groupby('sbp.id');
//echo $posts->getSQL() . '<br>';
$SE_NAVIGATOR = $posts->pageNavigator($section->parametrs->param1);
$postlist = $posts->getList();
/*
echo '[' . mysql_error() . ']<br>';
$qqq = se_db_query("SHOW COLUMNS FROM se_blog_posts");
while ($rrr = se_db_fetch_assoc($qqq)) {
    echo $rrr['Field'] . '<br>';
}
//*/
//echo '<pre>' . print_r($postlist, true) . '</pre>';
//echo mysql_error();
if (!empty($postlist)) {
    foreach ($postlist as $post) {
       // определяем категории в которых состоит пост
        $post['title'] = stripslashes(htmlspecialchars($post['title']));
        $post['short'] = //replace_values(
        stripslashes(htmlspecialchars_decode($post['short']));//);
        $post['catsvse'] = "";
        $postid = $post['id'];
       // определяем можно ли редактировать
        $post['acess'] = 2;
        if  (($user_group == 3) || ($flagmoders == 1) || (($post['author_id'] == $user_id) && ($post['author_id'] != 0))) {
            $post['acess'] = 1;
        }
        $post['catcount'] = $post['tagcount'] = 0;
        foreach (explode(',', $post['categors']) AS $cat) {
            ++$post['catcount'];
            $arr = array();
            list($arr['edincat'], $arr['edurl']) = explode('|', $cat);
            $arr['edincat'] = trim($arr['edincat']);
            $arr['edurl'] = urlencode(trim($arr['edurl']));
            $__data->setItemList($section, 'cat' . $post['id'], $arr);
        }
        if (!empty($post['tags'])) {
            foreach (explode(',', $post['tags']) as $tag) {
                ++$post['tagcount'];
                $__data->setItemList($section, 'tags' . $post['id'], array(
                                                                        'tag' => $post['id'] . '-' . $post['tagcount'],
                                                                        'tag2' => stripslashes(htmlspecialchars(trim($tag)))
                                                                    ));
            }
        }
        list(, $post['rating']) =  explode(chr(8), $post['rating']);
        $post['link'] = trim($post['url'], '/');
        // Пишем группы, в которых этот пост используется
        //Конец потсчета коментариев в посте
        // делаем из даты русскую дату :) 
        list($post['date_add_rus_day'], $post['date_add_rus_nedel'], $post['date_add_rus_god']) = bl_getFormatDate($section, date('Y-m-d',$post['beginning']));
       // конец руссификации даты 
        $__data->setItemList($section, 'blogs', $post);
    }
}
?>