<div class="specialProducts">
<repeat:objects>
    <div class="specialItem" style="position:relative;">
        <if:[record.flag_hit]=='Y'>
            <span class="flag_hit" title="[lang049]">[lang049]</span>
        </if>
        <if:[record.flag_new]=='Y'>
            <span class="flag_new" title="[lang050]">[lang050]</span>
        </if>
        <noempty:[record.percent]>
            <span class="flag_discount" title="[record.percent]%">[record.percent]%</span>
        </noempty>
        <noempty:[record.labels]>
            <repeat:labels[record.id] name=label>
                <span class="flag-label label-[label.code]" title="[label.name]">
                    <noempty:[label.image]><img class="flag-label-image" src="[label.image]" alt="[label.name]""></noempty>
                    <span class="flag-label-text">[label.name]</span>
                </span>
            </repeat:labels[record.id]>
        </noempty>
        <div class="specialImage"> 
            <a href="[record.linkshow][param330]" title="[record.name]">
                <img class="objectImage" src="[record.image_prev]" style="{$img_style}" alt="[record.img_alt]">
            </a>
        </div>
        <div class="specialTitle">
            <if:[param275]=='Y'>
                <a class="textTitle" href="[record.linkshow][param330]" title="[record.name]">[record.name]</a>
            <else>
                <span class="textTitle" title="[record.name]">[record.name]</span>
            </if>
        </div>
        <div class="specialRating">
            <span class="ratingOff" title="[lang058] [record.rating] [lang059] 5">
                <span class="ratingOn" style="width:[record.rating_percent]%;"></span>
            </span>
            <span class="ratingValue">[record.rating]</span>
            <span class="marks">(<label class="marksLabel">[lang056]</label> <span class="marksValue">[record.marks]</span>)</span>
        </div>
        <div class="specialPrice">
            <if:[param113]=='Y' && [record.oldprice]!=''>
                <span class="oldPrice">[record.oldprice]</span>
            </if>
            <span class="newPrice">[record.newprice]</span>
        </div>
        <div class="specialButton">
            <form class="form_addCart" style="margin:0px;" method="post" action="<se>[record.linkshow]</se>">
                <input type="hidden" name="addcart" value="[record.id]">
                <if:[record.maxcount]==0 && [param336]=='Y'>
                    <button type="button" class="buttonSend btnPreorder btn btn-default">Предзаказ</button>
                </if>
                <button class="buttonSend addcart btn btn-default<noempty:[record.incart]> inCartActive</noempty>" title="[lang022]" <empty:record.maxcount><if:[param233]=='Y'>disabled<else>style="display:none;"</if></empty>><i class="glyphicon glyphicon-shopping-cart"></i> [lang033]</button>
                <a class="details" href="[record.linkshow][param330]">[lang032]</a>
            </form>
        </div>                                
    </div>
</repeat:objects>
</div>
