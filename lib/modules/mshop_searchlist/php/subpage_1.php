<?php

$viewgoods = getRequest('viewgoods', 1);
// Подробный просмотр товара
$price = new seShopPrice();
$price->select();
$price->Where("lang='?'", $lang);
$price->andWhere("enabled='Y'");
$price->andWhere("id=?", $viewgoods);
$price_fields = $price->fetchOne();
$price_fields['buyshow'] = $price_fields['presence_count'];
if (($price_fields['presence_count'] == '') || ($price_fields['presence_count'] == -1)) {
    $price_fields['presence_count'] = $section->parametrs->param90;
    $price_fields['buyshow'] = 1;
}

$goodsprice = se_MoneyConvert($price_fields['price'], $price_fields['curr'], $pricemoney);
$discountproc = 0;
if (($price_fields['special_price']=='Y') || ($price_fields['discount']=='Y')){
    $shopdiscount = new plugin_shopDiscount40($price_fields['id']);
    $discountproc = $shopdiscount->execute();
}
if ($discountproc > 0) {
    $discount = $goodsprice * ($discountproc / 100);
    $price_fields['price'] = '<span style="text-decoration:line-through;" class="old_price">' . se_formatMoney($goodsprice, $pricemoney) . '</span><br>
                                <span class="new_price">' . se_formatMoney($goodsprice - $discount , $pricemoney) . '</span>';
} else { 
    $price_fields['price'] = '<span class="new_price">' . se_formatMoney($goodsprice, $pricemoney) . '</span>';
}

$id_analog = $price_fields['id_analog']; // используется, если установлен режим по полю id_analog
$price->find($viewgoods);
$more_photo_array = $price->getImages()->fetchOne(); // Одна из дополнительных фотографий товара $viewgoods.
$im = new plugin_ShopImages40();
$img_alt = ($price_fields['img_alt'] != '') ? $price_fields['img_alt'] : $price_fields['name'];
$img_block = '<img class="goodsPhoto" src="' . $im->getFullPriceImage($viewgoods) . 
                    '" border="0" title="' . str_replace('"', "'", $img_alt) . '">';
  //}
    

// **********************************    
// Аналоги
if ($section->parametrs->param80 != 'Y') { // обычные аналоги 
    $same = new seTable('shop_sameprice', 'ss');   
    $same->select('sp.*,sg.name as `ggroup`, ss.id as analog');
    $same->innerjoin('shop_price sp', 'ss.id_acc=sp.id');
    $same->innerjoin('shop_group sg', 'sg.id=sp.id_group');
    $same->Where("sg.lang='?'", $lang)->andWhere("sp.enabled='Y'");
    $same->andWhere("ss.id_price='?'", $viewgoods);
    $samelist = $same->getList();
    if ($same->getListCount() == 0) {
        $same->select('sp.*, sg.name as `ggroup`, ss.id as analog');
        $same->innerjoin('shop_price sp', 'ss.id_price=sp.id');
        $same->innerjoin('shop_group sg', 'sg.id=sp.id_group');
        $same->Where("sg.lang='?'", $lang)->andWhere("sp.enabled='Y'");
        $same->andWhere("ss.id_acc='?'", $viewgoods);
        $samelist = $same->getList();
    }         
} else { // Аналоги по полю id_analog
    if ((isset($id_analog)) && (!empty($id_analog))) {
        $price->select();
        $price->Where("lang = '?'", $lang);
        $price->andWhere("enabled = 'Y'");
        $price->leftjoin('shop_group sg', 'sg.id = sp.id_group');
        $price->andWhere("id_analog = '?'", $id_analog);
        $price->andWhere("id <> '?'", $viewgoods);
        $samelist = $price->getList();
    }
}
$analogscount = count($samelist);
foreach($samelist as $goods) {     
    if ($class != "tableRowOdd") {
        $class = "tableRowOdd";
    } else {
        $class = "tableRowEven";
    }
    $goods['itemstyle'] = $class;
    $goods['buyshow'] = $goods['presence_count'];
    if (($goods['presence_count'] == '') || ($goods['presence_count'] == -1)) {
        $goods['presence_count'] = $section->parametrs->param90;
        $goods['buyshow'] = 1;
    }
    $goodsprice = se_MoneyConvert($goods['price'], $goods['curr'], $pricemoney);
    $goods['newprice'] = '';
    if (!empty($goods['analog'])) {
        $goods['analog'] = $section->language->lang044;
    } else {
        $goods['analog'] = "--";
    }
    $discountproc = 0;
    if (($goods['special_price'] == 'Y') || ($goods['discount'] == 'Y')) {
        $shopdiscount = new plugin_shopDiscount40($goods['id']);
        $discountproc = $shopdiscount->execute();
        unset($shopdiscount);
    }
    if ($discountproc > 0){
        $discount = $goodsprice * ($discountproc / 100);
        $goods['newprice'] = '<span class="new_price">' . se_formatMoney($goodsprice - $discount , $pricemoney) . '</span>';
        $goods['oldprice'] = '<span style="text-decoration:line-through;" class="old_price">' . se_formatMoney($goodsprice, $pricemoney) . '</span>';
    } else { 
        $goods['oldprice'] = '<span class="new_price">' . se_formatMoney($goodsprice, $pricemoney) . '</span>';
    }
    add_simplexml_from_array(&$section, 'objects', $goods);
}          
  
              

?>