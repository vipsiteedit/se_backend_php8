<?php
 if (!isset($section->parametrs->param1)) $section->parametrs->param1 = "searchlist";
 if (!isset($section->parametrs->param17)) $section->parametrs->param17 = "catalogue";
 if (!isset($section->parametrs->param9)) $section->parametrs->param9 = "Y";
 if (!isset($section->parametrs->param10)) $section->parametrs->param10 = "Y";
 if (!isset($section->parametrs->param11)) $section->parametrs->param11 = "Y";
 if (!isset($section->parametrs->param12)) $section->parametrs->param12 = "Y";
 if (!isset($section->parametrs->param16)) $section->parametrs->param16 = "com";
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