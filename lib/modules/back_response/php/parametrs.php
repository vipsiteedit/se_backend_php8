<?php
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "new";
 if (empty($section->parametrs->param17)) $section->parametrs->param17 = "30";
 if (empty($section->parametrs->param13)) $section->parametrs->param13 = "Y";
 if (empty($section->parametrs->param14)) $section->parametrs->param14 = "N";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "31.12.2030";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "Подписаться";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "Фамилия";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "Имя";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "Отчество";
 if (empty($section->parametrs->param9)) $section->parametrs->param9 = "Email";
 if (empty($section->parametrs->param10)) $section->parametrs->param10 = "Телефон";
 if (empty($section->parametrs->param12)) $section->parametrs->param12 = "Запись добавлена в БД";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "Сохранить в CSV-файл";
 if (empty($section->parametrs->param16)) $section->parametrs->param16 = "Назад";
 if (empty($section->parametrs->param18)) $section->parametrs->param18 = "Подписка";
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