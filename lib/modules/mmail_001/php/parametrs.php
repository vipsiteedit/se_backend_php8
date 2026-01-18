<?php
 if (!isset($section->parametrs->param1)) $section->parametrs->param1 = "{$__data->prj->vars->adminmail}";
 if (!isset($section->parametrs->param3)) $section->parametrs->param3 = "Ваше сообщение отправлено администратору сайта.";
 if (!isset($section->parametrs->param7)) $section->parametrs->param7 = "Вам пришло сообщение";
 if (!isset($section->parametrs->param8)) $section->parametrs->param8 = "конец сообщения";
 if (!isset($section->parametrs->param11)) $section->parametrs->param11 = "Yes";
 if (!isset($section->parametrs->param13)) $section->parametrs->param13 = "2500";
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