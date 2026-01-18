<empty:{$quickview}>
<header:js>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
[lnk:rouble/rouble.css]
<if:[param282]=='Y'> 
[lnk:fancybox2/jquery.fancybox.css]    
[lnk:fancybox2/helpers/jquery.fancybox-thumbs.css] 
</if>
</header:js>
</empty>
<footer:js>
<empty:{$quickview}>
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
</empty>
<noempty:{$quickview}>
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
</noempty>
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
<empty:{$quickview}><div class="<if:[param344]=='n'>container<else>container-fluid</if>"></empty>
<div class="content ashopvit_compl<noempty:{$quickview}> quickShow</noempty>" data-type="[part.type]" data-id="[part.id]" [contedit]>
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
        <div class="goodsDetail" itemscope itemtype="http://schema.org/Product">
             <div class="goods-det-total">
            <div class="goods-det-image-block">
                 <div class="top-det-box">
                <div class="brand-name-box">
                <if:[param129]=='Y' && {$product_brand}!=''>
                <div class="goodsBrand">
                    <a href="{$brand_link}" class="brandValue" itemprop="brand">{$product_brand}</a>
                </div>
                </if>
                <if:([param329]!='N' || {$quickview}!='')>
                <div class="goodsDetTitle" itemprop="name">{$product_name}</div>
                </if>
                </div>
                <if:[param126]=='Y' && {$product_code}!=''>
                <div class="goodsDetArticle">
                    <label class="articleLabel">[lang005]</label>
                    <span class="articleValue">{$product_code}</span>
                </div>
                </if>
                </div>
                <if:[param266]=='Y'>
                <div class="goodsRating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                    <meta itemprop="worstRating" content="1">
                    <meta itemprop="bestRating" content="5">
                    <a class="anchor" href="<noempty:{$quickview}>{$product_link}</noempty>#reviews"><span class="ratingOff" title="[lang058] {$product_rating} [lang059] 5">
                        <span class="ratingOn" style="width:{$rating_percent}%;"></span>
                    </span></a>
                    <span class="ratingValue" itemprop="ratingValue">{$product_rating}</span>
                    <span class="marks">(<a class="marksLabel anchor" href="<noempty:{$quickview}>{$product_link}</noempty>#reviews">[lang056]</a> <span class="marksValue" itemprop="reviewCount">{$product_marks}</span>)</span>
                </div>
                </if>
                <div class="goodsLinkPhoto">
                    <a href="{$img_full}" title="{$price_img_alt}"  target="_blank">
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
                                    <a rel="imagebox" href="[foto.image]" data-middle="[foto.image_mid]" <noempty:foto.title>title="[foto.title]"</noempty> target="_blank">
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
            
            <div class="goods-det-info-block">
                <if:[param128]=='Y'>
                <div class="objectPresence">
                     <if:{$product_count} != 0><label class="presenceLabel">[lang009]</label></if>
                     <span class="presenceValue">{$product_count}</span>
                </div>
                </if>
            <if:([param133]!='N' && {$product_note}!='')>
                <div class="goodsDetNote" itemprop="description">{$product_note}</div>
            </if>
            {$product_modifications}
            [subpage name=option]
            
            <div class="goodsDetPriceBox" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <form class="form_addCart" method="post" action="<se>[thispage.link]</se>">
                    <if:[param138]=='Y'>
                        <div class="addCount form-group">
                            <label class="titleAddCount" for="add_count">[lang104]</label>
                            <div class="count-inp-box">
                                <input id="add_count" class="cartscount" type="number" min="{$step}" name="addcartcount" data-step="{$step}" value="{$step}" size="4">
                                <noempty:{$product_measure}>
                                        <span class="measure">{$product_measure}</span>
                                </noempty>
                            </div>
                        </div>
                    </if>
                    <if:[param342]=='Y' || [param343]=='Y'>
                        <div class="list-paydel-box">
                        <if:[param342]=='Y'>
                            <div class="list-payment-box">
                                <i class="fa fa-credit-card-alt" aria-hidden="true"></i><a href="javascript:void(0);" id="clickpay" class="link-paydel click-payment">[lang110]</a>     
                            </div>
                            <div class="payment-section paydel-section" style="display:none;">
                                <div class="paydel-triangle"></div>
                                [subpage name=payments]
                            </div>
                        </if>
                        <if:[param343]=='Y'>
                            <div class="list-delivery-box">
                                <i class="fa fa-truck" aria-hidden="true"></i><a href="#" id="clickdelv" class="link-paydel click-delivery">[lang111]</a>    
                            </div>
                            <div class="delivery-section paydel-section" style="display:none;">
                                [subpage name=deliveries]
                            </div>
                        </if>
                        </div>
                    </if>
                    <div class="goodsPrice<empty:{$realprice}> nullPrice</empty>">
                    <!--label class="priceLabel">[lang008]</label-->
                    <div class="priceValue">
                        <if:[param113]=='Y' && {$product_oldprice}!=''>
                            <div class="oldPrice">{$product_oldprice}</div>
                        </if>
                        <div class="newPrice">{$product_price}</div>
                        <meta itemprop="price" content="{$realprice}">
                        <meta itemprop="priceCurrency" content="{$pricemoney}">
                    </div>
                    
                    <div class="buttonBox">
                        <input type="hidden" name="addcart" value="{$product_id}">
                        <if:{$maxcnts}==0 && [param336]=='Y'>
                            <button type="button" class="buttonSend btnPreorder btn btn-default"><i class="fa fa-briefcase sign-preorder" aria-hidden="true"></i><span class="text-preorder">[lang107]</span></button>
                        </if>
                        <button class="buttonSend addcart btn btn-default<noempty:{$product_incart}> inCartActive</noempty>" title="[lang022]" <empty:{$maxcnts}><if:[param233]=='Y'>disabled<else>style="display:none;"</if></empty>><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="label-addcart">[lang033]</span></button>
                    </div>
                    </div>
                    <if:{$quickview}==''>
                            <if:[param340]=='Y'>
                                <div class="back-block-box">
                                    <button class="buttonSend back btn btn-link" onclick="<serv>history.back();return false;</serv>"><i class="fa fa-arrow-left" aria-hidden="true"></i><span class="sm-text">[lang031]</span><span class="ln-text">[lang109]</span></button>
                                </div>
                            </if>
                    <else>
                            <a class="lnkDetail btn btn-link" href="{$product_link}[param330]">[lang032]<i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                    </if>
                </form>
            </div>
            <if:[param269]=='Y'>
                <div class="blockCompare">
                <label title="[lang095]">
                    <input class="compare" type="checkbox" data-id="{$product_id}"<noempty:{$product_compare}> checked</noempty>>
                    <span class="compareLabel"><span class="compare-stext"<noempty:{$product_compare}> style="display:none;"</noempty>>[lang098]</span></span>
                </label>
                <a class="lnkInCompare" href="[param331].html" title="[lang096]"<empty:{$product_compare}> style="display:none;"</empty>>[lang097]</a>
                <a class="del-compare" href="[param331].html?clear_compare" title="[lang112]"<empty:{$product_compare}> style="display:none;"</empty>><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            </div>
            </if>
            <if:[param334]=='Y'>
                <div class="blockDelivery">
                    <label>Доставка:</label>
                    <div class="">
                    </div>
                </div>
            </if>
            <empty:{$quickview}>
                <if:[param312]!='N'>
                        [subpage name=socialbuttons]
                </if>
                </empty>
            </div>
            </div>
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
                <if:[param350]!='N'> 
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
<empty:{$quickview}></div></empty>
