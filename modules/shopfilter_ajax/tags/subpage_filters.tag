    <repeat:filters name=filter>
        <div class="filterItem" data-id='[filter.id]' data-type='[filter.type]'>
            <div class="filterTitle <if:[filter.expanded]==0>closed</if>">
                <span class="nameFilter">[filter.name]<noempty:filter.measure>, <span class="measure">[filter.measure]</span></noempty></span>
                <a href="#" class="clearFilter" style="display:none;">x</a>
            </div>
            <div class="filterValueList" <if:[filter.expanded]==0>style="display:none;"</if>>
                <if:[filter.type]=='range'>
                <div class="filterValueItem filterSlider">
                    <input type="text" class="min" size="5" name="f[[filter.id]][min]" value="[filter.min]" disabled style="display:none;">
                    <input type="text" class="max" size="5" name="f[[filter.id]][max]" value="[filter.max]" disabled style="display:none;">
                    <input type="text" class="from" size="5" name="f[[filter.id]][from]" value="[filter.from]">
                    <input type="text"  class="to" size="5" name="f[[filter.id]][to]" value="[filter.to]">
                    <input class="inputRange" type="text" style="display:none;" disabled>
                </div>
                </if>
                <if:[filter.type]=='list'>
                    <repeat:filter_values[filter.id] name=value>
                        <div class="filterValueItem listFeature">
                            <label>
                                <input class="inpCheckbox" type="checkbox" name="f[[filter.id]][]" value="[value.id]" <noempty:value.check>checked</noempty>>
                                <span class="filterValue">[value.value]</span>
                            </label>
                        </div>
                    </repeat:filter_values[filter.id]> 
                </if>
                <if:[filter.type]=='colorlist'>
                    <repeat:filter_values[filter.id] name=cvalue>
                       <div class="filterValueItem colorFeature">
                        <label class="itemValue" title="[cvalue.value]" data-id="[filter.id]">
                        <input class="inpRadio" style="display: none;"  type="checkbox" name="f[[filter.id]][]"
                             value="[cvalue.id]" <noempty:[cvalue.check]>checked</noempty>>
                             <noempty:[cvalue.color]>
                                <span class="featureValue" style="background-color:#[cvalue.color];"><span></span></span>
                             </noempty>
                             <noempty:[cvalue.image]>
                                <img class="featureValue" src="[cvalue.image]" style="width:16px;height:16px;">
                             </noempty>
                        </label>
                      </div>
                    </repeat:filter_values[filter.id]>
                </if>
                <if:[filter.type]=='bool'>
                    <div class="filterValueItem">
                        <label>
                            <input class="inpRadio" type="radio" name='f[[filter.id]]' value="1"<if:[filter.check]=='1'> checked</if>>
                            <span class="filterValue">[lang003]</span>
                        </label>
                    </div>
                    <div class="filterValueItem">
                        <label>
                            <input class="inpRadio" type="radio" name='f[[filter.id]]' value="0"<if:[filter.check]=='0'> checked</if>>
                            <span class="filterValue">[lang004]</span>
                        </label>
                    </div>
                    <div class="filterValueItem">
                        <label>
                            <input class="inpRadio" type="radio" name="f[[filter.id]]"<if:[filter.check]!='1' && [filter.check]!='0'> checked</if>>
                            <span class="filterValue">[lang005]</span>
                        </label>
                    </div>
                </if>
            </div>
        </div>
    </repeat:filters>
