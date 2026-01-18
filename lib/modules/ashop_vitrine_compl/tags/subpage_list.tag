<repeat:objects>
    <div class="productItem">
         <div class="blockImage<if:[param341]=='Y'> blockImageBox</if>">
            <if:[param327]=='Y'>
                <!--button class="quickView" title="[lang094]" data-id="[record.id]"><i class="fa fa-search" aria-hidden="true"></i></button-->
            </if>
            <a href="#" title="[record.img_alt]" class="quickView" data-id="[record.id]">
                <img class="objectImage img-responsive" src="[record.image_prev]" alt="[record.img_alt]">
                <if:[record.percent]!=0>
                        <span class="flag_discount" title="[record.percent]%">[record.percent]%</span>
                </if>
            </a>
        </div>
        <div class="price-info-box">
        <div class="blockInfo">
        <div class="objectTitle">
             <if:[record.flag_new]=='Y'>
                        <span class="flag_new" title="[lang050]"><i class="fa fa-certificate" aria-hidden="true"><span class="text-flag-new">[lang050]</span></i></span>
                </if>
             <if:[record.flag_hit]=='Y'>
                        <span class="flag_hit" title="[lang049]"><i class="fa fa-sun-o" aria-hidden="true"><i class="fa fa-thumbs-up" aria-hidden="true"></i></i></span>
                </if>
            <if:[param275]=='Y'>
                <a class="textTitle" title="[record.img_alt]" href="[record.linkshow][param330]">[record.name]</a>
            <else>
                <div class="good-list-title"><span class="goodsname">[record.name]</span></div>
            </if>
        </div>
        <noempty:record.note>
                <div class="objectNote">[record.note]</div>
        </noempty>
        <!--div class="blockArticle">
            <noempty:record.article>
                #<span class="articleValue">[record.article]</span>
            </noempty>
        </div-->
        <!--div class="blockPresence">
            <span class="presenceValue">[record.count]</span>
        </div-->
        </div>
        <div class="priceBox">
            <form class="form_addCart" method="post" action="<se>[record.linkshow]</se>">
                  <div class="price-addcart-box">
                <div class="priceStyle">
                       <if:[param113]=='Y' && [record.oldprice]!=''>
                                <span class="oldPrice hidden-xs">[record.oldprice]</span>
                        </if>
                        <span class="newPrice">[record.newprice]</span>
                </div>
                <input type="hidden" name="addcart" value="[record.id]">
                <if:[record.maxcount]==0 && [param336]=='Y'>
                    <button type="button" class="buttonSend btnPreorder btn btn-default"><i class="fa fa-briefcase sign-preorder" aria-hidden="true"></i><span class="text-preorder">[lang107]</span></button>
                </if>
                <input type="hidden" class="cartscount" name="addcartcount" data-step="[record.step]" value="[record.step]" size="3">
                <button class="buttonSend addcart btn btn-default btn-sm<noempty:[record.incart]> inCartActive</noempty>" title="[lang022]" <empty:record.maxcount><if:[param233]=='Y'>disabled<else>style="display:none;"</if></empty>><i class="fa fa-shopping-cart" aria-hidden="true"></i><label class="label-addcart">[lang033]</label></button>
                <!--a class="details btn btn-link btn-sm" href="[record.linkshow][param330]">[lang032]</a-->
                </div>
            </form>
        </div>
        </div>
    </div>
</repeat:objects>
