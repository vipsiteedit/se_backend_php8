<?php if($section->parametrs->param17!=""): ?>
<!--script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script-->
<header:js>
    [js:jquery/jquery.min.js]
</header:js> 
<script src="http://api-maps.yandex.ru/1.1/index.xml?key=<?php echo $section->parametrs->param17 ?>" type="text/javascript"></script>
<script type="text/javascript">
   function showAddress (value) {
        var map, geoResult;
        geoResult = null;
        YMaps.jQuery(function () {
            // Создание экземпляра карты и его привязка к созданному контейнеру
            map = new YMaps.Map(YMaps.jQuery("#YMapsID")[0]);
 
            // Установка для карты ее центра и масштаба
            map.setCenter(new YMaps.GeoPoint(0, 0), 100);
 
            // Добавление элементов управления
            map.addControl(new YMaps.TypeControl());
            
            map.addControl(new YMaps.ToolBar());
            map.addControl(new YMaps.Zoom());
            map.addControl(new YMaps.ScaleLine());
            
        //alert(value);
            // Удаление предыдущего результата поиска
            map.removeOverlay(geoResult);
 
            // Запуск процесса геокодирования
            var geocoder = new YMaps.Geocoder(value, {results: 1, boundedBy: map.getBounds()});
 
            // Создание обработчика для успешного завершения геокодирования
            YMaps.Events.observe(geocoder, geocoder.Events.Load, function () {
                // Если объект был найден, то добавляем его на карту
                // и центрируем карту по области обзора найденного объекта
                if (this.length()) {
                    geoResult = this.get(0);
                    map.addOverlay(geoResult);
                    map.setBounds(geoResult.getBounds());
                }
            });
 
            // Процесс геокодирования завершен неудачно
            YMaps.Events.observe(geocoder, geocoder.Events.Fault, function (geocoder, error) {
                alert("<?php echo $section->language->lang001 ?> " + error);
            })
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function(){  
        showAddress('<?php echo $section->parametrs->param10 ?>, <?php echo $section->parametrs->param11 ?>');
        });
</script>
<?php endif; ?>
<div class="content contacts" <?php echo $section->style ?>><div class="vcard">
<?php if(!empty($section->title)): ?><h3 class="contentTitle" <?php echo $section->style_title ?>>
  <span class="contentTitleTxt"><?php echo $section->title ?></span></h3>
<?php endif; ?>
<?php if(!empty($section->image)): ?>
  <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
<?php endif; ?>
<?php if(!empty($section->text)): ?>
  <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
<?php endif; ?>
<?php if($section->parametrs->param17!=""): ?>
  <div id="YMapsID" style="width:100%; height:400px"></div>
<?php endif; ?>
  <div class="name">
    <span class="orgtitle"><?php echo $section->language->lang007 ?></span><span class="fn org"><?php echo $section->parametrs->param6 ?></span>
  </div>
  <div class="phone">
    <span class="orgtitle"><?php echo $section->language->lang008 ?></span><span class="tel"><?php echo $section->parametrs->param7 ?></span>
  </div>
  <div class="adr">
    <span class="orgtitle"><?php echo $section->language->lang009 ?></span>
     <span class="postal-code"><?php echo $section->parametrs->param8 ?></span><?php echo $section->parametrs->param13 ?><span class="region"><?php echo $section->parametrs->param9 ?></span><?php echo $section->parametrs->param13 ?> 
     <span class="locality"><?php echo $section->parametrs->param10 ?></span><?php echo $section->parametrs->param13 ?><span class="street-address"><?php echo $section->parametrs->param11 ?></span>
     <br>
     <span class="url"><?php echo $section->language->lang010 ?> http://<?php echo $host ?></span>
  </div>
</div></div>
<?php if($section->parametrs->param15==Y): ?>


<?php endif; ?>
