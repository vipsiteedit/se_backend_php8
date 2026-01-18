<?php
 if (!isset($section->parametrs->param1) || $section->parametrs->param1=='') $section->parametrs->param1 = "{$__data->prj->vars->adminmail}";
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "Yes";
 if (!isset($section->parametrs->param13) || $section->parametrs->param13=='') $section->parametrs->param13 = "30";
 if (!isset($section->parametrs->param33) || $section->parametrs->param33=='') $section->parametrs->param33 = "8";
 if (!isset($section->parametrs->param35) || $section->parametrs->param35=='') $section->parametrs->param35 = "3000";
 if (!isset($section->parametrs->param36) || $section->parametrs->param36=='') $section->parametrs->param36 = "2";
 if (!isset($section->parametrs->param39) || $section->parametrs->param39=='') $section->parametrs->param39 = "Yes";
 if (!isset($section->parametrs->param40) || $section->parametrs->param40=='') $section->parametrs->param40 = "Yes";
 if (!isset($section->parametrs->param41) || $section->parametrs->param41=='') $section->parametrs->param41 = "Yes";
 if (!isset($section->parametrs->param44) || $section->parametrs->param44=='') $section->parametrs->param44 = "N";
 if (!isset($section->parametrs->param45) || $section->parametrs->param45=='') $section->parametrs->param45 = "Нажимая кнопку «Отправить», я принимаю условия <a class=\"lnkLicense\" href=\"/license/\" target=\"_blank\">Пользовательского соглашения</a> и даю своё согласие на обработку моих персональных данных";
 if (!isset($section->parametrs->param46) || $section->parametrs->param46=='') $section->parametrs->param46 = "N";
 if (!isset($section->parametrs->param47) || $section->parametrs->param47=='') $section->parametrs->param47 = "Принимаю <a href=\"#\" target=\"_blank\">условия</a>";
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
