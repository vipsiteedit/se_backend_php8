<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "{$__data->prj->vars->adminmail}";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "Отправить";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "Yes";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "Введите цифры с картинки";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "255";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "Вы не ввели имя";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "Вы не ввели E-mail";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "Не Верно введен E-mail";
 if (empty($section->parametrs->param9)) $section->parametrs->param9 = "Не введен текст сообщения";
 if (empty($section->parametrs->param10)) $section->parametrs->param10 = "Не верно введено число с картинки";
 if (empty($section->parametrs->param11)) $section->parametrs->param11 = "Не введено число с картинки";
 if (empty($section->parametrs->param12)) $section->parametrs->param12 = "Сообщение со страницы \"Контакты\"";
 if (empty($section->parametrs->param13)) $section->parametrs->param13 = "Служба временно отключена";
 if (empty($section->parametrs->param14)) $section->parametrs->param14 = "Ошибка отправки почты";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "Ваше сообщение отправлено администратору сайта.";
 if (empty($section->parametrs->param16)) $section->parametrs->param16 = "Ваше имя";
 if (empty($section->parametrs->param17)) $section->parametrs->param17 = "Ваш E-mail";
 if (empty($section->parametrs->param18)) $section->parametrs->param18 = "Введите текст";
 if (empty($section->parametrs->param19)) $section->parametrs->param19 = "Назад";
 if (empty($section->parametrs->param20)) $section->parametrs->param20 = "От:";
 if (empty($section->parametrs->param21)) $section->parametrs->param21 = "Текст сообщения:";
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