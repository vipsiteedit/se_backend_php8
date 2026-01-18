<?php
//BeginLib
//EndLib
function module_mspecial($razdel, $section = null)
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
if (getRequest('pricemoney')!='') {
    $pricemoney = htmlspecialchars(getRequest('pricemoney'), ENT_QUOTES);
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
          // Список неоплаченных заказов
 /*$result = se_db_query("SELECT DISTINCT shop_order.id AS `idorder`, shop_order.id_author, shop_order.date_order, shop_order.date_payee, shop_order.discount,
shop_order.curr, shop_order.status, shop_order.delivery_payee, shop_order.delivery_type, shop_order.delivery_status, shop_order.delivery_date,
(SELECT SUM((shop_tovarorder.price-shop_tovarorder.discount)*shop_tovarorder.count)
FROM shop_tovarorder
WHERE shop_tovarorder.id_order=`idorder`) AS `price_tovar`
FROM `shop_order`
INNER JOIN `shop_tovarorder` ON shop_order.id = shop_tovarorder.id_order
WHERE shop_order.id_author = '".$id_author."'
ORDER BY shop_order.id ASC"); */
           se_db_connect();
           $ORDER_PAYEELIST="";
/*
           $result=se_db_query("select id,status from `shop_order` where `id_author`='$id_author'
           AND (`status`='N' OR `status`='K') ORDER BY `id` ASC");
           $maxid=se_db_num_rows($result);
//*/
           $tbl = new seTable();
           $tbl->from("shop_order","so");
           $tbl->select("id,status");
           $tbl->where("`id_author`='?'",$id_author);
           $tbl->andWhere("`status`='N' OR `status`='K'");
           $tbl->orderby("id");
           $result = $tbl->getList();
           $i=1;
//           while ($line = mysql_fetch_array($result))
           foreach($result as $line) 
           {
             $selected="";
             if ($_ORDER_PAY>0) {
               if ($_ORDER_PAY==$line['id']) $selected="SELECTED";
             } else if ($i==$maxid) {
                $_ORDER_PAY=$line['id'];
                $selected="SELECTED";
               }
               $str_order=$line['id'];
             while (strlen($str_order)<6) $str_order="0".$str_order;
             $ORDER_PAYEELIST.='<OPTION value="'.$line['id'].'" '.$selected.'>'.$str_order.'</OPTION>';
             $i++;
           };
          // Список способов оплаты
//           $result = se_db_query("select (SUM(in_payee)-SUM(out_payee)) as res_payee, MAX(date_payee) AS max_date_payee from `shop_payee` where `id_author`='$id_author'");
           $SHOP_PAYEE_LIST="";
           $PAYEE_RES=0;
           $PAYEE_OUT=0;
           $PAYEE_IN=0;
           $tbl->from("shop_payee","sp");
           $tbl->select("(SUM(in_payee)-SUM(out_payee)) as res_payee, MAX(date_payee) AS max_date_payee");
           $tbl->where("`id_author`='?'",$id_author);
           $line = $tbl->fetchOne();       
 //          if (isset($result)) {
 ///             $line=mysql_fetch_array($result);
            if (!empty($line)) {
              $PAYEE_DATE=$line['max_date_payee'];
              $PAYEE_RES=se_formatMoney(se_MoneyConvert($line['res_payee'],"USD",$defvalut), $defvalut);
           }
/*
           $sql="SELECT `in_payee`, `out_payee`,`curr` from `shop_payee` WHERE (`id_author`=$id_author) AND (`date_payee`='$PAYEE_DATE');";
           $result = se_db_query($sql);
           while (@$line=mysql_fetch_array($result)) {
//*/
           $tbl->where("`id_author`='?'",$id_author);
           $tbl->andWhere("`date_payee`='?'",$PAYEE_DATE);
           $result = $tbl->getList();
           foreach($result as $line) { 
             $PAYEE_IN=se_formatMoney(se_MoneyConvert($line['in_payee'],"USD",),);
             //echo $line['out_payee']."<br>";
             $PAYEE_OUT=se_formatMoney(se_MoneyConvert(str_replace(",",".",$line['out_payee']),"USD",),);
           }
  require_once("modules/sshop_payment/function.php");
  $dataarr=array();
/*
  $result=se_db_query("SELECT logoimg,name_payment,startform,id,type,blank FROM shop_payment WHERE (lang='$lang') AND ((active='Y') OR active='True')");
  while ($res=se_db_fetch_array($result)) {
//*/
  $tbl->from("shop_payment","sp");
  $tbl->select("logoimg,name_payment,startform,id,type,blank");
  $tbl->where("lang='?'",$lang);
  $tbl->andWhere("active='Y' OR active='True'");
  $result = $tbl->getList();
  foreach($result as $res) {  
    if (!empty($res['logoimg'])) $res['logoimg']="<IMG src=\"/images/$lang/shoppayment/$res['logoimg']\">";
    $res['startform']=macrocomands($res['startform'], $res['id'], $_ORDER_PAYEE, "RUR", $lang);
    if (!empty($res['blank']) && ($maxid>0)) {
      if ($res['type']=='p') {
        $col3="<FORM style=\"margin:0px;\" action='/modules/sshop_payment/order.php' METHOD=POST target=_blank>\n";
        $col3.="<INPUT type=hidden name=\"lang\" value=\"".$lang."\">";
        $col3.="<INPUT type=hidden name=\"FP\" value=\"".$res['id']."\"><INPUT type=hidden name=\"ORDER_PAYEE\" value=\"".$_ORDER_PAY."\">\n";
        $col3.="<INPUT name=\"FORMA_PAYEE\" onclick=\"this.form.submit();\" class=buttonSend type=button value=\"".."\"></FORM>\n";
      } else {
        $col3="<FORM style=\"margin:0px;\" action='[@subpage2]' METHOD=POST><INPUT type=hidden name=\"FP\" value=\"".$res['id']."\">\n";
        $col3.="<INPUT type=hidden  name=\"ORDER_PAYEE\" value=\"".$_ORDER_PAY."\">\n";
        $col3.="<INPUT name=\"FORMA_PAYEE\" onclick=\"this.form.submit();\" class=buttonSend type=button value=\"".."\"></FORM>\n";
      }
    } else $col3="";
    $dataarr[]=array($res['logoimg'],$res['name_payment'],$res['startform'],$col3,$_ORDER_PAYEE);
  }
  se_show_fields($razdel,$dataarr);
//EndRazdel
}
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<div class=\"content contSpecial\" [part.style]>
<noempty:part.title>
  <h3 class=\"contentTitle\" [part.style_title]><span class=\"contentTitleTxt\">[part.title]</span></h3>
