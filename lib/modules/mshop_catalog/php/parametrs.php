<?php
 if (!isset($section->parametrs->param1)) $section->parametrs->param1 = "catalogue";
 if (!isset($section->parametrs->param2)) $section->parametrs->param2 = "Y";
 if (!isset($section->parametrs->param5)) $section->parametrs->param5 = "";
 if (!isset($section->parametrs->param4)) $section->parametrs->param4 = "0";
 if (!isset($section->parametrs->param6)) $section->parametrs->param6 = "Y";
 if (!isset($section->parametrs->param7)) $section->parametrs->param7 = "";
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