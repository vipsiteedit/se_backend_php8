<?php
 if (empty($section->parametrs->param28)) $section->parametrs->param28 = "";
 if (empty($section->parametrs->param29)) $section->parametrs->param29 = "Модераторская";
 if (empty($section->parametrs->param37)) $section->parametrs->param37 = "Редактировать точку";
 if (empty($section->parametrs->param38)) $section->parametrs->param38 = "Удалить точку";
 if (empty($section->parametrs->param45)) $section->parametrs->param45 = "20";
 if (empty($section->parametrs->param46)) $section->parametrs->param46 = "...";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "Y";
 if (empty($section->parametrs->param17)) $section->parametrs->param17 = "{$__data->prj->sitelicense}";
 if (empty($section->parametrs->param58)) $section->parametrs->param58 = "Y";
 if (empty($section->parametrs->param59)) $section->parametrs->param59 = "Y";
 if (empty($section->parametrs->param60)) $section->parametrs->param60 = "N";
 if (empty($section->parametrs->param61)) $section->parametrs->param61 = "Y";
 if (empty($section->parametrs->param62)) $section->parametrs->param62 = "Y";
 if (empty($section->parametrs->param63)) $section->parametrs->param63 = "Y";
 if (empty($section->parametrs->param42)) $section->parametrs->param42 = "default#darkblueSmallPoint";
 if (empty($section->parametrs->param43)) $section->parametrs->param43 = "default#blueSmallPoint";
 if (empty($section->parametrs->param44)) $section->parametrs->param44 = "default#lightbluePoint";
 if (empty($section->parametrs->param50)) $section->parametrs->param50 = "18";
 if (empty($section->parametrs->param51)) $section->parametrs->param51 = "29";
 if (empty($section->parametrs->param52)) $section->parametrs->param52 = "-9";
 if (empty($section->parametrs->param53)) $section->parametrs->param53 = "-29";
 if (empty($section->parametrs->param33)) $section->parametrs->param33 = "100";
 if (empty($section->parametrs->param34)) $section->parametrs->param34 = "300";
 if (empty($section->parametrs->param55)) $section->parametrs->param55 = "Y";
 if (empty($section->parametrs->param56)) $section->parametrs->param56 = "N";
 if (empty($section->parametrs->param20)) $section->parametrs->param20 = "Текст всплывающей подсказки";
 if (empty($section->parametrs->param21)) $section->parametrs->param21 = "Подробный просмотр";
 if (empty($section->parametrs->param22)) $section->parametrs->param22 = "Рисунок 1";
 if (empty($section->parametrs->param23)) $section->parametrs->param23 = "Рисунок 2";
 if (empty($section->parametrs->param24)) $section->parametrs->param24 = "Создать точку";
 if (empty($section->parametrs->param25)) $section->parametrs->param25 = "Отмена";
 if (empty($section->parametrs->param26)) $section->parametrs->param26 = "Добавить точку";
 if (empty($section->parametrs->param30)) $section->parametrs->param30 = "Назад";
 if (empty($section->parametrs->param31)) $section->parametrs->param31 = "Координаты";
 if (empty($section->parametrs->param32)) $section->parametrs->param32 = "Координаты введены не корректно";
 if (empty($section->parametrs->param35)) $section->parametrs->param35 = "Подробно";
 if (empty($section->parametrs->param36)) $section->parametrs->param36 = "Поместите в нужное место.";
 if (empty($section->parametrs->param39)) $section->parametrs->param39 = "Сохранить изменения";
 if (empty($section->parametrs->param40)) $section->parametrs->param40 = "Удалить картинку?";
 if (empty($section->parametrs->param41)) $section->parametrs->param41 = "Удалить точку ?";
 if (empty($section->parametrs->param47)) $section->parametrs->param47 = "Форма добавления точки";
 if (empty($section->parametrs->param48)) $section->parametrs->param48 = "Форма редактирования точки";
 if (empty($section->parametrs->param49)) $section->parametrs->param49 = "Подробный просмотр";
   foreach($section->parametrs as $__paramitem){
    foreach($__paramitem as $__name=>$__value){
      if (empty($__value)){
      }
      if (preg_match("/\[%([\w\d\-]+)%\]/u", $__value, $m)!=false){
        $section->parametrs->$__name = $__data->prj->$m;
      }
     }
   }
?>