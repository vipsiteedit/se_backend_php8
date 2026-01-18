<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "{$__data->prj->vars->adminmail}";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "home";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "Главная";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "Уважаемый(ая) [USERNAME]\\r\\nОт вас поступил запрос восстановления пароля на сайт [SITENAME]\\r\\n\\r\\nВаши авторизационные данные:\\r\\nЛогин: [USERLOGIN]\\r\\nПароль: [USERPASSWORD]\\r\\n";
 if (empty($section->parametrs->param11)) $section->parametrs->param11 = "Уважаемый Админ!\\r\\nВаш клиент [USERNAME] запросил восстановление пароля, который был отправлен на [USEREMAIL]";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "N";
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