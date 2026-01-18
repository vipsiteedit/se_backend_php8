<?php

// Читаем комментарии
$limitpagecomm = intval($section->parametrs->param28);

//$ident = substr(md5($section->parametrs->param33),0,20);
$cardimage = "/lib/cardimage.php?session={$sid}&{$time}";
$table = new seTable("article_comm");
$table->addWhere("ident = '?'", $ident);
$table->andWhere("comm_id = '?'", $_object);
$table->orderby("time", 1);
//echo $table->getSQL();
$ARTICLE_PAGES = $table->pageNavigator($limitpagecomm);
$commlist = $table->getList();//0, $limitpagecomm);  
/*
$pages = 0;  
$pg = 1;
if ($section->parametrs->param42 == "Yes") {
    if (isRequest('pg')) {
        $pg = getRequest('pg', 1);
    }
    if (count($commlist) > $limitpagecomm) {
        for ($pages = 0, $pgs = 0; $pgs < count($commlist); $pgs += $limitpagecomm) {
            ++$pages;
            $artpages = array('cur' => intval($pages == $pg), 'pg' => $pages);
            add_simplexml_from_array(&$section, "artpages", $artpages);
        }
    }
}
$begin = $limitpagecomm * ($pg - 1);
$end = $begin + $limitpagecomm;
//*/
//$FIELDS = '';
$fl = false;
$comments_count = count($commlist);
//if (!empty($commlist)) {
if ($comments_count) {  
    foreach ($commlist as $comm) {
//    for (; ($begin < $end) && ($begin < count($commlist)); ++$begin) {
//        $comm = $commlist[$begin];
        $comm['style'] = ($fl = !$fl) ? "tableRowOdd" : "tableRowEven";
        $comm['date'] = date("d.m.y", $comm['time']);
        $comm['comment'] = str_replace("\n", '<br>', $comm['comment']);
        if (((seUserGroup()) == 3) || ($access)) {
            $comm['access'] = true;  
        } else {                                        
            $comm['access'] = false;            
        }
        $__data->setItemList($section, 'comments', $comm);
    } 
}
// генерация антиспама 
$capcha = '/lib/cardimage.php?session=' . $sid . '&' . time();
//конец генерации антиспама 
unset($table);

?>