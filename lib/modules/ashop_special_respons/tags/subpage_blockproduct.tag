<div class="partSpecial[part.id] object b_special_simple-object blockAllItem swiper-slide [prod.empty_class]">
        <div class="b_special_simple-labels">
        <if:[prod.flag_hit]=='Y'>
            <span class="b_special_simple-label_hit" title="[lang009]">[lang009]</span>
        </if>
        <if:[prod.flag_new]=='Y'>
            <span class="b_special_simple-label_new" title="[lang010]">[lang010]</span>
        </if>
        <noempty:prod.percent>
            <span class="b_special_simple-label_discount" title="[prod.percent]%">[prod.percent]%</span>
        </noempty>
        <if:[prod.unsold]=='Y'>
            <span class="b_special_simple-label_price" title="[lang011]">[lang011]</span>
        </if>
        </div>
        <if:[param23]=='Y'>
            <div class="blockGroup">
                <a href="[prod.view_group]">[prod.group_name]</a>
            </div>
        </if>
        <if:[param22]=='Y'>
            <div class="blockImage b_special_simple-object_image_area">
                <a href="[prod.view_goods]" class="b_special_simple-object_image_link">
                    <img class="b_special_simple-object_image" title="[prod.altname]" alt="[prod.altname]" src="[prod.image_prev]" >
                    <input type="hidden" name="addcart" value="[prod.id]">
                </a>
            </div>
        </if>
        <div class="b_special_simple-object_content blockGoodsInfo">
        <if:[param24]=='Y'>
            <div class="blockTitle b_special_simple-object_title">
                <a class="b_special_simple-object_title_text" href="[prod.view_goods]" title="[prod.altname]">[prod.name]</a>
                <input type="hidden" name="addcart" value="[prod.id]">
            </div>
        </if>
        <if:[param21]=='Y'>
            <div class="blockRating" title="[lang005] [prod.rating] [lang006] 5">
                <span class="ratioOff">
                    <span class="ratioOn" style="width:[prod.ratio]%;"></span>
                </span>
            </div>
        </if>
        <if:[param25]=='Y'>
            <div class="blockArticle">
                <label class="artName">[lang001]</label>
                <span class="artValue">[prod.article]</span>
            </div>
        </if>
        <if:[param27]=='Y'>
            <noempty:prod.note>
            <div class="blockNote b_special_simple-object_text">
                <span class="noteText">[prod.note]</span>
            </div>
            </noempty>
        </if>
        <if:[param49]!='N'>
            <noempty:([prod.param_block])>
                [prod.param_block]
            </noempty>
        </if>
        <div class="b_special_simple-object_price_block">
        <if:[param26]=='Y'>
            <div class="blockPrice b_special_simple-object_price">
                <noempty:prod.old_price>
                    <span class="oldPrice b_special_simple-object_old_price">[prod.old_price]</span>
                </noempty>
                <span class="newPrice b_special_simple-object_newprice">[prod.new_price]</span>
            </div>
        </if>
        <div class="blockButton b_special_simple-object_button_area">
            <form method="post" class="form_addCart b_special_simple-object_form_add_to_cart">
                  <input type="hidden" name="addcart" value="[prod.id]">
                  <button title="[prod.btn_title]" class="buttonSend addcart b_special_simple-object_button_add_to_cart" [prod.disabled]>[lang003]</button>
            </form>
        </div>
        </div>
        </div>
</div>
