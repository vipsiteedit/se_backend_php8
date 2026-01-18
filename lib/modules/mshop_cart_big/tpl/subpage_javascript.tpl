<script type="text/javascript">
$(document).ready(function () {
    var id_time = null;
    
    $('.cartitem_count .buttonSend').show();
    
    $('.cartitem_inputcn').keypress(function(e){
        if ((e.which<48 || e.which>57) && e.which!=8 && e.which!=13){
            return false; 
        }
        if (e.which==13){ 
            $(this).blur();
            return false;
        }
    });
    
    $('#inputCoupon').keypress(function(e){
        if (e.which==13){ 
            $(this).blur();
            $('#btnApplyCoupon').click();
            return false;
        }
    });
    
    $('#linkSelectRegion').click(function(e){
        var link_region = $(this);
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
                'position': 'absolute', 
                'left': link_region.offset().left + link_region.outerWidth(true),
                'top': link_region.offset().top + link_region.outerHeight(true),
                'zIndex': '1001'
            }).fadeIn(100);
        
        return false;    
    });
    
    $(window).resize(function(){
        $('.windowOverlay').css({'width': $(window).innerWidth(), 'height': $(window).innerHeight()});
    }); 
 
    $('#cartGoodsForm input').keypress(function(e){
        if (e.which==13){
            $(this).blur();
            return false;
        }
    });       
    
    $('.cartitem_count .buttonSend').click(function(){
        var count_input = $(this).parent().find('.cartitem_inputcn');
        $(count_input).blur();
        var count = parseInt(count_input.val());   
        if (isNaN(count)) count = 0;
        if ($(this).data('action') == 'increment'){
            count++;    
        }
        else{
            if (count > 1) count--;
            else count = 1;    
        }
        
        $(count_input).val(count);
        $(count_input).change();
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
        url: '?ajax_cart',
        type: 'post',
        data: {ajax_cart: 1},
        dataType: "json",
        success: function(data){
            if (data.total){
                if (data.total.count == 0){
                    $('#emptyCartGoods').show(300);
                    $('#notEmptyCartGoods').hide();
                }
                if (data.total.discount)
                    $('#discountGoods span').html(data.total.discount);
                if (data.total.sum_order)
                    $('#blockCartTotalSum #totalSumPrice').html(data.total.sum_order);
                if (data.total.sum)
                    $('#summGoods span').html(data.total.sum);
                if (data.total.coupon) 
                    $('#blockCouponDiscount #blockSumCoupon').html(data.total.coupon);
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
            
            if (data.del){
                $('.itemCart[data-id='+data.del+']').hide(300, function(){
                    $(this).remove()
                });
            }
            
            if (data.update){
                var item = $('.itemCart[data-id='+data.update.id+']');
                if (data.update.count){
                    $(item).find('.cartitem_inputcn').val(data.update.count);
                    $(item).find('.summBlock').html(data.update.sum);
                }
                else $(item).hide(300, function(){
                    $(this).remove();
                });   
            }
            
            if (data.region_name){
                $('#selectedUserRegion a').html(data.region_name);
                $('#btnConfirmRegion').hide();
            }
            
            if (data.delivery){
                if (data.delivery.html){
                    $('#blockCartDelivery .blockCartList').html(data.delivery.html);
                    
                    if ($('#blockCartDelivery .blockCartList').find(':radio:checked').data('addr'))
                        $('#blockCartContact #reg_address').closest('.blockContactLine').fadeIn(300);
                    else
                        $('#blockCartContact #reg_address').closest('.blockContactLine').fadeOut(300);    
                }
            }
            
            if (data.payment){
                $('#blockCartPayment .paymentType').hide().find('input').attr({'checked': false, 'disabled':true});
                if (data.payment.id){
                    $.each(data.payment.id, function(){
                        radio = $('#blockCartPayment .paymentType[data-id='+this+']').show().find('input').attr('disabled', false);
                        if (this == data.payment.selected){
                            radio.click();
                        }                
                    });   
                 }
            }  
            
        },
        error: function(xhr, status, error){
            console.log([status, error]);
        }
    });
    
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
    
    $('.itemCart .btnDeleteItem').click(function(e){
        var item = $(this).closest('.itemCart');
        var item_id = item.data('id');
        $.ajax({
            data: {delcartname:item_id},
            overlay: $(item)
        });
        return false;
    });
    
    $('.cartitem_inputcn').change(function(){
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
            $('#blockCartContact #reg_address').closest('.blockContactLine').fadeIn(300);
        }
        else{
            $('#blockCartContact #reg_address').closest('.blockContactLine').fadeOut(300);    
        }
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
</script>
