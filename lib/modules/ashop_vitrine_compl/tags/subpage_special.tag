<div class="specialProducts">
<repeat:objects>
    <div class="specialItem" style="position:relative;">
        <div class="specialImage"> 
            <a href="[record.linkshow][param330]" title="[record.name]">
                <img class="objectImage" src="[record.image_prev]" style="{$img_style}" alt="[record.img_alt]">
            </a>
            <div class="blockFlag">
                <if:[record.flag_new]=='Y'>
                    <span class="flag_new" title="[lang050]"><i class="fa fa-certificate" aria-hidden="true"><span class="text-flag-new">[lang050]</span></i></span>
                </if>
                <if:[record.flag_hit]=='Y'>
                    <span class="flag_hit" title="[lang049]"><i class="fa fa-sun-o" aria-hidden="true"><i class="fa fa-thumbs-up" aria-hidden="true"></i></i></span>
                </if>
            </div>
            <noempty:[record.percent]>
            <span class="flag_discount" title="[record.percent]%">[record.percent]%</span>
            </noempty>
        </div>
        <div class="specialRating objectRating">
                <span class="ratingOff" title="[lang058] [record.rating] [lang059] 5">
                    <span class="ratingOn" style="width:[record.rating_percent]%;"></span>
                </span>
                <span class="ratingValue">[record.rating]</span>
                <span class="marks">(<a class="marksLabel" href="[record.linkshow]#reviews">[lang056]</a> <span class="marksValue">[record.marks]</span>)</span>
        </div>
        <div class="specialTitle">
            <if:[param275]=='Y'>
                <a class="textTitle" href="[record.linkshow][param330]" title="[record.name]">[record.name]</a>
            <else>
                <span class="textTitle" title="[record.name]">[record.name]</span>
            </if>
        </div>
        <div class="priceBox priceBoxSpecial">
            <form class="form_addCart" style="margin:0px;" method="post" action="<se>[record.linkshow]</se>">
            <div class="priceStyle specialTitle">
                <if:[param113]=='Y' && [record.oldprice]!=''>
                    <span class="oldPrice">[record.oldprice]</span>
                </if>
                <span class="newPrice">[record.newprice]</span>
            </div>
            <div class="buttonBox specialButton">
                <input type="hidden" name="addcart" value="[record.id]">
                <if:[record.maxcount]==0 && [param336]=='Y'>
                    <button type="button" class="buttonSend btnPreorder btn btn-default"><i class="fa fa-briefcase sign-preorder" aria-hidden="true"></i><span class="text-preorder">[lang107]</span></button>
                </if>
                <button class="buttonSend addcart btn btn-default<noempty:[record.incart]> inCartActive</noempty>" title="[lang022]" <empty:record.maxcount><if:[param233]=='Y'>disabled<else>style="display:none;"</if></empty>><i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
                <if:[param339]=='Y'><a class="details" href="[record.linkshow][param330]">[lang032]</a></if>
            </div>
            </form>
        </div>                                
    </div>
</repeat:objects>
</div>
