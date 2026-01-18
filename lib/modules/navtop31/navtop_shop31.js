var navtop_shop31_execute = function(params){ 
var prcInWork = 0, prsSub = [];
 $('.headerCatalog .headerCatalogItem.horiz.catalog').hover(function(){
  prcInWork = 1;
  var id = $(this).data('id'), $this = this;
  if ($.inArray(id,prsSub) == -1) {
  $.post('?loadsubcatalog', {id: id, level: 1}, function(data){
   $('.loadering').remove();
   if (data != '') {
    $($this).find('.headerCatalogSub .catWindow').html(data);
    prsSub.push(id);
   } else {
     //  return true;
   }
  },
  'html');
   $('.headerCatalogSub').append("<div class='loadering' style='width:100%; margin-top:-20px; text-align:center; padding:20px;'><img src='"+params.moduleurl+"loading.gif'></div>");
  }
  else {
   $('.loadering').remove();
  }
  return false;
 }, function(){
  $('.loadering').remove();
 });
}
