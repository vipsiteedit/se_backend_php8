<div class="partSpecial[part.id] object blockAllItem <empty:[record.count]> emptyGoods</empty>"> 
    <div class="blockGoodsInfo"">                    
        <if:[record.flag_hit]=='Y'>
            <span class="flag_hit" title="[lang009]">[lang009]</span>
        </if>
        <if:[record.flag_new]=='Y'>
            <span class="flag_new" title="[lang010]">[lang010]</span>
        </if>
        <noempty:record.percent>
            <span class="flag_discount" title="[record.percent]%">[record.percent]%</span>
        </noempty>
        <noempty:record.labels>
            <repeat:labels[record.id] name=label>
                <span class="flag-label label-[label.code]" title="[label.name]">
                    <noempty:[label.image]><img class="flag-label-image" src="[label.image]" alt="[label.name]""></noempty>
                    <span class="flag-label-text">[label.name]</span>
                </span>
            </repeat:labels[record.id]>
        </noempty>
        <if:[param23]=='Y'>
            <div class="blockGroup">
                <a href="[record.view_group]">[record.group_name]</a>
            </div>
        </if>  
        <if:[param22]=='Y'>
            <div class="blockImage">
                <a href="[record.view_goods]">
                    <img class="img-responsive center-block" title="[record.name]" alt="[record.name]" src="[record.image_prev]" style="{$img_style}">
                    <input type="hidden" name="addcart" value="[record.id]">
                </a>
            </div>
        </if>             
        <if:[param24]=='Y'>
            <div class="blockTitle">
                <a href="[record.view_goods]" title="[record.name]">[record.name]</a>
                <input type="hidden" name="addcart" value="[record.id]"> 
            </div>
        </if> 
        <if:[param21]=='Y'>
            <div class="blockRating" title="[lang005] [record.rating] [lang006] 5">
                <span class="ratioOff">
                    <span class="ratioOn" style="width:[record.ratio]%;"></span> 
                </span>
            </div>
        </if> 
        <if:[param25]=='Y'>
            <div class="blockArticle">
                <label class="artName">[lang001]</label> 
                <span class="artValue">[record.article]</span> 
            </div>
        </if>           
        <if:[param27]=='Y'>
            <noempty:record.note>
            <div class="blockNote">
                <span class="noteText">[record.note]</span>
            </div>
            </noempty>
        </if> 
        <if:[param49]!='N'>
            <noempty:([record.param_block])>
                [record.param_block]
            </noempty>    
        </if>
        <if:[param26]=='Y'>
            <div class="blockPrice">
                <label class="titlePrice">[lang002]</label>
                <noempty:record.old_price>
                    <span class="oldPrice">[record.old_price]</span> 
                </noempty>
                <span class="newPrice">[record.new_price]</span> 
            </div>
        </if> 
    </div>   
    <div class="blockButton">
        <form style="margin:0;" method="post" class="form_addCart">
            <input type="hidden" name="addcart" value="[record.id]"> 
            <button title="[record.btn_title]" class="buttonSend addcart btn btn-default" [record.disabled]><i class="glyphicon glyphicon-shopping-cart"></i> [lang003]</button>
        </form> 
        <span title=""></span>
        <a href="[record.view_goods]" class="goShowGoods">[lang004]</a>
    </div>     
</div>
