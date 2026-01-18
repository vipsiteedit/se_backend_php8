var mshop_special_big_execute = function(params){ 
$('.partSpecial'+params.id+'.emptyGoods').mousedown(function(){
    return false;    
});
$('.partSpecial'+params.id+':not(.emptyGoods) .'+params.p32).hover(function(event){
    $(this).addClass('hoverToDragGoods');    
}, function(){
    $(this).removeClass('hoverToDragGoods');    
});    
$('.partSpecial'+params.id+':not(.emptyGoods) .'+params.p32).draggable({
    start: function(event, ui){
        console.log(ui);
        if (window.ajax_request == true || true){
            ui.helper.removeClass('hoverToDragGoods').addClass('dragAjaxGoods');
        }
        else{
            return false;
        }    
    },
    containment:'document',
    helper:'clone',
    delay:10,
    //scroll:false,
    'zIndex':999,
    appendTo:'.bodySpecialGoods'+params.id,
    revert:'invalid'
});
}
