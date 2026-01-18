<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "10";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "blog";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "&#8594;";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "Последние";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "Популярные";
 if (empty($section->parametrs->param9)) $section->parametrs->param9 = " Просм. ";
 if (empty($section->parametrs->param10)) $section->parametrs->param10 = " Дата. ";
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