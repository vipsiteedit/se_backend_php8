<?php
 if (!isset($section->parametrs->param1)) $section->parametrs->param1 = "catalogue";
 if (!isset($section->parametrs->param2)) $section->parametrs->param2 = "Y";
 if (!isset($section->parametrs->param5)) $section->parametrs->param5 = "";
 if (!isset($section->parametrs->param4)) $section->parametrs->param4 = "0";
 if (!isset($section->parametrs->param7)) $section->parametrs->param7 = "1";
 if (!isset($section->parametrs->param10)) $section->parametrs->param10 = "Y";
 if (!isset($section->parametrs->param9)) $section->parametrs->param9 = "1";
 if (!isset($section->parametrs->param13)) $section->parametrs->param13 = "Y";
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