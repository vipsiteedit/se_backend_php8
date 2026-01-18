<?php

//если объявления резрещено создавать, то создаем
if ($section->parametrs->param28>0)
{
    $dates = date('Y-m-d',time()-$section->parametrs->param28*86400);
    se_db_delete("bbs","`date`<'$dates'");
}

// Доступ для добавления
$access_add = (seUserGroup() >= $group_num);
//Выводим объекты
    $selector = '';
    $bbs = new seTable('bbs');
    $bbs->select('DISTINCT town');
    $bbs->where("page='?'", $_page);
    $bbs->orderby('town');
    $townlist = $bbs->getlist();
//выбираем города из базы
 
foreach ($townlist as $line) {
  $line['selected'] = ($line['town']==$townselected) ? ' selected' : '';;
  $__data->setItemList($section, 'selecttown', $line);
}

  //$sql = new seTable('bbs');
  $bbs -> select('`id`, LEFT(`text`,600) as `text`, `short` as `title`, `img`, `date`, 
    `name`,`town`,`email`, `url` , `id_author`, `phone`');
  $bbs -> where("`page`='?'", $_page);

if (!empty($townselected)) {
  $bbs -> andwhere("town='$townselected'");
 } 
  $bbs -> orderby('id',1);

  $MANYPAGE = $bbs ->pageNavigator($pagen);
  $bbslist = $bbs -> getList();

 if (intval($section->parametrs->param4)==0) {
   $section->parametrs->param4 = 100;
 }

//перемещам указатель на нужную запись
foreach ($bbslist as $msg) {
  $msg['access'] = (($msg['id_author'] != 0 && $msg['id_author'] == seUserId()  || seUserGroup() == 3) && !isRequest('object'));
  $msg['date'] = date('d.m.Y', strtotime($msg['date']));

  if (!empty($msg['img'])) {
    list($imgname,$imgext)=explode(".", $msg['img']);
    $msg['image'] = "/images/bbs/".$imgname.".".$imgext;
  } else $dat[$i]['image'] = '';
 
  $msg['alt'] = htmlspecialchars($msg['title']);
  $msg['note'] = se_LimitString($msg['text'], $nChars, ' ...');
  $__data->setItemList($section, 'messags', $msg);
}
?>
