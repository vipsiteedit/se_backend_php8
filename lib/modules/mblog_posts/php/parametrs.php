<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "10";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "1";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "";
 if (empty($section->parametrs->param41)) $section->parametrs->param41 = "on";
 if (empty($section->parametrs->param35)) $section->parametrs->param35 = "on";
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