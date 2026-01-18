var mshop_catalog_picture_execute = function(params){ 
    $('body').click(function(){
		$('.shopGrouppic .submenu').hide();
	})
	$('.shopGrouppic .menu-action').click(function(){
		var $link = $(this),
			id = $link .data('id'), 
			code = $link .data('code'), 
			level = $link .data('level'),
			goToGoods = 1;
		$('.shopGrouppic .submenu').not('.submenu_mu' + id).hide();
		if (params.param4 == -1) {
			goToGoods = 0;
            if ($('.submenu_mu' + id).length == 0) {
			   if (params.param13 == 'Y') {
                    $.post('?ajax'+params.part_id+'&loadsub', {id: id, level: level}, function(data){
                        if (data != '') {
                            $(data).appendTo('.groupList .menuUnit' + id).show();
                        } else {
                            document.location = $link.attr('href');
                        }
                    }, 'html');
                } else { 
                    goToGoods = 1;
                }
            } 
			else {
				if ($('.submenu_mu' + id).is(':visible')) {
					$('.submenu_mu' + id).hide();
					goToGoods = 1;
                }
				else {
					$('.submenu_mu' + id).show();
				}
            }
        }
        if (goToGoods) {
			document.location = $link.attr('href');
        } 
		
        return false;
    });
}
