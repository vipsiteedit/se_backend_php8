<?php

$id_goods = $viewgoods;          
$price_fields = $psg->showGoodsDescription($viewgoods);      //получить информацию о товаре

// Кол-во посещений группы
$psg->countVizit($price_fields->id_group);  

//кол-во посещении товара  
$psg->countGoodsVizit($viewgoods);

//основная картинка
list($img_full, $nofoto) = $psg->getGoodsImage($price_fields['img'], intval($section->parametrs->param293), 'w', $section->parametrs->param292);
list($img_mid, $nofoto) = $psg->getGoodsImage($price_fields['img'], intval($section->parametrs->param286), 'w', $section->parametrs->param292);
if($nofoto) $section->parametrs->param282='N';        //если нет картинки
      
// Дополнительное фото
//$extra_photo = $psg->getGalleryImage($price_fields['id']);    
$extra_photo =  $psg->viewgalleryImages($price_fields['id'], $section->parametrs->param285, $section->parametrs->param286, $section->parametrs->param298, 'w', $section->parametrs->param292);// viewGalleryImages($section, $extra_photo);
if($extra_photo!='0')
    $__data->setList($section, 'photos', $extra_photo);


// Подробный просмотр товара
//echo var_dump($price_fields);  exit;
$price_fields_name = $price_fields['name'];
$price_fields_code = $price_fields['article'];
$price_fields_note = $price_fields['note'];
$price_fields_text = $price_fields['text'];
$price_fields_id = $price_fields['id'];    
$price_img_alt = $price_fields['img_alt'];
$price_fields_manufacturer = $price_fields['manufacturer'];                                
$__data->page->titlepage = $price_fields['title'];
$__data->page->description = $price_fields['description'];   
$__data->page->keywords = $price_fields['keywords']; 
if((strval($section->parametrs->param305)=='man') || ($price_fields['title'] == '')){
    $str_title = strval($section->parametrs->param303);
    $__data->page->titlepage = $psg->parseUserMask($str_title, $price_fields);
}
if((strval($section->parametrs->param305)=='man') || ($price_fields['description'] == '')){
    $str_description = strval($section->parametrs->param304);
    $__data->page->description = $psg->parseUserMask($str_description, $price_fields);
}


$psg->getPreviousParamsState($viewgoods);

//=====================
    list($goods_params, $price, $paramcount) = getTreeParam($section, 0, $viewgoods, $price_fields['presence_count'], 0, true, 1, $__MDL_URL);
    
    // --- Округление и сепараторы ---
    $rounded = ($section->parametrs->param243 == 'Y'); // округление

    if ($section->parametrs->param276 == 'Y') // сепаратор между 1 000 000
        $separator = ' ';
    else
        $separator = '';
    // -------------------------------
                                   
    if ($section->parametrs->param225 == seUserGroupName()) { // optcorp
        $ptype = 1;
        $priceHeaderDet = $section->language->lang018;
    } else if ($section->parametrs->param224 == seUserGroupName()) { // optovik  
        $ptype = 2;
        $priceHeaderDet = $section->language->lang019;
    } else {                                    // розничный покупатель
        $ptype = 0;
        $priceHeaderDet = $section->language->lang008;
    }                                       
    
    $list_cart_param = (!empty($_SESSION['SHOP_VITRINE']['selected'][$price_fields_id])) 
        ? join(',', $_SESSION['SHOP_VITRINE']['selected'][$price_fields_id]) : '';
    $plugin_amount = new plugin_shopAmount40($price_fields['id'], '', $ptype, 1,
                                           'param:' . $list_cart_param, 
                                           $pricemoney);
    
    $maxcnts = $plugin_amount->getPresenceCount();
/*    if($paramcount=='') $paramcount = -1;
    if($paramcount<$maxcnts) 
        $maxcnts = $paramcount;
    echo $paramcount."<br>";
    echo $maxcnts."<br>";
*/    //$goodsStyle = ($cnt != 0);
    list($price_fields_count, $goodsStyle) = getShopTextCount($section, $price_fields, $maxcnts);
    //$price_fields_count = $plugin_amount->showPresenceCount($section->language->lang017); // param69 - альтернативный текст "Есть"
    
    $realprice =  $plugin_amount->getPrice(true);
    $price_disc = $plugin_amount->showPrice(true, // discounted
                                           $rounded, // округлять ли цену
                                           $separator); // разделять ли пробелами 000 000 
    $pr_without_disc = $plugin_amount->showPrice(false, // discounted
                                          $rounded, // округлять ли цену
                                          $separator); // разделять ли пробелами 000 000

    $discount = $plugin_amount->getDiscount();
                  
    $price_fields_price = ''; 

    if(floatval($realprice) > 0){          
        if (($discount > 0) && ($section->parametrs->param113 == 'Y')) // отображать поле "Старая цена"
            $price_fields_price .= '
              <span style="text-decoration:line-through;" class="old_price" id="old_price_'.$section->id.'_'.$price_fields['id'].'">'.
                $pr_without_disc.'</span>';
        $price_fields_price .= '<span class="new_price" id="price_'.$section->id.'_'.$price_fields['id'].'">'.$price_disc.'</span>';
    }                         

    unset($plugin_amount);

    //заказ товара с нулевым значение
    if(floatval($realprice)>0){       
        $price_fields_nullprice = '0';       
    } else {
        $price_fields_nullprice = '1';
        $goodsStyle = false;        
    }

    $show_addcart = (($section->parametrs->param233 != 'Y') || $goodsStyle) ? 'display: inline' : 'display: none';
   
// **********************************
// Путь по каталогу к товару

        if ($old_format) {
            $path = new plugin_ShopCatPath40($title,'/');
            $cat_path = $path->getPath($viewgoods);
        } else {
            $dt = array();
            $tbl = new seTable("shop_group", "sg");
            $tbl->select("sg.id, sg.upid, sg.code_gr, sg.name");
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