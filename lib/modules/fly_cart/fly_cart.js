var fly_cart_execute = function(params){ 
var cart = {},
    send_ajax = false,
    ajax_request = true,
    cart_animate = false,
    flag_hover = false;
function updListCart(list){
    if (list.length > 0){
        cart.find('.goodInfo').removeClass('updAjax');
        for (var i = 0; i < list.length; i++){
            if (i in list) {
                if (cart.find('.goodInfo[data-id=' + list[i].id + ']').length > 0){
                    updAjaxCart(list[i]);
                }
                else{
                    addAjaxCart(list[i]); 
                }
            }   
        }
        cart.last().find('.goodInfo:not(.updAjax)').each(function(){
            delAjaxCart('delete', $(this).data('id'));
        });
    }
    else{
        delAjaxCart('clear', false);
    }
}
function sendAjax(data){
    send_ajax = true;
    if (!data)
        data = 'getcart=1';
    $.ajax({
        url: '?ajax'+params.id,
        type: 'post',
        dataType: 'json',
        data: data,
        beforeSend: function(){
            toggleLoader(false);        
        }, 
        success: function(result){
            if (result.incart){
                updListCart(result.incart);
                if (window.updateCartList)
                    updateCartList(result.incart);
            }        
            if (result.total){
                updSumCart(result.total);
                if (window.updateCartTotal)
                    updateCartTotal(result.total);
            } 
        },
        error:function(){
            toggleLoader(true);
        }
    });
}           
function updSumCart(sum){
    var cart_sum = cart.find('#summOrder, #summGoods'),
        cart_disc = cart.find('#summDiscount'),
        cart_count = cart.find('#countGoods');
    if (sum.amount && $(cart_sum).html() != sum.amount){     
        cart_sum.fadeOut(400, function(){
            $(this).html(sum.amount).fadeIn(400);
        });
    }
    if (sum.discount && $(cart_disc).html() != sum.discount){
        cart_disc.fadeOut(400, function(){
            $(this).html(sum.discount).fadeIn(400);
        });
    }
    if (sum.count && $(cart_count).html() != sum.count){    
        cart_count.fadeOut(400, function(){
            $(this).html(sum.count).fadeIn(400);
        });
    }            
}
function addAjaxCart(data){
    var new_add = '<div class="goodInfo updAjax" data-id="' + data.id + '"> ' +
        ' <a href="javascript:void(0)" class="goodCount">' + data.count + '</a> ' +
        ' <a class="linkShowGood" href="' + data.link + '" title="' + data.name + '">' + data.name + '</a> ' +
        ' <span class="goodPrice">' + data.sum + '</span> ' +
        ' <a class="linkDelGood" href="javascript:void(0);" title="' + params.param11 + '">&nbsp;</a> ' +
        ' </div>';
    $(new_add).insertBefore('.fixedCart .extendInfoCart .noGoods').hide().slideDown(400,function(){
        toggleLoader(true);
    });
    cart.find('.noGoods').hide(400);
    cart.find('.issetGoods').slideDown(400);        
}
function updAjaxCart(data){
    var upd_goods = cart.find('.goodInfo[data-id=' + data.id + ']');
    upd_goods.addClass('updAjax');
    if (upd_goods.find('.goodCount').html() != data.count){
    upd_goods.find('.goodCount')
        .fadeOut(400, function(){
            toggleLoader(true);
            $(this).html(data.count).fadeIn(400);
        });
    }
    else
        toggleLoader(true);
    if (upd_goods.find('.goodPrice').html() != data.sum){
    upd_goods.find('.goodPrice')
        .fadeOut(400, function(){
            $(this).html(data.sum).fadeIn(400);
        });
    }   
}
function delAjaxCart(action, id){
    if (action == 'delete' && id){
        var del_goods = cart.find('.goodInfo[data-id=' + id + ']'),
            del_animate = $('#fixedCart .goodInfo[data-id=' + id + ']');
    }
    else if (action == 'clear'){
        var del_goods = cart.find('.goodInfo'),
            del_animate = $('#fixedCart .goodInfo');
        cart.find('.shortInfoCart .noGoods').show(400);
        cart.find('.shortInfoCart .issetGoods').hide(400); 
    }
    if (del_goods.length == 0){
        toggleLoader(true);
    }
    del_animate.css({'position':'relative', 'z-index':'1000'})
        .effect('explode', 400, function(){
            toggleLoader(true);
            del_goods.css({'visibility':'hidden'})
                .show()
                .hide(400, function(){
                    del_goods.remove()
                    if ($('#fixedCart .goodInfo').length == 0){
                        cart.find('.noGoods').show(400);
                        cart.find('.issetGoods').hide(400);
                    }
                });
        });
    $('.ui-effects-explode').css({'z-index':'999'});    
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

$(document).ready(function(){
if (params.param16 == 'Y'){
    var fixed_cart = $('#fixedCart'),
        offset = fixed_cart.offset(),
        clone = fixed_cart.clone(),
        topPadding = 20;
    clone.attr('id','clone')
        .css({
            //'left':offset.left, 
            //'top':offset.top,
            'position' : '',
            'opacity':0.0,
            'visibility':'hidden'
        })
        .insertBefore(fixed_cart);  
    fixed_cart.css({'left':offset.left, 
        'top':offset.top,
        'marginTop': 0,
        'marginLeft': 0,
        'z-index': 998,
        'position' : 'absolute'
    });
    function updatePosition(){
		offset = clone.offset();
		fixed_cart.css({top:offset.top, left:offset.left});
		$(window).scroll();
	}
	
	$('img').load(updatePosition);
	$(window).load(updatePosition);
    $(window).resize(updatePosition);
    $(window).scroll(function() {    
        if (($(window).scrollTop() + topPadding > offset.top) || ($(window).scrollLeft() + topPadding > offset.left)) {
            if ($(window).scrollTop() + topPadding > offset.top){
                move_top = $(window).scrollTop() - offset.top + topPadding;
            }
            else{
                move_top = 0;
            }
    
            if ($(window).scrollLeft() + topPadding > offset.left){
                move_left = $(window).scrollLeft() - offset.left + topPadding;
            }
            else{
                move_left = 0;
            }
    
            fixed_cart.addClass('flyCart')
                .stop()
                .animate({marginTop: move_top, marginLeft: move_left}, 500);
        }
        else{
            fixed_cart.stop()
                .animate({marginTop: 0, marginLeft: 0}, 
                300, function(){
                    fixed_cart.removeClass('flyCart')
                });
        }  
    });
}

cart = $('.fixedCart');

if (params.param20 == 'Y'){
    cart.find('.butShowHide').click(function(){
        if (cart_animate == false && ajax_request == true){
            var ext_cart = cart.find('.extendInfoCart'),
                short_cart = cart.find('.shortInfoCart');
            cart_animate = true;
            if (short_cart.is(':hidden')){
                ext_cart.slideUp(500, function(){
                    short_cart.slideDown(500, function(){
                        cart_animate = false;     
                    });    
                });    
            }
            else{
                short_cart.slideUp(500, function(){
                    ext_cart.slideDown(500, function(){
                        cart_animate = false;     
                    });    
                });
            }
            cart.find('.butShowHide').fadeOut(500, function(){
                $(this).toggleClass('showExtCart').fadeIn(500);
            });
        }    
    });
}

if (params.param17 == 'Y'){
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

if (params.param19 == 'short'){
    cart.find('.extendInfoCart').hide();
    cart.find('.shortInfoCart').show();
    cart.find('.butShowHide').addClass('showExtCart');    
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
cart.find('.extendInfoCart').on('click', '.linkDelGood', function(event){
    if (ajax_request == true){
        var del_id = $(this).closest('.goodInfo').data('id');
        sendAjax({'delcartname': del_id});
         
    }
    return false;
});
cart.find('.clearCartLink').click(function(){
    if (ajax_request == true){
        sendAjax('cart_clear=1');
    }
    //$('#clearCartYes').click(function(){
    //    $('#fixedCart #blockClearCart').slideDown(400);
    //});
    return false;
});

$('#fixedCart #clearCartYes').click(function(){
    $('#fixedCart #blockClearCart').slideUp(400);
});

$('.blockGoods, .productItem, .goodsDetail, .theGoodContent, .blockAllItem:not(.emptyGoods), .specialItem, .goodsContent').on('submit', '.form_addCart', function(e){  
    if (ajax_request == true){
        var product = $(e.delegateTarget),
            object = product.find('.blockImage, .goodsLinkPhoto, .specialImage').last();
        if (!object.length > 0) {
            object = product;
        }
        toggleLoader(false);
        $.ajax({
            url: '?ajax'+params.id,
            data: $(this).closest('.form_addCart').serialize(),
            type: 'post',
            dataType: 'json',
            success:function(result){
                var clone = object.clone(),
                    x = object.offset(), 
                    x2 = cart.last().offset();
                if (result.incart){
                    cart.addClass('hoverCart');
                    $(clone).prependTo('body')
                        .css({'position' : 'absolute', 'z-index' : '999', 'left' : x.left, 'top': x.top})
                        .stop()
                        .animate({
                            opacity: 0.5,
                            top: x2.top,
                            left: x2.left
                        }, 400, function(){
                            $(this).remove();
                            cart.removeClass('hoverCart');
                            if (result.incart){
                                updListCart(result.incart);
                            }
                            if (result.total){
                                updSumCart(result.total);
                            }               
                        });
                }
            },
            error:function(){
                toggleLoader(true);
            }
        });
    }
    return false;    
});
});
}
