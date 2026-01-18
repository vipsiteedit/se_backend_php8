<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "подробнее";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "Вернуться назад";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "Y";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "N";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "N";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "Печать";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "Y";
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