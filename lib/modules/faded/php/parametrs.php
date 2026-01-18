<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = " далее...";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "500";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "3000";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "false";
   foreach($section->parametrs as $__paramitem){
    foreach($__paramitem as $__name=>$__value){
      if (empty($__value)){
      }
      if (preg_match("/\[%([\w\d\-]+)%\]/u", $__value, $m)!=false){
        $section->parametrs->$__name = $__data->prj->$m;
      }
     }
   }
?>