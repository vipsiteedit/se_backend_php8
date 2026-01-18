<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "30";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "Вернуться";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "30";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "Изображение&nbsp;";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "&nbsp;из&nbsp;";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "&nbsp;";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "&nbsp;";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "&nbsp;";
 if (empty($section->parametrs->param10)) $section->parametrs->param10 = "Загрузка...";
 if (empty($section->parametrs->param11)) $section->parametrs->param11 = "150";
 if (empty($section->parametrs->param12)) $section->parametrs->param12 = "800";
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