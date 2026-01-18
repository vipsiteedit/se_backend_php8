<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "short";
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