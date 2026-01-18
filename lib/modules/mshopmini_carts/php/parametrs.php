<?php
 if (!isset($section->parametrs->param1) || $section->parametrs->param1=='') $section->parametrs->param1 = "{$__data->prj->vars->adminmail}";
 if (!isset($section->parametrs->param29) || $section->parametrs->param29=='') $section->parametrs->param29 = "";
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "руб";
 if (!isset($section->parametrs->param24) || $section->parametrs->param24=='') $section->parametrs->param24 = "500";
 if (!isset($section->parametrs->param28) || $section->parametrs->param28=='') $section->parametrs->param28 = "Загружаемый файл превышает размер:";
 if (!isset($section->parametrs->param3) || $section->parametrs->param3=='') $section->parametrs->param3 = "Код";
 if (!isset($section->parametrs->param4) || $section->parametrs->param4=='') $section->parametrs->param4 = "Наименование";
 if (!isset($section->parametrs->param6) || $section->parametrs->param6=='') $section->parametrs->param6 = "Цена";
 if (!isset($section->parametrs->param7) || $section->parametrs->param7=='') $section->parametrs->param7 = "Кол-во";
 if (!isset($section->parametrs->param8) || $section->parametrs->param8=='') $section->parametrs->param8 = "Сумма";
 if (!isset($section->parametrs->param9) || $section->parametrs->param9=='') $section->parametrs->param9 = "Удалить";
 if (!isset($section->parametrs->param10) || $section->parametrs->param10=='') $section->parametrs->param10 = "Итого";
 if (!isset($section->parametrs->param11) || $section->parametrs->param11=='') $section->parametrs->param11 = "Продолжить покупки";
 if (!isset($section->parametrs->param12) || $section->parametrs->param12=='') $section->parametrs->param12 = "Пересчитать";
 if (!isset($section->parametrs->param13) || $section->parametrs->param13=='') $section->parametrs->param13 = "Очистить";
 if (!isset($section->parametrs->param14) || $section->parametrs->param14=='') $section->parametrs->param14 = "Заказать";
 if (!isset($section->parametrs->param15) || $section->parametrs->param15=='') $section->parametrs->param15 = "Имя";
 if (!isset($section->parametrs->param16) || $section->parametrs->param16=='') $section->parametrs->param16 = "Ваш e-mail";
 if (!isset($section->parametrs->param22) || $section->parametrs->param22=='') $section->parametrs->param22 = "Телефон";
 if (!isset($section->parametrs->param17) || $section->parametrs->param17=='') $section->parametrs->param17 = "Оформление заказа";
 if (!isset($section->parametrs->param19) || $section->parametrs->param19=='') $section->parametrs->param19 = "Здравствуйте,  [NAMECLIENT]!\\r\\n \\r\\nВы оформили заказ №[SHOP_ORDER_NUM] на сайте [THISNAMESITE]\\r\\n \\r\\n[HR][SHOP_ORDER_VALUE_LIST]\\r\\n[HR]Сумма заказа: [SHOP_ORDER_SUMM] [VALUTA]\\r\\n \\r\\n";
 if (!isset($section->parametrs->param20) || $section->parametrs->param20=='') $section->parametrs->param20 = "Здравствуйте,  Администратор!\\r\\n \\r\\nВаш клиент [NAMECLIENT] оформил заказ №[SHOP_ORDER_NUM] на сайте [THISNAMESITE]\\r\\n \\r\\n[HR][SHOP_ORDER_VALUE_LIST]\\r\\n[HR]Сумма заказа: [SHOP_ORDER_SUMM] [VALUTA]\\r\\n\\r\\ne-mail клиента: [CLIENTEMAIL]  \\r\\n";
 if (!isset($section->parametrs->param21) || $section->parametrs->param21=='') $section->parametrs->param21 = "Заказ отправлен. В ближайшее время наш менеджер свяжется с Вами.";
 if (!isset($section->parametrs->param23) || $section->parametrs->param23=='') $section->parametrs->param23 = "Заполните все поля, помеченные звездочками";
 if (!isset($section->parametrs->param25) || $section->parametrs->param25=='') $section->parametrs->param25 = "Да";
 if (!isset($section->parametrs->param26) || $section->parametrs->param26=='') $section->parametrs->param26 = "Нет";
 if (!isset($section->parametrs->param27) || $section->parametrs->param27=='') $section->parametrs->param27 = "Ошибка при отправке почты";
 if (!isset($section->parametrs->param32) || $section->parametrs->param32=='') $section->parametrs->param32 = "N";
 if (!isset($section->parametrs->param33) || $section->parametrs->param33=='') $section->parametrs->param33 = "Нажимая кнопку «Заказать», я принимаю условия <a class=\"lnkLicense\" href=\"/license/\" target=\"_blank\">Пользовательского соглашения</a> и даю своё согласие на обработку моих персональных данных";
 if (!isset($section->parametrs->param34) || $section->parametrs->param34=='') $section->parametrs->param34 = "N";
 if (!isset($section->parametrs->param35) || $section->parametrs->param35=='') $section->parametrs->param35 = "Принимаю <a href=\"#\" target=\"_blank\">условия</a>";
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
