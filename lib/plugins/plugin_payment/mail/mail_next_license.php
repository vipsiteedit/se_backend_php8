<?php

// Письмо о продлении
$message = "Здравствуйте!
Спасибо за оказываемое доверие к нам.

Компания EDGESTILE выражает свою благодарность за продление Вами системы создания
и управления сайтом EDGESTILE SiteEdit сроком на 12 месяцев.

Надеемся на долгое и взаимовыгодное сотрудничество и всегда рады видеть Вас в числе
наших Клиентов или Партнеров!

Хотим напомнить Вам Ваши регистрационные данные:
----------------------------
Серийный номер: $serial
Секретный код : $passw

Обновление программы Вы можете скачать на сайте http://www.edgestile.ru/clientaccount


Любые пожелания и вопросы, касающиеся работы с программой присылайте
в соответствующий раздел форума http://forum.siteedit.ru или в нашу службу технической поддержки
по адресу support@edgestile.ru.



С уважением,
Служба технической поддержки EDGESTILE
http://forum.siteedit.ru
http://www.edgestile.ru
http://www.siteedit.ru";

$message = convert_cyr_string($message, "w", "k");
$headers = "Content-Type: text/plain; charset=koi8-r\n";
$headers .= "From: EDGESTILE <support@edgestile.ru>\n";
$headers .= "Subject: SiteEdit\n";
$headers .= "X-Priority: 3\n";
$headers .= "Return-Part: <support@edgestile.ru>\n";
$headers .= "Content-Type: text/plain; charset=koi8-r\n";
mail($email, "", $message, $headers); //письмо клиенту

$message =
  "Продление лицензии:

SiteEdit $version

Регистрационные данные:
-----------------------
Серийный номер: $serial
Секретный код : $passw
e-mail:         $email
Дата:           $date

С уважением, Script.";

$message = convert_cyr_string($message, "w", "k");
mail("info@edgestile.ru; edgestile@edgestile.ru", "", $message, $headers); //письмо в службу поддержки
