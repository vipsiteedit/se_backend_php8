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
                $(".goodsDetRating").append('<span class="goodsDetVotesTitle"><?php echo $section->language->lang052 ?></span><a href="" class="goodsDetUp" title="<?php echo $section->language->lang053 ?>"><?php echo $section->language->lang053 ?></a><span class="goodsDetVotes"></span><a href="" class="goodsDetDown" title="<?php echo $section->language->lang054 ?>"><?php echo $section->language->lang054 ?></a><div class="goodsDetRatingErr"></div>');
                $(".goodsDetRating > .goodsDetRatingErr").hide();
                $.post("?ajax<?php echo $razdel ?>", {initvote: "1"}, onInitVotesSuccess);
                function onInitVotesSuccess(data) {  
                    $(".goodsDetRating > .goodsDetVotes").text(data);    
                    return false;
                }
                function errorReportingAuth() {
                    $(".goodsDetRating > .goodsDetRatingErr").text("<?php echo $section->language->lang055 ?>");        
                    $(".goodsDetRating > .goodsDetRatingErr").show();    
                    setTimeout(function(){ $(".goodsDetRating > .goodsDetRatingErr").hide(); return false;}, 3000);
                    return false;
                }
                function errorReportingDouble() {
                    $(".goodsDetRating > .goodsDetRatingErr").text("<?php echo $section->language->lang056 ?>");        
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
    createLBox("<?php echo $section->language->lang040 ?>", "<?php echo $section->language->lang041 ?>", "[module_url]");
 
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
<div class="content e_shopvit" > 
    <div class="goodsContentDet">
        <?php if($section->parametrs->param246=='Y'): ?>
            <div class="goodsPath">
                <?php if($old_format==0): ?>
                    <a class="goodsPathRoot" href="<?php echo seMultiDir()."/".$_page."/" ?>"><?php echo $section->language->lang020 ?></a>
                    <span class="goodsPathSepar">/</span>&nbsp;
                    <?php foreach($section->pathg as $pth): ?>
                        <a class="goodsLinkPath" href="<?php echo seMultiDir()."/".$_page."/" ?>cat/<?php echo $pth->cat ?>/"><?php echo $pth->cat_nm ?></a>&nbsp;
                        <span class="goodsPathSepar">/</span>&nbsp;
                    
<?php endforeach; ?>
                    <span class="goodsActivePath"><?php echo $price_fields_name ?></span>
                <?php else: ?>   
                    <a class="goodsPathRoot" href="<?php echo seMultiDir()."/".$_page."/" ?>"><?php echo $section->language->lang020 ?></a><?php echo $cat_path ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="goodsDetail">
            
            <<?php echo $section->parametrs->param297 ?> class="goodsDetTitle"><?php echo $price_fields_name ?></<?php echo $section->parametrs->param297 ?>>
            <?php if($section->parametrs->param126=='Y'): ?>
                <h4 class="goodsDetArticle"><?php echo $section->language->lang021 ?>&nbsp;<?php echo $price_fields_code ?></h4>
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
                        <label class="manuftitle"><?php echo $section->language->lang022 ?></label>
                        <span class="manufvar"><?php echo $price_fields_manufacturer ?>&nbsp;</span>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php if($section->parametrs->param217!='N'): ?>
                <div class="commentBlock">
                    <a href="#comment" class="read"><?php echo $section->language->lang042 ?></a>&nbsp;
                    <?php if($accessuser): ?>
                        <a href="#addcomment" class="add"><?php echo $section->language->lang043 ?></a>
                    <?php endif; ?>
                    
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param128=='Y'): ?>

                <?php if($price_fields_nullprice=='0'): ?>
            
                    <div class="presence">
                        <span class="goodsDetCountNaim"><?php echo $section->language->lang032 ?></span>
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
                        <button class="buttonSend addcart" title="<?php echo $section->language->lang015 ?>" style="<?php echo $show_addcart ?>"><?php echo $section->language->lang037 ?></button>
                        <button class="buttonSend back" onclick="history.back();return false;"><?php echo $section->language->lang035 ?></button>
                    </div>
                </form>
            </div>
            <?php if($section->parametrs->param133!='N'): ?>
                <div class="goodsDetNote"><?php echo $price_fields_note ?></div>
            <?php endif; ?>
            <!-- доп картинки -->
            <?php if($extra_photo!='0'): ?>
                <div class="morephotos">
                    <h3 class="goodsMorephotoHat"><?php echo $section->language->lang033 ?></h3>
                    <div id="photo">
                        <?php foreach($section->photos as $foto): ?>
                            <a rel="lightbox-foto" href="<?php echo $foto->image ?>" title="<?php echo $foto->title ?>" style="text-decoration: none">
                                <img src="<?php echo $foto->image_prev ?>" class="imgAll" alt="<?php echo $foto->title ?>" border="0">
                            </a>
                        
<?php endforeach; ?>
                    </div>
                </div>            
            <?php endif; ?>

            <?php if($section->parametrs->param134!='N'): ?> 
                <div class="goodsDetText"><?php echo $price_fields_text ?></div>
            <?php endif; ?>
        </div>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_analogs.php")) include $__MDL_ROOT."/php/subpage_analogs.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_analogs.tpl")) include $__data->include_tpl($section, "subpage_analogs"); ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_accomp.php")) include $__MDL_ROOT."/php/subpage_accomp.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_accomp.tpl")) include $__data->include_tpl($section, "subpage_accomp"); ?>
            <?php if($section->parametrs->param217!='N'): ?>
                <?php if(file_exists($__MDL_ROOT."/php/subpage_comment.php")) include $__MDL_ROOT."/php/subpage_comment.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_comment.tpl")) include $__data->include_tpl($section, "subpage_comment"); ?>
            <?php endif; ?>
    </div>
</div>
