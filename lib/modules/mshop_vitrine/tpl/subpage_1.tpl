<!-- Subpage 1. Подробно о товаре и сопутствующие товары с аналогами-->
<?php if(file_exists($__MDL_ROOT."/php/subpage_7.php")) include $__MDL_ROOT."/php/subpage_7.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_7.tpl")) include $__MDL_ROOT."/tpl/subpage_7.tpl"; ?>
<header:js>
<link rel="stylesheet" href="[module_url]cloud-zoom.css">
<link rel="stylesheet" href="/lib/js/ui/themes/ui-lightness/jquery.ui.all.css">

[js:ui/jquery.ui.min.js]
<script type="text/javascript" src="[module_url]cloud-zoom.1.0.2.min.js"></script>
<script type='text/javascript' src='[module_url]jquery.lightbox.js'></script>
</header:js>

    <?php if($section->parametrs->param266=='Y'): ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".goodsDetRating").append('<span class="goodsDetVotesTitle"><?php echo $section->parametrs->param267 ?></span><a href="" class="goodsDetUp" title="<?php echo $section->parametrs->param260 ?>"><?php echo $section->parametrs->param262 ?></a><span class="goodsDetVotes"></span><a href="" class="goodsDetDown" title="<?php echo $section->parametrs->param261 ?>"><?php echo $section->parametrs->param263 ?></a><div class="goodsDetRatingErr"></div>');
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
$("#photo a").lightBox({ 
txtImage:'&nbsp;<?php echo $section->parametrs->param248 ?>',
txtOf:'<?php echo $section->parametrs->param250 ?>',
overlayOpacity: 0.6, 
fixedNavigation:true, 
imageLoading: '[module_url]lightbox-ico-loading.gif', 
imageBtnPrev: '[module_url]foto_arrow_left.png', 
imageBtnNext: '[module_url]foto_arrow_right.png', 
imageBtnClose: '[module_url]foto_close.gif', 
imageBlank: '[module_url]lightbox-blank.gif' 
});
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
            
            
            <!-- Основная фотография с экранной лупой или без нее -->
            <div class="goodsLinkPhoto">
            <?php if(($section->parametrs->param282=='N')||($flag_img_not_exists)): ?>      <!-- Не использовать экранную лупу - сразу большое изображение -->
                <img class="goodsPhoto" src="<?php echo $img_full ?>" border="0" title="<?php echo $price_img_alt ?>" alt="<?php echo $price_img_alt ?>">
            <?php else: ?> 
                <?php if($section->parametrs->param282=='L'): ?>  <!-- Использовать экранную лупу -->
                    <a class="cloud-zoom" href="<?php echo $img_full ?>" id="zoom1" rel="position: 'right', adjustX: 10, adjustY: 0<?php if($section->parametrs->param283=='N'): ?>, showTitle: false<?php endif; ?><?php if($section->parametrs->param284!='auto'): ?>, zoomWidth: <?php echo $section->parametrs->param284 ?><?php endif; ?>"> 
                        <img class="goodsPhoto" src="<?php echo $img_mid ?>" alt="<?php echo $price_img_alt ?>" title="<?php echo $price_img_alt ?>">
                    </a>
                <?php else: ?>                <!-- Использовать всплывающее окно (jquery lightbox plugin) при клике на фото -->
                    <div id="photo">
                        <a rel="lightbox-foto" href="<?php echo $img_full ?>"<?php if($section->parametrs->param283=='Y'): ?> title="<?php echo $price_img_alt ?>"<?php endif; ?>>
                            <img class="goodsPhoto" src="<?php echo $img_mid ?>" alt="<?php echo $price_img_alt ?>">
                        </a>
                    </div>
                <?php endif; ?>
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
                <div class="presence">
                    <span class="goodsDetCountNaim"><?php echo $section->parametrs->param136 ?></span>
                    <span class="goodsDetCountStyle" id="count_<?php echo $razdel ?>_<?php echo $id_goods ?>"><?php echo $price_fields_count ?></span>
                </div>
            <?php endif; ?>
            <div class="goodsDetPriceBox">
                <form name="formtop" style="margin:0px;" method="post" action="">
                    <a name="f1"></a>
                    <?php if($section->parametrs->param231!='N'): ?> <!-- Использовать старый механизм дополнительных параметров -->
                        <?php if($originalcount!=0): ?>
                            <div class="divparam">
                                <label class="goodsParamTitle"><?php echo $section->parametrs->param178 ?></label>
                                <select class="goodsParamSelect" name="addcartparam[]">
                                    
                                    <?php echo $original ?>
                                </select>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>               <!-- Использовать НОВЫЙ механизм дополнительных параметров -->
                        <?php if($goods_params!=""): ?>
                            <div class="divparam">
                                <?php echo $goods_params ?>
                            </div>
                        <?php endif; ?> 
                    <?php endif; ?>
                    <!--/div-->
                    <div class="price">
                        <span class="goodsDetPriceNaim"><?php echo $priceHeaderDet ?></span>
                        <span class="goodsDetPriceStyle "><?php echo $price_fields_price ?></span>
                    </div>
                    <div class="goodsDetButtonBox">
                        <input type="hidden" name="addcart" value="<?php echo $price_fields_id ?>">
                        <span class="addcart_<?php echo $razdel ?>_<?php echo $id_goods ?>" style="<?php echo $show_addcart ?>">
                            <a class="buttonSend addcart" href="#1" onclick="formtop.submit();" title="<?php echo $section->parametrs->param9 ?>"><?php echo $section->parametrs->param3 ?></a>
                        </span>
                        <a class="buttonSend back" href="javascript:window.history.back()"><?php echo $section->parametrs->param11 ?></a>
                    </div>
                </form>
            </div>
            <?php if($section->parametrs->param133!='N'): ?>
                <div class="goodsDetNote"><?php echo $price_fields_note ?></div>
            <?php endif; ?>
            <?php if($morephoto!=''): ?>
                <?php if(file_exists($__MDL_ROOT."/php/subpage_2.php")) include $__MDL_ROOT."/php/subpage_2.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_2.tpl")) include $__MDL_ROOT."/tpl/subpage_2.tpl"; ?>
            <?php endif; ?>
        </div>
