<if:{$pricelist}>
<div class="b_special_simple" data-type="[part.type]">
    <div class="contentBody bodySpecialGoods[part.id]">
        <div id="partBlock[part.id]" class="blockGoods b_special_simple-object_area">
            <repeat:specobjects[record.id] name=prod>
                [subpage name=blockproduct]
            </repeat:specobjects[record.id]>
        </div> 
    </div>
</div>
</if>
