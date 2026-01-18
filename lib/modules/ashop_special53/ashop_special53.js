var ashop_special53_execute = function(params){ 
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

var specialModifications = function(ajax_url){
$('.modifications').off('change').on('change', 'input, select', function(){
    var $this = $(this),
        value = $this.val();
        $param = $this.closest('.itemFeature');
        $group = $this.closest('.groupFeature');
        $block_param = $this.closest('.modifications');
        $overlay = $block_param.find('.overlay').show();
        id_price = $block_param.data('goods');
    $.ajax({
        url: '?ajax'+ajax_url,
        type: 'post',
        data: {'id_price':id_price,'group':$group.data('id'), 'param':$param.data('id'), 'value':value},
        dataType: 'json',
        success: function(data){
        $('.modifications[data-goods=' + id_price + ']').each(function(){
            $group = $(this).find('.groupFeature');
            var $product = $(this).closest('.blockAllItem');
            if (data.price) {
                $product.find('.newPrice').html(data.price.new);
                $product.find('.oldPrice').html(data.price.old);    
            }
            if (data.presence) {
                $product.find('.presenceValue, .hpresence .presence').html(data.presence);    
            }
            if (data.count) {
                var $button = $product.find('.addcart');
                if (data.count=='0'){
                    $button.attr('disabled', true);
                }    
                else
                    $button.attr('disabled', false).show();    
            } 
            if (data.params) {
                for (var i in data.params) {
                    var param = data.params[i];
                    if (param.values){
                        $param = $group.find('.itemFeature[data-id='+i+']');
                        if (data.type=='radio') {
                            $param.find('.itemValue').hide().find('input').removeAttr('checked');
                        }
                        else {
                            $param.find('option').hide().removeAttr('selected');    
                        }
                        var $prev = null;
                        for (var j in param.values) {
                            var value = param.values[j];
                            if (data.type=='radio') {
                                var $value = $param.find('.itemValue[data-id='+j+']'); 
                            }
                            else {
                                var $value = $param.find('option[data-id='+j+']');
                            }
                            if ($value.length)
                                $prev = $value.show();
                            else {
                                if (data.type=='radio') {
                                    var prefix = $param.data('ident');
                                    if (!$prev) $prev = $param.find('.nameFeature');
                                    $value = $('<label class="itemValue" title="'+value.value+'" data-id="'+j+'"><input type="radio" name="feature['+prefix+'_'+id_price+'_'+$group.data('id')+'_'+i+']" value="'+j+'"><span class="featureValue">'+value.value+'</span></label>').insertAfter($prev);
                                    if (param.type == 'colorlist') {
                                        if (value.image)
                                            $value.find('.featureValue').replaceWith('<img class="featureValue" src="' + value.image + '">');
                                        else
                                            $value.find('.featureValue').css('background-color', '#'+value.color).html('<span></span>');
                                    }   
                                }
                                else{
                                    $value = $('<option title="'+value.value+'" data-id="'+j+'" value="'+j+'">'+value.value+'</option>')
                                    if (!$prev){ 
                                        $value.prependTo($param.find('select'));
                                    }
                                    else {   
                                        $value.insertAfter($prev);
                                    }
                                }       
                            }
                                
                            if (j == param.selected) {
                                if (data.type=='radio') {
                                    $value.find('input').attr('checked', true);
                                }
                                else{
                                    $value.attr('selected', true);
                                }
                            }    
           
                        }
                    }
                        
                }
            }
        });
        },
        complete: function(){
            $overlay.fadeOut(200);    
        }
    });
});
}
}
