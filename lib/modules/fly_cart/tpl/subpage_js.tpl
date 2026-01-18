<header:js>
[js:jquery/jquery.min.js]
<?php if($section->parametrs->param17=='Y'): ?>
[js:ui/jquery.ui.min.js]
<?php endif; ?>
</header:js>
<script type="text/javascript">
var ajax_request = true;
var cart_animate = false;
var flag_hover = false;
var rounded = "<?php echo $section->parametrs->param21 ?>";
function updSummCart(summ){
    var upd_s1 = $(".fixedCart .shortInfoCart");                 
    var upd_s2 = $(".fixedCart .orderSummAll");
    upd_s2.find("#summOrder")
        .fadeOut(500, function(){
            $(this).html(summ.s_amount).fadeIn(500);
        });
    
        upd_s1.find("#summGoods")
        .fadeOut(500, function(){
            $(this).html(summ.s_amount).fadeIn(500);
        });
     upd_s2.find("#summDiscount")
        .fadeOut(500, function(){
            $(this).html(summ.s_discount).fadeIn(500);
        });    
   
     upd_s1.find("#countGoods")
        .fadeOut(500, function(){
            $(this).html(summ.s_count).fadeIn(500);
        });             
}
function addAjaxCart(data){
    var new_add = $(".goodInfo:first").clone();
    new_add.removeAttr("style");
    new_add.removeClass("defHidden");              
    new_add.attr('data-id', data.id);
    new_add.find(".hiddenIdGood").val(data.id);
    new_add.find(".goodCount").html(data.count);
    new_add.find(".linkShowGood").html(data.name).attr({"title": data.name, "href": "<?php echo seMultiDir()."/".$section->parametrs->param3."/" ?>"+"show/" + data.code + '/'});
    new_add.find(".goodPrice").html(data.price);
    $(".fixedCart .extendInfoCart .noGoods").before(new_add);
    new_add.hide().slideDown(500,toggleLoader(true));
    $(".noGoods")
        .hide(500,function(){
            $(this).css('display', 'none');    
        });
    $(".issetGoods").slideDown(500);        
    updSummCart(data.sumdata);
}
function updAjaxCart(data){
    var upd_goods = $('.extendInfoCart [data-id=' + data.id + ']');
    upd_goods.find(".goodCount")
        .fadeOut(500, function(){
            toggleLoader(true);
            $(this).html(data.count);
        })
        .fadeIn(500);
    upd_goods.find(".goodPrice")
        .fadeOut(500, function(){
            $(this).html(data.summ);
        })
        .fadeIn(500);
    updSummCart(data.sumdata);
   
}
function delAjaxCart(data){
    var del_goods = $('.extendInfoCart .goodInfo[data-id=' + data.id + ']');
    var del_animate = $('#fixedCart .extendInfoCart .goodInfo[data-id=' + data.id + ']');
    
    del_animate.css({'position':'relative', 'z-index':'1000'})
        .effect("explode", 500, function(){
            toggleLoader(true);
            del_goods.css({'visibility':'hidden'})
                .show()
                .hide(500, function(){
                    del_goods.remove()
                    if ($("#fixedCart:first .extendInfoCart .goodInfo").size() <= 1){
                        $(".noGoods").show(500);
                        $(".issetGoods").hide();
                    }
                });
        });
    $(".ui-effects-explode").css({'z-index':'999'});
    updSummCart(data.sumdata);    
}
function toggleLoader(stop_loader){
    var load_block = $(".loaderAjax");
    if (stop_loader == false){
        ajax_request = false;
        load_block.show();
        $('.fixedCart, .blockGoodsInfo, .blockGoodsInfo *, .butAddCart').css('cursor','wait');
    }
    if (stop_loader == true){
        ajax_request = true;
        $('.fixedCart, .blockGoodsInfo, .blockGoodsInfo *, .butAddCart').css('cursor','auto');
        load_block.hide();
    }     
}
</script>
<?php if($section->parametrs->param16=='Y'): ?>
<script type="text/javascript">
$(document).ready(function(){
var offset = $("#fixedCart").offset();
var clone = $("#fixedCart").clone();
var topPadding = 20;
clone.attr('id','clone')
    .css({
        'left':offset.left, 
        'top':offset.top,
        'position' : '',
        'opacity':0.0,
        'visibility':"hidden"
    })
   .insertBefore("#fixedCart");  
$("#fixedCart")
    .css({'left':offset.left, 
        'top':offset.top,
        'marginTop': 0,
        'marginLeft': 0,
        'z-index': 998,
        'position' : 'absolute'
   });
$(window).resize(function() {
    $("#fixedCart").css({top:clone.offset().top, left:clone.offset().left});
});   
  
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
    
        $("#fixedCart")
            .addClass('flyCart')
            //.css({'left':offset.left})
            .stop()
            .animate({marginTop: move_top, marginLeft: move_left}, 500);
    }
    else{
        $("#fixedCart")
            .stop()
            .animate({marginTop: 0, marginLeft: 0}, 
            300, function(){
                $("#fixedCart").removeClass('flyCart')
                //$(this).css({'position' : ''});
                //clone.remove();
            });
    }
   /*
   if ($(window).scrollLeft() + topPadding > offset.left) {
    $("#fixedCart")
     .addClass('flyCart')
    // .css({'left':offset.left})
     .stop()
     .animate({marginTop: $(window).scrollTop() - offset.top + topPadding, marginLeft: $(window).scrollLeft() - offset.left + topPadding}, 500);
   }
   else {
    $("#fixedCart")
     .stop()
     .animate({marginLeft: 0}, 
      300, 
      function(){
       $("#fixedCart").removeClass('flyCart')
       //$(this).css({'position' : ''});
       //clone.remove();
      }
     );
   }
    */
});
});
</script>
<?php endif; ?>
<script type="text/javascript">
$(document).ready(function(){
var def_info = "<?php echo $section->parametrs->param19 ?>";
if (def_info == 'short'){
    $(".fixedCart .extendInfoCart").hide();
    $(".fixedCart .shortInfoCart").show();
    $(".fixedCart .butShowHide").addClass('showExtCart');    
}
 
$(".fixedCart").hover(function(){
    if (($(this).hasClass('flyCart')) && !($(this).hasClass('activeCart'))){
        $(this).removeClass('flyCart');
        flag_hover = true;
    }
},
function(){
    if (flag_hover){
        $(this).addClass('flyCart');
        flag_hover = false;
    }
});
<?php if($section->parametrs->param20=='Y'): ?> 
$(".fixedCart .butShowHide").click(function(){
    if (cart_animate == false && ajax_request == true){
        var ext_cart = $(".fixedCart .extendInfoCart");
        var short_cart = $(".fixedCart .shortInfoCart");
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
        $(".fixedCart .butShowHide").fadeOut(500, function(){
            $(this).toggleClass('showExtCart').fadeIn(500);
        });
    }    
});
<?php endif; ?>
<?php if($section->parametrs->param17=='Y'): ?>
$("#fixedCart").droppable({
    activeClass : "activeCart",
    hoverClass : "hoverCart",
    accept : ".blockGoodsInfo, .blockGoodsInfo *",
    tolerance: "touch",
    drop : function(event, ui){
        if (ajax_request == true){
            $(ui.draggable).draggable({ revert: 'invalid' });
            var id_good = ui.draggable.find("[name='addcart']").val();
            toggleLoader(false);
            $.ajax({
                url: '?ajax<?php echo $section->id ?>',
                data: {'addcart':id_good, 'addcartparam':''},
                type: 'post',
                dataType: "json",
                success: function(result){
                    if (result.action == 'add'){
                        addAjaxCart(result.data);    
                    }
                    else if(result.action == 'update'){
                        updAjaxCart(result.data);
                    }
                    else{
                        toggleLoader(true);
                    }
                },
                error:function(){
                    toggleLoader(true);
                }
            });
        }
        else {
            $(ui.draggable).draggable({ revert: true });
        }
    }  
});
<?php endif; ?>
$('.extendInfoCart').on('click', '.linkDelGood', function(event){
    if (ajax_request == true){
        toggleLoader(false);
        
        var del_id = $(this).closest('.goodInfo').data('id');
        $.ajax({
            url: '?ajax<?php echo $section->id ?>',
            type: 'post',
            data: {'delcartname':del_id, 'delcartparam':''},
            dataType: "json",
            success: function(result){
                if (result.action == 'remove')
                    delAjaxCart(result.data);
            },
            complete:function(){
                toggleLoader(true);
            }    
        });
    }
});
$('.blockGoods, .goodsDetail, .theGoodContent, .blockAllItem:not(.emptyGoods)').on('click', '.buttonSend.addcart, .buttonSend.buttonAddCart, .buttonSend.butAddCart', function(event){ 
    if (window.ajax_request !== undefined && window.ajax_request == true){
        var current_product = $(event.delegateTarget);
        var real_goods = current_product.find('.blockImage, .goodsLinkPhoto');
        if (!real_goods.length > 0) {
            real_goods = current_product;
        }
        
        var id_goods = current_product.find('[name=addcart]').val(),
            goods_param = current_product.find('[name=listcartparam]').val(),
            cart_count = current_product.find('[name=addcartcount]').val();
        
        toggleLoader(false);
        $.ajax({
            url: '?ajax<?php echo $section->id ?>',
            data: {'addcart':id_goods, 'addcartparam':goods_param, 'addcartcount':cart_count},
            type: 'post',
            dataType: "json",
            success:function(result){
                var clone_goods = real_goods.clone(),
                    fly_cart = $(".fixedCart:last");
                var x = real_goods.offset(), x2 = fly_cart.offset();
                if (result.action && result.action != 'empty'){
                    $(".fixedCart").addClass('hoverCart');
                    $(clone_goods)
                        .prependTo('body')
                        .css({'position' : 'absolute', 'z-index' : '999', 'left' : x.left, 'top': x.top})
                        .stop()
                        .animate({
                            opacity: 0.5,
                            top: x2.top,
                            left: x2.left
                        }, 500, function(){
                            $(this).remove();
                            $(".fixedCart").removeClass('hoverCart');
                            if (result.action == 'add'){
                                addAjaxCart(result.data);
                            }
                            if(result.action == 'update'){
                                updAjaxCart(result.data);
                            }               
                        });
                }
            },
            complete:function(){
                toggleLoader(true);
            }
        });
        return false;
    }    
});      
});
</script>
