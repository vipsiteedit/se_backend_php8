<?php

if ($flagADM!=1) return;   




    if (isRequest('GoTo')) {
        $Points = new seTable('yandexmaps');
        
        //считование 
        $coord = getRequest('coord',3);
        $text = getRequest('text',3);
        
    // Обработка картинок
    $width_prew = $section->parametrs->param33; 
      if ($width_prew==0)
      {
      $width_prew=100;
      }
      $width = $section->parametrs->param34; if ($width==0) $width=350;
      $img1 = se_set_image_prev($width_prew,$width,"yandexmaps",('img1'.time()),0);
      $img2 = se_set_image_prev($width_prew,$width,"yandexmaps",('img2'.time()),1);
    // Конец обработки картинок
        
        
             //Предполагаем, что все введеные данные корректны
      $flag=true;
        
      //обрабатываем Координаты
      if (empty($coord)) {
        $flag=false;
        $errortext=$section->parametrs->param32;
      }
        
        
        
        $XY= explode(",", $coord);
        
        if (!preg_match("/^\-?[\d]{1,3}.[\d]{1,6}$/", $XY[0])) {
        $flag=false;
        $errortext=$section->parametrs->param32;
      }
      if (!preg_match("/^\-?[\d]{1,3}.[\d]{1,6}$/", $XY[1])) {
        $flag=false;
        $errortext=$section->parametrs->param32;
      }
        
      if (!$flag) {  //если есть ошибки, то отправляем в космос

      }
      else {
        //добавляем запись
        $Points->insert();
        $Points->user_id = seUserId();
        $Points->x = $XY[0];
        $Points->y = $XY[1];
        $Points->text = $text; 
        $Points->lang =se_getLang();
        $Points->image1 = $img1; 
        $Points->image2 = $img2; 
        
        
       
         if ($Points->save()) {
           Header("Location: /".$_page."/?".time());
         }
         }
}

?>