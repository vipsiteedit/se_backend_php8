<?php
 if (!isset($section->parametrs->param1) || $section->parametrs->param1=='') $section->parametrs->param1 = "30";
 if (!isset($section->parametrs->param3) || $section->parametrs->param3=='') $section->parametrs->param3 = "80";
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "Вернуться";
 if (!isset($section->parametrs->param4) || $section->parametrs->param4=='') $section->parametrs->param4 = "Изображение&nbsp;";
 if (!isset($section->parametrs->param5) || $section->parametrs->param5=='') $section->parametrs->param5 = "&nbsp;из&nbsp;";
 if (!isset($section->parametrs->param6) || $section->parametrs->param6=='') $section->parametrs->param6 = "&nbsp;";
 if (!isset($section->parametrs->param7) || $section->parametrs->param7=='') $section->parametrs->param7 = "&nbsp;";
 if (!isset($section->parametrs->param8) || $section->parametrs->param8=='') $section->parametrs->param8 = "&nbsp;";
 if (!isset($section->parametrs->param10) || $section->parametrs->param10=='') $section->parametrs->param10 = "Загрузка...";
 if (!isset($section->parametrs->param11) || $section->parametrs->param11=='') $section->parametrs->param11 = "Y";
 if (!isset($section->parametrs->param12) || $section->parametrs->param12=='') $section->parametrs->param12 = "n";
   foreach($section->parametrs as $__paramitem){
    foreach($__paramitem as $__name=>$__value){
      while (preg_match("/\[%([\w\d\-]+)%\]/u", $__value, $m)!=false){
        $__result = $__data->prj->vars->$m[1];
        $__value = str_replace($m[0], $__result, $__value);
      }
      $section->parametrs->$__name = $__value;
     }
   }
?>
