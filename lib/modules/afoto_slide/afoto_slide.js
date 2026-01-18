var afoto_slide_execute = function(params){ 
if (typeof ($.fn.fancybox3) === "function") {
    $('.photoAlbumAdapt .slide-show').each(function(){
        $(this).attr('href', $(this).find('img').attr('src').replace('_prev.', '.'))    
    });
    $('.photoAlbumAdapt .slide-show').fancybox3();
}
}
