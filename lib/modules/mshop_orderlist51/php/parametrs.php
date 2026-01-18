<?php
 if (!isset($section->parametrs->param37)) $section->parametrs->param37 = "N";
 if (!isset($section->parametrs->param35)) $section->parametrs->param35 = "30";
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