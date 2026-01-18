var mshopmini_goods_execute = function(params){
   var $speclist = $('.shopmini .specprice .specpriceVal');
   for (var i =0; i < $speclist.length; i++){
      var price = $($speclist[i]).data('price');
      $($speclist[i]).html(getMiniPrice(price, params.p10));
   }
 
   function getMiniPrice(price, koff){
       koff = parseFloat(koff);
       if (isNaN(koff)) koff = 1.00;
       return parseFloat(price.replace(/\D+/g,"")) * koff;
   }
}
