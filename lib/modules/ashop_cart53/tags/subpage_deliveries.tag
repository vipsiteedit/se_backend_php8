<repeat:deliverylist name=delivery>
    <div class="deliveryType" data-id="[delivery.id]"> 
        <label title="[lang030]">
            <input class="radioDeliveryType" type="radio" name="delivery_type_id" value="[delivery.id]"[delivery.sel] data-addr="[delivery.addr]">
            <span class="deliveryTypeName">[delivery.name]</span>
        </label>
        <div class="deliveryTypePriceTime">
            <span class="deliveryTypePrice">[delivery.price]</span><span class="deliveryTypeTime">, [delivery.time]</span>
        </div>
        <noempty:delivery.note>
            <div class="deliveryTypeNote">[delivery.note]</div>
        </noempty>
        <noempty:delivery.sub>
            <div class="subdeliveries" <if:[delivery.sel]!=' checked'>style="display:none;"</if>>
                <repeat:sublist[delivery.id] name=sub>
                    <div class="subType">
                        <label>
                            <input type="radio" name="delivery_sub_[delivery.id]" value="[sub.id]" <noempty:sub.sel>checked</noempty> data-addr="[delivery.addr]">
                            <span class="subTypeName">[sub.name]</span>
                        </label>
                        <div class="subTypePriceTime">
                            <span class="subTypePrice">[sub.price]</span><span class="subTypeTime">, [sub.time]</span>
                        </div>
                        <noempty:sub.note>
                            <div class="subTypeNote">[sub.note]</div>
                        </noempty>
                    </div>
                </repeat:sublist[delivery.id]>    
            </div>
        </noempty>
    </div>
</repeat:deliverylist>
