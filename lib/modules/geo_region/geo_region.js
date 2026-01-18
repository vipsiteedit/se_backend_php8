var geo_region_execute = function(params){ 
var $region = $('.contRegionSelect .userRegion'),
    $confirm = $('.confirmRegion'),
    $select = $('.selectRegion'),
    region_pos = $region.find('.userRegionName').position(),
    timer = null;
function showSelect(){
    $confirm.hide();
    $select.css({
        'position': 'absolute',
        'top': region_pos.top + $region.find('.userRegionName').outerHeight(true) + 10 + 'px',
        'left': region_pos.left + $region.find('.userRegionName').outerWidth(true)/2 - $select.outerWidth(true)/2
    }).show(); 
    $select.find('input').focus();
    return false;   
}
    
function hideSelect(){
    $select.hide().find('input').val('');
    $select.find('.suggestRegions').empty();
}
    
$region.on('click', 'a', showSelect);
    
$select.on('click', function(){
    return false;
})
$('body').click(hideSelect);  
    
var ajax = null, text = null;
    
$('.suggestRegions').on('click', '.regionItem', function(){
    $item = $(this);
    $.ajax({
        url: params.ajax_url,
        type: 'post',
        dataType: 'json', 
        data: {'set_region': $item.data('id')},
        success: function(data){
            if (data.reload)
                document.location.reload();
            if (data.redirect)
                document.location.href = data.redirect;
        }
    })
    $('.userRegionName').text($item.find('.city').text()).attr('title', $item.find('.detail').text().replace(/\s+/g,' '));
    hideSelect();
})
    
$select.on('keyup', 'input', function(){
    var query = $(this).val();
    if (query && query.length >= 2 && query != text) {
        clearTimeout(timer);
		timer = setTimeout(function(){
        if (ajax) 
            ajax.abort();
        text = query;
        ajax = $.ajax({
            url: params.ajax_url,
            type: 'post',
            dataType: 'json', 
            data: {'c': query}, 
            success: function(data){
                if (data.html){
                    $('.suggestRegions').html(data.html).show();
                }
                else {
                    $('.suggestRegions').hide();
                }
                            
				}
        });
        }, 150);
   }
   else {
    $('.suggestRegions').hide();
   }
})
    
if ($confirm.length) {
    $(window).load(function(){
        $confirm.css({
            'top': region_pos.top + $region.find('.userRegionName').outerHeight(true) + 10 + 'px',
            'left': region_pos.left + $region.find('.userRegionName').outerWidth(true)/2 - $confirm.outerWidth(true)/2
        }).fadeIn(500);
    });  
    $confirm.on('click', '.btnConfirm, .lnkCancel, .close', function(e){
        e.preventDefault();
        $confirm.hide();
        if ($(this).is('.btnConfirm')) {
            $.ajax({
                url: params.ajax_url,
                data: 'confirm=1',
                type: 'post',
                dataType: 'json'
            })
        }
        else if ($(this).is('.lnkCancel')) {
            $confirm.hide(0,function(){
                showSelect();    
            });
        }
        return false;
    })    
}
}
