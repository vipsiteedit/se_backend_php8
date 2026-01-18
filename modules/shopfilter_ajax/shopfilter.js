var shopfilter_execute = function(params){ 
if (params.param1 == 'Y') {
    $('.shopFilter .filterSlider').each(function(){
        var $this = $(this),
            $min = $this.find('.min'),
            $max = $this.find('.max'),
            $from = $this.find('.from').hide(),
            $to = $this.find('.to').hide(),
            measure = $this.closest('.filterItem').find('.measure').html() ;
        $this.find('.inputRange').ionRangeSlider({
            min: parseFloat($min.val()), 
            max: parseFloat($max.val()),
            from: parseFloat($from.val()), 
            to: parseFloat($to.val()), 
            type: 'double',
            step: 0.1,
            hide_min_max: (params.param3=='N'),
            grid: (params.param2=='Y'),
            postfix: (measure != undefined) ? ' ' + measure : '',
            force_edges: true,
            onFinish: function(data){
                $from.val(data.from).change();
                $to.val(data.to);     
            }
        })
    })
}
filterInit();
filterLoad();
function filterInit() {
$('.filterTitle.closed').closest('.filterItem').find('input.from, input.to').attr('disabled', true);
    
$('.filterTitle').click(function(){
    var $this = $(this),
        $item = $this.closest('.filterItem');
    $this.toggleClass('closed');
    $item.find('.filterValueList').stop().slideToggle(250);
    if ($item.is('[data-type="range"]')) {
        $item.find('.inputRange').data("ionRangeSlider").update();  
        if ($this.is('.closed'))
			$item.find('.filterValueList').find('.from, .to').attr('disabled', true);
		else
			$item.find('.filterValueList').find('.from, .to').removeAttr('disabled');
    }        
})
var time = null,
    time2 = null,
    query = null;
$('.filterItem').on('change', 'input', function() {
    var $this = $(this).closest('.filterValueItem'),
        $block_filter = $this.closest('.shopFilter').css('position', 'relative'),
        $filter_item = $this.closest('.filterItem'),
        $block_ajax = $block_filter.find('.filterNotify').css({'position':'absolute'})  ;     
    var pos_y = $this.position().top + $this.outerHeight(true)/2 - $block_ajax.outerHeight(true)/2,
        pos_x = $this.position().left + ((params.param4=='left') ? (0 - ($block_ajax.outerWidth(true) + 10)) : ($filter_item.outerWidth(true) + 10)); 
    if ($block_ajax.is(':visible')) {
        $block_ajax.show(1).animate({'top':pos_y+'px','left':pos_x+'px'}, 300);
    }
    else {                
        $block_ajax.css({'top':pos_y+'px','left':pos_x+'px'}).fadeIn(300);    
    }
    clearTimeout(time2);
    clearTimeout(time); 
    time = setTimeout(function(){
        time2 = setTimeout(function(){
            $block_ajax.stop().fadeOut(300);
        }, 4000);
        var $form = $this.closest('form'),
            form_data = $form.serialize();
        if (query) 
            query.abort();
            
        query = $.ajax({
            url: params.ajax_url,
            type: 'get',
            beforeSend: function(){
                $block_ajax.find('.notifyOverlay').stop().fadeIn(100);    
            },
            dataType: 'json',
            data: form_data,
            success: function(data) {
                $block_ajax.find('.notifyOverlay').stop().fadeOut(100);
                $block_ajax.find('a').attr('href', $form.attr('action') + '?' + form_data);
                if (data.count != undefined) {
                    $block_ajax.find('.countFound').html(data.count);
                }           
                if (data.data != undefined) {
                   $('#filterList'+params.partNum).html(data.data);
                   filterInit();
                }
            },
            complete: function(jqXHR, textStatus){
            }  
        })
        
            
    }, 250);
}); 
$('.filterNotify').on({
    'mouseenter': function(){
        clearTimeout(time2);   
    },
    'mouseleave': function(){
        var $this = $(this);
        time2 = setTimeout(function(){
            $this.stop().fadeOut(300);
        }, 1000);
    }
});
$('.clearFilter').click(function(){
    $(this).closest('.filterItem').find('input').removeAttr('checked');
    return false;
})
$('.shopFilter .btnClear').click(function(){
    $(this).closest('form').find(':checkbox, :radio').removeAttr('checked');
    document.location.href = $(this).closest('form').attr('action');
    $('.shopFilter .filterSlider').each(function(){
        var $this = $(this),
            min = parseFloat($this.find('.min').val()),
            max = parseFloat($this.find('.max').val());
        $this.find('input.from').attr('value', min);
        $this.find('input.to').attr('value', max);
        if ($this.find('.irs').length) {
            $this.find('.inputRange').data("ionRangeSlider").update({
                from: min,
                to: max 
            });
        }            
    });
    return false;
});
}
   function filterLoad() {
       $('[data-id="'+params.partNum+'"]').load(params.ajax_url+'&main'); 
   }
}
