<?php

$posts = new seTable('se_blog_posts');
$posts->where("url = '?'", $postname);
$postlist = $posts->fetchOne();
if (!$posts->isFind()) {
    header("Location: $multidir/$_page/");
    exit();
}
if (seUserName() == "") {
    $usrn = "1";
}
$beginning = $posts->beginning;
$blog_date = date('d', $posts->beginning);
$blog_month = $month_arr[date('m', strtotime($posts->date_add)) - 1];
$blog_title = stripslashes(htmlspecialchars($posts->title));
$blog_short = //replace_values(
stripslashes(htmlspecialchars_decode($posts->short));//);
$blog_id = $posts->id;
$blog_full = //replace_values(
stripslashes(htmlspecialchars_decode($posts->full));//);
$blog_tags = $posts->tags;
$showshort = $posts->showshort;


$commenting = $posts->commenting;
$description_post = $posts->description_post;
$keywords_post = $posts->keywords_post;
$blog_id = $posts->id;
$tbl = new seTable("se_blog_posts", "bp");
$tbl->select("bp.url");
$tbl->where("bp.id = (SELECT MAX(bp2.id) " .
                        "FROM se_blog_posts bp2 " .
                        "WHERE " .
                            "(bp2.id < $blog_id) AND " .
                            "(lang = '$lang'))");
$tbl->fetchOne();
$prenews = trim($tbl->url, '/');
//===============================================================
$tbl = new seTable("se_blog_posts", "bp");
$tbl->select("bp.url");
$tbl->where("bp.id = (SELECT MIN(bp2.id) " .
                        "FROM se_blog_posts bp2 " .
                        "WHERE " .
                            "(bp2.id > $blog_id) AND " .
                            "(lang = '$lang'))");
