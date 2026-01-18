<?php
 if (empty($section->parametrs->param98)) $section->parametrs->param98 = "1";
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "10";
 if (empty($section->parametrs->param99)) $section->parametrs->param99 = "10";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "1000";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "100";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "Форум";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "600";
 if (empty($section->parametrs->param91)) $section->parametrs->param91 = "Администратор";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "10";
 if (empty($section->parametrs->param14)) $section->parametrs->param14 = "=>";
 if (empty($section->parametrs->param16)) $section->parametrs->param16 = "5";
 if (empty($section->parametrs->param17)) $section->parametrs->param17 = "204800";
 if (empty($section->parametrs->param18)) $section->parametrs->param18 = "2048000";
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