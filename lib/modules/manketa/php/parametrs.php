<?php
 if (!isset($section->parametrs->param1) || $section->parametrs->param1=='') $section->parametrs->param1 = "{$__data->prj->vars->adminmail}";
 if (!isset($section->parametrs->param52) || $section->parametrs->param52=='') $section->parametrs->param52 = "No";
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "Анкета";
 if (!isset($section->parametrs->param4) || $section->parametrs->param4=='') $section->parametrs->param4 = "Начало письма";
 if (!isset($section->parametrs->param5) || $section->parametrs->param5=='') $section->parametrs->param5 = "Конец письма";
 if (!isset($section->parametrs->param3) || $section->parametrs->param3=='') $section->parametrs->param3 = "Ваша анкета отправлена!";
 if (!isset($section->parametrs->param21) || $section->parametrs->param21=='') $section->parametrs->param21 = "Yes";
 if (!isset($section->parametrs->param23) || $section->parametrs->param23=='') $section->parametrs->param23 = "No";
 if (!isset($section->parametrs->param24) || $section->parametrs->param24=='') $section->parametrs->param24 = "No";
 if (!isset($section->parametrs->param26) || $section->parametrs->param26=='') $section->parametrs->param26 = "No";
 if (!isset($section->parametrs->param27) || $section->parametrs->param27=='') $section->parametrs->param27 = "No";
 if (!isset($section->parametrs->param29) || $section->parametrs->param29=='') $section->parametrs->param29 = "No";
 if (!isset($section->parametrs->param38) || $section->parametrs->param38=='') $section->parametrs->param38 = "Yes";
 if (!isset($section->parametrs->param39) || $section->parametrs->param39=='') $section->parametrs->param39 = "Yes";
 if (!isset($section->parametrs->param14) || $section->parametrs->param14=='') $section->parametrs->param14 = "Yes";
 if (!isset($section->parametrs->param43) || $section->parametrs->param43=='') $section->parametrs->param43 = "Да";
 if (!isset($section->parametrs->param45) || $section->parametrs->param45=='') $section->parametrs->param45 = "Нет";
 if (!isset($section->parametrs->param51) || $section->parametrs->param51=='') $section->parametrs->param51 = "200";
 if (!isset($section->parametrs->param55) || $section->parametrs->param55=='') $section->parametrs->param55 = "N";
 if (!isset($section->parametrs->param56) || $section->parametrs->param56=='') $section->parametrs->param56 = "Нажимая кнопку «Оформить», я принимаю условия <a class=\"lnkLicense\" href=\"/license/\" target=\"_blank\">Пользовательского соглашения</a> и даю своё согласие на обработку моих персональных данных";
 if (!isset($section->parametrs->param57) || $section->parametrs->param57=='') $section->parametrs->param57 = "N";
 if (!isset($section->parametrs->param58) || $section->parametrs->param58=='') $section->parametrs->param58 = "Принимаю <a href=\"#\" target=\"_blank\">условия</a>";
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
