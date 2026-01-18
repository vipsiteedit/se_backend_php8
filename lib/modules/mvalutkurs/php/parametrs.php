<?php
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "R00000";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = " = ";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "Курс на ";
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