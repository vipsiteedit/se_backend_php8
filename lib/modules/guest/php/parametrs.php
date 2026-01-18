<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "{$__data->prj->vars->adminmail}";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "Yes";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "На вашем сайте в гостевой добавлено новое сообщение";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "На ваше сообщение ответил администратор";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "Вам запрещено добавлять комментарии!";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "Вы слишком часто отправляете комментарии";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "Не корректно введено имя";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "Вы ввели некорректный E-mail";
 if (empty($section->parametrs->param9)) $section->parametrs->param9 = "Вы не ввели E-mail";
 if (empty($section->parametrs->param10)) $section->parametrs->param10 = "Вы не ввели текст.";
 if (empty($section->parametrs->param11)) $section->parametrs->param11 = "Не верно введено число";
 if (empty($section->parametrs->param12)) $section->parametrs->param12 = "Не введено число";
 if (empty($section->parametrs->param13)) $section->parametrs->param13 = "30";
 if (empty($section->parametrs->param14)) $section->parametrs->param14 = "Ответ Администратора:";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "Введите цифры с картинки";
 if (empty($section->parametrs->param16)) $section->parametrs->param16 = "Имя";
 if (empty($section->parametrs->param17)) $section->parametrs->param17 = "E-mail";
 if (empty($section->parametrs->param18)) $section->parametrs->param18 = "Ваше сообщение";
 if (empty($section->parametrs->param19)) $section->parametrs->param19 = "Отправить";
 if (empty($section->parametrs->param20)) $section->parametrs->param20 = "Логин Администратора";
 if (empty($section->parametrs->param21)) $section->parametrs->param21 = "Пароль администратора";
 if (empty($section->parametrs->param22)) $section->parametrs->param22 = "Логин";
 if (empty($section->parametrs->param23)) $section->parametrs->param23 = "Дата";
 if (empty($section->parametrs->param24)) $section->parametrs->param24 = "Текст записи";
 if (empty($section->parametrs->param25)) $section->parametrs->param25 = "Ответ администратора";
 if (empty($section->parametrs->param26)) $section->parametrs->param26 = "Удалить запись";
 if (empty($section->parametrs->param27)) $section->parametrs->param27 = "Заблокировать пользователя";
 if (empty($section->parametrs->param28)) $section->parametrs->param28 = "Сохранить";
 if (empty($section->parametrs->param29)) $section->parametrs->param29 = "Заблокированные адреса";
 if (empty($section->parametrs->param30)) $section->parametrs->param30 = "Удалить";
 if (empty($section->parametrs->param31)) $section->parametrs->param31 = "Нет заблокированных адресов";
 if (empty($section->parametrs->param32)) $section->parametrs->param32 = "Вернутся назад";
 if (empty($section->parametrs->param33)) $section->parametrs->param33 = "8";
 if (empty($section->parametrs->param34)) $section->parametrs->param34 = "Не верно введена дата";
 if (empty($section->parametrs->param35)) $section->parametrs->param35 = "3000";
 if (empty($section->parametrs->param36)) $section->parametrs->param36 = "2";
 if (empty($section->parametrs->param37)) $section->parametrs->param37 = "В вашем сообщении слишком много ссылок";
 if (empty($section->parametrs->param38)) $section->parametrs->param38 = "Ссылка на страницу";
 if (empty($section->parametrs->param39)) $section->parametrs->param39 = "Yes";
 if (empty($section->parametrs->param40)) $section->parametrs->param40 = "Yes";
 if (empty($section->parametrs->param41)) $section->parametrs->param41 = "Адрес";
 if (empty($section->parametrs->param42)) $section->parametrs->param42 = "На ваше сообщение ответил администратор:";
 if (empty($section->parametrs->param43)) $section->parametrs->param43 = "Сообщение с гостевой книги";
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