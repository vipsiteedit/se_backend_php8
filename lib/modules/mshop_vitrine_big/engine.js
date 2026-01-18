$(document).ready(function(){
$(".blockGoods").click(function(event){
if (window.ajax_request !== undefined){
    if ($(event.target).is(".buttonSend.buttonAddCart") && window.ajax_request == true){
        var real_goods = $(event.currentTarget);
                //console.log(real_goods.find('.blockImage').length);

        if (real_goods.find('.blockImage').length > 0) {
            real_goods = real_goods.find('.blockImage');
        }
        
        var clone_goods = real_goods.clone();
        var id_goods = $(this).find("[name='addcart']").val();
        var x = real_goods.offset(), x2 = $(".fixedCart:last #bodyCart").offset();
        var goods_param = $(this).find("[name='listcartparam']").val();
        var cart_count = $(this).find("[name='addcartcount']").val();
        toggleLoader(false);
        $.ajax({
            url: "?",
            data: {'addcart':id_goods, 'addcartparam':goods_param, 'addcartcount':cart_count},
            type: 'post',
            dataType: "json",
            success:function(result){
                if (result.action != 'empty' && result.action){
                    $(".fixedCart").addClass('hoverCart');
                    $(clone_goods)
                        //.insertBefore('.tableTable.tablePrice')
                        .prependTo('body')
                        .css({'position' : 'absolute', 'z-index' : '999', 'left' : x.left, 'top': x.top})
                        .stop()
                        .animate({
                            opacity: 0.5,
                            top: x2.top, 
                            left: x2.left}, 
                        500, function(){
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
                else{
                    toggleLoader(true);
                }
            },
            error:function(){
                toggleLoader(true);
            }
        });
    }
}
else{      
    if ($(event.target).is(".buttonSend.buttonAddCart")){
        $(event.currentTarget).find('.form_addCart').submit();
    }
}
});

$(".goodsDetail").click(function(event){
if (window.ajax_request !== undefined){
    if ($(event.target).is(".buttonSend.addcart") && window.ajax_request == true){
        var real_goods = $(event.currentTarget);
        if (real_goods.find('.goodsLinkPhoto').length > 0) {
            real_goods = real_goods.find('.goodsLinkPhoto');
        }
        var clone_goods = real_goods.clone();
        var id_goods = $(this).find("[name='addcart']").val();
        var x = real_goods.offset(), x2 = $(".fixedCart:last #bodyCart").offset();
        var goods_param = $(this).find("[name='listcartparam']").val();
        toggleLoader(false);
        $.ajax({
            url: "?",
            data: {'addcart':id_goods, 'addcartparam': goods_param},
            type: 'post',
            dataType: "json",
            success:function(result){
                if (result.action != 'empty' && result.action){
                    $(".fixedCart").addClass('hoverCart');
                    $(clone_goods)
                        //.insertBefore(real_goods)
                        .prependTo('body')
                        .css({'position' : 'absolute', 'z-index' : '999', 'left' : x.left, 'top': x.top})
                        .stop()
                        .animate({
                            opacity: 0.5,
                            top: x2.top, 
                            left: x2.left}, 
                        500, function(){
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
                else{
                    toggleLoader(true);
                }
            },
            error:function(){
                toggleLoader(true);
            }
        });
    }
}
else{
    if ($(event.target).is(".buttonSend.addcart"))
        $(event.currentTarget).find('.form_addCart').submit();    
}
});

$(".priceBox").click(function(event){
if (window.ajax_request !== undefined){
    if ($(event.target).is(".buttonSend.addcart") && window.ajax_request == true){
        var real_goods = $(event.currentTarget).parent().find('.blockImage');
        var clone_goods = real_goods.clone();
        var id_goods = $(this).find("[name='addcart']").val();
        var x = real_goods.offset(), x2 = $(".fixedCart:last #bodyCart").offset();
        var goods_param = $(this).find("[name='listcartparam']").val();
        toggleLoader(false);
        $.ajax({
            url: "?",
            data: {'addcart':id_goods, 'addcartparam': goods_param},
            type: 'post',
            dataType: "json",
            success:function(result){
                if (result.action != 'empty' && result.action){
                    $(".fixedCart").addClass('hoverCart');
                    $(clone_goods)
                        .css({'position' : 'absolute', 'z-index' : '999', 'left' : x.left, 'top': x.top})
                        //.insertBefore(real_goods)
                        .prependTo('body')
                        .stop()
                        .animate({
                            opacity: 0.5,
                            top: x2.top, 
                            left: x2.left}, 
                        500, function(){
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
                else{
                    toggleLoader(true);
                }
            },
            error:function(){
                toggleLoader(true);
            }
        });
    }
}
else{
    if ($(event.target).is(".buttonSend.addcart"))
        $(event.currentTarget).find('.form_addCart').submit();
}
});

});