<!--        
        <?php if($section->parametrs->param258!='N'): ?>                    < Использовать режим Вкладки >
            <div style="display: inline-block; width:100%;" id="tabs-good">
                <ul>
                    <?php if($section->parametrs->param134!='N'): ?>        < Отображать Подробное описание >
                        <li><a href="#tabs-text"><?php echo $section->parametrs->param256 ?></a></li>
                    <?php endif; ?>
                    <?php if($section->parametrs->param271!='N'): ?>        < Отображать спецификацию >
                        <li><a href="#tabs-spec"><?php echo $section->parametrs->param270 ?></a></li>
                    <?php endif; ?>
                </ul>
        <?php endif; ?>                           
        <?php if($section->parametrs->param134!='N'): ?>                    < Отображать Подробное описание >
            <?php if($section->parametrs->param258!='N'): ?>                < Использовать режим Вкладки >
                <div id="tabs-text" style="max-height:500px; overflow:auto;">
            <?php else: ?>
                <div class="goodsDetText">
            <?php endif; ?>
            <?php echo $price_fields_text ?>
            </div>
            <?php endif; ?>
            <?php if($section->parametrs->param271!='N'): ?>                < Отображать спецификацию >
                <?php if($section->parametrs->param258!='N'): ?>            < Использовать режим Вкладки >
                    <div id="tabs-spec" style="max-height:500px; overflow:auto;">
                <?php else: ?>
                    <div class="goodsDetSpec">
                <?php endif; ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_11.php")) include $__MDL_ROOT."/php/subpage_11.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_11.tpl")) include $__MDL_ROOT."/tpl/subpage_11.tpl"; ?>
                    </div>
            <?php endif; ?>
            <?php if($section->parametrs->param258!='N'): ?>                < Использовать режим Вкладки >
            </div>
                    <script type="text/javascript">
                    <
                    var $tabs = $( "#tabs-good" ).tabs();
                    $tabs.tabs('select', 0);
                    >
                    </script>
            <?php endif; ?>
-->
            <?php if($section->parametrs->param134!='N'): ?> 
                <div class="goodsDetText"><?php echo $price_fields_text ?></div>
            <?php endif; ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_9.php")) include $__MDL_ROOT."/php/subpage_9.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_9.tpl")) include $__MDL_ROOT."/tpl/subpage_9.tpl"; ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_10.php")) include $__MDL_ROOT."/php/subpage_10.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_10.tpl")) include $__MDL_ROOT."/tpl/subpage_10.tpl"; ?>
            <?php if($section->parametrs->param217!='N'): ?>
                <?php if(file_exists($__MDL_ROOT."/php/subpage_5.php")) include $__MDL_ROOT."/php/subpage_5.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_5.tpl")) include $__MDL_ROOT."/tpl/subpage_5.tpl"; ?>
            <?php endif; ?>
    </div>
</div>
