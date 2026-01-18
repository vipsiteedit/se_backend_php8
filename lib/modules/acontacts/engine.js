var myMap;
ymaps.geocode('Москва').then(function (res) {
    myMap = new ymaps.Map('YMapsID', {
        center: res.geoObjects.get(0).geometry.getCoordinates(),
        zoom : 10
    });
});
