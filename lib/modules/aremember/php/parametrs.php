<?php
 if (!isset($section->parametrs->param1)) $section->parametrs->param1 = "{$__data->prj->vars->adminmail}";
 if (!isset($section->parametrs->param2)) $section->parametrs->param2 = "home";
 if (!isset($section->parametrs->param15)) $section->parametrs->param15 = "N";
 if (!isset($section->parametrs->param14)) $section->parametrs->param14 = "Новый пароль";
 if (!isset($section->parametrs->param4)) $section->parametrs->param4 = "Уважаемый(ая) [USERNAME]\\r\\nОт вас поступил запрос восстановления пароля на сайт [SITENAME]\\r\\n\\r\\nВаши авторизационные данные:\\r\\nЛогин: [USERLOGIN]\\r\\nПароль: [USERPASSWORD]\\r\\n";
 if (!isset($section->parametrs->param11)) $section->parametrs->param11 = "Уважаемый Админ!\\r\\nВаш клиент [USERNAME] запросил восстановление пароля, который был отправлен на [USEREMAIL]";
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