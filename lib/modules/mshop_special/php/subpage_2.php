<?php

$usergroup = seUserGroup();
$path_imgall = '/images/' . se_getLang() . '/shopimg/';
$wwwdir = getcwd();
require_once("lib/lib_images.php");
$id_goods = getRequest('id', 1);

$goodsimg = new seTable('shop_img', 'si');
$goodsimg->select('si.id, si.id_price, si.picture, si.title');
$goodsimg->innerjoin('shop_price sp', 'sp.id=si.id_price');
$goodsimg->where('si.id_price=?', $id_goods);
$goodsimg->andwhere("sp.enabled='Y'");
$imglist = $goodsimg->getList();
$count = count($imglist);
if ($count) {
    $i = 0;
    $arr = array();
    foreach ($imglist as $row) {
        $arr[$i]['row'] = $i;
        $arr[$i]['id'] = $row['id'];
        $arr[$i]['picture'] = $row['picture'];
        $arr[$i]['image'] = $path_imgall . $row['picture'];
        $arr[$i]['title'] = $row['title'];
        $sourceimg = $row['picture'];
        $previmg = preg_replace("/(\.[^\.]+)$/iu", "_prev$1", $sourceimg);
        $arr[$i]['image_prev'] = $path_imgall . $previmg;        
        if (!file_exists($wwwdir . $path_imgall . $previmg) && file_exists($wwwdir . $path_imgall . $sourceimg)) {
            ThumbCreate($wwwdir . $path_imgall . $previmg, $wwwdir . $path_imgall . $sourceimg, array_pop(explode('.', $sourceimg)), 100);
        }
        $i++;
    }
    $__data->setList($section, 'photos', $arr);
}

?>