<noempty:{$user_fields}>
    <div class="content userfields">  
        <repeat:userfields name=field>
            <if:[field.is_group]>
                <div class="field-group">
                    <h4>[field.name]</h4>
                    <noempty:[field.description]>
                        <div class="description">[field.description]</div>
                    </noempty>
                </div>
            <else>
            <div class="user-field">
                <label>[field.name]<noempty:[field.required]><span class="required">*</span></noempty></label>   
                <if:[field.type]=='string' || [field.type]=='number' || [field.type]=='date'>
                    <input type="[field.type]" name="field_[field.code]" placeholder="[field.placeholder]" title="[field.placeholder]" <noempty:[field.required]>required</noempty> value="<noempty:[field.val]>[field.val]</noempty>">
                </if>
                <if:[field.type]=='text'>
                    <textarea name="field_[field.code]" placeholder="[field.placeholder]" title="[field.placeholder]" <noempty:[field.required]>required</noempty>><noempty:[field.val]>[field.val]</noempty></textarea>
                </if>
                <if:[field.type]=='select' && [field.values] != ''>
                    <select name="field_[field.code]" placeholder="[field.placeholder]" title="[field.placeholder]" <noempty:[field.required]>required</noempty>>
                        <repeat:values[field.id] name=value>
                            <option value="[value.name]" <noempty:[value.check]>selected</noempty>>[value.name]</option>
                        </repeat:values[field.id]>    
                    </select>
                </if>
                <if:[field.type]=='checkbox'>
                    <if:[field.values] != ''>
                        <repeat:values[field.id] name=value>
                            <label>
                                <input type="checkbox" name="field_[field.code][]" title="[field.placeholder]" value="[value.name]" <noempty:[value.check]>checked</noempty>>
                                <span>[value.name]</span>
                            </label>
                        </repeat:values[field.id]>   
                    <else>
                        <input type="checkbox" name="field_[field.code]" title="[field.placeholder]" <noempty:[field.required]>required</noempty> <noempty:[field.val]>checked</noempty>>    
                    </if>
                </if>
                <if:[field.type]=='radio'>
                    <if:[field.values]>
                        <repeat:values[field.id] name=value>
                            <label>
                                <input type="radio" name="field_[field.code]" title="[field.placeholder]" value="[value.name]" <noempty:[value.check]>checked</noempty>>
                                <span>[value.name]</span>
                            </label>
                        </repeat:values[field.id]>   
                    <else>
                        <input type="radio" name="field_[field.code]" title="[field.placeholder]" value="[value.name]" <noempty:[field.required]>required</noempty>>  
                    </if>
                </if>
                <noempty:[field.description]>
                    <div class="description">[field.description]</div>
                </noempty>
            </div>
            </if>
        </repeat:userfields>
    </div>
</noempty>
