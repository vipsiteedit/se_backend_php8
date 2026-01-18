$(function(){
    $region = $('.contRegionSelect .userRegion');
    $confirm = $('.confirmRegion');
    $select = $('.selectRegion');
    region_pos = $region.find('.userRegionName').position();
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
    
    $('.suggestRegions').on('click', '.item', function(){
        $item = $(this);
        $.ajax({
            url: mregion.ajax_url,
            type: 'post',
            dataType: 'json', 
            data: {'set_region': $item.data('id')}
        })
        $('.userRegionName').text($item.find('.city').text());
        hideSelect();
    })
    
    $select.on('keyup', 'input', function(){
        query = $(this).val();
        if (query && query.length >= 2 && query != text) {
            if (ajax) 
                ajax.abort();
            text = query;
            ajax = $.ajax({
                url: mregion.ajax_url,
				type: 'post',
				dataType: 'json', 
				data: {'c': query}, 
                success: function(data){
                    if (data.regions){
                        $('.suggestRegions').show().empty();
                        for (var i = 0; i < data.regions.length; i++){
                            if (i in data.regions) {
                                var r = data.regions[i];
                                $('.suggestRegions').append('<div class="regionItem" data-id="' + r.id + '"><div class="city">' + r.city + '</div><div class="detail">' + r.country + (r.region ? (', ' + r.region) : '') + ', ' + r.city + '</div></div>');
                            }
                        }
                    }
                    else {
                        $('.suggestRegions').hide();
                    }
                            
				}
			});
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
        $confirm.on('click', '.btnConfirm, .lnkCancel, .close', function(){
            $confirm.hide();
            if ($(this).is('.btnConfirm')) {
                $.ajax({
                    url: mregion.ajax_url,
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
})
