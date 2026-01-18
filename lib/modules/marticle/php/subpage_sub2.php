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
$fl = false;
$comments_count = count($commlist);
if ($comments_count) {  
    foreach ($commlist as $comm) {
        $comm['style'] = ($fl = !$fl) ? "tableRowOdd" : "tableRowEven";
        $comm['date'] = date("d.m.y", $comm['time']);
        $comm['comment'] = str_replace("\n", '<br>', $comm['comment']);
        if ((seUserGroup() == 3) || ($access)) {
            $comm['access'] = 1;  
        } else {                                        
            $comm['access'] = 0;            
        }
        $__data->setItemList($section, 'comments', $comm);
    } 
}
// генерация антиспама 
$capcha = '/lib/cardimage.php?session=' . $sid . '&' . time();
//конец генерации антиспама 
unset($table);

?>
