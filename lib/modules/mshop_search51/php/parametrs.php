<?php
 if (!isset($section->parametrs->param16)) $section->parametrs->param16 = "catalogue";
 if (!isset($section->parametrs->param19)) $section->parametrs->param19 = "Y";
 if (!isset($section->parametrs->param15)) $section->parametrs->param15 = "";
 if (!isset($section->parametrs->param14)) $section->parametrs->param14 = "2";
 if (!isset($section->parametrs->param17)) $section->parametrs->param17 = "10";
 if (!isset($section->parametrs->param18)) $section->parametrs->param18 = "40x40";
 if (!isset($section->parametrs->param20)) $section->parametrs->param20 = "Y";
 if (!isset($section->parametrs->param21)) $section->parametrs->param21 = "N";
 if (!isset($section->parametrs->param22)) $section->parametrs->param22 = "na";
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