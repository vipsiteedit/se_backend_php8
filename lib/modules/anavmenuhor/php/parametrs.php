<?php
 if (!isset($section->parametrs->param15) || $section->parametrs->param15=='') $section->parametrs->param15 = "4";
 if (!isset($section->parametrs->param16) || $section->parametrs->param16=='') $section->parametrs->param16 = "004";
 if (!isset($section->parametrs->param18) || $section->parametrs->param18=='') $section->parametrs->param18 = "Y";
 if (!isset($section->parametrs->param19) || $section->parametrs->param19=='') $section->parametrs->param19 = "search.html";
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