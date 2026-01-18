<?php
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "10";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "руб";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "shopcart";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "Y";
 if (empty($section->parametrs->param9)) $section->parametrs->param9 = "Y";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "Код";
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "Подробнее";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "Вернуться назад";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "Заказать";
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