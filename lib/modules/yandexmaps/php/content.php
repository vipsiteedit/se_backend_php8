<?php





 if ($flagADM==1) 
{
    $moder_link = "<a class=\"moderlink\" title=\"{$section->parametrs->param29}\" href=\"[@subpage3]\">".$section->parametrs->param29."</a>";
}







    // Обьявляю пременные
    $blizpoints='';

    

    
      $Points = new seTable('yandexmaps');
      // $Points ->where("page='?'", $_page);       // Отбираем только те точки котрые принадлежат модератору
       $Points -> select('`id`, LEFT(`text`,600) as `text`, `X`, `Y`, `image1`, `image2`');
     $Pointslist = $Points -> getList();

       foreach ( $Pointslist as $msg) {
            
            $img1vivod='';
            $img2vivod='';
            if ($section->parametrs->param55=="Y") {
                if ($msg['image1']!='') {  
                   $img= explode('.',$msg['image1']);
                   $imgfull1='/images/yandexmaps/'.($img[0].'_prev.'.$img[1]);   
                   $img1vivod='<IMG src=\''.$imgfull1.'\' border=\'0\'>';    
                }
            }
            if ($section->parametrs->param56=="Y") { 
                if ($msg['image2']!='') { 
                    $img= explode('.',$msg['image2']);
                    $imgfull2='/images/yandexmaps/'.($img[0].'_prev.'.$img[1]);       
                    $img2vivod='<IMG   src=\''.$imgfull2.'\' border=\'0\'>';  
                }
            } 



             $str=$msg['text']; 
             $leght=$section->parametrs->param45;

            $str = UTF8_substr ($str, 0, $leght);
             if ((strlen($msg['text']))>$leght) { $str.=$section->parametrs->param46; }
             $str = str_replace ("\r\n", "<br>", $str);

            $links='<a href=\'[link.subpage=4]?id='.($msg['id']).'/\'>'.$section->parametrs->param35.'</a>';
            $blizpoints.='
            //!!!НАЧАЛО!!!! Создание метки
            var placemark = new YMaps.Placemark(new YMaps.GeoPoint('. $msg['X'] .', '.$msg['Y'].'), {style: '.$p44.'});
             // Устанавливает содержимое балуна     
            placemark.setBalloonContent("'.$img1vivod.'&nbsp;'.$img2vivod.'<font color=\'#000000\'><div>'.$str.'</div></font><br>'.$links.'"); 
            //!!!КОНЕЦ!!!! 
            objManager.add(placemark, 11, 18);
            ';   
            $x= (round(($msg ['X']), 1))."";
            $y= (round(($msg ['Y']), 1))."";
            $arr10 [$x] [$y] = $msg ['X'].chr(8).$msg ['Y'];
            $x= (round(($msg ['X']), 0))."";
            $y= (round(($msg ['Y']), 0))."";
            $arr11 [$x] [$y] = $msg ['X'].chr(8).$msg ['Y'];
            };
         foreach ( $arr10 as $msg) {
            foreach ( $msg as $podmsg) {
              $XY= explode(chr(8), $podmsg);
              
              $blizpoints.='
            //!!!НАЧАЛО!!!! Создание метки
            var placemark = new YMaps.Placemark(new YMaps.GeoPoint('. $XY[0] .', '.$XY[1].'), {style: '.$p43.'});
             // По щелчку на карте происходит приближение    "default#blueSmallPoint"
                YMaps.Events.observe(placemark, placemark.Events.Click, function () {
                    map.setZoom(11, { smooth : true, position:new YMaps.GeoPoint('. $XY[0] .', '.$XY[1].'),  centering: true });
                });
            //!!!КОНЕЦ!!!! 
            objManager.add(placemark, 5, 10);
            ';   


            }
         };
         
         
         foreach ( $arr11 as $msg) {
            foreach ( $msg as $podmsg) {
              $XY= explode(chr(8), $podmsg);
              
              $blizpoints.='
            //!!!НАЧАЛО!!!! Создание метки
            var placemark = new YMaps.Placemark(new YMaps.GeoPoint('. $XY[0] .', '.$XY[1].'), {style: '.$p42.'});
 
             // По щелчку на карте происходит приближение     
                YMaps.Events.observe(placemark, placemark.Events.Click, function () {
                    map.setZoom(8, { smooth : true, position:new YMaps.GeoPoint('. $XY[0] .', '.$XY[1].'),  centering: true });
                });

            //!!!КОНЕЦ!!!! 
            objManager.add(placemark, 0, 4);
            ';   


            }
         };
            
            




           
?>