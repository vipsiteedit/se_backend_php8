<?php
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "n";
 if (!isset($section->parametrs->param5) || $section->parametrs->param5=='') $section->parametrs->param5 = "Y";
 if (!isset($section->parametrs->param3) || $section->parametrs->param3=='') $section->parametrs->param3 = "{$__data->prj->vars->sitephone}";
 if (!isset($section->parametrs->param4) || $section->parametrs->param4=='') $section->parametrs->param4 = "{$__data->prj->vars->sitemail}";
 if (!isset($section->parametrs->param28) || $section->parametrs->param28=='') $section->parametrs->param28 = "authorization";
 if (!isset($section->parametrs->param9) || $section->parametrs->param9=='') $section->parametrs->param9 = "Y";
 if (!isset($section->parametrs->param10) || $section->parametrs->param10=='') $section->parametrs->param10 = "Вам пришло сообщение";
 if (!isset($section->parametrs->param11) || $section->parametrs->param11=='') $section->parametrs->param11 = "конец сообщения";
 if (!isset($section->parametrs->param12) || $section->parametrs->param12=='') $section->parametrs->param12 = "2500";
 if (!isset($section->parametrs->param13) || $section->parametrs->param13=='') $section->parametrs->param13 = "Yes";
 if (!isset($section->parametrs->param14) || $section->parametrs->param14=='') $section->parametrs->param14 = "s";
 if (!isset($section->parametrs->param15) || $section->parametrs->param15=='') $section->parametrs->param15 = "{$__data->prj->vars->adminmail}";
 if (!isset($section->parametrs->param16) || $section->parametrs->param16=='') $section->parametrs->param16 = "Y";
 if (!isset($section->parametrs->param17) || $section->parametrs->param17=='') $section->parametrs->param17 = "Y";
 if (!isset($section->parametrs->param18) || $section->parametrs->param18=='') $section->parametrs->param18 = "Y";
 if (!isset($section->parametrs->param19) || $section->parametrs->param19=='') $section->parametrs->param19 = "Y";
 if (!isset($section->parametrs->param20) || $section->parametrs->param20=='') $section->parametrs->param20 = "Y";
 if (!isset($section->parametrs->param29) || $section->parametrs->param29=='') $section->parametrs->param29 = "N";
 if (!isset($section->parametrs->param21) || $section->parametrs->param21=='') $section->parametrs->param21 = "";
 if (!isset($section->parametrs->param24) || $section->parametrs->param24=='') $section->parametrs->param24 = "N";
 if (!isset($section->parametrs->param25) || $section->parametrs->param25=='') $section->parametrs->param25 = "Нажимая кнопку «Оформить», я принимаю условия <a href=\"/license/\" target=\"_blank\">Пользовательского соглашения</a> и даю своё согласие на обработку моих персональных данных";
 if (!isset($section->parametrs->param26) || $section->parametrs->param26=='') $section->parametrs->param26 = "N";
 if (!isset($section->parametrs->param27) || $section->parametrs->param27=='') $section->parametrs->param27 = "Принимаю <a href=\"#\" target=\"_blank\">условия</a>";
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