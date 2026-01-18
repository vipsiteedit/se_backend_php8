<?php

$nameclient = trim($this->user->last_name.' '.$this->user->first_name.' '.$this->user->sec_name);
$message = "Здравствуйте!
Уважаемый(ая) $nameclient!
На Ваш лицевой счет поступили зачислены средства в количестве ".se_formatMoney($insumm, $this->curr)."
Спасибо Вам за доверие к нашей компании!

Любые пожелания и вопросы, касающиеся финансовых вопросов присылайте
по адресу sales@edgestile.ru или admin@edgestile.ru.



С уважением,
Компания Эджестайл (EDGESTILE)
http://www.edgestile.ru
http://www.siteedit.ru";

$message=convert_cyr_string($message, "w", "k");
  $headers ="Content-Type: text/plain; charset=koi8-r\n";
  $headers .="From: EDGESTILE <sales@edgestile.ru>\n";
  $headers .="Subject: EDGESTILE\n";
  $headers .="X-Priority: 3\n";
  $headers .="Return-Part: <sales@edgestile.ru>\n";
  $headers .="Content-Type: text/plain; charset=utf-8\n";
mail($email, "", $message, $headers); //письмо клиенту
