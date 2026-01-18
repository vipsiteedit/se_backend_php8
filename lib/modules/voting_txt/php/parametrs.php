<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "0";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "[namepage]";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "Всего проголосовало";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "#000000";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "Голосовать";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "Результат";
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