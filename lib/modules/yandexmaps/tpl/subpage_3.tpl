 <?php if($flagADM==1): ?> 
<div class="content geopoints moders">
 <?php if($section->parametrs->param17!=""): ?>
<header:js>
    [js:jquery/jquery.min.js]
</header:js> 
<script src="http://api-maps.yandex.ru/1.1/index.xml?key=<?php echo $section->parametrs->param17 ?>" type="text/javascript"></script>
    <script type="text/javascript">
            // Создание обработчика для события window.onLoad
        YMaps.jQuery(function () {
        
                // Создает стиль
                var s = new YMaps.Style();
                // Создает стиль значка метки
                s.iconStyle = new YMaps.IconStyle();
                    s.iconStyle.href = "<?php echo $section->image ?>";
                    s.iconStyle.size = new YMaps.Point(<?php echo $section->parametrs->param50 ?>, <?php echo $section->parametrs->param51 ?>);
                    s.iconStyle.offset = new YMaps.Point(<?php echo $section->parametrs->param52 ?>, <?php echo $section->parametrs->param53 ?>);
        
        
        
            // Создание экземпляра карты и его привязка к созданному контейнеру
            map = new YMaps.Map(document.getElementById("YMapsID"));
            // Установка для карты ее центра и масштаба
            map.setCenter(new YMaps.GeoPoint(95.432148,65.496389), 2);
           <?php if($section->parametrs->param58==Y): ?>  
                map.addControl(new YMaps.Zoom());
                map.enableScrollZoom();
           <?php endif; ?>
           
            <?php if($section->parametrs->param59==Y): ?> 
            map.addControl(new YMaps.TypeControl());
            <?php endif; ?>
            <?php if($section->parametrs->param60==Y): ?> 
            map.addControl(new YMaps.SearchControl());
            <?php endif; ?>
           
           
           
           // Создание диспетчера объектов и добавление его на карту
            var objManager = new YMaps.ObjectManager();
            map.addOverlay(objManager);
            // Добавление объектов в диспетчер объектов
            
            <?php echo $blizpoints ?>  
        });
    </script>
<?php endif; ?>
<?php if($section->parametrs->param17!=""): ?>
  <div id="YMapsID" style="width:100%; height:400px"></div>
<?php endif; ?>
<?php if($section->parametrs->param15==Y): ?>


<?php endif; ?>
  <div class="groupButton">
   <input class="buttonSend goButton" onclick="document.location = '\<?php echo seMultiDir()."/".$_page."/".$razdel."/sub1/" ?>'" type="submit" value="<?php echo $section->parametrs->param26 ?>">
  <input class="buttonSend backButton" onclick="document.location = '<?php echo seMultiDir()."/".$_page."/" ?>'" type="button" value="<?php echo $section->parametrs->param30 ?>">
  </div>
</div>
<?php endif; ?> 

