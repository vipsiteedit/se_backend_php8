<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "10";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "blog";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "&#8594;";
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