$tbl->fetchOne();
$nxtnews = trim($tbl->url, '/');
$tagcount = 0;
list($date_add_day, $date_add_nedel, $date_add_god) = bl_getFormatDate($section, date('Y-m-d', $beginning));
// конец руссификации даты 
if (!empty($blog_tags)) {
    foreach (explode(',', $blog_tags) as $tag) {
        ++$tagcount;
        $tag = htmlspecialchars(trim($tag));
        $__data->setItemList($section, 'blog_tags', array(
                                                        'tag1' => $blog_id . '-' . $tagcount,
                                                        'tag2' => $tag
                                                    ));
    }
}
$blog_cats = '';
$ratingn = trim($posts->rating);
$catpost = new seTable('se_blog_category_post');
$catpost->select("*");
$catpost->where("id_post = '$blog_id'");
$catpostlist = $catpost->getList();
$catvivod = count($catpostlist);
if ($catvivod) {
    foreach ($catpostlist as $catpoel) {
        $bdcat = new seTable('blogcategories');
        $bdcat->select("*");
        $cidcategory = $catpoel['id_category'];
        $bdcat->find($cidcategory);
        $edincat = $bdcat->name;
        $edurl = $bdcat->url;
        $__data->setItemList($section, 'catout', array(
                                                        'dt1' => urlencode(trim($edurl)),
                                                        'dt2' => trim($edincat)
                                                    ));
    } 
}
$__data->page->titlepage = $blog_title;
if (trim($description_post)) {
    $__data->page->description = htmlspecialchars($description_post);   
} else {                                           
    $__data->page->description = htmlspecialchars($blog_short);
}
$__data->page->keywords = htmlspecialchars($keywords_post);
// обработка количества просмотров
$idlink = intval($posts->id);
if ($_SESSION['VWblog'][$idlink] != 1) {
    $_SESSION['VWblog'][$idlink] = 1;
    $viewing = $posts->hits;
    $viewing = $viewing + 1;
    $posts->hits = $viewing;
    $posts->save();
}
$viewing = se_db_output($posts->hits);
//Конец обработки просмотров
// Обработка рейтинга
$ratingtext = "";
$rating = explode(chr(8), $ratingn);
if (isRequest('GoRating')) {  //обработка кнопки голосовать 
    $rating0 = floatval(str_replace(',', '.', $rating[0]));
    $rating1 = intval($rating[1]);
    $golos= getRequest('adv2', 2);
    if ($_SESSION['RT'][$idlink] != 1) {
        $_SESSION['RT'][$idlink] = 1;
        if ($golos > 0) {
            $golos -= $rating0;
            ++$rating1;
            $golos = $golos / $rating1;
            $rating0 += floatval($golos);
            $rating[0] = $rating0;
            $rating[1] = $rating1;
            $posts->rating = implode(chr(8), $rating);
            $posts->save();
        }
    }
}
$rating0 = round($rating[0]);
$rating1 = round($rating[1]);
// генерация антиспама 
$sid = session_id();
$tim= time();
if (isRequest('GoTonewbbs') && trim(getRequest('commentsinstext' . getRequest('iddopcomvv', 3), 3)) !='') {
    $pin = getRequest('pin', 3) ;
    $iddopcomvv = "";
    if (empty($pin)) { // если основная капча пустая то будем искать внутренние коментарии иначе работаем с осноными
        $iddopcomvv = getRequest('iddopcomvv', 3);
    }
    //проверка антиспама
    if ($_SESSION['guest_nospan'.$section->id] <> $_POST['usrcode']) {
        $errstpin = "errorinp";
        $errorpin = $section->language->lang033;
        $err = true;
    }

    $pin = getRequest('pin' . $iddopcomvv, 3);
    if (isset($pin)) {
        if (empty($pin)) {
            $errstpin = "errorinp";
            $errorpin = $section->language->lang033;
            $err = true;
        } else {
            require_once getcwd(). "/lib/card.php";
            if(!checkcard($pin)) {
                $errstpin = "errorinp"; 
                $errorpin = $section->language->lang033;
                $err = true;
            }
        }
    }   
    // конец проверки антиспасма
    if ($err) {
        $commentsinstext = (getRequest('commentsinstext' . $iddopcomvv, 3));
    }
    if (!$err) {
        $newbbsmsg = new seTable('se_blog_comments');
        $newbbsmsg->select('max(id) as obid');
        $newbbsmsg->fetchone();
        $newbbsmsg->insert();
        $newbbsmsg->idfiles = $blog_id;
        if (!empty($iddopcomvv)) {
            $newbbsmsg->upid = $iddopcomvv;
        } 
        $newbbsmsg->message = (getRequest('commentsinstext' . $iddopcomvv, 3));
        if (seUserName() == "") {
            $name = getRequest('name' . $iddopcomvv, 3);
            if (empty($name)) {
                $nameauthor = $section->language->lang035;
            } else {
                $nameauthor = $name;
            }
        } else {
            $nameauthor = seUserName();
        }
        $newbbsmsg->date_add = date("Y-m-d");
        $newbbsmsg->author = ($nameauthor);
        $newbbsmsg->save();
        header("Location: " . seMultiDir() . "/" . $_page . "/post/" . $postname . "?" . time());
        exit();
    }
}
//обработка коментариев 
$newbbsmsg = new seTable('se_blog_comments');
$newbbsmsg->select('id, upid, idfiles, date_add, message, author');
$newbbsmsg->where("`idfiles` = '$blog_id'");
//----------------------------------------------------    
// Построение уровней коментариев
$newbbsmsg->orderby('id', 0);
$newbbsmsglist = $newbbsmsg->getList(); 
$itogmass = array();
foreach ($newbbsmsglist as $elem) {
    if (empty($elem['upid']))  { 
        $elemitog = $elem;  // Перетаскиваем все данные не изменные
        $elemitog['level'] = "0";
        array_push($itogmass, $elemitog);
    }
}
for ($i = 0; $i <= 100; $i++) {
    foreach ($itogmass as $elemitog) {
        if ($elemitog[level] == $i) {
            foreach ($newbbsmsglist as $elem) {
                if ($elemitog['id'] == $elem['upid']) {
                    $upid = $elem['upid'];
                    $j = 0;
                    foreach ($itogmass as $mom) {
                        $j++;
                        if ($mom['id'] == $upid) {
                            $numrod = $j;       
                        }
                    } 
                    $level = $itogmass[$numrod][level];
                    $p = $elem;  // опять же перетаскиываем все данные выбранной массива из двумерного массива
                    $p['level'] = $i + 1;
                    $elemitogmas[1] = $p;
                    array_splice($itogmass, $numrod, 0, $elemitogmas);
                }                                                             
            }
        }   
    }   
}
$comments = '';
foreach ($itogmass as $msg) {
    $msg['date_add_rus'] = join(' ', bl_getFormatDate($section, $msg['date_add']));
// конец руссификации даты
// вставь сюда ссылку для модерирования коментария пример обращения к странице http://testeng.e-stile.ru/newbbs/1/sub5/?id0k&idmsg#
    $msg['masterlink'] = intval(($user_group == 3) || ($flag == 1) || ($flagmoders==1));
    $__data->setItemList($section, 'comments', $msg);
}
// конец обработки коментариев

$usrcode = md5(time());
$_SESSION['guest_nospan'.$section->id] = $usrcode;

?>