<?php
 if (!isset($section->parametrs->param16)) $section->parametrs->param16 = "Y";
 if (!isset($section->parametrs->param17)) $section->parametrs->param17 = "Y";
 if (!isset($section->parametrs->param19)) $section->parametrs->param19 = "ext";
 if (!isset($section->parametrs->param20)) $section->parametrs->param20 = "Y";
 if (!isset($section->parametrs->param21)) $section->parametrs->param21 = "N";
 if (!isset($section->parametrs->param1)) $section->parametrs->param1 = "opt";
 if (!isset($section->parametrs->param2)) $section->parametrs->param2 = "corp";
 if (!isset($section->parametrs->param3)) $section->parametrs->param3 = "catalogue";
 if (!isset($section->parametrs->param4)) $section->parametrs->param4 = "shopcart";
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