<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "1";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "32";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "7";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "Y";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "right";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "white";
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