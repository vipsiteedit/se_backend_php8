var mshop_compare_execute = function(params){ 
$('.showCompare').on('click', 'span', function(){
    if (!$(this).is('.selected')){
        $(this).addClass('selected').siblings().removeClass('selected');
        if ($(this).is('.showAll'))
            $('.f-same').show();
        else
            $('.f-same').hide();    
    }
    return false;   
})
if (typeof ($.fn.stickyTableHeaders) === "function") {
    $('.tableCompare').stickyTableHeaders();
}
}
