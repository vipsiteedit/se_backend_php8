<html>
<head>
<title> :: Фотографии товара</title>
<LINK href="/skin/default.css" id=defaultCSS rel=stylesheet type="text/css">
</head>
<body>
<div id="content">
<div id="e_shop" class="content" [contedit]="">
<div align="center">

<?php
// Путь к папкам с рисунками
if (empty($language)) $language = 'rus';
$path_imggroup = '/images/'.$language.'/shopgroup/';
$path_imgprice = '/images/'.$language.'/shopprice/';
$path_imgall = '/images/'.$language.'/shopimg/';
$wwwdir=getcwd().'/../..';
// Конектимся к базе
require_once("../../system/conf_mysql.php");
require_once("../../lib/lib_database.php");
require_once("../../lib/lib_images.php");
se_db_connect();
if (!empty($_GET['shop'])) $page_shop = htmlspecialchars($_GET['shop'], ENT_QUOTES);
else $page_shop = "";

if (!empty($_GET['goods'])) $id_goods = htmlspecialchars($_GET['goods'], ENT_QUOTES);
else $id_goods = "";

if (!empty($_GET['subg'])) $subg = htmlspecialchars($_GET['subg'], ENT_QUOTES);
else $subg = 0;

$rnamegoods = se_db_fetch_array(se_db_query("SELECT article, name FROM `shop_price` WHERE (id='".$id_goods."')AND(enabled='Y') LIMIT 1;"));
if (!empty($rnamegoods))
    print '<div >'.$rnamegoods['article'].'&nbsp;<b><a href="#" target="_blank">'.$rnamegoods['name'].'</a></b></div><br>';
?>

<div>

<?php

if (!empty($id_goods)) {
    $res = se_db_query("SELECT shop_img.id, shop_img.id_price, shop_img.picture, shop_img.title
                        FROM `shop_img` INNER JOIN shop_price ON shop_price.id=shop_img.id_price
                        WHERE (shop_img.id_price='".$id_goods."')AND(shop_price.enabled='Y');");
    $i = 0;
    $arr=array();
    while ($row = se_db_fetch_array($res)) {
        $arr[$i]['id'] = $row['id'];
        $arr[$i]['picture'] = $row['picture'];
        $arr[$i]['title'] = $row['title'];

       $sourceimg=$row['picture'];
       $extimg=explode('.',$sourceimg);
       $previmg=@$extimg[0].'_prev.'.@$extimg[1];
       if (!file_exists($wwwdir.$path_imgall.$previmg))
        ThumbCreate($wwwdir.$path_imgall.$previmg, $wwwdir.$path_imgall.$sourceimg, @$extimg[1],100);
        $i++;
    }

    if (!empty($arr)) {
        foreach ($arr as $k => $v) {
            if ($subg == $k) $class = "imgActive"; else $class = "imgAll";
            print '<a href="?shop='.$page_shop.'&goods='.$id_goods.'&subg='.$k.'"><img
                       src="'.$path_imgall.substr($v['picture'], 0, strrpos($v['picture'],".")).'_prev'.substr($v['picture'], strrpos($v['picture'],".")).'"
                       class="'.$class.'" title="'.$v['title'].'" alt="'.$v['title'].'" height="70" border="0"></a>&nbsp;&nbsp;';
        }

         print '<br><br><div><img src="'.$path_imgall.$arr[$subg]['picture'].'" class="imgShow" title="'.$arr[$subg]['title'].'" alt="'.$arr[$subg]['title'].'" border="0"></div>';
    }else
         print '<br><br><div>Дополнительных фотографий нет</div>';
}

?>
</div>
<br><br>
<div>
<a href="javascript:window.close()"><b>Закрыть</b></a>
</div>

</div>
</div>
</div>
</body>
</html>