<?php if(empty($quickview)): ?>
<header:js>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
[lnk:rouble/rouble.css]
<?php if(strval($section->parametrs->param282)=='Y'): ?> 
[lnk:fancybox2/jquery.fancybox.css]    
[lnk:fancybox2/helpers/jquery.fancybox-thumbs.css] 
<?php endif; ?>
</header:js>
<?php endif; ?>
<footer:js>
<?php if(empty($quickview)): ?>
[js:jquery/jquery.min.js]
[js:jquery/jquery.mousewheel.js]
<?php if(strval($section->parametrs->param319)=='Y'): ?> 
[js:jquery/jcarousellite.js]
<?php endif; ?>
<?php if(strval($section->parametrs->param311)=='Y'): ?> 
[js:jquery/zoomsl.min.js]                   
<?php endif; ?>
<?php if(strval($section->parametrs->param282)=='Y'): ?> 
[js:fancybox2/jquery.fancybox.pack.js] 
[js:fancybox2/helpers/jquery.fancybox-thumbs.js] 
<?php endif; ?>
[include_js({
    id: <?php echo $section->id ?>,
    ajax_url: '?ajax<?php echo $section->id ?>',
    param321: '<?php echo $section->parametrs->param321 ?>',
    param307: '<?php echo $section->parametrs->param307 ?>',
    param308: '<?php echo $section->parametrs->param308 ?>',
    param309: '<?php echo $section->parametrs->param309 ?>',
    param233: '<?php echo $section->parametrs->param233 ?>',
    show_product: {id: '<?php echo $product_id ?>', name: '<?php echo $product_name ?>', category: '<?php echo $product_group ?>', brand: '<?php echo $product_brand ?>', variant: ''}
})]
<?php endif; ?>
<?php if(!empty($quickview)): ?>
<script type="text/javascript">
$('.colorFeature .itemValue ').each(function(){
    var colors = $('.featureValue',this).css('background-color');
    var arr_colors = colors.replace("rgb(",'').replace(")",'').split(',');
    if(arr_colors[0] > 239 && arr_colors[1] > 239 && arr_colors[2] > 239) {
        $('.featureValue',this).addClass('featureValueWhite');
        if($('>input',this).is(':checked')){
            $('>input',this).closest('.itemValue').addClass('selected');
        }
    }
});
$('.itemValue>input').click(function(){
    $('.itemValue.selected').removeClass('selected');
    $(this).closest('.itemValue').addClass('selected');
});
</script>
<?php endif; ?>
<script type="text/javascript">
$(window).resize(function(){
   resizeImg('.specialImage .objectImage', '.specialImage');
   setTimeout("sizeHeightBlock('.ashopvit_compl .goodsLinkPhoto', '.ashopvit_compl .goodsPhoto')",200);
});
resizeImg('.specialImage .objectImage', '.specialImage');
$('.ashopvit_compl .goodsPhoto').one('load', function() {
    sizeHeightBlock('.ashopvit_compl .goodsLinkPhoto', '.ashopvit_compl .goodsPhoto');
}).each(function() {
    if(this.complete) $(this).load();
});
</script>
</footer:js> 
<?php if(empty($quickview)): ?><div class="<?php if(strval($section->parametrs->param344)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>"><?php endif; ?>
<div class="content ashopvit_compl<?php if(!empty($quickview)): ?> quickShow<?php endif; ?>" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" >
    <div id="product" class="goodsContentDet <?php if(strval($section->parametrs->param85)=='Y'): ?>notAjaxCart<?php endif; ?>">
        <?php if(empty($quickview)): ?>
        <?php if(strval($section->parametrs->param246)!='N'): ?>
            <ol class="goodsPath breadcrumb">
                <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                    <a class="goodsPathRoot" href="<?php echo $__data->getLinkPageName() ?>" itemprop="url"><span itemprop="title"><?php echo $section->language->lang019 ?></span></a>
                </li>
                <?php foreach($section->pathg as $pth): ?>
                    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                        <a class="goodsLinkPath" href="<?php echo $__data->getLinkPageName() ?>cat/<?php echo $pth->cat ?>/" itemprop="url"><span itemprop="title"><?php echo $pth->cat_nm ?></span></a>
                    </li>
                
