<noempty:{$options}>
<div class="product-options">
    <repeat:option_group name=ogroup>
        <div class="option-group-item" data-id="[ogroup.id]">
            <noempty:[ogroup.id]>
                <h3>[ogroup.name]</h3>
                <noempty:[ogroup.description]>
                    <div class="">[ogroup.description]</div>
                </noempty>
            </noempty>
        <repeat:option[ogroup.id] name=option>
            <div class="option-item" data-id="[option.id]" data-type="[option.type_price]">
                <noempty:[option.image]>
                    <img class="option-item-image" src="[option.image]">
                </noempty>
                <h4>[option.name]</h4>
                <noempty:[option.description]> 
                    <div class="option-item-description">[option.description]</div> 
                </noempty>
                <if:[option.type]=='select'>
                    <select name="option[[option.id]]">
                        <repeat:option_value[option.id] name=ovalue>
                            <option value="[ovalue.id]" data-price="[ovalue.price_value]" <noempty:[ovalue.selected]>selected</noempty>>[ovalue.name]</option>    
                        </repeat:option_value[option.id]>
                    </select>
                <else>
                    <div class="option-value-list">
                        <repeat:option_value[option.id] name=ovalue>
                            <div class="option-value-item">
                                <label class="">
                                    <if:[option.type]=='radio'>
                                        <input type="radio" name="option[[option.id]]" value="[ovalue.id]" data-price="[ovalue.price_value]" <noempty:[ovalue.selected]>checked</noempty>>
                                    <else>
                                        <input type="checkbox" name="option[[option.id]][]" value="[ovalue.id]" data-price="[ovalue.price_value]" <noempty:[ovalue.selected]>checked</noempty>>    
                                    </if> 
                                    <noempty:[ovalue.image]> 
                                        <img class="value-item-image" src="[ovalue.image]"> 
                                    </noempty>  
                                    <span class="value-item-name">[ovalue.name]</span> 
                                    <noempty:[ovalue.price]> 
                                        <span class="value-item-price">Цена: [ovalue.price]</span>
                                    </noempty>  
                                    <noempty:[ovalue.description]> 
                                        <div class="value-item-description">[ovalue.description]</div> 
                                    </noempty> 
                                    <noempty:[ovalue.is_counted]> 
                                        <input type="number" name="ocount[[ovalue.id]]" value="1" min="1" step="1">
                                    </noempty>  
                                </label>
                            </div>
                        </repeat:option_value[option.id]>
                    </div>
                </if>
            </div>
        </repeat:option_group>  
        </div>
    </repeat:option_group>    
</div>
</noempty>
