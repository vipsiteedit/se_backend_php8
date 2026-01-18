<?php

// Дополнительное фото
$usergroup = seUserGroup();
$path_imgall = '/images/'.se_getLang().'/shopimg/';
$path_imgmain = '/images/'.se_getLang().'/shopprice/';
$wwwdir = getcwd();
require_once("lib/lib_images.php");

$id_goods = $viewgoods;          
                
$prices = new seShopPrice();
$prices->select('sp.id, article, sp.name, id_group, img');
$prices->innerjoin('shop_group sg', 'sg.id=sp.id_group');
$prices->where("enabled='?'", 'Y');
$prices->andwhere('sp.id=?', $id_goods);
$prices->fetchOne();
$goodslink = $prices->article.'&nbsp;<b><a href="#" target="_blank">'.$prices->name.'</a></b>';
$id_visit_group = $prices->id_group;
         
// Кол-во посещений группы
$visits = new seShopGroup();
$visits->update('visits', '`visits`+1');
$visits->where('id=?', $id_visit_group);
$visits->save(); 

if (!file_exists($wwwdir.$path_imgall))
    mkdir($wwwdir.$path_imgall);   

if ($id_goods) {
    $arr = array();
    
    // Создаем среднюю и маленькую превьюшки главной картинки
    $sourceimg = $prices->img;
    $extimg = explode('.', $sourceimg);
    $midimg = @$extimg[0].'_mid.'.@$extimg[1];
    $previmg = @$extimg[0].'_prev.'.@$extimg[1];
    
    $flag_img_exists = !empty($sourceimg) && file_exists($wwwdir.$path_imgmain.$sourceimg);  // Существование главной картинки
    $flag_img_not_exists = !$flag_img_exists; // :D 
        
    if (($section->parametrs->param282 == 'L') && $img_exists) { // Отображать главное изображение тоже в галерее дополнительных фотографий    
        $i = 0;
        $arr[$i]['row'] = $i;
        $arr[$i]['id'] = $prices->id;
        $arr[$i]['picture'] = $prices->img;
        $arr[$i]['image'] = $path_imgmain.$prices->img;
        if ($prices->img_alt)
            $arr[$i]['title'] = $prices->img_alt;
        else
            $arr[$i]['title'] = $prices->name;
    
        $arr[$i]['image_mid'] = $path_imgall.$midimg;
        $arr[$i]['image_prev'] = $path_imgall.$previmg;
    }       
                  
    if ($flag_img_exists) { 
        // делаем среднее изображение
        if (!file_exists($wwwdir.$path_imgall.$midimg)) { 
            ThumbCreate($wwwdir.$path_imgall.$midimg, $wwwdir.$path_imgmain.$sourceimg, @$extimg[1], $section->parametrs->param286);
        }
        else {
            $size = getimagesize($wwwdir.$path_imgall.$midimg);
            if ($size[0] != $section->parametrs->param286) 
                ThumbCreate($wwwdir.$path_imgall.$midimg, $wwwdir.$path_imgmain.$sourceimg, @$extimg[1], $section->parametrs->param286);
        }
    
        // делаем минимальное изображение
        if (!file_exists($wwwdir.$path_imgall.$previmg)) {    
            ThumbCreate($wwwdir.$path_imgall.$previmg, $wwwdir.$path_imgmain.$sourceimg, @$extimg[1], $section->parametrs->param285);
        }
        else {
            $size = getimagesize($wwwdir.$path_imgall.$previmg);
            if ($size[0] != $section->parametrs->param285) 
                ThumbCreate($wwwdir.$path_imgall.$previmg, $wwwdir.$path_imgmain.$sourceimg, @$extimg[1], $section->parametrs->param285);
        }
    }
    
    $goodsimg = new seTable('shop_img', 'si');
    $goodsimg->select('si.id, si.id_price, si.picture, si.title');
    $goodsimg->innerjoin('shop_price sp', 'sp.id=si.id_price');
    $goodsimg->where('si.id_price=?', $id_goods);
    $goodsimg->andwhere("sp.enabled='Y'");
    $imglist = $goodsimg->getList();

    $i = 1;
    foreach ($imglist as $row) {
        $arr[$i]['row'] = $i;
        $arr[$i]['id'] = $row['id'];
        $arr[$i]['picture'] = $row['picture'];
        $arr[$i]['image'] = $path_imgall.$row['picture'];
        $arr[$i]['title'] = $row['title'];
        $sourceimg = $row['picture'];
        $extimg=explode('.', $sourceimg);
        $midimg = @$extimg[0].'_mid.'.@$extimg[1];
        $previmg = @$extimg[0].'_prev.'.@$extimg[1];
        $arr[$i]['image_mid'] = $path_imgall.$midimg;
        $arr[$i]['image_prev'] = $path_imgall.$previmg;

        if (file_exists($wwwdir.$path_imgall.$sourceimg)) {    
            // Создаем среднее изображение    
            if (!file_exists($wwwdir.$path_imgall.$midimg)) {    
                ThumbCreate($wwwdir.$path_imgall.$midimg, $wwwdir.$path_imgall.$sourceimg, @$extimg[1], $section->parametrs->param286);
            }
            else {
                $size = getimagesize($wwwdir.$path_imgall.$midimg);
                if ($size[0] != $section->parametrs->param286)
                    ThumbCreate($wwwdir.$path_imgall.$midimg, $wwwdir.$path_imgall.$sourceimg, @$extimg[1], $section->parametrs->param286);
            }
            
            // Создаем минимальное изображение
            if (!file_exists($wwwdir.$path_imgall.$previmg)) {
                ThumbCreate($wwwdir.$path_imgall.$previmg, $wwwdir.$path_imgall.$sourceimg, @$extimg[1], $section->parametrs->param285);
            }
            else {
                $size = getimagesize($wwwdir.$path_imgall.$previmg);
                if ($size[0] != $section->parametrs->param285)
                    ThumbCreate($wwwdir.$path_imgall.$previmg, $wwwdir.$path_imgall.$sourceimg, @$extimg[1], $section->parametrs->param285);            
            }
        }
        $i++;
    }
    $__data->setList($section, 'photos', $arr);
}

