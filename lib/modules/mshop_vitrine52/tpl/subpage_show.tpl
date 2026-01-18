<?php if(empty($quickview)): ?>
<header:js>
[lnk:rouble/rouble.css]
<?php if(strval($section->parametrs->param282)=='Y'): ?> 
[lnk:fancybox2/jquery.fancybox.css]    
[lnk:fancybox2/helpers/jquery.fancybox-thumbs.css] 
<?php endif; ?>
</header:js>
<footer:js>
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

<script type="text/javascript" src="[module_url]mshop_vitrine52.js"></script>
<script type="text/javascript"> mshop_vitrine52_execute({
    id: <?php echo $section->id ?>,
    ajax_url: '?ajax<?php echo $section->id ?>',
    param321: '<?php echo $section->parametrs->param321 ?>',
    param307: '<?php echo $section->parametrs->param307 ?>',
    param308: '<?php echo $section->parametrs->param308 ?>',
    param309: '<?php echo $section->parametrs->param309 ?>',
    param233: '<?php echo $section->parametrs->param233 ?>',
    show_product: {id: '<?php echo $product_id ?>', name: '<?php echo $product_name ?>', category: '<?php echo $product_group ?>', brand: '<?php echo $product_brand ?>', variant: ''}
});</script>
</footer:js> 
<?php endif; ?>
<div class="content e_shopvit<?php if(!empty($quickview)): ?> quickShow<?php endif; ?>" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" > 
    <div id="product" class="goodsContentDet <?php if(strval($section->parametrs->param85)=='Y'): ?>notAjaxCart<?php endif; ?>">
        <?php if(empty($quickview)): ?>
        <?php if(strval($section->parametrs->param246)!='N'): ?>
            <ol class="goodsPath breadcrumb">
                <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                    <a class="goodsPathRoot" href="<?php echo seMultiDir()."/".$_page."/" ?>" itemprop="url"><span itemprop="title"><?php echo $section->language->lang019 ?></span></a>
                </li>
                <?php foreach($section->pathg as $pth): ?>
                    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                        <a class="goodsLinkPath" href="<?php echo seMultiDir()."/".$_page."/" ?>cat/<?php echo $pth->cat ?>/" itemprop="url"><span itemprop="title"><?php echo $pth->cat_nm ?></span></a>
                    </li>
                
