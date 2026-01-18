<empty:{$quickview}>
<header:js>
[lnk:rouble/rouble.css]
<if:[param282]=='Y'> 
[lnk:fancybox2/jquery.fancybox.css]    
[lnk:fancybox2/helpers/jquery.fancybox-thumbs.css] 
</if>
</header:js>
<footer:js>
[js:jquery/jquery.min.js]
[js:jquery/jquery.mousewheel.js]
<if:[param319]=='Y'> 
[js:jquery/jcarousellite.js]
</if>
<if:[param311]=='Y'> 
[js:jquery/zoomsl.min.js]
</if>
<if:[param282]=='Y'> 
[js:fancybox2/jquery.fancybox.pack.js] 
[js:fancybox2/helpers/jquery.fancybox-thumbs.js] 
</if>
[include_js({
    id: [part.id],
    ajax_url: '?ajax[part.id]',
    param321: '[param321]',
    param307: '[param307]',
    param308: '[param308]',
    param309: '[param309]',
    param233: '[param233]',
    show_product: {id: '{$product_id}', name: '{$product_name}', category: '{$product_group}', brand: '{$product_brand}', variant: ''}
})]
</footer:js> 
</empty>
<div class="content e_shopvit<noempty:{$quickview}> quickShow</noempty>" data-type="[part.type]" data-id="[part.id]" [contedit]> 
    <div id="product" class="goodsContentDet <if:'[param85]'=='Y'>notAjaxCart</if>">
        <empty:{$quickview}>
        <if:[param246]!='N'>
            <ol class="goodsPath breadcrumb">
                <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                    <a class="goodsPathRoot" href="[thispage.link]" itemprop="url"><span itemprop="title">[lang019]</span></a>
                </li>
                <repeat:pathg name=pth>
                    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                        <a class="goodsLinkPath" href="[thispage.link]<SERV>cat/[pth.cat]/</SERV>" itemprop="url"><span itemprop="title">[pth.cat_nm]</span></a>
                    </li>
                </repeat:pathg>
                <if:[param246]=='A'>
                    <li class="goodsLinkPath active">{$product_name}</li>    
                </if>
            </ol>
        </if>
        </empty>
        <div class="goodsDetail col-lg-12 col-md-12 col-sm-12 col-xs-12" itemscope itemtype="http://schema.org/Product">
            <if:[param329]!='N' || {$quickview}!=''>
                <h1 class="goodsDetTitle" itemprop="name">{$product_name}</h1>
            </if>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="goodsLinkPhoto">
                    <a rel="nofollow" rel="" href="{$img_full}" title="{$price_img_alt}"  target="_blank">
                        <img class="goodsPhoto img-responsive center-block" src="{$img_mid}" style="max-width:100%;" alt="{$price_img_alt}" data-large="{$img_full}" itemprop="image">
                    </a>
                </div>
                <if:[param320]!='N'>
                    <div class="morephotos">
                        <if:[param319]=='Y'> 
                            <span class="prev">&lt;</span>
                            <div class="photoSlider">
                        </if>
                        <ul class="imageList">
                            <repeat:photos name=foto>
                                <li class="imageItem<if:[foto.image]=={$img_full}> activeImg</if>" data-id="[foto.id]">
                                    <a rel="nofollow imagebox" href="[foto.image]" data-middle="[foto.image_mid]" <noempty:foto.title>title="[foto.title]"</noempty> target="_blank">
                                        <img src="[foto.image_prev]" class="imgAll" border="0" style="{$img_prev_style}" <noempty:foto.alt>alt="[foto.alt]"</noempty>>
                                    </a>
                                </li>
                            </repeat:photos>
                        </ul>
                        <if:[param319]=='Y'> 
                            </div>
                            <span class="next">&gt;</span>
                        </if>
                    </div>            
                </if>
            </div>   
            
            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">        
            <if:[param133]!='N' && {$product_note}!=''>
                <div class="goodsDetNote" itemprop="description">{$product_note}</div>
            </if>
            <noempty:{$brand_image}>
                <a rel="nofollow" class="brandImage" href="{$brand_link}" title="{$product_brand}">
                    <img src="{$brand_image}" alt="{$product_brand}">
                </a>
            </noempty>
            <if:[param126]=='Y' && {$product_code}!=''>
                <div class="goodsDetArticle">
                    <label class="articleLabel">[lang005]</label>
                    <span class="articleValue">{$product_code}</span>
                </div>
            </if>
            <if:[param129]=='Y' && {$product_brand}!=''>
                <div class="goodsBrand">
                    <label class="brandLabel">[lang024]</label>
                    <a rel="nofollow" href="{$brand_link}" class="brandValue" itemprop="brand">{$product_brand}</a>
                </div>
            </if>
            <if:[param266]=='Y'>
                <div class="goodsRating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                    <meta itemprop="worstRating" content="0">
                    <meta itemprop="bestRating" content="5">
                    <label class="ratingLabel">[lang057]</label>
                    <a rel="nofollow" class="anchor" href="<noempty:{$quickview}>{$product_link}</noempty>#reviews"><span class="ratingOff" title="[lang058] {$product_rating} [lang059] 5">
                        <span class="ratingOn" style="width:{$rating_percent}%;"></span>
                    </span></a>
                    
                    <span class="ratingValue" itemprop="ratingValue">{$product_rating}</span>
                    <span class="marks">(<a rel="nofollow" class="marksLabel anchor" href="<noempty:{$quickview}>{$product_link}</noempty>#reviews">[lang056]</a> <span class="marksValue" itemprop="reviewCount">{$product_marks}</span>)</span>
                </div>
            </if>
            <if:[param269]=='Y'>
                <div class="blockCompare">
                    <label title="[lang095]">
                        <input class="compare" type="checkbox" data-id="{$product_id}"<noempty:{$product_compare}> checked</noempty>>
                        <span class="compareLabel">[lang098]</span>
                    </label>
                    <a class="lnkInCompare" href="[param331].html" title="[lang096]"<empty:{$product_compare}> style="display:none;"</empty>>[lang097]</a>
                </div>
            </if> 
            <if:[param334]=='Y'>
                <div class="blockDelivery">
                    <label>Доставка:</label>
                    <div class="">   
                    </div>
                </div>
            </if>
            
            <div class="goodsDetPriceBox" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <noempty:{$product_modifications}>
                    {$product_modifications}
                </noempty>
                <if:[param128]=='Y'>
                    <div class="presence">
                        <label class="presenceLabel">[lang009]</label>
                        <span class="presenceValue" itemprop="availability">{$product_count}</span>
                    </div>               
                </if>
                <div class="goodsPrice<empty:{$realprice}> nullPrice</empty>">
                    <label class="priceLabel">[lang008]</label>
                    <span class="priceValue">
                        <if:[param113]=='Y' && {$product_oldprice}!=''>
                            <span class="oldPrice">{$product_oldprice}</span>
                        </if>
                        <span class="newPrice">{$product_price}</span>
                        <meta itemprop="price" content="{$realprice}">
                        <meta itemprop="priceCurrency" content="{$pricemoney}">
                    </span>
                </div>
                <form class="form_addCart" method="post" action="<se>[thispage.link]</se>">
                    <if:[param138]=='Y'>
                        <div class="addCount form-group">
                            <label class="" for="add_count">[lang104]</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-btn ">
                                    <button type="button" class="btn btn-default" data-action="dec">-</button>
                                </span>
                                <input id="add_count" class="form-control" type="text" name="addcartcount" data-step="{$step}" value="{$step}" size="3">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default" data-action="inc">+</button>
                                </span>
                            </div>
                            <noempty:{$product_measure}>
                                <span class="measure">{$product_measure}</span>
                            </noempty>
                        </div>
                    </if>
                    <div class="goodsButton form-group">
                        <input type="hidden" name="addcart" value="{$product_id}">
                        <if:{$maxcnts}==0 && [param336]=='Y'>
                            <button type="button" class="buttonSend btnPreorder btn btn-default">[lang118]</button>
                        </if>
                        <button class="buttonSend addcart btn btn-default<noempty:{$product_incart}> inCartActive</noempty>" title="[lang022]" <empty:{$maxcnts}><if:[param233]=='Y'>disabled<else>style="display:none;"</if></empty>><i class="glyphicon glyphicon-shopping-cart"></i> [lang033]</button>
                        <if:{$quickview}==''>
                            <button class="buttonSend back btn btn-link" onclick="<serv>history.back();return false;</serv>">[lang031]</button>
                        <else>
                            <a class="lnkDetail btn btn-link" href="{$product_link}[param330]">[lang032]</a>
                        </if>
                    </div>
                </form>
            </div>
            </div>
            <if:[param312]!='N'>
                [subpage name=socialbuttons]
            </if>
            <empty:{$quickview}>
                <if:[param258]=='Y'>
                    <div class="tabs" style="display:none;">
                        <ul class="tabsNav"></ul>
                        <div class="tabsContent"></div>
                    </div>
                </if>
                <if:[param134]!='N'> 
                    [subpage name=text]
                </if>
                <if:[param306]!='N'> 
                    [subpage name=features]
                </if>
                <if:[param217]!='N'>
                    [subpage name=comment]
                </if> 
                <if:[param314]!='N'> 
                    [subpage name=reviews]
                </if>
                <if:[param337]!='N'> 
                    [subpage name=files]
                </if>
            </empty>
        </div>
            <empty:{$quickview}>
                <if:[param316]!='N'>
                    [subpage name=accomp]
                </if>
                <if:[param317]!='N'>
                    [subpage name=analogs]
                </if>
            </empty>
    </div>  
    <if:[param336]=='Y'>
        [subpage name=preorder]
    </if>   
</div>
