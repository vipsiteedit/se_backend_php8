var shopfilter_ajax_execute = function(params){ 
   function filterLoad() {
      $.ajax({
            url: params.ajax_url+'&main',
            type: 'get',
            success: function(data) {
                if (data!='') {
                   $("[data-id="+params.partNum+"]").html(data);
                   $("[data-id="+params.partNum+"]").css('display', 'block');
                   filterInit();
                }
            }
      });
   }  
   filterLoad();
   
function filterInit() {
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
            force_edges: true
        })
    })
} 
$('.shopFilter .filterSlider').each(function(){
	if (!$(this).closest('.filterValueList').is(':visible')) {
		$(this).find('.from, .to').attr('disabled', true);
	}
})	
$('.filterTitle').off().on('click', function(){
    var $this = $(this),
        $item = $this.closest('.filterItem');
    $this.toggleClass('closed');
    $item.find('.filterValueList').stop().slideToggle(250);
    if ($item.is('[data-type="range"]')) {
        if (params.param1 == 'Y')
            $item.find('.inputRange').data("ionRangeSlider").update();
		if ($this.is('.closed'))
			$item.find('.filterValueList').find('.from, .to').attr('disabled', true);
		else
			$item.find('.filterValueList').find('.from, .to').removeAttr('disabled');
	}
})
var time = null,
    query = null;
$('.filterItem').off().on('change', 'input', function() {
    var $this = $(this).closest('.filterValueItem'),
        $block_filter = $this.closest('.shopFilter').css('position', 'relative'),
        $filter_item = $this.closest('.filterItem');     
    clearTimeout(time); 
    time = setTimeout(function(){
        var $form = $this.closest('form'),
            form_data = $form.serialize();
        if (query) 
            query.abort();  
        query = $.ajax({
            url: window.location.pathname,
            type: 'get',
            data: form_data,
            beforeSend: function(){
                $('.goodsContent').addClass('loading')
                $block_filter.addClass('loading');
                window.history.replaceState(null, null, $form.attr('action') + '?' + form_data);
            },
            success: function(data) {
                $block_filter.removeClass('loading'); 
                $('.goodsContent').removeClass('loading');
                if (data) {
                   $('#filterList'+params.partNum).replaceWith($(data).find('#filterList' + params.partNum));
                   $('.goodsContent').replaceWith($(data).find('.goodsContent'));
                   filterInit();
                }
            }  
        })        
    }, 100);
}); 
$('.shopFilter .btnClear').off().on('click', function(){
    $(this).closest('form').find(':checkbox, :radio').removeAttr('checked').first().change();
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
}
