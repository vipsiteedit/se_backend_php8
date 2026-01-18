<?php
 if (!isset($section->parametrs->param2)) $section->parametrs->param2 = "shopcart";
 if (!isset($section->parametrs->param5)) $section->parametrs->param5 = "Y";
 if (!isset($section->parametrs->param10)) $section->parametrs->param10 = "N";
 if (!isset($section->parametrs->param8)) $section->parametrs->param8 = "optovik";
 if (!isset($section->parametrs->param9)) $section->parametrs->param9 = "optcorp";
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