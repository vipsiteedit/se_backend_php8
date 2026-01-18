<repeat:objects>
    <div class="productItem row">
        <div class="blockName col-lg-5 col-md-5 col-sm-5 col-xs-6">
            <if:[param275]=='Y'>
                <a class="goodsname" title="[record.img_alt]" href="[record.linkshow][param330]">[record.name]</a>
            <else>
                <span class="goodsname">[record.name]</span>
            </if>
        </div>
        <div class="blockArticle col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <noempty:record.article>
                #<span class="articleValue">[record.article]</span>
            </noempty>
        </div>
        <div class="blockPresence col-lg-1 col-md-1 col-sm-1 hidden-xs">
            <span class="presenceValue">[record.count]</span>
        </div>
        <div class="blockPrice col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <if:[param113]=='Y' && [record.oldprice]!=''>
                <span class="oldPrice hidden-xs">[record.oldprice]</span>
            </if>
            <span class="newPrice">[record.newprice]</span>
        </div>
        <div class="blockAddCart col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <form class="form_addCart" method="post" action="<se>[record.linkshow]</se>">    
                <input type="hidden" name="addcart" value="[record.id]">
                <if:[record.maxcount]==0 && [param336]=='Y'>
                    <button type="button" class="buttonSend btnPreorder btn btn-default">Предзаказ</button>
                </if>
                <input type="hidden" class="cartscount" name="addcartcount" data-step="[record.step]" value="[record.step]" size="3">
                <button class="buttonSend addcart btn btn-default btn-sm<noempty:[record.incart]> inCartActive</noempty>" title="[lang022]" <empty:record.maxcount><if:[param233]=='Y'>disabled<else>style="display:none;"</if></empty>><i class="glyphicon glyphicon-shopping-cart"></i> [lang033]</button>
                <a class="details btn btn-link btn-sm" href="[record.linkshow][param330]">[lang032]</a>
            </form>
        </div>
    </div>
</repeat:objects>
