<?php if($section->parametrs->param17!=""): ?>
<!--script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"type="text/javascript"></script-->
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
                        // Добавление элементов управления
           
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
          
                       
             <?php echo $style ?>
             <?php echo $blizpoints ?>
             
           
        });
    </script>
<?php endif; ?>
<div class="content geopoints" <?php echo $section->style ?>>
<div class="vcard">
<?php if($section->title!=''): ?><h3 class="contentTitle" <?php echo $section->style_title ?>>
  <span class="contentTitleTxt"><?php echo $section->title ?></span></h3>
<?php endif; ?>
<?php if($section->text!=''): ?>
  <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
<?php endif; ?>
<?php echo $moder_link ?>
<?php if($section->parametrs->param17!=""): ?>
  <div id="YMapsID" style="width:100%; height:400px"></div>
<?php endif; ?>
<?php if($section->parametrs->param15==Y): ?>


<?php endif; ?>
</div>
</div>
