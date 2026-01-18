<?php if($section->parametrs->param15=='Y'): ?>
<header:js>
    [js:jquery/jquery.min.js]
    <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script type="text/javascript">
    var geo = {
        Address: '<?php echo $section->parametrs->param9 ?>, <?php echo $section->parametrs->param10 ?>, <?php echo $section->parametrs->param11 ?>'
    }  
    var param = '<?php echo $section->parametrs->param18 ?>';
    </script>
    
    <script type="text/javascript">
    ymaps.ready(init);
function init() {
    var myMap = new ymaps.Map('YMapsID', {
        center: [55.753994, 37.622093],
        zoom: <?php echo $section->parametrs->param19 ?>,
    });
    console.log(param);
    if (param == 'N') {
        myMap.behaviors.disable(['wheel','scrollZoom']);
    }
    //myMap.behaviors.disable(['wheel','scrollZoom']);
    // Поиск координат центра Нижнего Новгорода.
    ymaps.geocode(geo.Address, {
        /**
         * Опции запроса
         * @see http://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/geocode.xml
         */
        // boundedBy: myMap.getBounds(), // Сортировка результатов от центра окна карты
        // strictBounds: true, // Вместе с опцией boundedBy будет искать строго внутри области, указанной в boundedBy
        results: 1 // Если нужен только один результат, экономим трафик пользователей
    }).then(function (res) {
            // Выбираем первый результат геокодирования.
            var firstGeoObject = res.geoObjects.get(0),
                // Координаты геообъекта.
                coords = firstGeoObject.geometry.getCoordinates(),
                // Область видимости геообъекта.
                bounds = firstGeoObject.properties.get('boundedBy');
            // Добавляем первый найденный геообъект на карту.
            myMap.geoObjects.add(firstGeoObject);
            // Масштабируем карту на область видимости геообъекта.
            myMap.setBounds(bounds, {
                checkZoomRange: true // проверяем наличие тайлов на данном масштабе.
            });
            /**
             * Все данные в виде javascript-объекта.
             */
            console.log('Все данные геообъекта: ', firstGeoObject.properties.getAll());
            /**
             * Метаданные запроса и ответа геокодера.
             * @see http://api.yandex.ru/maps/doc/geocoder/desc/reference/GeocoderResponseMetaData.xml
             */
            console.log('Метаданные ответа геокодера: ', res.metaData);
            /**
             * Метаданные геокодера, возвращаемые для найденного объекта.
             * @see http://api.yandex.ru/maps/doc/geocoder/desc/reference/GeocoderMetaData.xml
             */
            console.log('Метаданные геокодера: ', firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData'));
            /**
             * Точность ответа (precision) возвращается только для домов.
             * @see http://api.yandex.ru/maps/doc/geocoder/desc/reference/precision.xml
             */
            console.log('precision', firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.precision'));
            /**
             * Тип найденного объекта (kind).
             * @see http://api.yandex.ru/maps/doc/geocoder/desc/reference/kind.xml
             */
            console.log('Тип геообъекта: %s', firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.kind'));
            console.log('Название объекта: %s', firstGeoObject.properties.get('name'));
            console.log('Описание объекта: %s', firstGeoObject.properties.get('description'));
            console.log('Полное описание объекта: %s', firstGeoObject.properties.get('text'));
            /**
             * Если нужно добавить по найденным геокодером координатам метку со своими стилями и контентом балуна, создаем новую метку по координатам найденной и добавляем ее на карту вместо найденной.
             */
            /**
             var myPlacemark = new ymaps.Placemark(coords, {
             iconContent: 'моя метка',
             balloonContent: 'Содержимое балуна <strong>моей метки</strong>'
             }, {
             preset: 'islands#violetStretchyIcon'
             });
             myMap.geoObjects.add(myPlacemark);
             */
        });
}
</script>
    
    
    <script type="text/javascript" src="[module_url]engine.js"></script>
    <style type="text/css">
        #YMapsID {
            margin: 0;
            padding: 0;
        }
    </style>
</header:js> 
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
<?php if($section->parametrs->param15=='Y'): ?>
  <div id="YMapsID"  style="width:<?php echo $section->parametrs->param16 ?>; height:<?php echo $section->parametrs->param17 ?>"></div>
<?php endif; ?>
  <div class="name">
    <span class="orgtitle"><?php echo $section->language->lang007 ?></span><span class="fn org"><?php echo $section->parametrs->param6 ?></span>
  </div>
  <div class="phone">
    <span class="orgtitle"><?php echo $section->language->lang008 ?></span><span class="tel"><?php echo $section->parametrs->param7 ?></span>
  </div>
  <div class="adr">
    <span class="orgtitle"><?php echo $section->language->lang009 ?></span>
     <span class="postal-code"><?php echo $section->parametrs->param8 ?></span><?php if($section->parametrs->param8): ?><?php echo $section->parametrs->param13 ?><?php endif; ?>
     <span class="region"><?php echo $section->parametrs->param9 ?></span><?php if($section->parametrs->param9): ?><?php echo $section->parametrs->param13 ?><?php endif; ?>
     <span class="locality"><?php echo $section->parametrs->param10 ?></span><?php if($section->parametrs->param10): ?><?php echo $section->parametrs->param13 ?><?php endif; ?>
     <span class="street-address"><?php echo $section->parametrs->param11 ?></span>
     <br>
     <span class="url"><?php echo $section->language->lang010 ?> http://<?php echo $_SERVER['HTTP_HOST'] ?></span>
  </div>
</div></div>
