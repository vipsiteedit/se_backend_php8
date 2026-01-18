<?php

// ################################################
// ### Подробный вывод данных выбранного товара
// ###



$id_goods = getRequest('id', 1);
$tbl = new seTable("shop_price","sp");
$tbl->select("article, price, name, img, note, text, curr, id, special_price, discount");
$tbl->find($id_goods);
$lin = $tbl->fetchOne();
              
$objecttitle = $lin['name'];
$objectnote = $lin['note'];
$objecttext = $lin['text'];

 $discountproc = 0;
 $goodsprice = se_MoneyConvert($lin['price'], $lin['curr'], $pricemoney); 

    if ($lin['special_price']=='Y' || $lin['discount']=='Y')
    {
        $shopdiscount = new plugin_shopDiscount40($lin['id']);
        $discountproc = $shopdiscount->execute();
        unset($shopdiscount);
    }
    if ($discountproc > 0)
    {
        $discount = $goodsprice * ($discountproc / 100);
        $object_new_price = se_formatMoney($goodsprice - $discount, $pricemoney);
        $objectprice = se_formatMoney($goodsprice, $pricemoney);
    }    
    else
    { 
        $objectprice = '';
        $object_new_price = se_formatMoney($goodsprice, $pricemoney);
    }




//$objectprice = se_formatMoney(se_MoneyConvert($lin['newprice'], $lin['curr'], $pricemoney), $pricemoney); 
$im = new plugin_ShopImages40();
$lin['img'] = $im->getFullPriceImage($id_goods);
unset($im);
if (!empty($lin['img'])) 
    $objectimage = '<img class="objectImage" alt="'.$objecttitle.'" src="'. $lin['img'].'" border="0">';
else 
    $objectimage = '';
    
$objectarticle = $lin['article'];
$objectid = $lin['id'];

?>