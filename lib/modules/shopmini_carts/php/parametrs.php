<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "{$__data->prj->vars->adminmail}";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "руб";
 if (empty($section->parametrs->param24)) $section->parametrs->param24 = "500";
 if (empty($section->parametrs->param28)) $section->parametrs->param28 = "Загружаемый файл превышает размер:";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "Код";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "Наименование";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "Цена";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "Кол-во";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "Сумма";
 if (empty($section->parametrs->param9)) $section->parametrs->param9 = "Удалить";
 if (empty($section->parametrs->param10)) $section->parametrs->param10 = "Итого";
 if (empty($section->parametrs->param11)) $section->parametrs->param11 = "Продолжить покупки";
 if (empty($section->parametrs->param12)) $section->parametrs->param12 = "Пересчитать";
 if (empty($section->parametrs->param13)) $section->parametrs->param13 = "Очистить";
 if (empty($section->parametrs->param14)) $section->parametrs->param14 = "Заказать";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "Имя";
 if (empty($section->parametrs->param16)) $section->parametrs->param16 = "Ваш e-mail";
 if (empty($section->parametrs->param22)) $section->parametrs->param22 = "Телефон";
 if (empty($section->parametrs->param17)) $section->parametrs->param17 = "Оформление заказа";
 if (empty($section->parametrs->param19)) $section->parametrs->param19 = "Здравствуйте,  [NAMECLIENT]!\\r\\n \\r\\nВы оформили заказ №[SHOP_ORDER_NUM] на сайте [THISNAMESITE]\\r\\n \\r\\n[HR][SHOP_ORDER_VALUE_LIST]\\r\\n[HR]Сумма заказа: [SHOP_ORDER_SUMM] [VALUTA]\\r\\n \\r\\n";
 if (empty($section->parametrs->param20)) $section->parametrs->param20 = "Здравствуйте,  Администратор!\\r\\n \\r\\nВаш клиент [NAMECLIENT] оформил заказ №[SHOP_ORDER_NUM] на сайте [THISNAMESITE]\\r\\n \\r\\n[HR][SHOP_ORDER_VALUE_LIST]\\r\\n[HR]Сумма заказа: [SHOP_ORDER_SUMM] [VALUTA]\\r\\n \\r\\n";
 if (empty($section->parametrs->param21)) $section->parametrs->param21 = "Заказ отправлен. В ближайшее время наш менеджер свяжется с Вами.";
 if (empty($section->parametrs->param23)) $section->parametrs->param23 = "Заполните все поля, помеченные звездочками";
 if (empty($section->parametrs->param25)) $section->parametrs->param25 = "Да";
 if (empty($section->parametrs->param26)) $section->parametrs->param26 = "Нет";
 if (empty($section->parametrs->param27)) $section->parametrs->param27 = "Ошибка при отправке почты";
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