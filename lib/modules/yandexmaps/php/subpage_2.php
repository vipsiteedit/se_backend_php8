<?php

if ($flagADM!=1) return;   

$points = new seTable('yandexmaps');
$id = getRequest('id', 1);
   
  $points -> find($id);
      
      
  $text= $points->text; 
  $coord= $points->x.','.$points->y; 
  $img1 =$points->image1; 
  $img2 =$points->image2; 
      
      $imgfull1='/images/yandexmaps/'.($points->image1);   
      $img1vivod='<IMG class="obj img1v" alt="" src="'.$imgfull1.'" border="0">';
      if (($points->image1)!="") {$flag1="1";}    
      $imgfull2='/images/yandexmaps/'.$points->image2;       
      $img2vivod='<IMG class="obj img2v" alt="" src="'.$imgfull2.'" border="0">'; 
      if (($points->image2)!="") {$flag2="1";}  

    if (isRequest('GoTo')) {
        
        
        //считование 
        $coord = se_db_output(getRequest('coord',3));
        $text = se_db_output(getRequest('text',3));
        
    // Обработка картинок
    $width_prew = $section->parametrs->param33; 
      if ($width_prew==0)
      {
      $width_prew=100;
      }
      $width = $section->parametrs->param34; if ($width==0) $width=350;
      if (is_uploaded_file($_FILES['userfile']['tmp_name'][0])){ 
        $img1 = se_set_image_prev($width_prew,$width,"yandexmaps",('img1'.time()),0);
      }
      if (is_uploaded_file($_FILES['userfile']['tmp_name'][1])){ 
        $img2 = se_set_image_prev($width_prew,$width,"yandexmaps",('img2'.time()),1);
      }

    // Конец обработки картинок
      
      //Обработка  удаления картинок
        if (isRequest('delimg1')) {
         $img1="";
        }
      
        if (isRequest('delimg2')) {
         $img2="";
        }
      
        
        
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
       
       // $points->user_id = seUserId(); //  Если админ поправит значит точка становится админской ;)
        $points->x = $XY[0];
        $points->y = $XY[1];
        $points->text = $text; 
        $points->lang =se_getLang();
        $points->image1 = $img1; 
        $points->image2 = $img2; 
        
        
       
         if ($points->save()) {
           Header("Location: /".$_page."/?".time());
         }
       }
}

//обработка удаления точки
        if (isRequest('delpoint')) {
          $points -> delete(); 
          Header("Location: /{$_page}/{$razdel}/sub3/?".time());
        }

?>