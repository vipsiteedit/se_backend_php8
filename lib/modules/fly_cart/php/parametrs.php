<?php
 if (!isset($section->parametrs->param16)) $section->parametrs->param16 = "Y";
 if (!isset($section->parametrs->param17)) $section->parametrs->param17 = "Y";
 if (!isset($section->parametrs->param19)) $section->parametrs->param19 = "ext";
 if (!isset($section->parametrs->param20)) $section->parametrs->param20 = "Y";
 if (!isset($section->parametrs->param21)) $section->parametrs->param21 = "N";
 if (!isset($section->parametrs->param1)) $section->parametrs->param1 = "opt";
 if (!isset($section->parametrs->param2)) $section->parametrs->param2 = "corp";
 if (!isset($section->parametrs->param3)) $section->parametrs->param3 = "catalogue";
 if (!isset($section->parametrs->param4)) $section->parametrs->param4 = "shopcart";
 if (!isset($section->parametrs->param5)) $section->parametrs->param5 = "Нет товаров";
 if (!isset($section->parametrs->param6)) $section->parametrs->param6 = "[корзина пустая]";
 if (!isset($section->parametrs->param7)) $section->parametrs->param7 = "всего товаров";
 if (!isset($section->parametrs->param8)) $section->parametrs->param8 = "на сумму";
 if (!isset($section->parametrs->param9)) $section->parametrs->param9 = "сумма скидки";
 if (!isset($section->parametrs->param10)) $section->parametrs->param10 = "сумма заказа";
 if (!isset($section->parametrs->param12)) $section->parametrs->param12 = "Корзина";
 if (!isset($section->parametrs->param13)) $section->parametrs->param13 = "В корзину";
 if (!isset($section->parametrs->param11)) $section->parametrs->param11 = "удалить товар из корзины";
 if (!isset($section->parametrs->param14)) $section->parametrs->param14 = "обработка запроса...";
 if (!isset($section->parametrs->param15)) $section->parametrs->param15 = "сменить режим корзины";
 if (!isset($section->parametrs->param18)) $section->parametrs->param18 = "Перейти  в корзину";
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