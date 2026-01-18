<?php
 if (empty($section->parametrs->param11)) $section->parametrs->param11 = "";
 if (empty($section->parametrs->param14)) $section->parametrs->param14 = "0";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "blog";
 if (empty($section->parametrs->param24)) $section->parametrs->param24 = "blog";
 if (empty($section->parametrs->param23)) $section->parametrs->param23 = "blog";
 if (empty($section->parametrs->param30)) $section->parametrs->param30 = "0";
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