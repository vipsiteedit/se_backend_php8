<?php
 if (!isset($section->parametrs->param0) || $section->parametrs->param0=='') $section->parametrs->param0 = "10";
 if (!isset($section->parametrs->param1) || $section->parametrs->param1=='') $section->parametrs->param1 = "{$__data->prj->vars->adminmail}";
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "10";
 if (!isset($section->parametrs->param8) || $section->parametrs->param8=='') $section->parametrs->param8 = "\"Уважаемый администратор сайта [SITE]!\\r\\n\\r\\nНа Вашем сайте с помощью системы \"Поиск по сайту\" искали следующие слова и выражения:\\r\\n\\r\\n    Дата       Время       IP адрес       Строка запроса\\r\\n";
 if (!isset($section->parametrs->param9) || $section->parametrs->param9=='') $section->parametrs->param9 = "monlinenews,";
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