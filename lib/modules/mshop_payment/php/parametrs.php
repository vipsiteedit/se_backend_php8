<?php
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "Дата операции";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "Приход";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "Расход";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "Баланс на";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "№ заказа";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "Способ оплаты";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "Yes";
 if (empty($section->parametrs->param9)) $section->parametrs->param9 = "Лицевой счет";
 if (empty($section->parametrs->param10)) $section->parametrs->param10 = "Оплата осуществляется при наличии средств на Вашем лицевом счете.";
 if (empty($section->parametrs->param11)) $section->parametrs->param11 = "Выбрать оплату";
 if (empty($section->parametrs->param12)) $section->parametrs->param12 = "Оплата заказа : ";
 if (empty($section->parametrs->param14)) $section->parametrs->param14 = "Вернуться";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "Движение средств по счету";
 if (empty($section->parametrs->param16)) $section->parametrs->param16 = "Дата";
 if (empty($section->parametrs->param17)) $section->parametrs->param17 = "Приход";
 if (empty($section->parametrs->param18)) $section->parametrs->param18 = "Расход";
 if (empty($section->parametrs->param19)) $section->parametrs->param19 = "Остаток";
 if (empty($section->parametrs->param20)) $section->parametrs->param20 = "Тип операции";
 if (empty($section->parametrs->param21)) $section->parametrs->param21 = "Комментарий";
 if (empty($section->parametrs->param22)) $section->parametrs->param22 = "Оплата с лицевого счета:";
 if (empty($section->parametrs->param23)) $section->parametrs->param23 = "Подтвердите оплату заказа ";
 if (empty($section->parametrs->param24)) $section->parametrs->param24 = "Оплатить";
 if (empty($section->parametrs->param25)) $section->parametrs->param25 = "RUR";
 if (empty($section->parametrs->param26)) $section->parametrs->param26 = "Сумма заказа ";
 if (empty($section->parametrs->param27)) $section->parametrs->param27 = "Оплата невозможна! Недостаточно средств на лицевом счете";
 if (empty($section->parametrs->param28)) $section->parametrs->param28 = "Оплата с лицевого счета успешно проведена!";
 if (empty($section->parametrs->param29)) $section->parametrs->param29 = "Остаток на начало месяца";
 if (empty($section->parametrs->param30)) $section->parametrs->param30 = "Учетный период";
 if (empty($section->parametrs->param31)) $section->parametrs->param31 = "Январь,Февраль,Март,Апрель,Май,Июнь,Июль,Август,Сентябрь,Октябрь,Ноябрь,Декабрь";
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