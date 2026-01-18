<?php

// проверка от тех кто хочет взломать
if ($flagmoders != 1) {
    if ($user_group < $group_num) {
        header ("Location: $multidir/$_page/?" . time());
        exit();
    } 
}
// конец проверки
$id = getRequest('id', 1);
if ($id) {
    $posts = new seTable('se_blog_posts');
    $posts->find($id);
    if (($user_group != 3) && ($flagmoders != 1) && ($posts->author_id != $user_id)) {
        header("Location: $multidir/$_page/?" . time());
        exit();
    }
}
$errortext = '';
if (isRequest('GoTonewblog') && ($user_id || ($user_group == 3))) {
    $title = addslashes(trim(getRequest('title', 5)));
    $anons = addslashes(trim(getRequest('anons', 5)));
    $full = addslashes(trim(getRequest('full', 5)));
    $keywords = addslashes(trim(getRequest('keywords', 5)));
    $description = addslashes(trim(getRequest('description', 5)));
    $tegi = addslashes(trim(getRequest('tegi', 5)));
    $razcom = getRequest('razcom');
    $skrit = getRequest('skrit');
    $selidcat = getRequest('selidcat', 5);
    $event = intval(getRequest('setevent') == 'on');
    if (count($selidcat) == 0) {
        $errortext .= "\r\n" . $section->parametrs->param54;
    }
    if (empty($title)) {
        $errortext .= "\r\n" . $section->parametrs->param55;
    }
    if ($event) {
        $ehour = trim(getRequest('event_hour'));
        $eminute = trim(getRequest('event_minute'));
        $eday = trim(getRequest('event_day'));
        $emonth = trim(getRequest('event_month'));
        $eyear = trim(getRequest('event_year'));
        if (!preg_match("/^\d{2}$/iu", $ehour) || (intval($ehour) > 23)) {
            $errortext .= "\r\n" . "Часы должны состоять только из двух цифр и быть не больше 23";
        }
        if (!preg_match("/^\d{2}$/iu", $eminute) || (intval($eminute) > 59)) {
            $errortext .= "\r\n" . "Минуты должны состоять только из двух цифр и быть не больше 59";
        }
        if (!preg_match("/^\d{2}$/iu", $eday)) {
            $errortext .= "\r\n" . "День должен состоять из двух цифр";
        } else if (intval($eday) < 1) {
            $errortext .= "\r\n" . "Минимальное значение дня должно быть единицей";
        } else if (in_array($emonth, array(1, 3, 5, 7, 8, 10, 12)) && (intval($eday) > 31)) {
            $errortext .= "\r\n" . "Для выбранного месяца максимальное число 31";
        } else if (in_array($emonth, array(4, 6, 9, 11)) && (intval($eday) > 30)) {
            $errortext .= "\r\n" . "Для выбранного месяца максимальное число 30";
        } else if ($emonth == 2) {
            if (intval($eyear) & 3) {
                if (intval($eday) > 28) {
                    $errortext .= "\r\n" . "Для выбранного месяца максимальное число 28";
                }
            } else if (intval($eday) > 29) {
                $errortext .= "\r\n" . "Для выбранного месяца максимальное число 29";
            }
        }
        if (trim($tegi) == '') {
            $errortext .= "\r\n" . "При указании события необходимо ввести хотя бы один тег";
        } 
    }
    if (empty($anons)) {
        $errortext .= "\r\n" . $section->parametrs->param56;
    }
    if (empty($full)) {
        $errortext .= "\r\n" . $section->parametrs->param57;
    }
//    if (($errortext == "") && 0) {
    if ($errortext == "") {
        $posts = new seTable('se_blog_posts');
        $posts->find($id);//?
        $posts->lang = $lang;
        if (!$posts->id) {
            $posts->author_id = $user_id;
            $posts->date_add = date("Y-m-d");
        }
        $posts->url = getTranslitName($title); 
        $posts->title = $title;
/*
        $posts->short = preg_replace("/&lt;(\/?)script([^&]*)&gt;/iu", 
                                        "&amp;lt;$1" . "script$2&amp;gt;", $anons); 
        $posts->short = preg_replace("/<(\/?)script([^>]*)>/iu", 
                                        "&amp;lt;$1" . "script$2&amp;gt;", $posts->short);
        $posts->full = preg_replace("/&lt;(\/?)script([^&]*)&gt;/iu", 
                                        "&amp;lt;$1" . "script$2&amp;gt;", $full);
        $posts->full = preg_replace("/<(\/?)script([^>]*)>/iu", 
                                        "&amp;lt;$1" . "script$2&amp;gt;", $posts->full);
/*/
        $posts->short = $anons; 
        $posts->full = $full;
//*/
        $posts->tags = $tegi;
        $posts->commenting = $razcom;
        $posts->comment_presence = $skrit;
        $posts->keywords_post = $keywords;
        $posts->description_post = $description;
        if ($event) {
            $posts->event = 'Y';
            $posts->beginning = mktime($ehour, $eminute, 0, $emonth, $eday, $eyear);
        } else {
            $posts->event = 'N';
            $posts->beginning = 0;
        }
        $idpost = $posts->save();
        if ($id) {
            $svaz = new seTable('se_blog_category_post');
            $svaz->where("id_post = '?'", $id);
            $svaz->deletelist();
        } else {
            $id = $idpost;
        }
        foreach ($selidcat as $idcat) {
            $svaz = new seTable('se_blog_category_post');
            $svaz->insert();
            $svaz->id_category = $idcat;
            $svaz->id_post = $id;
            $svaz->save();
        }
        header("Location: $multidir/$_page/?" . time());
        exit();
    }
    $title = stripslashes(htmlspecialchars($title));
    $anons = stripslashes(htmlspecialchars($anons));
    $full = stripslashes(htmlspecialchars($full));
    $keywords = stripslashes(htmlspecialchars($keywords));
    $description = stripslashes(htmlspecialchars($description));
    $tegi = stripslashes(htmlspecialchars($tegi));
    $errortext = nl2br(trim($errortext));
} else if ($id) {
    $title = stripslashes(htmlspecialchars($posts->title));
/*
    $anons = htmlspecialchars(stripslashes(preg_replace("/&amp;lt;(\/?)script([^&]*)&amp;gt;/iu", 
                                                            "&lt;$1" . "script$2&gt;", $posts->short))); 
    $full = htmlspecialchars(stripslashes(preg_replace("/&amp;lt;(\/?)script([^&]*)&amp;gt;/iu", 
                                                            "&lt;$1" . "script$2&gt;", $posts->full)));
/*/
    $anons = htmlspecialchars(stripslashes($posts->short)); 
    $full = htmlspecialchars(stripslashes($posts->full));
//*/
    $author_id = $posts->author_id;
    $keywords = stripslashes(htmlspecialchars($posts->keywords_post));
    $description = stripslashes(htmlspecialchars($posts->description_post));
    $tegi = stripslashes(htmlspecialchars($posts->tags));
    $razcom = $posts->commenting;
    $skrit = $posts->comment_presence;
    $event = intval($posts->event == 'Y');
    list($ehour, $eminute, $eday, $emonth, $eyear) = explode('.', date("H.i.d.m.Y", $posts->beginning));
} else {
    $event = 0;
    $title = $anons = $full = $keywords = $description = $tegi = $razcom = $skrit = '';
}
if (!$event) {
    list($ehour, $eminute, $eday, $emonth, $eyear) = explode('.', date("H.i.d.m.Y", time() + 86400));
}
//$emonth = intval($emonth) - 1;
if ($id && isRequest('delkom')) { //удаляем 
    // Удаляем старые  категории
    $svaz = new seTable('se_blog_category_post');
    $svaz->where("id_post = '?'", $id);
    $svaz->deletelist();
    $posts = new seTable('se_blog_posts');
    $posts->delete($id);
    header("Location: $multidir/$_page/?" . time());
    exit();
}
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
foreach ($itogmass as $sel) {
/*
    $probel = "";
    for ($i = 1; $i <= $sel['level']; $i++) {
        $probel .= "   ";
    }
    $sel['name'] = $probel . $sel['name'];
//*/
    if ($id) {
        $svaz = new seTable('se_blog_category_post');
        $svaz->select('id, id_category, id_post');
        $svaz->where("id_category = '?'", $sel['id']);
        $svaz->andWhere("id_post = '?'", $id);
        $agwq = $svaz->fetchOne();
//        $sel['selected'] = "";
        if (!empty($agwq)) {
            $sel['checked'] = " checked=checked";
        }
    }
    $__data->setItemList($section, 'categories', $sel);                    
}

?>