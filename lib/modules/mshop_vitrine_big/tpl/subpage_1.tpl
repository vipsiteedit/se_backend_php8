<!-- Subpage 1. Подробно о товаре и сопутствующие товары с аналогами -->
<header:js>
[js:jquery/jquery.min.js]
[js:ui/jquery.ui.min.js]
<script type="text/javascript" src="[module_url]cloud-zoom.1.0.2.min.js"></script>
<script type='text/javascript' src='[module_url]jquery.lightbox.js'></script>
<script type="text/javascript" src="[module_url]function.js"></script>
<link rel="stylesheet" href="[module_url]cloud-zoom.css">
</header:js>

    <?php if($section->parametrs->param266=='Y'): ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".goodsDetRating").append('<span class="goodsDetVotesTitle"><?php echo $section->parametrs->param267 ?></span><a href="" class="goodsDetUp" title="<?php echo $section->parametrs->param262 ?>"><?php echo $section->parametrs->param262 ?></a><span class="goodsDetVotes"></span><a href="" class="goodsDetDown" title="<?php echo $section->parametrs->param263 ?>"><?php echo $section->parametrs->param263 ?></a><div class="goodsDetRatingErr"></div>');
                $(".goodsDetRating > .goodsDetRatingErr").hide();
                $.post("?ajax<?php echo $razdel ?>", {initvote: "1"}, onInitVotesSuccess);
                function onInitVotesSuccess(data) {  
                    $(".goodsDetRating > .goodsDetVotes").text(data);    
                    return false;
                }
                function errorReportingAuth() {
                    $(".goodsDetRating > .goodsDetRatingErr").text("<?php echo $section->parametrs->param264 ?>");        
                    $(".goodsDetRating > .goodsDetRatingErr").show();    
                    setTimeout(function(){ $(".goodsDetRating > .goodsDetRatingErr").hide(); return false;}, 3000);
                    return false;
                }
                function errorReportingDouble() {
                    $(".goodsDetRating > .goodsDetRatingErr").text("<?php echo $section->parametrs->param265 ?>");        
                    $(".goodsDetRating > .goodsDetRatingErr").show();    
                    setTimeout(function(){ $(".goodsDetRating > .goodsDetRatingErr").hide(); return false;}, 3000);
                    return false;
                }
                function onAjaxSuccess(data) {  
                    if (data=="err_auth") { 
                        errorReportingAuth();
                    } else if (data=="err_double_vote") { 
                        errorReportingDouble();
                    } else {
                        $(".goodsDetRating > .goodsDetVotes").text(data);    
                    }
                    return false;
                }
                $(".goodsDetRating > .goodsDetUp").click(function(){
                    $.post("?ajax<?php echo $razdel ?>", {vote: "1"}, onAjaxSuccess);
                    return false;
                });
                $(".goodsDetRating > .goodsDetDown").click(function(){
                    $.post("?ajax<?php echo $razdel ?>", {vote: "-1"}, onAjaxSuccess);
                    return false;
                });
            })
        </script>
    <?php endif; ?>
    
<script type="text/javascript">
$(document).ready(function(){ 
    createLBox("<?php echo $section->parametrs->param248 ?>", "<?php echo $section->parametrs->param250 ?>", "[module_url]");
 
});
</script>
<header:js>
<style type="text/css">
#jquery-overlay {
 position: absolute;
 top: 0;
 left: 0;
 z-index: 10000;
 width: 100%;
 height: 500px;
}
#jquery-lightbox {
 position: absolute;
 top: 0;
 left: 0;
 width: 100%;
 z-index: 10010;
 text-align: center;
 line-height: 0;
}
#jquery-lightbox a img { border: none; }
#lightbox-container-image-box {
 position: relative;
 background-color: #fff;
 width: 250px;
 height: 250px;
 margin: 0 auto;
}
#lightbox-container-image { padding: 10px; }
#lightbox-loading {
 position: absolute;
 top: 40%;
 left: 0%;
 height: 25%;
 width: 100%;
 text-align: center;
 line-height: 0;
}
#lightbox-nav {
 position: absolute;
 top: 0;
 left: 0;
 height: 100%;
 width: 100%;
 z-index: 10005;
}
#lightbox-container-image-box > #lightbox-nav { left: 0; }
#lightbox-nav a { outline: none;}
#lightbox-nav-btnPrev, #lightbox-nav-btnNext {
 width: 49%;
 height: 100%;
 zoom: 1;
 display: block;
}
#lightbox-nav-btnPrev { 
 left: 0; 
 float: left;
}
#lightbox-nav-btnNext { 
 right: 0; 
 float: right;
}
#lightbox-container-image-data-box {
 font: 10px Verdana, Helvetica, sans-serif;
 background-color: #fff;
 margin: 0 auto;
 line-height: 1.4em;
 overflow: auto;
 width: 100%;
 padding: 0 10px;
}
#lightbox-container-image-data { 
 color: #666; 
}
#lightbox-container-image-data #lightbox-image-details { 
 width: 70%; 
 float: left; 
 text-align: left; 
} 
#lightbox-image-details-caption { font-weight: bold; }
#lightbox-image-details-currentNumber {
 display: block; 
 clear: left; 
 padding-bottom: 1.0em; 
} 
#lightbox-secNav-btnClose {
 width: 70px; 
 float: right;
 padding-bottom: 0.7em; 
}
</style>
</header:js>

