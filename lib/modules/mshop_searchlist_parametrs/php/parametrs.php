<?php
 if (empty($section->parametrs->param140)) $section->parametrs->param140 = "";
 if (empty($section->parametrs->param106)) $section->parametrs->param106 = "";
 if (empty($section->parametrs->param115)) $section->parametrs->param115 = "N";
 if (empty($section->parametrs->param62)) $section->parametrs->param62 = "Очистить";
 if (empty($section->parametrs->param63)) $section->parametrs->param63 = "Искать";
 if (empty($section->parametrs->param67)) $section->parametrs->param67 = "от";
 if (empty($section->parametrs->param68)) $section->parametrs->param68 = "до";
 if (empty($section->parametrs->param145)) $section->parametrs->param145 = "Быстрый";
 if (empty($section->parametrs->param146)) $section->parametrs->param146 = "Расширенный";
 if (empty($section->parametrs->param147)) $section->parametrs->param147 = "Редактировать";
 if (empty($section->parametrs->param148)) $section->parametrs->param148 = "Сохранить";
 if (empty($section->parametrs->param149)) $section->parametrs->param149 = "да";
 if (empty($section->parametrs->param150)) $section->parametrs->param150 = "нет";
 if (empty($section->parametrs->param151)) $section->parametrs->param151 = "неважно";
 if (empty($section->parametrs->param152)) $section->parametrs->param152 = "Все параметры";
 if (empty($section->parametrs->param153)) $section->parametrs->param153 = "Расширенный поиск";
 if (empty($section->parametrs->param154)) $section->parametrs->param154 = "Назад";
 if (empty($section->parametrs->param155)) $section->parametrs->param155 = "Очистить все";
 if (empty($section->parametrs->param156)) $section->parametrs->param156 = "Все группы";
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