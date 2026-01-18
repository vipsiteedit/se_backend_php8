var header_shop30_execute = function(params){ 
var minus = 0;

var timer=null, ajax=null;


$(document).ready(function(){
             minus = 30;
             var widthsearch = parseInt($('#top_b_header .row').css('width')) - minus - parseInt($('.searchbut').css('width')) + 'px';
             $('#section-header .livesearch').css('width',widthsearch);
             var heightsearch = parseInt($('#top_b_header .row').css('height'));
             $('#section-header .livesearch').css('height',heightsearch);
             var widthsearch = parseInt($('#top_b_header .row').css('width')) - minus - parseInt($('.searchbut').css('width')) + 'px';
             $('#section-header .search-head').css('width',widthsearch);
             var heightsearch = parseInt($('#top_b_header .row').css('height'));
             $('#section-header .search-head').css('min-height',heightsearch);
             $('#section-header .suggestions').css('margin-top',parseInt(heightsearch) + 'px');
   });
        
   $(window).resize(function(){
             if($(window).width() > 767) {
                 minus = 30;
             }
             else {
                 minus = 30;
             }
             var widthsearch = parseInt($('#top_b_header .row').css('width')) - minus - parseInt($('.searchbut').css('width')) + 'px';
             $('#section-header .livesearch').css('width',widthsearch);
             var heightsearch = parseInt($('#top_b_header .row').css('height'));
             $('#section-header .livesearch').css('height',heightsearch);
             var widthsearch = parseInt($('#top_b_header .row').css('width')) - minus - parseInt($('.searchbut').css('width')) + 'px';
             $('#section-header .search-head').css('width',widthsearch);
             var heightsearch = parseInt($('#top_b_header .row').css('height'));
             $('#section-header .search-head').css('min-height',heightsearch);
             $('#section-header .suggestions').css('margin-top',parseInt(heightsearch)  + 'px');
   });

   
   function show_search() { 
      $('#section-header .searchbut').addClass('trigger-close');
      $('#section-header .search-head').css('display','block');
      $('#section-header .livesearch').animate({right: "0px"}, 300, function(){
          $('.suggestions, .search-head .buttonSend').css('display','block');
      });       
   }
   
   function hide_search() { 
      $('#section-header .searchbut').removeClass('trigger-close');
      $('#section-header .suggestions').css('display','none');
      $('#section-header .livesearch').animate({right: "-1281px"}, 300, function(){
          $('#section-header .search-head').css('display', 'none');          
      });
      $('#section-header .suggestions, #section-header .search-head .buttonSend').css('display','none');                     
      
   }    
    
   $('.searchbut').click(function(){
        if(!$(this).hasClass('trigger-close')) {
            clearTimeout($.data(this, 'slideTimer'));
            $.data(this, 'slideTimer', setTimeout(function() {
                show_search();
            }, 250));
        } else {
            clearTimeout($.data(this, 'slideTimer'));
            $.data(this, 'slideTimer', setTimeout(function() {
                hide_search();
            }, 250));
        }           
   });
   
   
var closeSuggest = function(suggest){
    
    setTimeout(function(){
		suggest.hide();
	}, 100);
    
}

var input = $("#headersearch"),
	suggest = $('<div class="suggestions"></div>').insertAfter(input).hide(),
    preloader = $('<span class="preloader"></span>').insertAfter(suggest).hide(),
    query = '';



suggest.on('click', '.suggestItem', function(){
    input.val($(this).data('suggest')).focus(); 
    window.location.href = $(this).find('a').attr('href');
    //console.log('hi');
})

$(document).click(function(){closeSuggest(suggest)});  

input.keydown(function(e) {
    if (suggest.is(':visible') && (e.keyCode == 38 || e.keyCode == 40)) {
		var selected = suggest.children('.suggestItem.selected'),
			next = null,
            value = query;
		if (selected.length) {
			if (e.keyCode == 38) {
				if (!suggest.children('.suggestItem:first.selected').length) {
					next = selected.prev('.suggestItem');
				}
			} 
			else {
				if (!suggest.children('.suggestItem:last.selected').length) 
                    next = selected.next('.suggestItem');
			}
			selected.removeClass('selected');
		}
        else {
            if (e.keyCode == 38) {
                next = suggest.children('.suggestItem:last');
            }
            else
                next = suggest.children('.suggestItem:first');
        }
        if (next){
            value = next.addClass('selected').data('suggest');
        }
		input.val(value);
	}  
	if (e.keyCode == 13 || e.keyCode == 27) {
		closeSuggest(suggest);
        if (e.keyCode == 13) {
            var selected = suggest.children('.suggestItem.selected');
            if (selected.length) {
                window.location.href = selected.find('a').attr('href')
                return false;
            }
        }
	} 
}).keyup(function(e) {
    if (e.keyCode == 37 || e.keyCode == 38 || e.keyCode == 39 || e.keyCode == 40 || e.keyCode == 13 || e.keyCode == 27) {
        return false;
    }
    query = input.val();
    //console.log(params.ajax_url);
	if (query && query.length >= params.min_length) {
		clearTimeout(timer);
		timer = setTimeout(function(){
			if (ajax) 
                ajax.abort();
            ajax = $.ajax({
				url: params.ajax_url,
				type: 'get',
				dataType: 'json', 
				data: {'q': query}, 
				beforeSend: function(){
                    preloader.show();
                },
                success: function(data){
					if (data.goods) {
                        suggest.empty();
                        for (var i = 0; i < data.goods.length; i++){
                            if (i in data.goods) {
                                var g = data.goods[i];
                                suggest.append('<div class="suggestItem" data-suggest="' + g.suggest + '"> <span class="goodsImage">' + g.image + '</span><div class="goodsName"><a title="' + g.suggest + '" href="' + g.url + '">' + g.name + '</a></div><div class="goodsArticle">' + g.article + '</div><div class="goodsPrice">' + g.price + '</div></div>');
                            }
                        }
                        suggest.show();    
                    }   
					else {
						closeSuggest(suggest);
					}
				},
                complete: function(){
                    preloader.hide();
                }
			});
		}, 300);
	}
	else {
		closeSuggest(suggest);
	}
});

        
/*********************/

var cart = {},
    send_ajax = false,
    ajax_request = true,
    cart_animate = false,
    flag_hover = false,
	last = '';

  function ga_setCurr(curr) {
    if (typeof(ga) === 'function') {
        ga('set', '&cu', curr);
    }   
  }

  function ga_sendEvents(events) {
    if (events.length > 0 && typeof(ga) === 'function'){
        for (var i = 0; i < events.length; i++){
            if (i in events) {
                ga('ec:addProduct', {
                    'id': events[i].id,
                    'name': events[i].name,
                    'category': events[i].group,
                    'brand': events[i].brand,
                    'variant': events[i].variant,
                    'price': events[i].price,
                    'quantity': events[i].count
                });
                ga('ec:setAction', events[i].event);
                if (events[i].event == 'add'){
                    ga('send', 'event', 'UX', 'click', 'add to cart');
                }
                else if (events[i].event == 'remove') {
                    ga('send', 'event', 'UX', 'click', 'remove from cart');
                } 
            }
        }  
    } 
  }

  if (params.curr)
    ga_setCurr(params.curr); 
    
  function sendAjax(data){
    if (params.is_local == 1) return;
    send_ajax = true;
    if (!data)
        data = 'getcart=1';
    $.ajax({
        url: '?ajax' + params.id,
        type: 'post',
        dataType: 'json',
        data: data,
        beforeSend: function(){
            toggleLoader(false);        
        }, 
        success: function(result){      
            if (result.total){
                updSumCart(result.total);
                if (window.updateCartTotal)
                    updateCartTotal(result.total);
            } 
            if (result.events) {
                ga_sendEvents(result.events);
            }
            toggleLoader(true);
        },
        error:function(){
            toggleLoader(true);
        }
    });
  }
           
  function updSumCart(sum){
    
	var cart_count = cart.find('#informer_shop_cart-count_goods');
    if (sum.count/* && $(cart_count).html() != sum.count*/){    
		$('#section-header .basketbut').addClass('act');
        cart_count.fadeOut(400, function(){
            $(this).html(sum.count).fadeIn(400);
        });
    } 
    toggleLoader(true);           
  }


  function toggleLoader(stop_loader){
    var load_block = cart.find('.loaderAjax');
    ajax_request = stop_loader;
    if (stop_loader == false){
        load_block.show();
        $('.fixedCart, .blockGoodsInfo, .blockGoodsInfo *, .butAddCart').css('cursor','wait');
    }
    else{
        $('.fixedCart, .blockGoodsInfo, .blockGoodsInfo *, .butAddCart').css('cursor','auto');
        load_block.hide();
    }     
  } 

var cart = $('.basketbut');

if (params.param7 == 'Y'){
    $('#fixedCart').droppable({
        activeClass : 'activeCart',
        hoverClass : 'hoverCart',
        accept : '.blockGoodsInfo, .blockGoodsInfo *',
        tolerance: 'touch',
        drop : function(event, ui){
            if (ajax_request == true){
                $(ui.draggable).draggable({revert: 'invalid' });
                var id_good = ui.draggable.find('[name="addcart"]').val();
                sendAjax({'addcart':id_good, 'addcartparam':''});
            }
            else {
                $(ui.draggable).draggable({revert: true});
            }
        }  
    });
}

if (!send_ajax)
    sendAjax();    

cart.hover(function(){
    if (($(this).hasClass('flyCart')) && !($(this).hasClass('activeCart'))){
        $(this).removeClass('flyCart');
        flag_hover = true;
    }
},function(){
    if (flag_hover){
        $(this).addClass('flyCart');
        flag_hover = false;
    }
});

$('#fixedCart #clearCartYes').click(function(){
    $('#fixedCart #blockClearCart').slideUp(400);
});

$('body').on('submit', '.form_addCart', function(e){
    e.preventDefault();
    if (ajax_request == true){
        var form = $(this),
            product = form.closest('.blockGoods, .productItem, .goodsDetail, .theGoodContent, .blockAllItem:not(.emptyGoods), .specialItem, .goodsContent').first(),
            object = product.find('.blockImage, .goodsLinkPhoto, .specialImage').last();
        if (!object.length > 0) {
            object = product;
        }
        toggleLoader(false);
        if (params.is_local == 1) return;

        $.ajax({
            url: '?ajax' + params.id,
            data: form.serialize(),
            type: 'post',
            dataType: 'json',
            success:function(result){
                //ajax_request = true;
                if (!result) return;
                if (object.length > 0) {
                var clone = object.clone(),
                    x = object.offset(), 
                    x2 = cart.last().offset();
                if (result.incart){
                    cart.addClass('hoverCart');
                    $(clone).prependTo('body')
                        .css({'position' : 'absolute', 'z-index' : '9999', 'left' : x.left, 'top': x.top})
                        .stop()
                        .animate({
                            opacity: 0.2, 
                            top: x2.top,
                            left: x2.left
                        }, 400, function(){
                            $(this).remove();
                            cart.removeClass('hoverCart');
                                              
                        });
                }
                }
                product.find('button.addcart').addClass('inCartActive');
                if (result.total){
                    updSumCart(result.total);
                }
                if (result.events) {
                    ga_sendEvents(result.events);
                }
                if (product.closest('.notAjaxCart').length)
                    document.location.href='/'+params.shopcart+'/';
                if (result.product){
                    var $content = $(result.product),
                        $input = $content.find('.count input');
                    $input.parent().on('click', 'button', function(){
                        var count = parseFloat($input.val()),
                            step =  parseFloat($input.data('step'));  
                        if (isNaN(step)) step = 1;
                        if (isNaN(count)) count = step;
                        count += $(this).data('action') == 'inc' ? step : (count > step ? -step : 0);
        
                        $input.val(Math.round(count*1000)/1000).change();
                        
                    }); 
                    $input.change(function(){
                        last = $(this).attr('name').match(/\[(.*)]/)[1];
                        sendAjax($input.serialize());
                    });
                    $content.on('click', '.continueShop', function(){
                        $.fancybox.close();
                    });
                    $.fancybox({content:$content, width:700, autoSize: false});
                }
            },
            error:function(){
                toggleLoader(true);
            }
        });
    } 
});

}
