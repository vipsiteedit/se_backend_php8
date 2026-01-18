<?php
 if (!isset($section->parametrs->param8)) $section->parametrs->param8 = "Yes";
 if (!isset($section->parametrs->param10)) $section->parametrs->param10 = "Оплата осуществляется при наличии средств на Вашем лицевом счете.";
 if (!isset($section->parametrs->param23)) $section->parametrs->param23 = "Подтвердите оплату заказа ";
 if (!isset($section->parametrs->param25)) $section->parametrs->param25 = "RUR";
 if (!isset($section->parametrs->param27)) $section->parametrs->param27 = "Оплата невозможна! Недостаточно средств на лицевом счете";
 if (!isset($section->parametrs->param28)) $section->parametrs->param28 = "Оплата с лицевого счета успешно проведена!";
 if (!isset($section->parametrs->param32)) $section->parametrs->param32 = "Чтобы просматривать свои заказы и иметь доступ к лицевому счету необходимо авторизоваться";
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