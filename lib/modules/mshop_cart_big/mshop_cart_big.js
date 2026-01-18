var mshop_cart_big_execute = function(params){ 
function updateCartTotal(total){
    var amount = $("#tdTotalGoods");
    if (total.count == 0){
        $('#notEmptyCartGoods').slideUp(400, function(){
            $('#emptyCartGoods').slideDown(400);
        });
    }
    else{
        $('#emptyCartGoods').slideUp(400, function(){
            $('#notEmptyCartGoods').slideDown(400);
        });    
    }
    if (total.discount)
        amount.find('#discountGoods span').html(total.discount);
    if (total.amount)
         amount.find('#summGoods span').html(total.amount);
    if (total.weight)
         amount.find('#weightGoods span').html(total.weight);
    if (total.volume)
         amount.find('#volumeGoods span').html(total.volume);
    if (total.sum_order)
         $('#blockCartTotalSum #totalSumPrice').html(total.sum_order);      
    if (total.coupon) 
         $('#blockCouponDiscount #blockSumCoupon').html(total.coupon);
}
function updateCartList(list){
    if (list.length > 0){
        $('.blockCartList .itemCart').removeClass('updateAjax');
        for (var i = 0; i < list.length; i++){
            if (i in list) {
                var item = $('.itemCart[data-id=' + list[i].id + ']');
                if (item.length > 0){
                    item.addClass('updateAjax');
                    item.find('.cartitem_inputcn').val(list[i].count);
                    item.find('.summBlock').html(list[i].sum);
                }
                else{
                    addCartProduct(list[i]);
                }    
            } 
        }
        $('.blockCartList .itemCart:not(.updateAjax)').each(function(){
            $(this).css({'height':$(this).outerHeight()}).children().fadeOut(400, function(){
                $(this).parent().slideUp(400, function(){
                    $(this).remove();
                });
                $(this).remove();
            });
        });
    }  
}
function addCartProduct(data){
var insert = '<tr class="tableRow itemCart updateAjax" data-id="' + data.id +'">' +
    '<td class="itemImageCart"><a href="' + data.link + '"><img src="' + data.img + '"></img></a></td>' +                                        
    '<td class="itemInfoGoodsCart"><a class="linkname" href="' + data.link + '">' + data.shortname + '</a>' +
    (data.params ? '<div class="cartitem_params">' + data.params + '</div>' : '') + 
    '<div class="cartitem_article">Артикул: <span>' + data.article + '</span></div>' + 
    '<div class="cartitem_presence">Наличие: <span>' + data.presence + '</span></div>' +
    '<div class="cartitem_price"><span class="itemPriceTitle">Цена:</span>' +
    (data.oldprice < data.newprice ? ' <span class="itemOldPrice">' + data.oldprice + '</span>' : '') +
    ' <span class="itemNewPrice">' + data.newprice + '</span></div></td>' +   
    '<td class="itemCountCart"><div class="cartitem_count"><a href="#" class="buttonSend decCountItem" data-action="decrement">-</a> <input class="cartitem_inputcn" type="text" name="countitem[' + data.id + ']" value="' + data.count + '" size="3"> <a href="#" class="buttonSend incCountItem" data-action="increment">+</a></div></td>' +
    '<td class="itemSumCart"><span class="summBlock">' + data.sum + '</span></td>' +
    '<td class="itemDeleteCart"><a href="' + params.page_link + 'delcartname/' + data.id + '/" class="btnDeleteItem" title="Удалить товар из корзины">&nbsp;</a></td></tr>';
    $(insert).insertBefore('.tableListGoods #trTotalOrder').hide().show(400);
}
    
$(document).ready(function () {

    $(document).click(function(event){
        if (!$(event.target).is('.linkShowNote') && !$(event.target).is('.paymentNote')){
            $('.paymentNote').hide();
        }
    });
    
    $('.paymentType').on('click', '.linkShowNote', function(e){
        var payment = $(this);
        var note = payment.closest('.paymentType').find('.paymentNote');
        var x = payment.position();           
        if (note.is(':hidden')) {
            $('.paymentNote').hide();
            note.css({'position': 'absolute', 'left' : x.left + payment.outerWidth(true), 'top': x.top + payment.outerHeight(true)}).fadeIn(150);
        }
        else {
            $('.paymentNote').hide();    
        }
        return false;
    }); 

    var id_time = null;
    
    $('.cartitem_count .buttonSend').show();
    
    $('#cartGoodsForm').on('keypress', 'input', function(e){
        return !(e.which == 13 && $(this).blur());
    });
    
    $('.tableListGoods').on('keypress', '.cartitem_inputcn', function(e){
        return !((e.which < 48 || e.which > 57) && e.which != 8 && e.which!=13);
    });                                                
    
    $('#inputCoupon').keypress(function(e){
        e.which==13 && $('#btnApplyCoupon').click();
    });
    
    $('#blockCartDelivery').on('click', '#linkSelectRegion', function(e){
        var link_region = $(this);
        var pos = link_region.offset();
        $('<div class="windowOverlay">')
            .appendTo('body')
            .css({
                'position': 'fixed',           
                'width': $(window).innerWidth(),
                'height': $(window).innerHeight(),
                'background-color': 'rgb(0,0,0)',
                'opacity': '0.4',
                'zIndex': 1000,
                'left': 0,
                'top': 0
            }).hide().fadeIn(100);
        
        $('.blockSelectUserRegion')
            .css({
                'position': 'fixed', 
                'left': (window.innerWidth - $('.blockSelectUserRegion').outerWidth(true))/2,
                'top': (window.innerHeight - $('.blockSelectUserRegion').outerHeight(true))/2,
                'zIndex': '1001'
            }).appendTo('body').fadeIn(100);
        
        return false;    
    });
    
    $(window).resize(function(){
        $('.windowOverlay').css({'width': $(window).innerWidth(), 'height': $(window).innerHeight()});
    });       

    $('.tableListGoods').on('click', '.cartitem_count .buttonSend', function(){
        var input = $(this).parent().find('.cartitem_inputcn'),
            count = parseInt(input.val());   
        if (isNaN(count)) count = 1;
        count += $(this).data('action') == 'increment' ? 1 : (count > 1 ? -1 : 0);
        input.val(count).change();
        return false;
    });
    


    $(document).ajaxStart(function(event, xhr, options){
        $('#test_order').attr('disabled', true);
    }).ajaxSend(function(event, xhr, options){
        if (options.overlay){ 
            options.overlay = ajaxOverlay(options.overlay); 
        }
    }).ajaxComplete(function(event, xhr, options){
        if (options.overlay){
            $(options.overlay).fadeOut(100, function(){
                $(this).remove();
            });
        }    
    }).ajaxStop(function(event, xhr, options){
        $('#test_order').removeAttr('disabled');    
    });
    
    $('body').on('click', '.windowOverlay, .blockSelectUserRegion .btnClose', function(){
        $('.windowOverlay').fadeOut(100, function(){
            $(this).remove()
        });
        $('.blockSelectUserRegion').fadeOut(100);
        return false;
    });
    
    $('#btnConfirmRegion').click(function(){
        var data = $('.blockSelectUserRegion select').serialize();
        $.ajax({
            data: data+'&set_region=1',
            overlay: $('#blockCartDelivery .blockCartList')
        });
        $('.windowOverlay').fadeOut(100, function(){
            $(this).remove()
        });
        $('.blockSelectUserRegion').fadeOut(100);
    });
     
    $.ajaxSetup({
        url: params.ajax_url,
        crossDomain: (params.param16 == 'N'),
        type: 'post',
        dataType: "json",
        success: function(data){
            if (data.total){
                updateCartTotal(data.total);
                if (window.updSumCart)
                    updSumCart(data.total);
            }
            if (data.incart){
                updateCartList(data.incart);
                if (window.updListCart){
                    updListCart(data.incart);
                }    
            }
            if (data.coupon){ 
                $('#blockCouponDiscount #noteCoupon').html(data.coupon.note);
                if (data.coupon.find){
                    $('#blockCouponDiscount #blockSumCoupon').html(data.coupon.show);                               
                }
                else{
                    $('#blockCouponDiscount #blockSumCoupon').empty();
                }
            }

            if (data.region_name){
                $('#selectedUserRegion a').html(data.region_name);
                $('#btnConfirmRegion').hide();
            }
            
            if (data.delivery){
                if (data.delivery.html){
                    $('#blockCartDelivery .blockCartList').html(data.delivery.html);
                    
                    if ($('#blockCartDelivery .blockCartList').find(':radio:checked').data('addr'))
                        $('#blockCartContact').find('.blockContactPostIndex, .blockContactAddress').fadeIn(300); 
                    else
                        $('#blockCartContact').find('.blockContactPostIndex, .blockContactAddress').fadeOut(300);    
                }
            }
            
            if (data.payment){
                $('#blockCartPayment .paymentType').hide().find('input').attr({'checked': false, 'disabled':true});
                if (data.payment.id){
                    $('#blockCartPayment').show();
                    $.each(data.payment.id, function(){
                        radio = $('#blockCartPayment .paymentType[data-id='+this+']').show().find('input').attr('disabled', false);
                        if (this == data.payment.selected){
                            radio.click();
                        }                
                    });   
                 }
                 else
                     $('#blockCartPayment').hide();
            }  
            
        },
        error: function(xhr, status, error){
            console.log([status, error]);
        }
    });
    
    if (window.send_ajax == undefined || !window.send_ajax){
        $.ajax({data: 'getcart=1'});
        send_ajax = true;
    }
    
    $('#blockCouponDiscount #btnApplyCoupon').click(function(){
        var code_coupon = $(this).closest('#blockCouponDiscount').find('#inputCoupon').val(); 
        if (code_coupon){
            $.ajax({
                data: {code_coupon:code_coupon},
                overlay: $('#blockCouponDiscount')
            });
        }
        return false;
    });
    
    $('.tableListGoods').on('click', '.btnDeleteItem', function(e){
        var item = $(this).closest('.itemCart');
        var item_id = item.data('id');
        $.ajax({
            data: {delcartname:item_id},
            overlay: $(item)
        });
        return false;
    });
    
    $('.tableListGoods').on('change', '.cartitem_inputcn', function(){
        clearTimeout(id_time); 
        var count_item = $(this);
        id_time = setTimeout(function(){
            $.ajax({
                data: $(count_item).serialize(),
                overlay: $(count_item).closest('.itemCart')
            });
        }, 300);    
    });
    
    $('#blockCartDelivery .blockCartList').on('change', '.radioDeliveryType', function(){             
        $.ajax({
            data: $(this).serialize(),
            overlay: $('#blockCartDelivery .blockCartList')
        });
        if ($(this).data('addr')){
            $('#blockCartContact').find('.blockContactPostIndex, .blockContactAddress').fadeIn(300);  
        }
        else{
            $('#blockCartContact').find('.blockContactPostIndex, .blockContactAddress').fadeOut(300);     
        }
    });
    $('.ajaxSelect').change(function(){
        select = $(this);
        next_select = select.closest('.blockSelection').next();
        $.ajax({
            data: select.serialize()+'&get_region=1',
            beforeSend: function(){
                next_select.find('.ajaxPreloader').show();
                $('#btnConfirmRegion').attr('disabled', true);
                next_select.find('select').empty();
            },
            success: function(data){
                next_select.find('.ajaxPreloader').hide();
                $('#btnConfirmRegion').attr('disabled', false);
                if (data.region){
                    $('.blockSelectRegion select').html(data.region);
                    //$('.blockSelectCity select').empty();
                    next_select.find('select').change();
                }
                if (data.city){
                    $('.blockSelectCity select').html(data.city);
                }
            } 
        });     
    });
    $('.blockSelectUserRegion select').change(function(){
        $('#btnConfirmRegion').show();
    });

});
function ajaxOverlay(selector){
    $(selector).each(function(){
        var item = $(this);
        var offset = item.offset();
        var overlay = $('<div class="ajaxOverlay">')
            .appendTo('body')
            .append('<div class="ajaxPreloader">загрузка...</div>')
            .css({
                'position': 'absolute',
                'display': 'none',
                'width': item.innerWidth(),
                'height': item.innerHeight()
            });
        if (offset !== undefined){
            $(overlay).css({'left': offset.left, 'top': offset.top});
        }       
        $(overlay).fadeIn(100)
    });
    return $('.ajaxOverlay');    
}
}
