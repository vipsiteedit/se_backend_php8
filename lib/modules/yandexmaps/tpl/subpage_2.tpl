 
    <?php if($flagADM==1): ?> 

<div class="content geopoints editpoint">
   <?php if($section->parametrs->param17!=""): ?>
        <header:js>
            [js:jquery/jquery.min.js]
        </header:js> 
        <script src="http://api-maps.yandex.ru/1.1/index.xml?key=<?php echo $section->parametrs->param17 ?>" type="text/javascript"></script>
        <script type="text/javascript">
            // Создание обработчика для события window.onLoad
        YMaps.jQuery(function () {
            // Создание экземпляра карты и его привязка к созданному контейнеру
            var map = new YMaps.Map(document.getElementById("YMapsID"));
            // Установка для карты ее центра и масштаба
            map.setCenter(new YMaps.GeoPoint(<?php echo $coord ?>), 14);
            // Добавление элементов управления
           <?php if($section->parametrs->param61==Y): ?>  
                map.addControl(new YMaps.Zoom());
                map.enableScrollZoom();
           <?php endif; ?>
           
            <?php if($section->parametrs->param62==Y): ?> 
                map.addControl(new YMaps.TypeControl());
            <?php endif; ?>
            <?php if($section->parametrs->param63==Y): ?> 
                map.addControl(new YMaps.SearchControl());
            <?php endif; ?>
            
            // Создание перетаскиваемой метки
            var placemark = new YMaps.Placemark(map.getCenter(), {draggable: true});
            placemark.setBalloonContent("<?php echo $section->parametrs->param36 ?>"); 
            map.addOverlay(placemark);
            YMaps.Events.observe(placemark, placemark.Events.DragEnd, function (obj) {
            // отправляем координаты в форму
                             
                document.getElementById ('coord').value = obj.getGeoPoint();
               
                });
            });
        </script>
    <?php endif; ?>
    <div class="ContetntTitle"> <?php echo $section->parametrs->param48 ?> </div>
    <?php if($section->parametrs->param17!=""): ?>
        <div id="YMapsID" style="width:100%; height:400px"></div>
    <?php endif; ?>
<form style="margin:0px;" action="" method="post" enctype="multipart/form-data">
   <div class="errortext"><?php echo $errortext ?></div>
   <div class="form">
        <div class="obj coord">
            <label class="title" for="coord"><?php echo $section->parametrs->param31 ?></label>
             <div class="field">
                <input class="inputs" id="coord" name="coord" value="<?php echo $coord ?>" maxlength="50">
             </div> 
        </div>
  
        <div class="obj text"> 
            <label class="title" for="text"><?php echo $section->parametrs->param20 ?></label>
            <div class="field">
                <textarea class="inputs" id="text" name="text" rows="10" cols="40"><?php echo $text ?></textarea>
            </div>
        </div>
  
        <div class="prosm"> 
            <div class="prosmtitl">
                <label class="title" for="text"><?php echo $section->parametrs->param21 ?></label>
            </div> 
            <div class="poleris">
            
         
            <?php echo $img1vivod ?>
            <?php if($flag1==1): ?>
                <div class="obj imgdel1">
                    <div class="field"> 
                        <input  class="inputs fieldvn" type="checkbox" name="delimg1" value="true" id="delimg1"> 
                    </div>
                    <label class="title" for="del"><?php echo $section->parametrs->param40 ?></label>
                </div>
            <?php endif; ?>
            <div class="obj img1"> 
                <label class="title" for="text"><?php echo $section->parametrs->param22 ?></label>
            <div class="field">
                <input class="inputs fieldvn" id="userfile" type="file" name="userfile[]">
            </div> 
        </div> 
   </div>  
   <div class="poleris">
        
        <?php echo $img2vivod ?>
        <?php if($flag2==1): ?>
            <div class="obj imgdel2">
                <div class="field"> 
                    <input  class="inputs fieldvn" type="checkbox" name="delimg2" value="true" id="delimg2"> 
                </div>
                <label class="title" for="del"><?php echo $section->parametrs->param40 ?></label>
            </div>
        <?php endif; ?>
        <div class="obj img2"> 
        <label class="title" for="text"><?php echo $section->parametrs->param23 ?></label>
        <div class="field"><input class="inputs fieldvn" id="userfile" type="file" name="userfile[]"></div> 
   </div> 
  </div> 
     </div> 
  <div class="obj delete">
        
     <div class="field"> <input  class="inputs" type="checkbox" name="delpoint" value="true" id="delpoint"> </div>
    
      <label class="title" for="del"><?php echo $section->parametrs->param41 ?></label>
  </div>
  </div>  
  <div class="groupButton">
  <input class="buttonSend goButton" name="GoTo" type="submit" value="<?php echo $section->parametrs->param39 ?>">
  <input class="buttonSend backButton" onclick="document.location = '<?php echo seMultiDir()."/".$_page."/" ?>'" type="button" value="<?php echo $section->parametrs->param25 ?>">
  
</div>
</form>
 
    <?php endif; ?> 