<?php endforeach; ?>
                <?php if(strval($section->parametrs->param246)=='A'): ?>
                    <li class="goodsLinkPath active"><?php echo $product_name ?></li>    
                <?php endif; ?>
            </ol>
        <?php endif; ?>
        <?php endif; ?>
        <div class="goodsDetail col-lg-12 col-md-12 col-sm-12 col-xs-12" itemscope itemtype="http://schema.org/Product">
            <?php if(strval($section->parametrs->param329)!='N' || $quickview!=''): ?>
                <h1 class="goodsDetTitle" itemprop="name"><?php echo $product_name ?></h1>
            <?php endif; ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
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
            
            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">        
            <?php if(strval($section->parametrs->param133)!='N' && $product_note!=''): ?>
                <div class="goodsDetNote" itemprop="description"><?php echo $product_note ?></div>
            <?php endif; ?>
            <?php if(!empty($brand_image)): ?>
                <a class="brandImage" href="<?php echo $brand_link ?>" title="<?php echo $product_brand ?>">
                    <img src="<?php echo $brand_image ?>" alt="<?php echo $product_brand ?>">
                </a>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param126)=='Y' && $product_code!=''): ?>
                <div class="goodsDetArticle">
                    <label class="articleLabel"><?php echo $section->language->lang005 ?></label>
                    <span class="articleValue"><?php echo $product_code ?></span>
                </div>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param129)=='Y' && $product_brand!=''): ?>
                <div class="goodsBrand">
                    <label class="brandLabel"><?php echo $section->language->lang024 ?></label>
                    <a href="<?php echo $brand_link ?>" class="brandValue" itemprop="brand"><?php echo $product_brand ?></a>
                </div>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param266)=='Y'): ?>
                <div class="goodsRating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                    <meta itemprop="worstRating" content="1">
                    <meta itemprop="bestRating" content="5">
                    <label class="ratingLabel"><?php echo $section->language->lang057 ?></label>
                    <a class="anchor" href="<?php if(!empty($quickview)): ?><?php echo $product_link ?><?php endif; ?>#reviews"><span class="ratingOff" title="<?php echo $section->language->lang058 ?> <?php echo $product_rating ?> <?php echo $section->language->lang059 ?> 5">
                        <span class="ratingOn" style="width:<?php echo $rating_percent ?>%;"></span>
                    </span></a>
                    
                    <span class="ratingValue" itemprop="ratingValue"><?php echo $product_rating ?></span>
                    <span class="marks">(<a class="marksLabel anchor" href="<?php if(!empty($quickview)): ?><?php echo $product_link ?><?php endif; ?>#reviews"><?php echo $section->language->lang056 ?></a> <span class="marksValue" itemprop="reviewCount"><?php echo $product_marks ?></span>)</span>
                </div>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param269)=='Y'): ?>
                <div class="blockCompare">
                    <label title="<?php echo $section->language->lang095 ?>">
                        <input class="compare" type="checkbox" data-id="<?php echo $product_id ?>"<?php if(!empty($product_compare)): ?> checked<?php endif; ?>>
                        <span class="compareLabel"><?php echo $section->language->lang098 ?></span>
                    </label>
                    <a class="lnkInCompare" href="<?php echo seMultiDir()."/".$section->parametrs->param331."/" ?>" title="<?php echo $section->language->lang096 ?>"<?php if(empty($product_compare)): ?> style="display:none;"<?php endif; ?>><?php echo $section->language->lang097 ?></a>
                </div>
            <?php endif; ?> 
            <?php if(strval($section->parametrs->param334)=='Y'): ?>
                <div class="blockDelivery">
                    <label>Доставка:</label>
                    <div class="">   
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="goodsDetPriceBox" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <?php if(!empty($product_modifications)): ?>
                    <?php echo $product_modifications ?>
                <?php endif; ?>
                <?php if(strval($section->parametrs->param128)=='Y'): ?>
                    <div class="presence">
                        <label class="presenceLabel"><?php echo $section->language->lang009 ?></label>
                        <span class="presenceValue" itemprop="availability"><?php echo $product_count ?></span>
                    </div>               
                <?php endif; ?>
                <div class="goodsPrice<?php if(empty($realprice)): ?> nullPrice<?php endif; ?>">
                    <label class="priceLabel"><?php echo $section->language->lang008 ?></label>
                    <span class="priceValue">
                        <?php if(strval($section->parametrs->param113)=='Y' && $product_oldprice!=''): ?>
                            <span class="oldPrice"><?php echo $product_oldprice ?></span>
                        <?php endif; ?>
                        <span class="newPrice"><?php echo $product_price ?></span>
                        <meta itemprop="price" content="<?php echo $realprice ?>">
                        <meta itemprop="priceCurrency" content="<?php echo $pricemoney ?>">
                    </span>
                </div>
                <form class="form_addCart" method="post" action="">
                    <?php if(strval($section->parametrs->param138)=='Y'): ?>
                        <div class="addCount form-group">
                            <label class="" for="add_count"><?php echo $section->language->lang104 ?></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-btn ">
                                    <button type="button" class="btn btn-default" data-action="dec">-</button>
                                </span>
                                <input id="add_count" class="form-control" type="text" name="addcartcount" data-step="<?php echo $step ?>" value="<?php echo $step ?>" size="3">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default" data-action="inc">+</button>
                                </span>
                            </div>
                            <?php if(!empty($product_measure)): ?>
                                <span class="measure"><?php echo $product_measure ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="goodsButton form-group">
                        <input type="hidden" name="addcart" value="<?php echo $product_id ?>">
                        <?php if($maxcnts==0 && strval($section->parametrs->param336)=='Y'): ?>
                            <button type="button" class="buttonSend btnPreorder btn btn-default">Предзаказ</button>
                        <?php endif; ?>
                        <button class="buttonSend addcart btn btn-default<?php if(!empty($product_incart)): ?> inCartActive<?php endif; ?>" title="<?php echo $section->language->lang022 ?>" <?php if(empty($maxcnts)): ?><?php if(strval($section->parametrs->param233)=='Y'): ?>disabled<?php else: ?>style="display:none;"<?php endif; ?><?php endif; ?>><i class="glyphicon glyphicon-shopping-cart"></i> <?php echo $section->language->lang033 ?></button>
                        <?php if($quickview==''): ?>
                            <button class="buttonSend back btn btn-link" onclick="history.back();return false;"><?php echo $section->language->lang031 ?></button>
                        <?php else: ?>
                            <a class="lnkDetail btn btn-link" href="<?php echo $product_link ?><?php echo $section->parametrs->param330 ?>"><?php echo $section->language->lang032 ?></a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            </div>
            <?php if(strval($section->parametrs->param312)!='N'): ?>
                <?php if(file_exists($__MDL_ROOT."/php/subpage_socialbuttons.php")) include $__MDL_ROOT."/php/subpage_socialbuttons.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_socialbuttons.tpl")) include $__data->include_tpl($section, "subpage_socialbuttons"); ?>
            <?php endif; ?>
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
