var mshop_catalog_picture_menu_execute = function(params){ 
$(document).ready(function(){
var event = 'click';
if (params.action == undefined || params.action != 'click') {
    event = 'mouseenter';
	$('.shopGrouppicMenu').on('mouseleave', '.menuUnit', function(e){             
		e.preventDefault();
		var $this = $(this),
			id = $this.data('id');
		$('.shopGrouppicMenu .submenu_mu' + id).hide();
	});
}
$(".shopGrouppicMenu .sub-sub").hide();
$('.shopGrouppicMenu').on(event, '.menuUnit', function(e){
	e.preventDefault();
	e.stopPropagation()
	var $this = $(this),
		level = $this.data('level'),
		id = $this.data('id');   
	
    if ($('.shopGrouppicMenu .submenu_mu' + id).length)
		$this.data('check', 'true'); 
	
    if (!$this.data('check')) {
		$.ajax({
			type: 'POST',
			url: '?loadsub',
			async: false,
			data: {id: id, level: level},
            dataType: 'html',
			success : function(data) {
				if (data != '')
					$(data).appendTo($this).hide(); 
				else if (event == 'click')
					document.location = $this.find('a.menu').attr('href');
				$this.data('check', 'true'); 
			}
		});
	}   
	$('.shopGrouppicMenu .submenu_mu' + id).toggle();
});   
})
}
