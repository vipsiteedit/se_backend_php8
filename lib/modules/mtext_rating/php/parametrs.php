<?php
 if (!isset($section->parametrs->param8) || $section->parametrs->param8=='') $section->parametrs->param8 = "30";
 if (!isset($section->parametrs->param1) || $section->parametrs->param1=='') $section->parametrs->param1 = "0";
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "[namepage]";
 if (!isset($section->parametrs->param9) || $section->parametrs->param9=='') $section->parametrs->param9 = "подробнее";
   foreach($section->parametrs as $__paramitem){
    foreach($__paramitem as $__name=>$__value){
      while (preg_match("/\[%([\w\d\-]+)%\]/u", $__value, $m)!=false){
        $__result = $__data->prj->vars->$m[1];
        $__value = str_replace($m[0], $__result, $__value);
      }
      $section->parametrs->$__name = $__value;
     }
   }
?>