// Подробный просмотр товара

$price->select();
//$price->where("enabled = 'Y'");           
$price->Where("id = '?'", $viewgoods);
$price_fields = $price->fetchOne();
    //проверка на существование и на статус активный товара
    if(($price_fields['enabled'] == 'N') || (!$price_fields['enabled'])){
        Header('404 Not Found', true, 404);
        exit();
    }
$price_fields_name = $price_fields['name'];
$price_fields_code = $price_fields['article'];
$price_fields_note = $price_fields['note'];
$price_fields_text = $price_fields['text'];
$price_fields_id = $price_fields['id'];    
$price_fields_manufacturer = $price_fields['manufacturer'];                                

if (trim($price_fields['title']))
    $__data->page->titlepage = htmlspecialchars($price_fields['title']);
else
    $__data->page->titlepage = htmlspecialchars($price_fields['name']);

$__data->page->keywords = htmlspecialchars($price_fields['keywords']); 

if (trim($price_fields['description']))
    $__data->page->description = htmlspecialchars($price_fields['description']);   
else                                           
    $__data->page->description = htmlspecialchars($price_fields['note']);

$price_img_alt = $price_fields['img_alt'];
if (empty($price_img_alt))
    $price_img_alt = htmlspecialchars($price_fields_name);
    
getPreviousParamsState($viewgoods);

//list($goods_params, $addprice, $paramcount) = getTreeParam($section, 0, $viewgoods, $price_fields['presence_count']);

// Строим прорисовку параметров товара

  // Обработка количества        
//list($price_fields_count, $goodsStyle) = getShopTextCount($section, $price_fields, $paramcount);

//$show_addcart = (($section->parametrs->param233 != 'Y') || $goodStyle) ? 'display: inline' : 'display: none';

  // Обработка цены
//list($price_fields_price, $priceHeaderDet) = getShopActualPrice($section, $price_fields, $addprice);

