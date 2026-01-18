<?php
 if (!isset($section->parametrs->param19)) $section->parametrs->param19 = "";
 if (!isset($section->parametrs->param27)) $section->parametrs->param27 = "";
 if (!isset($section->parametrs->param26)) $section->parametrs->param26 = "Y";
 if (!isset($section->parametrs->param3)) $section->parametrs->param3 = "...";
 if (!isset($section->parametrs->param4)) $section->parametrs->param4 = "2";
 if (!isset($section->parametrs->param5)) $section->parametrs->param5 = "Каталог";
 if (!isset($section->parametrs->param7)) $section->parametrs->param7 = ", ";
 if (!isset($section->parametrs->param21)) $section->parametrs->param21 = "N";
 if (!isset($section->parametrs->param17)) $section->parametrs->param17 = "/";
 if (!isset($section->parametrs->param28)) $section->parametrs->param28 = "90x90";
 if (!isset($section->parametrs->param29)) $section->parametrs->param29 = "50x50";
 if (!isset($section->parametrs->param30)) $section->parametrs->param30 = "200x200";
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