<?php endforeach; ?>
                <?php if(strval($section->parametrs->param246)=='A'): ?>
                    <li class="goodsLinkPath active"><?php echo $product_name ?></li>    
                <?php endif; ?>
            </ol>
        <?php endif; ?>
        <?php endif; ?>
        <div class="goodsDetail" itemscope itemtype="http://schema.org/Product">
             <div class="goods-det-total">
            <div class="goods-det-image-block">
                 <div class="top-det-box">
                <div class="brand-name-box">
                <?php if(strval($section->parametrs->param129)=='Y' && $product_brand!=''): ?>
                <div class="goodsBrand">
                    <a href="<?php echo $brand_link ?>" class="brandValue" itemprop="brand"><?php echo $product_brand ?></a>
                </div>
                <?php endif; ?>
                <?php if(strval($section->parametrs->param329)!='N' || $quickview!=''): ?>
                <div class="goodsDetTitle" itemprop="name"><?php echo $product_name ?></div>
                <?php endif; ?>
                </div>
                <?php if(strval($section->parametrs->param126)=='Y' && $product_code!=''): ?>
                <div class="goodsDetArticle">
                    <label class="articleLabel"><?php echo $section->language->lang005 ?></label>
                    <span class="articleValue"><?php echo $product_code ?></span>
                </div>
                <?php endif; ?>
                </div>
                <?php if(strval($section->parametrs->param266)=='Y'): ?>
                <div class="goodsRating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                    <meta itemprop="worstRating" content="1">
                    <meta itemprop="bestRating" content="5">
                    <a class="anchor" href="<?php if(!empty($quickview)): ?><?php echo $product_link ?><?php endif; ?>#reviews"><span class="ratingOff" title="<?php echo $section->language->lang058 ?> <?php echo $product_rating ?> <?php echo $section->language->lang059 ?> 5">
                        <span class="ratingOn" style="width:<?php echo $rating_percent ?>%;"></span>
                    </span></a>
                    <span class="ratingValue" itemprop="ratingValue"><?php echo $product_rating ?></span>
                    <span class="marks">(<a class="marksLabel anchor" href="<?php if(!empty($quickview)): ?><?php echo $product_link ?><?php endif; ?>#reviews"><?php echo $section->language->lang056 ?></a> <span class="marksValue" itemprop="reviewCount"><?php echo $product_marks ?></span>)</span>
                </div>
                <?php endif; ?>
                <div class="goodsLinkPhoto">
                    <a href="<?php echo $img_full ?>" title="<?php echo $price_img_alt ?>"  target="_blank">
                        <img class="goodsPhoto img-responsive center-block" src="<?php echo $img_mid ?>" style="max-width:100%;" alt="<?php echo $price_img_alt ?>" data-large="<?php echo $img_full ?>" itemprop="image">
                    </a>
                </div>
                <?php if(strval($section->parametrs->param320)!='N'): ?>
                    <div class="morephotos">
                        <?php if(strval($section->parametrs->param319)=='Y'): ?> 
                            <span class="prev">&lt;</span>
                            <div class="photoSlider">
                        <?php endif; ?>
                        <ul class="imageList">
                            <?php foreach($section->photos as $foto): ?>
                                <li class="imageItem<?php if($foto->image==$img_full): ?> activeImg<?php endif; ?>" data-id="<?php echo $foto->id ?>">
                                    <a rel="imagebox" href="<?php echo $foto->image ?>" data-middle="<?php echo $foto->image_mid ?>" <?php if(!empty($foto->title)): ?>title="<?php echo $foto->title ?>"<?php endif; ?> target="_blank">
                                        <img src="<?php echo $foto->image_prev ?>" class="imgAll" border="0" style="<?php echo $img_prev_style ?>" <?php if(!empty($foto->alt)): ?>alt="<?php echo $foto->alt ?>"<?php endif; ?>>
                                    </a>
                                </li>
                            
