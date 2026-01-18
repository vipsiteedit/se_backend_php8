<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "yes";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "Производитель";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "Вариант поставки";
 if (empty($section->parametrs->param28)) $section->parametrs->param28 = "translit";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "translit";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "Y";
 if (empty($section->parametrs->param9)) $section->parametrs->param9 = "Y";
 if (empty($section->parametrs->param10)) $section->parametrs->param10 = "N";
 if (empty($section->parametrs->param11)) $section->parametrs->param11 = "first";
 if (empty($section->parametrs->param12)) $section->parametrs->param12 = "admin";
 if (empty($section->parametrs->param13)) $section->parametrs->param13 = "admin";
 if (empty($section->parametrs->param14)) $section->parametrs->param14 = "N";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "1";
 if (empty($section->parametrs->param16)) $section->parametrs->param16 = "2013-01-01 00:00:00";
 if (empty($section->parametrs->param39)) $section->parametrs->param39 = "RUR-RUB,USD-USD";
 if (empty($section->parametrs->param40)) $section->parametrs->param40 = "Y";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "Розничная";
 if (empty($section->parametrs->param34)) $section->parametrs->param34 = "Дилерская";
 if (empty($section->parametrs->param33)) $section->parametrs->param33 = "Оптовая";
 if (empty($section->parametrs->param18)) $section->parametrs->param18 = "Y";
 if (empty($section->parametrs->param19)) $section->parametrs->param19 = "Y";
 if (empty($section->parametrs->param36)) $section->parametrs->param36 = "Y";
 if (empty($section->parametrs->param29)) $section->parametrs->param29 = "N";
 if (empty($section->parametrs->param20)) $section->parametrs->param20 = "Y";
 if (empty($section->parametrs->param21)) $section->parametrs->param21 = "Y";
 if (empty($section->parametrs->param22)) $section->parametrs->param22 = "Y";
 if (empty($section->parametrs->param38)) $section->parametrs->param38 = "Y";
 if (empty($section->parametrs->param30)) $section->parametrs->param30 = "Y";
 if (empty($section->parametrs->param31)) $section->parametrs->param31 = "Y";
 if (empty($section->parametrs->param23)) $section->parametrs->param23 = "Y";
 if (empty($section->parametrs->param24)) $section->parametrs->param24 = "N";
 if (empty($section->parametrs->param27)) $section->parametrs->param27 = "Y";
 if (empty($section->parametrs->param25)) $section->parametrs->param25 = "Y";
 if (empty($section->parametrs->param26)) $section->parametrs->param26 = "Y";
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