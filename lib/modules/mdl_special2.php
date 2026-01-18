<?php
//BeginLib
//EndLib
function module_special2($razdel, $section = null)
{
   $__module_subpage = array();
   $__data = seData::getInstance();
   $_page = $__data->req->page;
   $_razdel = $__data->req->razdel;
   $_sub = $__data->req->sub;
   unset($SE);
   if ($section == null) return;
if (empty($section->params[0]->value)) $section->params[0]->value = " далее..";
if (empty($section->params[1]->value)) $section->params[1]->value = "Вернуться назад";
if (empty($section->params[2]->value)) $section->params[2]->value = "Заказать";
if (empty($section->params[3]->value)) $section->params[3]->value = "shopcart";
if (empty($section->params[4]->value)) $section->params[4]->value = "Y";
global $pricemoney;
// Инициализируем язык
$lang = se_getlang();
// Тип валюты
if (!empty($_GET['pricemoney'])) {
    $pricemoney = htmlspecialchars($_GET['pricemoney'], ENT_QUOTES);
    $_SESSION['pricemoney'] = $pricemoney;
} elseif (!empty($_POST['pricemoney'])) {
    $pricemoney = htmlspecialchars($_POST['pricemoney'], ENT_QUOTES);
    $_SESSION['pricemoney'] = $pricemoney;
} elseif (!empty($_SESSION['pricemoney']))
    $pricemoney = $_SESSION['pricemoney'];
else {
    $main = new seTable('main');
    $main->where("lang='$lang'");
    $main->fetchOne();
    $pricemoney = $main->basecurr;
    if (empty($pricemoney)) $pricemoney ='RUR';
}
if (isRequest('addcart')) 
{
    $shopcart = new plugin_ShopCart();
    $shopcart->addCart();  
    if ($section->params[4]->value == 'Y')
        header("Location: /".$section->params[3]->value);
    else
        header("Location: /$_page");
    exit();
}
$price = new seShopPrice();
// Формируем список прайсов
foreach($section->objects as $obit)
{ 
    $price->select();
    $price->where("article='?'", $obit->text1);
    $price->andwhere("enabled = 'Y'");
    $price->andwhere("lang='?'", $lang);
    $price->fetchOne();
    $obit->text2 = se_formatMoney(se_MoneyConvert($price->price, 
                                                   $price->curr, 
                                                   $pricemoney, 
                                                   date("Ymd")), $pricemoney);                                             
}
//BeginSubPages
if (($razdel != $__data->req->razdel) || empty($__data->req->sub)){
//BeginRazdel
//EndRazdel
}
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<div class=\"content\" id=\"cont_spec\"[part.style]>
<noempty:part.title><h3 class=\"contentTitle[part.style_title]\">[part.title]</h3> </noempty>
<noempty:part.image><img border=\"0\" class=\"contentImage[part.style_image]\" src=\"[part.image]\" alt=\"[part.image_alt]\"></noempty>
<noempty:part.text><div class=\"contentText[part.style_text]\">[part.text]</div> </noempty>
[records]
</div> 
<!-- =============== END CONTENT ============= -->";
$__module_content['object'] = "
<div class=\"object\">
<noempty:record.title><h4 class=\"objectTitle\">[record.title]</h4> </noempty>
<noempty:record.image><img border=\"0\" class=\"objectImage\" src=\"[record.image_prev]\" border=\"0\" alt=\"[record.image_alt]\"></noempty>
<noempty:record.note><div class=\"objectNote\">[record.note]</div> </noempty>
<div class=\"priceBlock\">
<font color=\"red\" class=\"specprice\">[record.text2] </font> 
<form style=\"margin:0px;\" method=\"post\">
<input type=\"hidden\" name=\"addcart\" value=\"[record.text1]\">
<input class=buttonSend type=submit value=\"{$section->params[2]->value}\">
<input class=\"buttonNext\" type=\"button\" value=\"{$section->params[0]->value}\" onClick=\"document.location.href='[record.link_detail]'\"></form> 
</div> 
</div> 
";
$__module_content['show'] = "
<!-- =============== START SHOW PAGE ============= -->
<div class=\"content\" id=\"view\">
<noempty:record.title><h4 class=\"objectTitle\">[record.title]</h4> </noempty>
<noempty:record.image><div id=\"objimage\"><img class=\"objectImage\" alt=\"[record.image_alt]\" src=\"[record.image]\" border=\"0\"></div> </noempty>
<noempty:record.note><div class=\"objectNote\">[record.note]</div> </noempty>
<div class=\"objectText\">[record.text]</div> 
<input class=\"buttonSend\" onclick=\"window.history.back()\" type=\"button\" value=\"{$section->params[1]->value}\"> 
</div> 
";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};