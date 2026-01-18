<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "{$__data->prj->adminmail}";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "Анкета";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "Начало письма";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "Конец письма";
 if (empty($section->parametrs->param11)) $section->parametrs->param11 = "Отправить";
 if (empty($section->parametrs->param12)) $section->parametrs->param12 = "Вернуться";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "Ваша анкета отправлена!";
 if (empty($section->parametrs->param21)) $section->parametrs->param21 = "Yes";
 if (empty($section->parametrs->param22)) $section->parametrs->param22 = "Имя";
 if (empty($section->parametrs->param23)) $section->parametrs->param23 = "No";
 if (empty($section->parametrs->param24)) $section->parametrs->param24 = "No";
 if (empty($section->parametrs->param25)) $section->parametrs->param25 = "Адрес";
 if (empty($section->parametrs->param26)) $section->parametrs->param26 = "No";
 if (empty($section->parametrs->param27)) $section->parametrs->param27 = "No";
 if (empty($section->parametrs->param28)) $section->parametrs->param28 = "Телефон";
 if (empty($section->parametrs->param29)) $section->parametrs->param29 = "No";
 if (empty($section->parametrs->param38)) $section->parametrs->param38 = "Yes";
 if (empty($section->parametrs->param10)) $section->parametrs->param10 = "E-mail";
 if (empty($section->parametrs->param39)) $section->parametrs->param39 = "Yes";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "Не указан e-mail администратора";
 if (empty($section->parametrs->param40)) $section->parametrs->param40 = "Ошибка в e-mail администратора";
 if (empty($section->parametrs->param16)) $section->parametrs->param16 = "Не введено поле";
 if (empty($section->parametrs->param18)) $section->parametrs->param18 = "Неверное число с картинки";
 if (empty($section->parametrs->param19)) $section->parametrs->param19 = "Ошибка при отправке почты";
 if (empty($section->parametrs->param44)) $section->parametrs->param44 = "Заполните все поля, помеченные звездочками";
 if (empty($section->parametrs->param46)) $section->parametrs->param46 = "Телефон введен неверно";
 if (empty($section->parametrs->param14)) $section->parametrs->param14 = "Yes";
 if (empty($section->parametrs->param13)) $section->parametrs->param13 = "Число с картинки";
 if (empty($section->parametrs->param43)) $section->parametrs->param43 = "Да";
 if (empty($section->parametrs->param45)) $section->parametrs->param45 = "Нет";
 if (empty($section->parametrs->param51)) $section->parametrs->param51 = "200";
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