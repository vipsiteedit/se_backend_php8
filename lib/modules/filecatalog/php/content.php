<?php

// Проверка не модер ли нас посетил который прописан в параметрах
$flag = 0; 
$moderi = $section->parametrs->param47;
$moders = explode(",", $moderi);
foreach($moders as $moder) {
    $userlogin = trim($moder); // очищаем от лишних пробелов массив модераторов
    if (($userlogin==seUserLogin()) && (seUserLogin()!="")) {
        $flag=1;
    }
}
// конец проверки на модера 
//если объявления резрещено создавать, то создаем
if ($section->parametrs->param28>0) {
$dates = date('Y-m-d',time()-$section->parametrs->param28*86400);
se_db_delete("filecatalog","`date`<'$dates'");
}
if (seUserGroup() >= $group_num) {
    $filecatalog_add_link = "<a class=\"newMsg\" title=\"{$section->parametrs->param3}\" href=\"[@subpage1]\">" . $section->parametrs->param3 . "</a>";
}
//Выводим объекты
$selector = '';
$filecatalog = new seTable('filecatalog');
// $filecatalog->select('DISTINCT theme');
//$filecatalog->where("page='?'", $_page);
//$filecatalog->orderby('theme');
//$townlist = $filecatalog->getlist();
//выбираем города из базы
if (!empty($_GET['sheet'])) {
    $sheet = htmlspecialchars($_GET['sheet'], ENT_QUOTES);
} else {
    $sheet = "1";
}
if (intval($pagen) == 0) {
    $limitpage = "";   
    $limit = " ";
} else {
    if ((!empty($sheet)) && ($sheet > 1)) {
        $limitpage = "LIMIT " . ($pagen * $sheet - $pagen) . "," . $pagen;
    } else {
        $limitpage = "LIMIT " . $pagen;
    }
}
//$sql = new seTable('filecatalog');
//  $filecatalog -> select('`id`, LEFT(`text`,600) as `text`, `short`, `img`, `date`, `name`, `nameurl` , `url` , `user_id`, `viewing`, `statistics`');
$filecatalog->select('`id`, `text`, `short`, `img`, `date`, `name`, `nameurl` , `url` , `user_id`, `viewing`, `statistics`');
$filecatalog->where("`page` = '?'", $req['page']);
// обработка поиска
//$psearsh=getRequest('psearsh');
if (isRequest('GoSearch')) { 
    $search=se_db_output($req['searchtxt']);
     // echo $search;
    if (isRequest('psearsh') && getRequest('psearsh')) { //удаляем рисунок
         $filecatalog->addWhere("`short` LIKE '%{$search}%'");
    } else {
        $filecatalog->andWhere("`short` LIKE '%{$search}%'");
    }
}
// конец обработки поиска 
$filecatalog->orderby('id', 1);
//echo $filecatalog -> getSQL();
$MANYPAGE = $filecatalog->pageNavigator($pagen);
$filecataloglist = $filecatalog->getList();
$width_prew = intval($section->parametrs->param4); 
if ($width_prew == 0) {
    $width_prew = 100;
}
//перемещам указатель на нужную запись
$dat = array();
$i = 0;
foreach ($filecataloglist as $msg) {
    if ((($msg['user_id'] != 0) && ($msg['user_id'] == seUserId())  || (seUserGroup() == 3) || ($flag == 1)) && !isRequest('object')) {
//        $filecatalogeditlink = "<a id=\"editfilecatalog\" style=\"text-decoration:none;\" title=\"$edtitle\" href=\"/$_page/$razdel/sub2/id/{$msg['id']}/\">$edkey</a>";
        $msg['icanedit'] = 1;
    } else {
//        $filecatalogeditlink = "";
        $msg['icanedit'] = 0;
    }
    $msg['date'] = date('d.m.Y', strtotime($msg['date']));
    $msg['title'] = $filecatalogeditlink . $msg['short'];
    if (!empty($msg['img'])) {
        list($imgname, $imgext) = explode(".", $msg['img']);
        $imgprev = "/images/filecatalog/" . $imgname . "." . $imgext;
        $msg['image'] = '<IMG alt="' . se_db_output($msg['short']) . '" border="0" class="objectImage" src="' . $imgprev . '" width="' . $width_prew . '">';
    } else {
        $msg['image'] = '';
    }
    $msg['note'] = utf8_substr((nl2br($msg['text'])), 0, $nChars); 
    if (strlen($msg['text']) > $nChars) {
        $dat[$i]['note'] .= "...";
    }
    $msg['text'] = nl2br($msg['text']);
    // обробатываем ссылки
    $nameurl = explode(chr(8), nl2br($msg['nameurl']));
    $url = explode(chr(8), nl2br($msg['url']));
    $links = "";
    for ($j = 0; $j < count($url); $j++) {
        if (!empty($nameurl[$j])) {
            $links .= '<div class="links"><a href="http://' . $url[$j] . '" target=_blank>' . $nameurl[$j] . '</a></div><br>';
        } else {
            $links .= '<div class="links"><a href="http://' . $url[$j] . '" target=_blank>' . $url[$j] . '</a></div><br>';
        }                                                              
    }
    $msg['links'] = $links;
    $__data->setItemList($section, 'objects', $msg);
}
 
?>