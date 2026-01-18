<div id="objectMap-<?php echo $contact->id ?>" class="contact-item-map" style="width:600px; height:400px;"></div>
<footer:js>
<script type='text/javascript'>
    ymaps.ready(init);
    function init(){
        var geocoder = new ymaps.geocode('<?php echo $contact->address ?>', {
            results: 1
        });
        geocoder.then(function (res) {
            var coord = res.geoObjects.get(0).geometry.getCoordinates();
            var map = new ymaps.Map('objectMap-<?php echo $contact->id ?>', {
                center: coord,
                zoom: 14,
                behaviors: ['default', 'scrollZoom']
            });
            var placemark = new ymaps.Placemark(coord, {
                balloonContent: <?php if(!empty($contact->name)): ?>'<h5><?php echo $contact->name ?></h5><?php endif; ?><?php if(!empty($contact->image)): ?><img src="<?php echo $contact->image ?>"><?php endif; ?><i style="display:inline-block;max-width:200px;"><?php echo $contact->address ?></i>'
            });
            map.geoObjects.add(placemark);
            placemark.balloon.open();        
                    
            map.controls.add('mapTools').add('zoomControl').add('typeSelector');
        });
    }
</script>
</footer:js>