//=====================
    list($goods_params) = getTreeParam($section, 0, $viewgoods, $price_fields['presence_count']);
    
    // --- Округление и сепараторы ---
    $rounded = ($section->parametrs->param243 == 'Y'); // округление

    if ($section->parametrs->param276 == 'Y') // сепаратор между 1 000 000
        $separator = ' ';
    else
        $separator = '';
    // -------------------------------
                                   
    if ($section->parametrs->param225 == seUserGroupName()) { // optcorp
        $ptype = 1;
        $priceHeaderDet = $section->parametrs->param227;
    } else if ($section->parametrs->param224 == seUserGroupName()) { // optovik  
        $ptype = 2;
        $priceHeaderDet = $section->parametrs->param122;
    } else {                                    // розничный покупатель
        $ptype = 0;
        $priceHeaderDet = $section->parametrs->param121;
    }                                       
    
    $plugin_amount = new plugin_shopAmount40($price_fields['id'], '', $ptype, 1,
                                           'param:' . join(',', $_SESSION['SHOP_VITRINE']['selected'][$price_fields_id]), 
                                           $pricemoney);
    
    $cnt = $plugin_amount->getPresenceCount();
    $goodsStyle = ($cnt != 0);
    $show_addcart = (($section->parametrs->param233 != 'Y') || $goodsStyle) ? 'display: inline' : 'display: none';
    
    $price_fields_count = $plugin_amount->showPresenceCount($section->parametrs->param69); // param69 - альтернативный текст "Есть"
    
    $price_disc = $plugin_amount->showPrice(true, // discounted
                                           $rounded, // округлять ли цену
                                           $separator); // разделять ли пробелами 000 000 
    $pr_without_disc = $plugin_amount->showPrice(false, // discounted
                                          $rounded, // округлять ли цену
                                          $separator); // разделять ли пробелами 000 000

    $discount = $plugin_amount->getDiscount();
                  
    $price_fields_price = '';       
    if (($discount > 0) && ($section->parametrs->param113 == 'Y')) // отображать поле "Старая цена"
        $price_fields_price .= '
          <span style="text-decoration:line-through;" class="old_price" id="old_price_'.$section->id.'_'.$price_fields['id'].'">'.
            $pr_without_disc.'</span>';
    $price_fields_price .= '<span class="new_price" id="price_'.$section->id.'_'.$price_fields['id'].'">'.$price_disc.'</span>';                         

    unset($plugin_amount);

//=====================


  
$original = '';
$originalcount = 0;
if (!empty($price_fields['orig_numbers'])){
    foreach(explode(',', $price_fields['orig_numbers']) as $orig){
        $original .= '<option value="' . htmlspecialchars($orig) . '">' . htmlspecialchars($orig) . '</option>' . "\n";
        $originalcount++;
    }
}
$id_analog = $price_fields['id_analog']; // используется, если установлен режим по полю id_analog
$price->find($viewgoods);
$more_photo_array = $price->getImages()->fetchOne(); // Одна из дополнительных фотографий 
$im = new plugin_ShopImages40();
$morephoto = '';
if (!empty($more_photo_array)) {
    $morephoto = $more_photo_array['id'];
}
$img_full = $im->getFullPriceImage($viewgoods);
$img_mid =  $im->getMidPriceImage($viewgoods);
$img_prev = $im->getPrevPriceImage($viewgoods);
    
/*$loupe_gallery =    
'<a class="cloud-zoom" href="pic/ukraina-big.gif" id="zoom1" rel="position: \'right\', adjustX: 10, adjustY: 0"> 
 <img src="pic/ukraina-middle.png" alt="" width="200" height="185" />
</a>

<ul>
 <li>
  <a class="cloud-zoom-gallery" href="pic/ukraina-big.gif" title="" rel="useZoom: \'zoom1\', smallImage: \'pic/ukraina-middle.png\'">
   <img src="pic/ukraina-small.png" alt="" width="100" height="105" />
  </a>
 </li>
 <li>
  <a class="cloud-zoom-gallery" href="pic/poland-big.gif" title="" rel="useZoom: \'zoom1\', smallImage: \'pic/poland-middle.png\'">
   <img src="pic/poland-small.png" alt="" width="100" height="105" />
  </a>
 </li>
 <li>
  <a class="cloud-zoom-gallery" href="pic/armenia-big.gif" title="" rel="useZoom: \'zoom1\', smallImage: \'pic/armenia-middle.gif\'">
   <img src="pic/armenia-small.gif" alt="" width="100" height="105" />
  </a>
 </li>
</ul>';
  */
    
// **********************************
// Путь по каталогу к товару
if ($old_format) {
    $path = new plugin_ShopCatPath40($title,'/');
    $cat_path = $path->getPath($viewgoods);
} else {
    $dt = array();
    $tbl = new seTable("shop_group", "sg");
    $tbl->select("sg.*");
    $tbl->innerjoin("shop_price sp", "sp.id_group = sg.id");
    $tbl->where("sp.id = '$viewgoods'");

    $tbl->fetchOne();
    while ($tbl->id != $base_group) {
        $dt[] = array(
                    'cat' => $tbl->code_gr,
                    'cat_nm' => $tbl->name
                );
        $gr = $tbl->upid;
        unset($tbl);
        $tbl = new seTable("shop_group", "sg");
        $tbl->find($gr);       
    }
    unset($tbl);
    while (count($dt)) {
        $__data->setItemList($section, 'pathg', array_pop($dt));
    }
}
  

?>