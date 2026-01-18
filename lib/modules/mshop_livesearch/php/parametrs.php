<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "searchlist";
 if (empty($section->parametrs->param16)) $section->parametrs->param16 = "catalogue";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "";
 if (empty($section->parametrs->param13)) $section->parametrs->param13 = "Y";
 if (empty($section->parametrs->param14)) $section->parametrs->param14 = "1";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "Искать";
 if (empty($section->parametrs->param12)) $section->parametrs->param12 = "Расширенный поиск";
 if (empty($section->parametrs->param17)) $section->parametrs->param17 = "Поиск по каталогу";
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