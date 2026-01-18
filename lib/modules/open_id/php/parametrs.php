<?php
 if (!isset($section->parametrs->param6) || $section->parametrs->param6=='') $section->parametrs->param6 = "s";
 if (!isset($section->parametrs->param23) || $section->parametrs->param23=='') $section->parametrs->param23 = "t";
 if (!isset($section->parametrs->param24) || $section->parametrs->param24=='') $section->parametrs->param24 = "y";
 if (!isset($section->parametrs->param25) || $section->parametrs->param25=='') $section->parametrs->param25 = "y";
 if (!isset($section->parametrs->param4) || $section->parametrs->param4=='') $section->parametrs->param4 = "registration";
 if (!isset($section->parametrs->param32) || $section->parametrs->param32=='') $section->parametrs->param32 = "";
 if (!isset($section->parametrs->param34) || $section->parametrs->param34=='') $section->parametrs->param34 = "";
 if (!isset($section->parametrs->param3) || $section->parametrs->param3=='') $section->parametrs->param3 = "Авторизация";
 if (!isset($section->parametrs->param21) || $section->parametrs->param21=='') $section->parametrs->param21 = "Логин";
 if (!isset($section->parametrs->param22) || $section->parametrs->param22=='') $section->parametrs->param22 = "Пароль";
 if (!isset($section->parametrs->param19) || $section->parametrs->param19=='') $section->parametrs->param19 = "Запомнить";
 if (!isset($section->parametrs->param10) || $section->parametrs->param10=='') $section->parametrs->param10 = "Войти";
 if (!isset($section->parametrs->param20) || $section->parametrs->param20=='') $section->parametrs->param20 = "Регистрация";
 if (!isset($section->parametrs->param33) || $section->parametrs->param33=='') $section->parametrs->param33 = "Забыл пароль";
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "Войти как пользователь";
 if (!isset($section->parametrs->param1) || $section->parametrs->param1=='') $section->parametrs->param1 = "Авторизация";
 if (!isset($section->parametrs->param13) || $section->parametrs->param13=='') $section->parametrs->param13 = "Доступ";
 if (!isset($section->parametrs->param14) || $section->parametrs->param14=='') $section->parametrs->param14 = "Приветствуем,";
 if (!isset($section->parametrs->param18) || $section->parametrs->param18=='') $section->parametrs->param18 = "Связать аккаунт";
 if (!isset($section->parametrs->param26) || $section->parametrs->param26=='') $section->parametrs->param26 = "Отвязать аккаунт";
 if (!isset($section->parametrs->param15) || $section->parametrs->param15=='') $section->parametrs->param15 = "Выход";
 if (!isset($section->parametrs->param5) || $section->parametrs->param5=='') $section->parametrs->param5 = "Введите ваш E-mail";
 if (!isset($section->parametrs->param7) || $section->parametrs->param7=='') $section->parametrs->param7 = "Сохранить";
 if (!isset($section->parametrs->param29) || $section->parametrs->param29=='') $section->parametrs->param29 = "Удалить";
 if (!isset($section->parametrs->param30) || $section->parametrs->param30=='') $section->parametrs->param30 = "Назад";
 if (!isset($section->parametrs->param31) || $section->parametrs->param31=='') $section->parametrs->param31 = "Cвязанных аккаунтов нет";
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