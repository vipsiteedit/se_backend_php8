<?php
 if (!isset($section->parametrs->param1) || $section->parametrs->param1=='') $section->parametrs->param1 = "{$__data->prj->vars->adminmail}";
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "Отправить";
 if (!isset($section->parametrs->param3) || $section->parametrs->param3=='') $section->parametrs->param3 = "Yes";
 if (!isset($section->parametrs->param4) || $section->parametrs->param4=='') $section->parametrs->param4 = "Введите цифры с картинки";
 if (!isset($section->parametrs->param5) || $section->parametrs->param5=='') $section->parametrs->param5 = "65000";
 if (!isset($section->parametrs->param6) || $section->parametrs->param6=='') $section->parametrs->param6 = "Вы не ввели имя";
 if (!isset($section->parametrs->param7) || $section->parametrs->param7=='') $section->parametrs->param7 = "Вы не ввели E-mail";
 if (!isset($section->parametrs->param8) || $section->parametrs->param8=='') $section->parametrs->param8 = "Не Верно введен E-mail";
 if (!isset($section->parametrs->param9) || $section->parametrs->param9=='') $section->parametrs->param9 = "Не введен текст сообщения";
 if (!isset($section->parametrs->param10) || $section->parametrs->param10=='') $section->parametrs->param10 = "Не верно введено число с картинки";
 if (!isset($section->parametrs->param11) || $section->parametrs->param11=='') $section->parametrs->param11 = "Не введено число с картинки";
 if (!isset($section->parametrs->param12) || $section->parametrs->param12=='') $section->parametrs->param12 = "Сообщение со страницы \"Контакты\"";
 if (!isset($section->parametrs->param13) || $section->parametrs->param13=='') $section->parametrs->param13 = "Служба временно отключена";
 if (!isset($section->parametrs->param14) || $section->parametrs->param14=='') $section->parametrs->param14 = "Ошибка отправки почты";
 if (!isset($section->parametrs->param15) || $section->parametrs->param15=='') $section->parametrs->param15 = "Ваше сообщение отправлено администратору сайта.";
 if (!isset($section->parametrs->param16) || $section->parametrs->param16=='') $section->parametrs->param16 = "Ваше имя";
 if (!isset($section->parametrs->param17) || $section->parametrs->param17=='') $section->parametrs->param17 = "Ваш E-mail";
 if (!isset($section->parametrs->param18) || $section->parametrs->param18=='') $section->parametrs->param18 = "Введите текст";
 if (!isset($section->parametrs->param19) || $section->parametrs->param19=='') $section->parametrs->param19 = "Назад";
 if (!isset($section->parametrs->param20) || $section->parametrs->param20=='') $section->parametrs->param20 = "От:";
 if (!isset($section->parametrs->param21) || $section->parametrs->param21=='') $section->parametrs->param21 = "Текст сообщения:";
 if (!isset($section->parametrs->param22) || $section->parametrs->param22=='') $section->parametrs->param22 = "fixed";
 if (!isset($section->parametrs->param25) || $section->parametrs->param25=='') $section->parametrs->param25 = "N";
 if (!isset($section->parametrs->param26) || $section->parametrs->param26=='') $section->parametrs->param26 = "Нажимая кнопку «Отправить», я принимаю условия <a class=\"lnkLicense\" href=\"/license/\" target=\"_blank\">Пользовательского соглашения</a> и даю своё согласие на обработку моих персональных данных";
 if (!isset($section->parametrs->param27) || $section->parametrs->param27=='') $section->parametrs->param27 = "N";
 if (!isset($section->parametrs->param28) || $section->parametrs->param28=='') $section->parametrs->param28 = "Принимаю <a href=\"#\" target=\"_blank\">условия</a>";
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
