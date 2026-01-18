var accordion_execute = function(params){ 
var selector = $("#id"+params.id+".accordion");
selector.accordion({ 
   header: ".object > .objectTitle",
   heightStyle: params.p10,
   animate: parseInt(params.p11,10)
});
if (params.p9 == 'true'){
   if (!$.browser.msie || ($.browser.msie && $.browser.version != "7.0")) {
         selector.accordion( "option", "collapsible", true );
   };
   selector.accordion( "option", "active", false );
}
}
