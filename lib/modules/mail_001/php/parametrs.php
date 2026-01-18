<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "{$__data->prj->adminmail}";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "Сообщение со страницы \"Контакты\"";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "Ваше сообщение отправлено администратору сайта.";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "Ваше Имя";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "Ваш e-Mail";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "Ваше Сообщение";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "Вам пришло сообщение";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "конец сообщения";
 if (empty($section->parametrs->param9)) $section->parametrs->param9 = "Отправить";
 if (empty($section->parametrs->param10)) $section->parametrs->param10 = "Вернуться";
 if (empty($section->parametrs->param11)) $section->parametrs->param11 = "Yes";
 if (empty($section->parametrs->param12)) $section->parametrs->param12 = "Число с картинки";
 if (empty($section->parametrs->param13)) $section->parametrs->param13 = "2500";
 if (empty($section->parametrs->param14)) $section->parametrs->param14 = "Сервис временно недоступен";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "Заполните поле";
 if (empty($section->parametrs->param16)) $section->parametrs->param16 = "Правильно заполните поле";
 if (empty($section->parametrs->param17)) $section->parametrs->param17 = "Неверное число";
 if (empty($section->parametrs->param18)) $section->parametrs->param18 = "Текст сообщения";
 if (empty($section->parametrs->param20)) $section->parametrs->param20 = "Ошибка при отправке почты";
 if (empty($section->parametrs->param22)) $section->parametrs->param22 = "От";
 if (empty($section->parametrs->param23)) $section->parametrs->param23 = "E-mail";
   foreach($section->parametrs as $__paramitem){
    foreach($__paramitem as $__name=>$__value){
      if (empty($__value)){
      }
      if (preg_match("/\[%([\w\d\-]+)%\]/u", $__value, $m)!=false){
        $section->parametrs->$__name = $__data->prj->$m;
      }
     }
   }
?>