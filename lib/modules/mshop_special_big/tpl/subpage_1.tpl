<header:js>
[js:jquery/jquery.min.js]
<?php if($section->parametrs->param45=='Y'): ?>
[js:ui/jquery.ui.min.js]
<?php endif; ?>
<?php if($section->parametrs->param33=='rotate'): ?>
<script type="text/javascript" src="[module_url]jquery.carousel.js"></script>
<?php endif; ?>
</header:js>
<?php if($section->parametrs->param46=='Y'): ?>
<script type="text/javascript">
$(document).ready(function(){
$(".partSpecial<?php echo $section->id ?>.emptyGoods").mousedown(function(){
    return false;    
});
$(".partSpecial<?php echo $section->id ?>:not(.emptyGoods) .<?php echo $section->parametrs->param32 ?>").hover(function(event){
    $(this).addClass('hoverToDragGoods');    
}, function(){
    $(this).removeClass('hoverToDragGoods');    
});    
$(".partSpecial<?php echo $section->id ?>:not(.emptyGoods) .<?php echo $section->parametrs->param32 ?>").draggable({
    start: function(event, ui){
        if (window.ajax_request == true){
            ui.helper.removeClass('hoverToDragGoods').addClass('dragAjaxGoods');
        }
        else{
            return false;
        }    
    },
    containment:'document',
    helper:'clone',
    delay:10,
    //scroll:false,
    'zIndex':999,
    appendTo:'.bodySpecialGoods<?php echo $section->id ?>',
    revert:'invalid'
});
});
</script>
<?php endif; ?>
<?php if($section->parametrs->param45=='Y'): ?>
<script type="text/javascript">
$(document).ready(function(){ 
$(".form_addCart").submit(function(){
    if (window.ajax_request !== undefined)
        return false;
}); 
$(".partSpecial<?php echo $section->id ?>:not(.emptyGoods)").click(function(event){
    if ($(event.target).is(":submit") && window.ajax_request == true){   
        var but_t = $(event.target);
        var real_goods = $(event.currentTarget).find('.<?php echo $section->parametrs->param32 ?>');
        var id_goods = $(this).find("[name='addcart']").val();
        var x = real_goods.offset(), x2 = $(".fixedCart:last #bodyCart").offset();
        toggleLoader(false);
        $.ajax({
            url: "",
            data: {'addcart':id_goods, 'addcartparam':''},
            type: 'post',
            dataType: "json",
            success:function(result){
                if (result.action != 'empty' && result.action){
                    $(".fixedCart").addClass('hoverCart');
                    var clone_goods = real_goods.clone();
                    $(clone_goods)
                        .addClass('dragAjaxGoods')
                        .css({'position' : 'absolute', 'z-index' : '999', 'left' : x.left, 'top': x.top})
                        .insertBefore('.bodySpecialGoods<?php echo $section->id ?>')
                        .stop()
                        .animate({
                            opacity: 0.3,
                            top: x2.top, 
                            left: x2.left}, 
                        500, function(){
                            $(this).remove();
                            $(".fixedCart").removeClass('hoverCart');
                            if (result.action == 'add'){
                                addAjaxCart(result.data);
                                //but_t.removeAttr('disabled');
                            }
                            if(result.action == 'update'){
                                updAjaxCart(result.data);
                                but_t.removeAttr('disabled');
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
    return false;
    } 
});
   
});
</script>
<?php endif; ?>
<?php if($section->parametrs->param33=='rotate'): ?>
<script type="text/javascript">
$(document).ready(function() {        
$('#partRotate<?php echo $section->id ?> .rtContainer').Carousel({
    position: "<?php echo $section->parametrs->param34 ?>",
    visible: <?php echo $section->parametrs->param35 ?>,
    rotateBy: <?php echo $section->parametrs->param36 ?>,
    speed: <?php echo $section->parametrs->param37 ?>,
    direction: <?php echo $section->parametrs->param42 ?>,
    btnNext: '#partRotate<?php echo $section->id ?> #nextRotate',
    btnPrev: '#partRotate<?php echo $section->id ?> #prevRotate',      
    auto: <?php echo $section->parametrs->param38 ?>,      
    delay: <?php echo $section->parametrs->param39 ?>,
    dirAutoSlide:<?php echo $section->parametrs->param40 ?>,
    margin: 0      
});    
});
</script>
<?php endif; ?>