<?php endforeach; ?>
                        </ul>
                        <?php if(strval($section->parametrs->param319)=='Y'): ?> 
                            </div>
                            <span class="next">&gt;</span>
                        <?php endif; ?>
                    </div>            
                <?php endif; ?>
            </div>   
            
            <div class="goods-det-info-block">
                <?php if(strval($section->parametrs->param128)=='Y'): ?>
                <div class="objectPresence">
                     <?php if($product_count != 0): ?><label class="presenceLabel"><?php echo $section->language->lang009 ?></label><?php endif; ?>
                     <span class="presenceValue"><?php echo $product_count ?></span>
                </div>
                <?php endif; ?>
            <?php if(strval($section->parametrs->param133)!='N' && $product_note!=''): ?>
                <div class="goodsDetNote" itemprop="description"><?php echo $product_note ?></div>
            <?php endif; ?>
            <?php echo $product_modifications ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_option.php")) include $__MDL_ROOT."/php/subpage_option.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_option.tpl")) include $__data->include_tpl($section, "subpage_option"); ?>
            
            <div class="goodsDetPriceBox" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <form class="form_addCart" method="post" action="">
                    <?php if(strval($section->parametrs->param138)=='Y'): ?>
                        <div class="addCount form-group">
                            <label class="titleAddCount" for="add_count"><?php echo $section->language->lang104 ?></label>
                            <div class="count-inp-box">
                                <input id="add_count" class="cartscount" type="number" min="<?php echo $step ?>" name="addcartcount" data-step="<?php echo $step ?>" value="<?php echo $step ?>" size="4">
                                <?php if(!empty($product_measure)): ?>
                                        <span class="measure"><?php echo $product_measure ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if(strval($section->parametrs->param342)=='Y' || strval($section->parametrs->param343)=='Y'): ?>
                        <div class="list-paydel-box">
                        <?php if(strval($section->parametrs->param342)=='Y'): ?>
                            <div class="list-payment-box">
                                <i class="fa fa-credit-card-alt" aria-hidden="true"></i><a href="javascript:void(0);" id="clickpay" class="link-paydel click-payment"><?php echo $section->language->lang110 ?></a>     
                            </div>
                            <div class="payment-section paydel-section" style="display:none;">
                                <div class="paydel-triangle"></div>
                                <?php if(file_exists($__MDL_ROOT."/php/subpage_payments.php")) include $__MDL_ROOT."/php/subpage_payments.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_payments.tpl")) include $__data->include_tpl($section, "subpage_payments"); ?>
                            </div>
                        <?php endif; ?>
                        <?php if(strval($section->parametrs->param343)=='Y'): ?>
                            <div class="list-delivery-box">
                                <i class="fa fa-truck" aria-hidden="true"></i><a href="#" id="clickdelv" class="link-paydel click-delivery"><?php echo $section->language->lang111 ?></a>    
                            </div>
                            <div class="delivery-section paydel-section" style="display:none;">
                                <?php if(file_exists($__MDL_ROOT."/php/subpage_deliveries.php")) include $__MDL_ROOT."/php/subpage_deliveries.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_deliveries.tpl")) include $__data->include_tpl($section, "subpage_deliveries"); ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="goodsPrice<?php if(empty($realprice)): ?> nullPrice<?php endif; ?>">
                    <!--label class="priceLabel"><?php echo $section->language->lang008 ?></label-->
                    <div class="priceValue">
                        <?php if(strval($section->parametrs->param113)=='Y' && $product_oldprice!=''): ?>
                            <div class="oldPrice"><?php echo $product_oldprice ?></div>
                        <?php endif; ?>
                        <div class="newPrice"><?php echo $product_price ?></div>
                        <meta itemprop="price" content="<?php echo $realprice ?>">
                        <meta itemprop="priceCurrency" content="<?php echo $pricemoney ?>">
                    </div>
                    
                    <div class="buttonBox">
                        <input type="hidden" name="addcart" value="<?php echo $product_id ?>">
                        <?php if($maxcnts==0 && strval($section->parametrs->param336)=='Y'): ?>
                            <button type="button" class="buttonSend btnPreorder btn btn-default"><i class="fa fa-briefcase sign-preorder" aria-hidden="true"></i><span class="text-preorder"><?php echo $section->language->lang107 ?></span></button>
                        <?php endif; ?>
                        <button class="buttonSend addcart btn btn-default<?php if(!empty($product_incart)): ?> inCartActive<?php endif; ?>" title="<?php echo $section->language->lang022 ?>" <?php if(empty($maxcnts)): ?><?php if(strval($section->parametrs->param233)=='Y'): ?>disabled<?php else: ?>style="display:none;"<?php endif; ?><?php endif; ?>><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="label-addcart"><?php echo $section->language->lang033 ?></span></button>
                    </div>
                    </div>
                    <?php if($quickview==''): ?>
                            <?php if(strval($section->parametrs->param340)=='Y'): ?>
                                <div class="back-block-box">
                                    <button class="buttonSend back btn btn-link" onclick="history.back();return false;"><i class="fa fa-arrow-left" aria-hidden="true"></i><span class="sm-text"><?php echo $section->language->lang031 ?></span><span class="ln-text"><?php echo $section->language->lang109 ?></span></button>
                                </div>
                            <?php endif; ?>
                    <?php else: ?>
                            <a class="lnkDetail btn btn-link" href="<?php echo $product_link ?><?php echo $section->parametrs->param330 ?>"><?php echo $section->language->lang032 ?><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                    <?php endif; ?>
                </form>
            </div>
            <?php if(strval($section->parametrs->param269)=='Y'): ?>
                <div class="blockCompare">
                <label title="<?php echo $section->language->lang095 ?>">
                    <input class="compare" type="checkbox" data-id="<?php echo $product_id ?>"<?php if(!empty($product_compare)): ?> checked<?php endif; ?>>
                    <span class="compareLabel"><span class="compare-stext"<?php if(!empty($product_compare)): ?> style="display:none;"<?php endif; ?>><?php echo $section->language->lang098 ?></span></span>
                </label>
                <a class="lnkInCompare" href="<?php echo seMultiDir()."/".$section->parametrs->param331."/" ?>" title="<?php echo $section->language->lang096 ?>"<?php if(empty($product_compare)): ?> style="display:none;"<?php endif; ?>><?php echo $section->language->lang097 ?></a>
                <a class="del-compare" href="<?php echo seMultiDir()."/".$section->parametrs->param331."/" ?>?clear_compare" title="<?php echo $section->language->lang112 ?>"<?php if(empty($product_compare)): ?> style="display:none;"<?php endif; ?>><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            </div>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param334)=='Y'): ?>
                <div class="blockDelivery">
                    <label>Доставка:</label>
                    <div class="">
                    </div>
                </div>
            <?php endif; ?>
            <?php if(empty($quickview)): ?>
                <?php if(strval($section->parametrs->param312)!='N'): ?>
                        <?php if(file_exists($__MDL_ROOT."/php/subpage_socialbuttons.php")) include $__MDL_ROOT."/php/subpage_socialbuttons.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_socialbuttons.tpl")) include $__data->include_tpl($section, "subpage_socialbuttons"); ?>
                <?php endif; ?>
                <?php endif; ?>
            </div>
            </div>
            <?php if(empty($quickview)): ?>
                <?php if(strval($section->parametrs->param258)=='Y'): ?>
                    <div class="tabs" style="display:none;">
                        <ul class="tabsNav"></ul>
                        <div class="tabsContent"></div>
                    </div>
                <?php endif; ?>
                <?php if(strval($section->parametrs->param134)!='N'): ?> 
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_text.php")) include $__MDL_ROOT."/php/subpage_text.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_text.tpl")) include $__data->include_tpl($section, "subpage_text"); ?>
                <?php endif; ?>
                <?php if(strval($section->parametrs->param306)!='N'): ?> 
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_features.php")) include $__MDL_ROOT."/php/subpage_features.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_features.tpl")) include $__data->include_tpl($section, "subpage_features"); ?>
                <?php endif; ?>
                <?php if(strval($section->parametrs->param217)!='N'): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_comment.php")) include $__MDL_ROOT."/php/subpage_comment.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_comment.tpl")) include $__data->include_tpl($section, "subpage_comment"); ?>
                <?php endif; ?> 
                <?php if(strval($section->parametrs->param314)!='N'): ?> 
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_reviews.php")) include $__MDL_ROOT."/php/subpage_reviews.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_reviews.tpl")) include $__data->include_tpl($section, "subpage_reviews"); ?>
                <?php endif; ?>
                <?php if(strval($section->parametrs->param350)!='N'): ?> 
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_files.php")) include $__MDL_ROOT."/php/subpage_files.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_files.tpl")) include $__data->include_tpl($section, "subpage_files"); ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
            <?php if(empty($quickview)): ?>
                <?php if(strval($section->parametrs->param316)!='N'): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_accomp.php")) include $__MDL_ROOT."/php/subpage_accomp.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_accomp.tpl")) include $__data->include_tpl($section, "subpage_accomp"); ?>
                <?php endif; ?>
                <?php if(strval($section->parametrs->param317)!='N'): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_analogs.php")) include $__MDL_ROOT."/php/subpage_analogs.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_analogs.tpl")) include $__data->include_tpl($section, "subpage_analogs"); ?>
                <?php endif; ?>
            <?php endif; ?>
    </div>  
    <?php if(strval($section->parametrs->param336)=='Y'): ?>
        <?php if(file_exists($__MDL_ROOT."/php/subpage_preorder.php")) include $__MDL_ROOT."/php/subpage_preorder.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_preorder.tpl")) include $__data->include_tpl($section, "subpage_preorder"); ?>
    <?php endif; ?>   
</div>
<?php if(empty($quickview)): ?></div><?php endif; ?>
