<?php
 if (!isset($section->parametrs->param3)) $section->parametrs->param3 = "catalogue";
 if (!isset($section->parametrs->param1)) $section->parametrs->param1 = "100x100";
 if (!isset($section->parametrs->param2)) $section->parametrs->param2 = "N";
 if (!isset($section->parametrs->param10)) $section->parametrs->param10 = "N";
 if (!isset($section->parametrs->param4)) $section->parametrs->param4 = "Y";
 if (!isset($section->parametrs->param5)) $section->parametrs->param5 = "Y";
 if (!isset($section->parametrs->param6)) $section->parametrs->param6 = "Y";
 if (!isset($section->parametrs->param7)) $section->parametrs->param7 = "Y";
 if (!isset($section->parametrs->param8)) $section->parametrs->param8 = "Y";
 if (!isset($section->parametrs->param11)) $section->parametrs->param11 = "Y";
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