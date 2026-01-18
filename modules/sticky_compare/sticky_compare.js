var sticky_compare_execute = function(params){
	
function _$(el){return $(el,'.sticky_compare[data-content-id="'+params.id+'"]')};

_$('.sticky_compare-compare_items__params__fixed_titles').css('padding-top',_$('.sticky_compare-compare_items__header').height()+'px');
_$('.sticky_compare-control_block__button_show').on('click',function(event){
	event.stopPropagation();
	_$('.sticky_compare-control_block__area').toggleClass('__visible')}
);

$('body').on('click',function(){_$('.sticky_compare-control_block__area').removeClass('__visible')});
_$('.sticky_compare-control_block__area').on('click',function(event){event.stopPropagation()});

function prefix(prop){
	var text=prop;
	upcase_prop=prop[0].toUpperCase()+prop.substring(1);
	if('Moz'+upcase_prop in document.body.style){text='-moz-'+prop};
	if('webkit'+upcase_prop in document.body.style){text='-webkit-'+prop};
	if('ms'+upcase_prop in document.body.style){text='-ms-'+prop};
	if(prop in document.body.style){text=prop};
	return text
};

var prefix_transform=prefix('transform');

var items_scroll=new IScroll('.sticky_compare[data-content-id="'+params.id+'"] .sticky_compare-compare_items_area__wrapper',{
	eventPassthrough:!0,
	scrollbars:!0,
	scrollX:!0,
	scrollY:!1,
	interactiveScrollbars:!0,
	probeType:3
});

items_scroll.on('scroll',function(){
	var left=-this.x;
	if(left<0)left=0;
	_$('.__fixed_element').css(prefix_transform,'translate3d('+left+'px,0,0)')
});

$(window).load(function(){items_scroll.refresh()});

$(window).on('scroll load',function(event){
	var max_top=_$('.sticky_compare-compare_items_area').height()-(_$('.sticky_compare-compare_items__header__wrapper').height()+params.min_visible_area);
	var top=_$('.sticky_compare-compare_items__header__wrapper').get(0).getBoundingClientRect().top;
	top=Math.round(-top);
	if(top>max_top){top=max_top};
	if(top>=0){
        htop = $('html').find('.section-header-fixed').height();
        if(htop>0){
            top = top + htop;
        }
		_$('.sticky_compare-compare_items__header').css(prefix_transform,'translate3d(0,'+top+'px,0)')
	}else{
		_$('.sticky_compare-compare_items__header').css(prefix_transform,'translate3d(0,0,0)')}
	}
);

$(window).on('resize load',function(event){
	_$('.sticky_compare-compare_items__params__fixed_titles .sticky_compare-group_param').each(function(i){
		var max_height=0;
		var f_height=this.getBoundingClientRect().height;
		if(f_height!=0){var ct_height=_$('.sticky_compare-compare_items_area .sticky_compare-group_param').eq(i).get(0).getBoundingClientRect().height;
		if(f_height>=ct_height){max_height=f_height}else{max_height=ct_height};
		$(this).height(max_height);
		_$('.sticky_compare-compare_items_area .sticky_compare-group_param').eq(i).height(max_height)}else{$(this).height('auto');
		_$('.sticky_compare-compare_items_area .sticky_compare-group_param').eq(i).height('auto')}})}
);

function check_param(){
	_$('.sticky_compare-compare_items_area .sticky_compare-group_param:not([data-id^="g_"])').each(function(i){var find_difference=!1;
	var prev_value=null;
	$(this).find('.sticky_compare-item_param__value').each(function(){if(prev_value==null){prev_value=$(this).text()}else{if(prev_value!=$(this).text()){find_difference=!0}}});
	if(find_difference==!0){$(this).attr({'data-diff':'f-diff'});
	_$('.sticky_compare-compare_items__params__fixed_titles .sticky_compare-group_param:not([data-id^="g_"])').eq(i).attr({'data-diff':'f-diff'})}else{$(this).attr({'data-diff':'f-same'});
	_$('.sticky_compare-compare_items__params__fixed_titles .sticky_compare-group_param:not([data-id^="g_"])').eq(i).attr({'data-diff':'f-same'})}});
	_$('.sticky_compare-compare_items_area .sticky_compare-group_param[data-id^="g_"]').each(function(i){var group=$(this);
	group.attr({'data-diff':'f-same'});
	$(this).nextUntil('[data-id^="g_"]').each(function(){if($(this).attr('data-diff')=='f-diff'){group.attr({'data-diff':'f-diff'})}});
	_$('.sticky_compare-compare_items__params__fixed_titles .sticky_compare-group_param[data-id^="g_"]').eq(i).attr({'data-diff':group.attr('data-diff')})}
)};

_$('.sticky_compare-control_item__difference__checkbox').on('change',function(){
	if(this.checked){check_param();
	_$('[data-diff="f-same"]').hide()}else{_$('[data-diff="f-same"]').show()}
});

_$('.sticky_compare-item__button_remove').on('click',function(event){event.preventDefault();

var el=this;

$.ajax({url:el.href,success:function(data){
	var id=el.getAttribute('data-remove-id');
	var elems=_$('[data-item-id="'+id+'"]');
	elems.fadeOut(params.hide_time,function(){elems.remove();
	items_scroll.refresh()})
}})
})
}
