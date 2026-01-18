<repeat:objects>
    <div class="productItem col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="blockImage col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <if:[param327]=='Y'>
                <button class="quickView" title="[lang094]" data-id="[record.id]">[lang094]</button>
            </if>
            <a href="[record.linkshow][param330]" title="[record.img_alt]">
                <img class="objectImage img-responsive" src="[record.image_prev]" style="{$img_style}" alt="[record.img_alt]">
            </a>
            <if:[record.flag_hit]=='Y'>
                <span class="flag_hit" title="[lang049]">[lang049]</span>
            </if>
            <if:[record.flag_new]=='Y'>
                <span class="flag_new" title="[lang050]">[lang050]</span>
            </if>
            <if:[record.percent]!=0>
                <span class="flag_discount" title="[record.percent]%">[record.percent]%</span>
            </if>
            <noempty:[record.labels]>
                <repeat:labels[record.id] name=label>
                    <span class="flag-label label-[label.code]" title="[label.name]">
                        <noempty:[label.image]><img class="flag-label-image" src="[label.image]" alt="[label.name]""></noempty>
                        <span class="flag-label-text">[label.name]</span>
                    </span>
                </repeat:labels[record.id]>
            </noempty>
            <if:[param269]=='Y'>
              <div class="blockCompare">
                <label title="[lang095]">
                    <input class="compare" type="checkbox" data-id="[record.id]"<noempty:record.compare> checked</noempty>>
                    <span class="compareLabel">[lang098]</span>
                </label>
                <a class="lnkInCompare" href="[param331].html" title="[lang096]"<empty:record.compare> style="display:none;"</empty>>[lang097]</a>
              </div>
            </if>
        </div>
        <div class="blockInfo col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <if:[param275]=='Y'>
               <h4 class="hidden-xs"><a class="textTitle" href="[record.linkshow][param330]">[record.name]</a></h4>
               <a class="textTitle hidden-lg hidden-md hidden-sm" href="[record.linkshow][param330]">[record.name]</a>
            <else>
                <span class="textTitle">[record.name]</span>
            </if>
            <if:[param74]=='Y' >
                <noempty:record.note>
                    <div class="objectNote">[record.note]</div>
                </noempty>
            </if>
            <if:[param57]=='Y'>
              <noempty:record.article>
                <div class="objectArticle">
                    <label class="articleLabel">[lang005]</label>
                    <span class="articleValue">[record.article]</span>
                </div>
              </noempty>
            </if>
            <if:[param65]=='Y'>
            <noempty:record.brand>
                <div class="objectBrand">
                    <label class="brandLabel">[lang024]</label>
                    <span class="brandValue">[record.brand]</span>
                </div>
            </noempty>
            <if:[param266]=='Y'>
              <div class="objectRating">
                <label class="ratingLabel">[lang057]</label>
                <span class="ratingOff" title="[lang058] [record.rating] [lang059] 5">
                    <span class="ratingOn" style="width:[record.rating_percent]%;"></span>
                </span>
                <span class="ratingValue">[record.rating]</span>
                <span class="marks">(<a class="marksLabel" href="[record.linkshow]#reviews">[lang056]</a> <span class="marksValue">[record.marks]</span>)</span>
              </div>
            </if>
          </if>
          [record.modifications]
        </div>
        
        <div class="se_cart col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="priceBox">
                <div class="priceStyle<empty:record.realprice> nullPrice</empty>">
                    <if:[param226]=='Y'>
                        <label class="priceLabel">[lang008]</label>
                    </if>
                    <if:[param113]=='Y' && [record.oldprice]!=''>
                        <span class="oldPrice">[record.oldprice]</span>
                    </if>
                    <span class="newPrice">[record.newprice]</span>
                </div>
            </div>
            <if:[param55]=='Y'>
                <div class="objectPresence">
                    <label class="presenceLabel">[lang009]</label>
                    <span class="presenceValue">[record.count]</span>
                </div>
            </if>
        
            <form class="form_addCart" method="post" action="<se>[thispage.link]</se>">
                <if:[param205]=='Y'>
                    <div class="addCount">
                        <input class="cartscount" type="number" min="[record.step]" name="addcartcount" step="[record.step]" value="[record.step]" size="4">
                        <span class="measure">[record.measure]</span>
                    </div>
                </if>
                <div class="buttonBox">
                    <input type="hidden" name="addcart" value="[record.id]">
                    <if:[record.maxcount]==0 && [param336]=='Y'>
                        <button type="button" class="buttonSend btnPreorder btn btn-default">Предзаказ</button>
                    </if>
                    <button class="buttonSend addcart btn btn-default<noempty:[record.incart]> inCartActive</noempty>" title="[lang022]" <empty:record.maxcount><if:[param233]=='Y'>disabled<else>style="display:none;"</if></empty>><i class="glyphicon glyphicon-shopping-cart"></i> [lang033]</button>
                    <a class="details btn btn-link" href="[record.linkshow][param330]">[lang032]</a>
                </div>
            </form>
         </div>
    </div>
</repeat:objects>
