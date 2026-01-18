var mcontacts_execute = function(params){
var myMap;
ymaps.geocode(params.city+', '+params.addr).then(function (res) {
    myMap = new ymaps.Map('YMapsID', {
        center: res.geoObjects.get(0).geometry.getCoordinates(),
        zoom : 10
    });
});
}
