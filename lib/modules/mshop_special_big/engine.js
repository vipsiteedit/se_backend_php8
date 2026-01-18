var draggableSpecial = function(part_id, obj){
$('.partSpecial'+part_id+'.emptyGoods').mousedown(function(){
    return false;    
});
$('.partSpecial'+part_id+':not(.emptyGoods) .'+obj).hover(function(event){
    $(this).addClass('hoverToDragGoods');    
}, function(){
    $(this).removeClass('hoverToDragGoods');    
});    
$('.partSpecial'+part_id+':not(.emptyGoods) .'+obj).draggable({
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
    appendTo:'.bodySpecialGoods'+part_id,
    revert:'invalid'
});
}
