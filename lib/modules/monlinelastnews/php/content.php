<?php

$wdth = intval($section->parametrs->param6);
$thisdate = time() + 86400;
$news = new seTable('news','n');
$news->select("`n`.`id`, LEFT(`n`.`text`,600) as `text`, `n`.img, `n`.title, `n`.`news_date`");
$news->innerjoin("news_category nc", "`n`.id_category = `nc`.id");
$news->Where("nc.lang = '?'", $lang);
$news->AndWhere("n.pub_date <= '$thisdate'");
$news->andWhere("n.active = 'Y'");
$news->orderBy('news_date', 1);
$news->addOrderBy('id');
if (trim($newskod) != '') {
    $news->andWhere("`nc`.ident = '?'", $newskod);
}
$newslist = $news->getList(0,$limit);
$data = array();
$nchar = intval($section->parametrs->param4);
$nn = 0;
unset($section->objects);
list($site) = explode('/', $_SERVER['SERVER_PROTOCOL']);
$site = strtolower($site) . '://' . $_SERVER['HTTP_HOST'];
if (!empty($newslist)) {
    foreach($newslist as $line) {
        $data[$nn] = $line;
        $id = $line['id'];
        $notetext = str_replace(array("\n",'&nbsp;'), ' ', strip_tags($line['text']));
        $notetext = str_replace("\r", '', $notetext);
        $line['date'] = date("d.m.Y", $line['news_date']);
        $link = '/'.$section->parametrs->param2.'/?show_to='.$id;
        if (!empty($line['img'])) {
            $source = "/images/{$lang}/newsimg/".$line['img'];
            $line['image_prev'] = se_getDImage($source, $wdth);
            $source = "/images/{$lang}/newsimg/".$line['img'];
/*     // убрано так как искажает превьюшку
            list($width, $height) = GetImageSize(getcwd().$source);
            if ($wdth < $width) {
                $height = round($height * $wdth/$width,0);
                $width = $wdth;
            }
            $line['width'] = $width;
            $line['height'] = $height;
*/
        } else {
            $line['image_prev'] = '';
        }                                                 
        if (utf8_strlen($notetext) > $nchar) {
            $line['text'] = se_LimitString(strip_tags($notetext), $nchar, ' ..');
            $line['link'] = '<a class="newslinks" href="'.seMultiDir(). '/show'.$section->parametrs->param7.'/'. $id.'/">'.$section->parametrs->param3.'</a>';
        } else {
            $line['text']= strip_tags($line['text']);
        }
        $line['shownews'] = seMultiDir(). '/show'.$section->parametrs->param7.'/'. $id.'/';
        // ссылка на субстраницу, если есть подробный текст для новости
        $nn++;
        $__data->setItemList($section, 'objects', $line);
    }
}
?>
