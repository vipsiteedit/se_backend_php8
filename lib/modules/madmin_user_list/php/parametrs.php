<?php
 if (empty($section->parametrs->param115)) $section->parametrs->param115 = "30";
 if (empty($section->parametrs->param131)) $section->parametrs->param131 = "{$__data->prj->vars->adminmail}";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "Здравствуйте, %user% \\r\\n Администратор %site% активизировал Вам доступ на сайте.\\r\\nКоды Авторизации (логин и пароль) были высланы на указанный Вами E-Mail во время регистрации.\\r\\n\\r\\nС уважением, %site%";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "Активизация доступа на сайте %site%";
 if (empty($section->parametrs->param111)) $section->parametrs->param111 = "20";
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