<?php
 if (!isset($section->parametrs->param1) || $section->parametrs->param1=='') $section->parametrs->param1 = "catalogue";
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "clientaccount";
 if (!isset($section->parametrs->param3) || $section->parametrs->param3=='') $section->parametrs->param3 = "";
 if (!isset($section->parametrs->param7) || $section->parametrs->param7=='') $section->parametrs->param7 = "N";
 if (!isset($section->parametrs->param4) || $section->parametrs->param4=='') $section->parametrs->param4 = "N";
 if (!isset($section->parametrs->param5) || $section->parametrs->param5=='') $section->parametrs->param5 = "Y";
 if (!isset($section->parametrs->param6) || $section->parametrs->param6=='') $section->parametrs->param6 = "Самовывоз";
 if (!isset($section->parametrs->param14) || $section->parametrs->param14=='') $section->parametrs->param14 = "0";
 if (!isset($section->parametrs->param8) || $section->parametrs->param8=='') $section->parametrs->param8 = "Y";
 if (!isset($section->parametrs->param9) || $section->parametrs->param9=='') $section->parametrs->param9 = "Y";
 if (!isset($section->parametrs->param10) || $section->parametrs->param10=='') $section->parametrs->param10 = "Y";
 if (!isset($section->parametrs->param11) || $section->parametrs->param11=='') $section->parametrs->param11 = "N";
 if (!isset($section->parametrs->param12) || $section->parametrs->param12=='') $section->parametrs->param12 = "optovik";
 if (!isset($section->parametrs->param13) || $section->parametrs->param13=='') $section->parametrs->param13 = "optcorp";
 if (!isset($section->parametrs->param15) || $section->parametrs->param15=='') $section->parametrs->param15 = "80";
 if (!isset($section->parametrs->param16) || $section->parametrs->param16=='') $section->parametrs->param16 = "Y";
 if (!isset($section->parametrs->param17) || $section->parametrs->param17=='') $section->parametrs->param17 = "Y";
 if (!isset($section->parametrs->param18) || $section->parametrs->param18=='') $section->parametrs->param18 = "Y";
 if (!isset($section->parametrs->param19) || $section->parametrs->param19=='') $section->parametrs->param19 = "N";
 if (!isset($section->parametrs->param26) || $section->parametrs->param26=='') $section->parametrs->param26 = "N";
 if (!isset($section->parametrs->param21) || $section->parametrs->param21=='') $section->parametrs->param21 = "N";
 if (!isset($section->parametrs->param22) || $section->parametrs->param22=='') $section->parametrs->param22 = "Нажимая кнопку «Оформить», я принимаю условия <a class=\"lnkLicense\" href=\"/license/\" target=\"_blank\">Пользовательского соглашения</a> и даю своё согласие на обработку моих персональных данных";
 if (!isset($section->parametrs->param24) || $section->parametrs->param24=='') $section->parametrs->param24 = "N";
 if (!isset($section->parametrs->param25) || $section->parametrs->param25=='') $section->parametrs->param25 = "Принимаю <a href=\"#\" target=\"_blank\">условия</a>";
 if (!isset($section->parametrs->param27) || $section->parametrs->param27=='') $section->parametrs->param27 = "N";
 if (!isset($section->parametrs->param28) || $section->parametrs->param28=='') $section->parametrs->param28 = "N";
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