<script type="text/javascript">
$(document).ready(function(){
$(".goodsDetail").click(function(event){
if (window.ajax_request !== undefined){
    if ($(event.target).is(".buttonSend.addcart") && window.ajax_request == true){
        var real_goods = $(event.currentTarget).find('.goodsLinkPhoto');
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
});
</script>
 
<div class="content e_shopvit" > 
    <div class="goodsContentDet">
        <?php if($section->parametrs->param246=='Y'): ?>
            <div class="goodsPath">
                <?php if($old_format==0): ?>
                    <a class="goodsPathRoot" href="<?php echo seMultiDir()."/".$_page."/" ?>"><?php echo $section->parametrs->param234 ?></a>
                    <span class="goodsPathSepar">/</span>&nbsp;
                    <?php foreach($section->pathg as $pth): ?>
                        <a class="goodsLinkPath" href="<?php echo seMultiDir()."/".$_page."/" ?>cat/<?php echo $pth->cat ?>/"><?php echo $pth->cat_nm ?></a>&nbsp;
                        <span class="goodsPathSepar">/</span>&nbsp;
                    
<?php endforeach; ?>
                    <span class="goodsActivePath"><?php echo $price_fields_name ?></span>
                <?php else: ?>   
                    <a class="goodsPathRoot" href="<?php echo seMultiDir()."/".$_page."/" ?>"><?php echo $section->parametrs->param234 ?></a><?php echo $cat_path ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="goodsDetail">
            
            <h4 class="goodsDetTitle"><?php echo $price_fields_name ?></h4>
            <?php if($section->parametrs->param126=='Y'): ?>
                <h4 class="goodsDetArticle"><?php echo $section->parametrs->param22 ?>&nbsp;<?php echo $price_fields_code ?></h4>
            <?php endif; ?>
            
            
            <!-- Основная фотография -->
            <div class="goodsLinkPhoto">
            <?php if($section->parametrs->param282=='N'): ?>      <!-- большое изображение -->
                <img class="goodsPhotoBig" src="<?php echo $img_full ?>" border="0" title="<?php echo $price_img_alt ?>" alt="<?php echo $price_img_alt ?>">
            <?php endif; ?> 
            
            <?php if($section->parametrs->param282=='L'): ?>    <!-- Использовать экранную лупу -->
                <a class="cloud-zoom" href="<?php echo $img_full ?>" id="zoom1" rel="position: 'right', adjustX: 10, adjustY: 0, showTitle: false, zoomWidth: <?php echo $section->parametrs->param284 ?>">
                    <img class="goodsPhoto" src="<?php echo $img_mid ?>" border="0" alt="<?php echo $price_img_alt ?>" title="<?php echo $price_img_alt ?>">
                </a>
            <?php endif; ?>
            
            <?php if($section->parametrs->param282=='Z'): ?>   <!-- Использовать всплывающее окно (jquery lightbox plugin) при клике на фото -->            
                <div id="photo">
                    <a id="lightbox-foto1" rel="lightbox-foto" href="<?php echo $img_full ?>" title="<?php echo $price_img_alt ?>">
                        <img class="goodsPhoto" src="<?php echo $img_mid ?>" border="0" alt="<?php echo $price_img_alt ?>">
                    </a>
                </div>
            <?php endif; ?>
            </div>
                        
            <?php if($section->parametrs->param266=='Y'): ?>
                <div class="goodsDetRating">
                    
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param129=='Y'): ?>
                <?php if($price_fields_manufacturer!=''): ?>
                    <div class="manuf">
                        <label class="manuftitle"><?php echo $section->parametrs->param135 ?></label>
                        <span class="manufvar"><?php echo $price_fields_manufacturer ?>&nbsp;</span>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php if($section->parametrs->param217!='N'): ?>
                <div class="commentBlock">
                    <a href="#comment" class="read"><?php echo $section->parametrs->param218 ?></a>&nbsp;
                    <?php if($accessuser): ?>
                        <a href="#addcomment" class="add"><?php echo $section->parametrs->param219 ?></a>
                    <?php endif; ?>
                    
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param128=='Y'): ?>

                <?php if($price_fields_nullprice=='0'): ?>
            
                    <div class="presence">
                        <span class="goodsDetCountNaim"><?php echo $section->parametrs->param136 ?></span>
                        <span class="goodsDetCountStyle" id="count_<?php echo $razdel ?>_<?php echo $id_goods ?>"><?php echo $price_fields_count ?></span>
                    </div>

                <?php else: ?>
           
                    <div class="objectNullPrice"><?php echo $section->parametrs->param294 ?></div> 

                <?php endif; ?>
            
                    
            <?php endif; ?>
            <div class="goodsDetPriceBox">
                <form class="form_addCart" name="formtop" style="margin:0px;" method="post" action="">
                    <a name="f1"></a>
                    <?php if($goods_params!=""): ?>
                        <div class="divparam">
                            <?php echo $goods_params ?>
                        </div>
                    <?php endif; ?> 
                    <?php if($price_fields_nullprice=='0'): ?>
                        <div class="price">
                            <span class="goodsDetPriceNaim"><?php echo $priceHeaderDet ?></span>
                            <span class="goodsDetPriceStyle "><?php echo $price_fields_price ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="goodsDetButtonBox">
                        <input type="hidden" name="addcart" value="<?php echo $price_fields_id ?>">
                        <input type="hidden" name="listcartparam" value="<?php echo $list_cart_param ?>">
                        <span class="addcart_<?php echo $razdel ?>_<?php echo $id_goods ?>" style="<?php echo $show_addcart ?>">
                            <a class="buttonSend addcart" href="javascript:void(0);" title="<?php echo $section->parametrs->param9 ?>"><?php echo $section->parametrs->param3 ?></a>
                        </span>
                        <a class="buttonSend back" href="javascript:history.back();"><?php echo $section->parametrs->param11 ?></a>
                    </div>
                </form>
            </div>
            <?php if($section->parametrs->param133!='N'): ?>
                <div class="goodsDetNote"><?php echo $price_fields_note ?></div>
            <?php endif; ?>
            <!-- доп картинки -->
            <?php if($extra_photo!='0'): ?>
                <div class="morephotos">
                    <h3 class="goodsMorephotoHat"><?php echo $section->parametrs->param26 ?></h3>
                    <div id="photo">
                        <?php foreach($section->photos as $foto): ?>
                            <a rel="lightbox-foto" href="<?php echo $foto->image ?>" title="<?php echo $foto->title ?>" style="text-decoration: none">
                                <img src="<?php echo $foto->image_prev ?>" class="imgAll" alt="<?php echo $foto->title ?>" border="0">
                            </a>
                        
<?php endforeach; ?>
                    </div>
                </div>            
            <?php endif; ?>
            <!--       подключение вкладок subpage name=vkladki -->
            <?php if($section->parametrs->param134!='N'): ?> 
                <div class="goodsDetText"><?php echo $price_fields_text ?></div>
            <?php endif; ?>
        </div>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_9.php")) include $__MDL_ROOT."/php/subpage_9.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_9.tpl")) include $__MDL_ROOT."/tpl/subpage_9.tpl"; ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_10.php")) include $__MDL_ROOT."/php/subpage_10.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_10.tpl")) include $__MDL_ROOT."/tpl/subpage_10.tpl"; ?>
            <?php if($section->parametrs->param217!='N'): ?>
                <?php if(file_exists($__MDL_ROOT."/php/subpage_5.php")) include $__MDL_ROOT."/php/subpage_5.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_5.tpl")) include $__MDL_ROOT."/tpl/subpage_5.tpl"; ?>
            <?php endif; ?>
    </div>
</div>
