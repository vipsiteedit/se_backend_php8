<header:js>
[lnk:rouble/rouble.css]
[lnk:fancybox2/jquery.fancybox.css]
[lnk:fancybox2/helpers/jquery.fancybox-thumbs.css]
</header:js> 
<footer:js>
[js:jquery/jquery.min.js]

<script type="text/javascript" src="[module_url]mshop_vitrine_big51.js"></script>
<script type="text/javascript"> mshop_vitrine_big51_execute({
    id: <?php echo $section->id ?>,
    p321: '<?php echo $section->parametrs->param321 ?>',
    p307: '<?php echo $section->parametrs->param307 ?>',
    p308: '<?php echo $section->parametrs->param308 ?>',
    p309: '<?php echo $section->parametrs->param309 ?>',
});</script>
[js:jquery/jquery.mousewheel.js]
<?php if(strval($section->parametrs->param319)=='Y'): ?> 
[js:jquery/jcarousellite.js]
<?php endif; ?>
<?php if(strval($section->parametrs->param282)=='Y'): ?> 
[js:fancybox2/jquery.fancybox.pack.js] 
[js:fancybox2/helpers/jquery.fancybox-thumbs.js] 
<?php endif; ?>
<?php if(strval($section->parametrs->param311)=='Y'): ?> 
[js:jquery/zoomsl.min.js]
<?php endif; ?>
</footer:js> 
<div class="content e_shopvit" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" > 
    <div id="product" class="goodsContentDet <?php if(strval($section->parametrs->param85)=='Y'): ?>notAjaxCart<?php endif; ?>">
        <?php if(strval($section->parametrs->param246)!='N'): ?>
            <div class="goodsPath">
                <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                    <a class="goodsPathRoot" href="<?php echo seMultiDir()."/".$_page."/" ?>" itemprop="url"><span itemprop="title"><?php echo $section->language->lang019 ?></span></a>
                </span>
                <?php foreach($section->pathg as $pth): ?>
                    <span class="goodsPathSepar">/</span>
                    <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                        <a class="goodsLinkPath" href="<?php echo seMultiDir()."/".$_page."/" ?>cat/<?php echo $pth->cat ?>/" itemprop="url"><span itemprop="title"><?php echo $pth->cat_nm ?></span></a>
                    </span>
                
<?php endforeach; ?>
                <?php if(strval($section->parametrs->param246)=='A'): ?>
                    <span class="goodsPathSepar">/</span>
                    <span class="goodsLinkPath"><?php echo $product_name ?></span>    
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="goodsDetail" itemscope itemtype="http://schema.org/Product">
            <<?php echo $section->parametrs->param297 ?> class="goodsDetTitle" itemprop="name"><?php echo $product_name ?></<?php echo $section->parametrs->param297 ?>>
            <div class="goodsLinkPhoto">
                <a href="<?php echo $img_full ?>" title="<?php echo $price_img_alt ?>"">
                    <img class="goodsPhoto" src="<?php echo $img_mid ?>" alt="<?php echo $price_img_alt ?>" data-large="<?php echo $img_full ?>" itemprop="image">
                </a>
            </div>           
            <?php if(strval($section->parametrs->param133)!='N' && $product_note!=''): ?>
                <div class="goodsDetNote" itemprop="description"><?php echo $product_note ?></div>
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
                    <!--span class="ratingOff" title="<?php echo $section->language->lang058 ?> <?php echo $product_rating ?> <?php echo $section->language->lang059 ?> 5"-->
                        <span class="ratingOn" style="width:<?php echo $rating_percent ?>%;"></span>
                    </span></a>
                    <span class="ratingValue" itemprop="ratingValue"><?php echo $product_rating ?></span>
                    <span class="marks">(<a class="marksLabel anchor" href="<?php if(!empty($quickview)): ?><?php echo $product_link ?><?php endif; ?>#reviews"><?php echo $section->language->lang056 ?></a> <span class="marksValue" itemprop="reviewCount"><?php echo $product_marks ?></span>)</span>
                </div>
            <?php endif; ?>
            <div class="goodsDetPriceBox" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <?php echo $product_modifications ?>
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
                <form class="form_addCart" name="formtop" style="margin:0px;" method="post" action="">
                    <?php if(strval($section->parametrs->param138)=='Y'): ?>
                        <div class="addCount">
                            <label for="add_count">Кол-во:</label>
                            <input id="add_count" type="number" min="<?php echo $step ?>" name="addcartcount" step="<?php echo $step ?>" value="<?php echo $step ?>" size="4">
                            <span class="measure"><?php echo $product_measure ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="goodsButton">
                        <input type="hidden" name="addcart" value="<?php echo $product_id ?>">
                        <button class="buttonSend addcart" title="<?php echo $section->language->lang022 ?>" <?php if($maxcnts==0): ?>disabled<?php endif; ?>><?php echo $section->language->lang033 ?></button>
                        <button class="buttonSend back" onclick="history.back();return false;"><?php echo $section->language->lang031 ?></button>
                    </div>
                </form>
            </div>
            <?php if(strval($section->parametrs->param312)!='N'): ?>
                <?php if(file_exists($__MDL_ROOT."/php/subpage_social.php")) include $__MDL_ROOT."/php/subpage_social.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_social.tpl")) include $__data->include_tpl($section, "subpage_social"); ?>
            <?php endif; ?>
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
        </div>
            <?php if(strval($section->parametrs->param316)!='N'): ?>
                <?php if(file_exists($__MDL_ROOT."/php/subpage_accomp.php")) include $__MDL_ROOT."/php/subpage_accomp.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_accomp.tpl")) include $__data->include_tpl($section, "subpage_accomp"); ?>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param317)!='N'): ?>
                <?php if(file_exists($__MDL_ROOT."/php/subpage_analogs.php")) include $__MDL_ROOT."/php/subpage_analogs.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_analogs.tpl")) include $__data->include_tpl($section, "subpage_analogs"); ?>
            <?php endif; ?>
    </div>
</div>
