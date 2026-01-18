<footer:js>[include_js()]</footer:js>
<header:js>
[lnk:rouble/rouble.css]
</header:js>
<footer:js>
[js:jquery/jquery.min.js]
[include_js({'id':[part.id], 'link':'[thispage.link]','p16':'[param16]',curr: '{$baseCurr}'})]
</footer:js>
<div class="content contShopCartNew" data-type="[part.type]" data-id="[part.id]" [contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle">
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText">[part.text]</div>
    </noempty>
    <div class="contentBody" style="position:relative;">       
        <div id="notEmptyCartGoods" <serv><if:{$total['count']}==0>style="display:none;"</if></serv>>
            <div <if:{$error_message}==''>style="display:none;"</if> id="blockMessageWarning"<se> class="sysedit"</se>>
                <span>{$error_message}</span>
            </div>
            <form id="cartGoodsForm" style="margin:0px;" name="cartgoodsform" action="<se>[link.subpage=confirm]</se>" method="post">     
                <div class="blockCartContent" id="blockCartGoods">
                    <div class="blockCartTitle">
                        <span>[lang039]</span>
                    </div>
                    <div class="blockCartList">
                        [subpage name=products]
                    </div>
                </div>
                <div class="continueShoppingArea">
                    <a class="continueShopping btn btn-link" href="[param1].html">[lang072]</a>
                </div>
                <if:[param8]=='Y'>
                <div class="blockCartContent" id="blockCouponDiscount">
                    <div id="blockCouponApply">
                        <span id="couponTitle">[lang040]</span>
                        <input id="inputCoupon" type="text" name="code_coupon" value="<if:{$find_coupon}>{$code_coupon}</if>">
                        <input type="button" class="buttonSend" id="btnApplyCoupon" title="[lang041]" value="[lang042]">
                        <div id="noteCoupon">
                            <if:{$find_coupon}>{$note_coupon}<else>[lang043]</if>
                        </div>
                    </div>
                    <div id="blockSumCoupon">
                        <if:{$find_coupon}>
                            -{$sum_coupon}
                        </if>
                    </div>
                </div> 
                </if> 
                
                <div class="blockCartContent" id="blockCartContact">
                    <div class="blockCartTitle">
                        <span>[lang045]</span>
                    </div>
                    <div class="blockCartList">
                        [subpage name=contacts]
                    </div> 
                </div>
                                      
                <div class="blockCartContent" id="blockCartDelivery">
                    [subpage name=selectregion]
                    <div class="blockCartTitle">
                        <span class="deliveryTitle">[lang044]</span>
                        <span id="selectedUserRegion">[lang068]<a class="userRegionName" href="#">{$region_city}</a></span>
                    </div> 
                    <div class="blockCartList">
                        [subpage name=deliveries]    
                    </div>
                    <div class="blockContactAddress"<empty:{$addr}> style="display:none;"</empty>>
                        <div class="blockRegLabel">
                            <label for="reg_address">[lang033]</label><span class="required">*</span>
                        </div>
                        <div class="blockRegInput">
                            <textarea style="width:600px;height:100px;" class="inputCartContact <noempty:{$error_address}>inputCartError</noempty>" id="reg_address" name="contact_address">{$contact_address}</textarea>
                            <div class="regCartError <se>sysedit</se>"<empty:{$error_address}> style="display:none;"</empty>>{$error_address}</div>
                        </div> 
                    </div>
                </div>     
                
                <if:[param9]=='Y'>
                    [subpage name=payments]
                </if>
                
                [subpage name=license]
                
                <div class="blockCartContent" id="blockCartTotalSum">
                    <span id="totalSumTitle">[lang047]</span> 
                    <span id="totalSumPrice">{$sum_total_order}</span>        
                </div>
                
                <div class="blockCartContent blockButtonOrder">
                    <button class="buttonSend" id="test_order" name="place_order">[lang048]</button>
                </div>
            </form>
        </div>
        <div id="emptyCartGoods" <serv><if:{$total['count']}!=0>style="display:none;"</if></serv>>
            <div id="emptyCart">[lang049]</div>
            <a class="linkGoShopping" href="[param1].html">[lang050]</a>
        </div>
    </div>
</div>
