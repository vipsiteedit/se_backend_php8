<?php
 if (!isset($section->parametrs->param33)) $section->parametrs->param33 = "rotate";
 if (!isset($section->parametrs->param18)) $section->parametrs->param18 = "special";
 if (!isset($section->parametrs->param5)) $section->parametrs->param5 = "10";
 if (!isset($section->parametrs->param48)) $section->parametrs->param48 = "200";
 if (!isset($section->parametrs->param13)) $section->parametrs->param13 = "all";
 if (!isset($section->parametrs->param6)) $section->parametrs->param6 = "name";
 if (!isset($section->parametrs->param20)) $section->parametrs->param20 = "1";
 if (!isset($section->parametrs->param10)) $section->parametrs->param10 = "catalogue";
 if (!isset($section->parametrs->param4)) $section->parametrs->param4 = "shopcart";
 if (!isset($section->parametrs->param14)) $section->parametrs->param14 = "";
 if (!isset($section->parametrs->param29)) $section->parametrs->param29 = "N";
 if (!isset($section->parametrs->param7)) $section->parametrs->param7 = "Артикул: ";
 if (!isset($section->parametrs->param17)) $section->parametrs->param17 = "Цена: ";
 if (!isset($section->parametrs->param19)) $section->parametrs->param19 = "В корзину";
 if (!isset($section->parametrs->param1)) $section->parametrs->param1 = "подробнее";
 if (!isset($section->parametrs->param28)) $section->parametrs->param28 = "Оценка пользователей:&nbsp;";
 if (!isset($section->parametrs->param47)) $section->parametrs->param47 = "&nbsp;из&nbsp;";
 if (!isset($section->parametrs->param30)) $section->parametrs->param30 = "Добавить текущий товар в корзину";
 if (!isset($section->parametrs->param31)) $section->parametrs->param31 = "Текущего товара нет в наличии";
 if (!isset($section->parametrs->param46)) $section->parametrs->param46 = "Y";
 if (!isset($section->parametrs->param45)) $section->parametrs->param45 = "Y";
 if (!isset($section->parametrs->param32)) $section->parametrs->param32 = "blockGoodsInfo";
 if (!isset($section->parametrs->param8)) $section->parametrs->param8 = "N";
 if (!isset($section->parametrs->param21)) $section->parametrs->param21 = "N";
 if (!isset($section->parametrs->param23)) $section->parametrs->param23 = "N";
 if (!isset($section->parametrs->param22)) $section->parametrs->param22 = "Y";
 if (!isset($section->parametrs->param24)) $section->parametrs->param24 = "Y";
 if (!isset($section->parametrs->param25)) $section->parametrs->param25 = "N";
 if (!isset($section->parametrs->param26)) $section->parametrs->param26 = "Y";
 if (!isset($section->parametrs->param27)) $section->parametrs->param27 = "Y";
 if (!isset($section->parametrs->param34)) $section->parametrs->param34 = "h";
 if (!isset($section->parametrs->param35)) $section->parametrs->param35 = "3";
 if (!isset($section->parametrs->param36)) $section->parametrs->param36 = "1";
 if (!isset($section->parametrs->param42)) $section->parametrs->param42 = "true";
 if (!isset($section->parametrs->param37)) $section->parametrs->param37 = "1000";
 if (!isset($section->parametrs->param38)) $section->parametrs->param38 = "false";
 if (!isset($section->parametrs->param39)) $section->parametrs->param39 = "10";
 if (!isset($section->parametrs->param40)) $section->parametrs->param40 = "false";
   foreach($section->parametrs as $__paramitem){
    foreach($__paramitem as $__name=>$__value){
      if (empty($__value)){
      }
      if (preg_match("/\[%([\w\d\-]+)%\]/u", $__value, $m)!=false){
        $section->parametrs->$__name = $__data->prj->vars->$m[1];
      }
     }
   }
?>