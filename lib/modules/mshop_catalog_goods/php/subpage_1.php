<?php

// Дополнительное фото
$usergroup = seUserGroup();
$path_imgall = '/images/' . se_getLang() . '/shopimg/';
$wwwdir = getcwd();
require_once("lib/lib_images.php");

$id_goods = $viewgoods;          

$prices = new seShopPrice();
$prices->select('article, sp.name, id_group');
$prices->innerjoin('shop_group sg', 'sg.id=sp.id_group');
$prices->where("enabled='?'", 'Y');
$prices->andwhere('sp.id=?', $id_goods);
$prices->fetchOne();
$goodslink = $prices->article . '&nbsp;<b><a href="#" target="_blank">' . $prices->name . '</a></b>';
$id_visit_group = $prices->id_group;
  
// Кол-во посещений группы
$visits = new seShopGroup();
$visits->update('visits', '`visits`+1');
$visits->where('id=?', $id_visit_group);
$visits->save(); 

if ($id_goods) {
    $goodsimg = new seTable('shop_img', 'si');
    $goodsimg->select('si.id, si.id_price, si.picture, si.title');
    $goodsimg->innerjoin('shop_price sp', 'sp.id=si.id_price');
    $goodsimg->where('si.id_price=?', $id_goods);
    $goodsimg->andwhere("sp.enabled='Y'");
    $imglist = $goodsimg->getList();
    $i = 0;
    $arr=array();
    foreach ($imglist as $row) {
        $arr[$i]['row'] = $i;
        $arr[$i]['id'] = $row['id'];
        $arr[$i]['picture'] = $row['picture'];
        $arr[$i]['image'] = $path_imgall . $row['picture'];
        $arr[$i]['title'] = $row['title'];
        $sourceimg=$row['picture'];
        $extimg=explode('.', $sourceimg);
        $previmg = @$extimg[0] . '_prev.' .  @$extimg[1];
        $arr[$i]['image_prev'] = $path_imgall . $previmg;
        if (!file_exists($wwwdir . $path_imgall . $previmg) && file_exists($wwwdir . $path_imgall . $sourceimg)) {
            ThumbCreate($wwwdir . $path_imgall . $previmg, $wwwdir . $path_imgall . $sourceimg, @$extimg[1],100);
        }
        $i++;
    }
    $__data->setList($section, 'photos', $arr);
}

// Подробный просмотр товара
/*
$price->select();
$price->where("enabled = 'Y'");           
$price->andWhere("id = '?'", $viewgoods);
$price_fields = $price->fetchOne(); */

$shop_price->select();
$shop_price->where("enabled = 'Y'");           
$shop_price->andWhere("id = '?'", $viewgoods);
$price_fields = $shop_price->fetchOne();

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

//=====================
    
    // --- Округление и сепараторы ---
    $rounded = ($section->parametrs->param243 == 'Y'); // округление

    if ($section->parametrs->param276 == 'Y') // сепаратор между 1 000 000
        $separator = ' ';
    else
        $separator = '';
    // -------------------------------
                                   
/*    if ($section->parametrs->param225 == seUserGroupName()) { // optcorp
        $ptype = 1;
        $priceHeaderDet = $section->parametrs->param227;
    } else if ($section->parametrs->param224 == seUserGroupName()) { // optovik  
        $ptype = 2;
        $priceHeaderDet = $section->parametrs->param122;
    } else {                                    // розничный покупатель
        $ptype = 0;
        $priceHeaderDet = $section->parametrs->param121;
    }                                       
*/   
    $plugin_amount = new plugin_shopAmount40($price_fields['id'], '', 0, 1, '', $pricemoney);
                                           //$_SESSION['SHOP_VITRINE']['selected'][$price_fields_id][1]
    
    $cnt = $plugin_amount->getPresenceCount();
    $goodsStyle = ($cnt != 0);
    $show_addcart = (($section->parametrs->param233 != 'Y') || $goodsStyle) ? 'display: inline' : 'display: none';
    
    $price_fields_count = $plugin_amount->showPresenceCount($section->parametrs->param69); // param69 - альтернативный текст "Есть"
    
    $price_disc = $plugin_amount->showPrice(false, // discounted
                                           $rounded, // округлять ли цену
                                           $separator); // разделять ли пробелами 000 000 
    $price_fields_price = '';       
/*    $pr_without_disc = $plugin_amount->showPrice(false, // discounted
                                          $rounded, // округлять ли цену
                                          $separator); // разделять ли пробелами 000 000

    $discount = $plugin_amount->getDiscount();
                  
    if (($discount > 0) && ($section->parametrs->param113 == 'Y')) // отображать поле "Старая цена"
        $price_fields_price .= '
          <span style="text-decoration:line-through;" class="old_price" id="old_price_'.$section->id.'_'.$goods['id'].'">'.
            $pr_without_disc.'</span>';
*/
    $price_fields_price .= '<span class="new_price" id="price_'.$section->id.'_'.$spl['id'].'">'.$price_disc.'</span>';                         

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
//$price->find($viewgoods);
$shop_price->find($viewgoods);
$more_photo_array = $shop_price->getImages()->fetchOne(); // Одна из дополнительных фотографий 
$im = new plugin_ShopImages40();
$morephoto = '';
if (!empty($more_photo_array)){
    $morephoto = $more_photo_array['id'];
}

$img_block = $im->getPrevPriceImage($viewgoods);


if (!empty($id)){
    $shop_price->select("id,img");
    $shop_price->find($id);
    if ($shop_price->img != ''){
        $shop_img = '<img class="viewImage" alt="'.htmlspecialchars($shop_price->title).'" 
        src="'. $IMAGE_DIR . strval($shop_price->img) . '" border="0">';
    }else {
        $shop_img = '';
    }
}

// **********************************
// Путь по каталогу к товару
$path = new plugin_ShopCatPath40($title,'/', !$old_format);
$cat_path = $path->getPath($viewgoods);

?>