</noempty>
<noempty:part.image>
  <img border=\"0\" class=\"contentImage\" [part.style_image] src=\"[part.image]\" alt=\"[part.image_alt]\" title=\"[part.image_alt]\">
</noempty>
<noempty:part.text>
  <div class=\"contentText\" [part.style_text]>[part.text]</div>
</noempty>
[records]
</div> 
<!-- =============== END CONTENT ============= -->";
$__module_content['object'] = "
<div class=\"object\" >
<noempty:record.title>
  <h4 class=\"objectTitle\">[record.title]</h4>
</noempty>
<noempty:record.image>
  <img border=\"0\" class=\"objectImage\" src=\"[record.image_prev]\" border=\"0\" alt=\"[record.image_alt]\">
</noempty>
<noempty:record.note>
  <div class=\"objectNote\">[record.note]</div>
</noempty>
<div class=\"priceBlock\">
<div class=\"specprice\">[record.text2]</div>
<form style=\"margin:0px;\" method=\"post\">
<input type=\"hidden\" name=\"addcart\" value=\"[record.text1]\">
<input class=\"buttonSend order\" type=submit value=\"{$section->params[2]->value}\">
<input class=\"buttonSend next\" type=\"button\" value=\"{$section->params[0]->value}\" onClick=\"document.location.href='[record.link_detail]'\"></form> 
</div> 
</div> 
";
$__module_content['show'] = "
<!-- =============== START SHOW PAGE ============= -->
<div class=\"content\">
<div id=\"view\">
<noempty:record.title>
  <h4 class=\"objectTitle\">[record.title]</h4>
</noempty>
<noempty:record.image>
  <div id=\"objimage\"><img class=\"objectImage\" alt=\"[record.image_alt]\" src=\"[record.image]\" border=\"0\"></div>
</noempty>
<noempty:record.note>
  <div class=\"objectNote\">[record.note]</div>
</noempty>
<div class=\"objectText\">[record.text]</div> 
<input class=\"buttonSend\" onclick=\"window.history.back()\" type=\"button\" value=\"{$section->params[1]->value}\">
</div> 
</div> 
";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};