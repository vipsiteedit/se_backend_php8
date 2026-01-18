<?php

  $points = new seTable('yandexmaps');
  $id = getRequest('id', 1);
   
  $points -> find($id);
  
      $textVivod=' <div class="text"> '.($points->text).'</div>'; 
      
      $imgfull1='/images/yandexmaps/'.($points->image1);   
      $img1vivod='<IMG class="img1" alt="" src="'.$imgfull1.'" border="0">';    
      $imgfull2='/images/yandexmaps/'.$points->image2;       
      $img2vivod='<IMG class="img2" alt="" src="'.$imgfull2.'" border="0">';    
      
  

?>