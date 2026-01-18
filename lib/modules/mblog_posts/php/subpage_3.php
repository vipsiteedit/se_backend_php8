<?php

/*
// проверка от тех кто хочет взломать
if ($flagmoders != 1) {
    if ($user_group < $group_num) {
        header ("Location: $multidir/$_page/?" . time());
        exit();
    } 
}
// конец проверки
$id = getRequest('id', 1);
$flag = 1;
if (isRequest('delkom')) { //удаляем 
    // Удаляем старые  категории
    $svaz= new seTable('se_blog_category_post');
    $svaz->select('id, id_category, id_post');
    $svaz->where("id_post = '?'", $id);
    $svaz->deletelist();
    $posts = new seTable('se_blog_posts');
    // $posts->find($id);
    $posts->delete($id);
    header("Location: $multidir/$_page/?" . time());
    exit();
}
if (isRequest('GoTonewblog')) {
    $title = getRequest('title', 5);
    $anons = getRequest('anons', 5);
    $full = getRequest('full', 5);
    $keywords = getRequest('keywords');
    $description = getRequest('description');
    $tegi = getRequest('tegi', 5);
    $razcom = getRequest('razcom');
    $skrit = getRequest('skrit');
    $selidcat = getRequest('selidcat', 5);
    $flag = "1";
// обрабатываем оlибки 
    if (count($selidcat) == 0) {
        $flag = "0";
        $errortext .= "<br>" . $section->parametrs->param54;
    }
    if (empty($title)) {
        $flag = "0";
        $errortext .= "<br>".$section->parametrs->param55;
    }
    if (empty($anons)) {
        $flag = "0";
        $errortext .= "<br>" . $section->parametrs->param56;
    }
    if (empty($full)) {
        $flag = "0";
        $errortext .= "<br>" . $section->parametrs->param57;
    }
    // закончили отработку оlибок 
    if ($flag == "1") {
//echo "gfhgfhgfh";
        $posts = new seTable('se_blog_posts');
//$posts->insert();
        $posts->find($id);
        $posts->author_id = $user_id;
        $posts->url = getTranslitName($title); 
        $posts->title = $title;
        $posts->short = $anons; 
        $posts->full = $full; 
        $posts->lang = $lang;
        $posts->tags = se_db_output($tegi);
        $posts->keywords_post = se_db_output($keywords);
        $posts->description_post = se_db_output($description);
        $posts->commenting = se_db_output($razcom);
        $posts->comment_presence = se_db_output($skrit);
        $idpost = $posts->save();
        // Удаляем старые и пропиlим новые категории
        $svaz = new seTable('se_blog_category_post');
        $svaz->select('id, id_category, id_post ');
        $svaz->where("id_post = '?'", $id);
        $svaz->deletelist();
        foreach ($selidcat as $idcat){
            $svaz = new seTable('se_blog_category_post');
            $svaz->insert();
            $svaz->id_category = $idcat;
            $svaz->id_post = $id;
            $svaz->save();
        }
        header ("Location: $multidir/$_page/?" . time());
        exit();
    }
}
$posts = new seTable('se_blog_posts');
$posts->find($id);
$title = $posts->title;
$anons = $posts->short;
$full = $posts->full;
$author_id = $posts->author_id;
$keywords = $posts->keywords_post;
$description = $posts->description_post;
$tegi = $posts->tags ;
$razcom = $posts->commenting;
$skrit = $posts->comment_presence;
if (($user_group != 3) && ($flagmoders != 1) && ($author_id != $user_id)) {
    header("Location: $multidir/$_page/?" . time()); // если доступа нет возврат на гл страницу
    exit();
}
//----------------------------------------------------    
// Построение уровней категорий
$categories = new seTable('blogcategories');
$categories->select('id, lang, upid, name');
$categories->where("lang = '?'", $lang);   // теперь запрос выборка языка                                                     
$categories->orderby('id', 0);
$categorieslist = $categories->getList(); 
$itogmass = array();
foreach ($categorieslist as $elem) {
    if (empty($elem['upid']))  { 
        $elemitog['id'] = $elem['id'];
        $elemitog['level'] = "0";
        $elemitog['name'] = $elem['name'];
        array_push($itogmass, $elemitog);
    }
}
for ($i = 0; $i <= 100; $i++) {
    foreach ($itogmass as $elemitog) {
        if ($elemitog['level'] == $i) {
            foreach ($categorieslist as $elem) {
                if ($elemitog['id']==$elem['upid']) {
                    $upid = $elem['upid'];
                    $j = 0;
                    foreach ($itogmass as $mom) {
                        $j++;
                        if ($mom['id'] == $upid) {
                            $numrod = $j;       
                        }
                    } 
                    $level = $itogmass[$numrod]['level'];
                    $p['id'] = $elem['id'];
                    $p['level'] = $i + 1;
                    $p['name'] = $elem['name'];
                    $elemitogmas[1] = $p;
                    array_splice($itogmass, $numrod, 0, $elemitogmas);
                }                                                             
            }
        }   
    }   
}
//  echo $spisok;
foreach ($itogmass as $sel) {
    $probel = "";
    for ($i = 1; $i <= $sel['level']; $i++) {
        $probel .= "   ";
    }
    $sel['name'] = $probel . $sel['name'];
    $svaz = new seTable('se_blog_category_post');
    $svaz->select('id, id_category, id_post');
    $svaz->where("id_category = '?'", $sel['id']);
    $svaz->andWhere("id_post = '?'", $id);
    $agwq = $svaz->fetchOne();
//print_r($agwq);
//echo "<br>";
    $sel['selected'] = "";
    if (!empty($agwq)) {
        $sel['selected'] = "selected";
    }
    $__data->setItemList($section, 'categories', $sel);                    
}
// Закончили  выстраивать категории
//-----------------------------------------------
*/

?>