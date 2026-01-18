<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "0";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "[namepage]";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "Вернуться";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "Голосовать";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "Рейтинг";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "10";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "admin";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "Очистить рейтинг";
   foreach($section->parametrs as $__paramitem){
    foreach($__paramitem as $__name=>$__value){
      if (empty($__value)){
      }
      if (preg_match("/\[%([\w\d\-]+)%\]/u", $__value, $m)!=false){
        $section->parametrs->$__name = $__data->prj->$m;
      }
     }
   }
?>