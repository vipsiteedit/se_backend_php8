<repeat:objects>
    <div class="productItem product-id-[record.id]">
        <div class="product-item-block">
        <div class="blockImage<if:[param341]=='Y'> blockImageBox</if>"> 
            <if:[param327]=='Y'>
                <button class="quickView" title="[lang094]" data-id="[record.id]"><i class="fa fa-search" aria-hidden="true"></i></button>
            </if>
            <a href="[record.linkshow][param330]" title="[record.img_alt]">
                <img class="objectImage img-responsive center-block" src="[record.image_prev]" alt="[record.img_alt]">
            </a>
            <div class="blockFlag">
            <if:[record.flag_new]=='Y'>
                <span class="flag_new" title="[lang050]"><i class="fa fa-sun-o" aria-hidden="true"><div class="flag-new-circle"></div><span class="text-flag-new">[lang050]</span></i></span>
            </if>
            <if:[record.flag_hit]=='Y'>
                <span class="flag_hit" title="[lang049]"><i class="fa fa-sun-o" aria-hidden="true"><i class="fa fa-thumbs-up" aria-hidden="true"></i></i></span>
            </if>
            </div>
            <if:[record.percent]!=0>
                <div class="flag_discount" title="[record.percent]%">[record.percent]%</div>
            </if>
        </div>
        <div class="total-object-box">
        <if:[param266]=='Y'>
            <div class="objectRating">
                <span class="ratingOff" title="[lang058] [record.rating] [lang059] 5">
                    <span class="ratingOn" style="width:[record.rating_percent]%;"></span>
                </span>
                <span class="ratingValue">[record.rating]</span>
                <span class="marks">(<a class="marksLabel" href="[record.linkshow]#reviews">[lang056]</a> <span class="marksValue">[record.marks]</span>)</span>
            </div>
        </if>
        <div class="object-brand-article toggle-object">
        <if:[param84]=='Y'>
            <noempty:record.brand>
                <div class="objectBrand">
                    <!--label class="brandLabel">[lang024]</label-->
                    <span class="brandValue">[record.brand]</span>
                </div>
            </noempty>
        </if>
        <if:[param83]=='Y'>
            <noempty:record.article>
                <div class="objectArticle">
                    <!--label class="articleLabel">[lang005]</label-->
                    <span class="articleValue">[record.article]</span>
                </div>
            </noempty>
        </if>
        </div>
        <div class="objectTitle">
            <if:[param275]=='Y'>
                <a class="textTitle" href="[record.linkshow][param330]">[record.name]</a>
            <else>
                <span class="textTitle">[record.name]</span>
            </if>
        </div>
        <if:[param114]=='Y' >
            <noempty:record.note>
                <div class="objectNote">[record.note]</div>
            </noempty>
        </if>
        <div class="priceBox">
            <form class="form_addCart" method="post" action="<se>[thispage.link]</se>">
                <div class="price-button-box">
                <div class="priceStyle<empty:[record.realprice]> nullPrice</empty>">
                <if:[param226]=='Y'>
                    <!--div class="priceLabel">[lang008]</div-->
                </if>
                <if:[param113]=='Y' && [record.oldprice]!=''>
                    <div class="oldPrice">[record.oldprice]</div>
                </if>
                <div class="newPrice">[record.newprice]</div>
                </div>
                <div class="buttonBox">
                    <input type="hidden" name="addcart" value="[record.id]">
                    <if:[record.maxcount]==0 && [param336]=='Y'>
                        <button type="button" class="buttonSend btnPreorder btn btn-default"><i class="fa fa-briefcase sign-preorder" aria-hidden="true"></i><span class="text-preorder">[lang107]</span></button><!-- Lang -->
                    </if>
                    <button class="buttonSend addcart btn btn-default<noempty:[record.incart]> inCartActive</noempty>" title="[lang022]" <empty:record.maxcount><if:[param233]=='Y'>disabled<else>style="display:none;"</if></empty>><i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
                    <if:[param339]=='Y'><a class="details btn btn-link" href="[record.linkshow][param330]">[lang032]</a></if>
                </div>
                </div>
                <if:[param332]=='Y'>
                    <div class="addCount toggle-object">
                         <div class="count-title">[lang108]</div><!-- Lang -->
                         <div class="count-inp-box">
                        <input class="cartscount" type="number" min="[record.step]" name="addcartcount" step="[record.step]" value="[record.step]" size="4">
                        <span class="measure" <noempty:[record.measure]>style="padding-left:9px;"</noempty>>[record.measure]</span>
                        </div>
                    </div>
                </if>
            </form>
        </div>
        [record.modifications]
        </div>
        <div class="bottom-box toggle-object">
             <if:[param269]=='Y'>
             <div class="blockCompare">
                <label title="[lang095]">
                    <input class="compare" type="checkbox" data-id="[record.id]"<noempty:[record.compare]> checked</noempty>>
                    <span class="compareLabel"><span class="compare-stext"<if:[record.compare]!=0> style="display:none;"</if>>[lang098]</span></span>
                </label>
                <a class="lnkInCompare" href="[param331].html" title="[lang096]"<empty:[record.compare]> style="display:none;"</empty>>[lang097]</a>
                <a class="del-compare" href="[param331].html?clear_compare" title="[lang112]"<empty:[record.compare]> style="display:none;"</empty>><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            </div>
            </if>
            <if:[param111]=='Y'>
            <div class="objectPresence">
                <if:[record.count] != 0><label class="presenceLabel">[lang009]</label></if>
                <span class="presenceValue">[record.count]</span>
            </div>
            </if>
        </div>
        </div>
    </div>
</repeat:objects>
